<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\StockCard;
use App\Models\WithdrawalCard;
use App\Models\WithdrawalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalCardController extends Controller
{
    public function index(Request $request)
    {
        $query = WithdrawalCard::with(['items.material', 'creator'])
            ->orderBy('withdrawal_date', 'desc')
            ->orderBy('id', 'desc');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('document_no', 'like', "%{$request->search}%")
                  ->orWhere('pic', 'like', "%{$request->search}%")
                  ->orWhere('part_name', 'like', "%{$request->search}%")
                  ->orWhere('line', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('withdrawal_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('withdrawal_date', '<=', $request->date_to);
        }

        $withdrawals = $query->paginate(10)->withQueryString();

        return view('withdrawal-cards.index', compact('withdrawals'));
    }

    public function create()
    {
        $materials = Material::where('is_active', true)
            ->where('current_stock', '>', 0)
            ->orderBy('name')
            ->get();
        $documentNo = WithdrawalCard::generateDocumentNo();

        return view('withdrawal-cards.create', compact('materials', 'documentNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'withdrawal_date' => 'required|date',
            'pic' => 'required|string|max:100',
            'line' => 'required|string|max:100',
            'part_name' => 'required|string|max:255',
            'work_order' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($request) {
            $withdrawal = WithdrawalCard::create([
                'document_no' => WithdrawalCard::generateDocumentNo(),
                'withdrawal_date' => $request->withdrawal_date,
                'pic' => $request->pic,
                'line' => $request->line,
                'part_name' => $request->part_name,
                'work_order' => $request->work_order,
                'notes' => $request->notes,
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $material = Material::lockForUpdate()->findOrFail($item['material_id']);

                if ($material->current_stock < $item['quantity']) {
                    throw new \Exception("Stok {$material->name} tidak mencukupi. Stok saat ini: {$material->current_stock} {$material->unit}");
                }

                $stockBefore = $material->current_stock;
                $material->current_stock -= $item['quantity'];
                $material->save();

                WithdrawalItem::create([
                    'withdrawal_card_id' => $withdrawal->id,
                    'material_id' => $material->id,
                    'quantity' => $item['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $material->current_stock,
                    'notes' => $item['notes'] ?? null,
                ]);

                StockCard::create([
                    'material_id' => $material->id,
                    'transaction_date' => $request->withdrawal_date,
                    'type' => 'out',
                    'quantity_in' => 0,
                    'quantity_out' => $item['quantity'],
                    'balance' => $material->current_stock,
                    'reference_no' => $withdrawal->document_no,
                    'source' => "Pengambilan: {$request->line} - {$request->part_name}",
                    'notes' => $request->notes,
                    'withdrawal_card_id' => $withdrawal->id,
                    'created_by' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('withdrawal-cards.index')
            ->with('success', 'Kartu pengambilan berhasil dibuat dan stok telah diperbarui.');
    }

    public function show(WithdrawalCard $withdrawalCard)
    {
        $withdrawalCard->load(['items.material', 'creator', 'approver']);
        return view('withdrawal-cards.show', compact('withdrawalCard'));
    }

    public function destroy(WithdrawalCard $withdrawalCard)
    {
        if ($withdrawalCard->status === 'approved') {
            return back()->with('error', 'Kartu pengambilan yang sudah disetujui tidak dapat dihapus.');
        }

        $withdrawalCard->delete();

        return redirect()->route('withdrawal-cards.index')
            ->with('success', 'Kartu pengambilan berhasil dihapus.');
    }
}
