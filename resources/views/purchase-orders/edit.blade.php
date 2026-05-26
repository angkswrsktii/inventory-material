@extends('layouts.app')
@section('title', 'Edit Purchase Order')
@section('topbar-title', __('app.nav.purchasing') . ' — ' . __('app.nav.purchase_order'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('purchase-orders.index') }}">Purchase Order</a>
    <span class="sep">/</span>
    <span>Edit: {{ $purchaseOrder->po_number }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Edit Purchase Order</div>
        <div class="page-subtitle">Pembaruan data PO Draft</div>
    </div>
</div>

<div style="max-width:1000px;">
    <form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST">
        @csrf @method('PUT')

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-invoice-dollar" style="color:var(--accent);margin-right:8px;"></i>Informasi PO</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">No. PO</label>
                        <input type="text" class="form-control" value="{{ $purchaseOrder->po_number }}" readonly style="background:var(--surface-2);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal PO <span class="required">*</span></label>
                        <input type="date" name="order_date" class="form-control" value="{{ old('order_date', $purchaseOrder->order_date->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Referensi PR</label>
                        <input type="text" class="form-control" value="{{ $purchaseOrder->purchaseRequest->pr_number ?? '-' }}" readonly style="background:var(--surface-2);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Supplier <span class="required">*</span></label>
                        <select name="m_supplier_id" class="form-control" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $purchaseOrder->m_supplier_id == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $purchaseOrder->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-cubes" style="color:var(--warning);margin-right:8px;"></i>Item Pesanan</span>
            </div>
            <div class="table-wrap">
                <table id="itemsTable">
                    <thead>
                        <tr>
                            <th>Item (Material)</th>
                            <th width="120">Qty</th>
                            <th width="200">Harga Satuan <span class="required">*</span></th>
                            <th width="200">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrder->items as $index => $item)
                        <tr>
                            <td>
                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                <input type="text" class="form-control" value="{{ $item->material->name ?? '-' }}" readonly style="background:var(--surface-2); font-weight:500;">
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <input type="number" id="qty_{{ $index }}" class="form-control" value="{{ $item->quantity }}" readonly style="background:var(--surface-2); font-size:13px; padding:6px; width:100%;">
                                    <span style="font-size:12px;color:var(--text-muted); width:30px;">{{ $item->unit }}</span>
                                </div>
                            </td>
                            <td>
                                <input type="number" id="price_per_qty_{{ $index }}" name="items[{{ $index }}][price_per_qty]" class="form-control" value="{{ $item->price_per_qty }}" min="0" step="0.01" oninput="calculateTotal({{ $index }})" required style="font-size:13px; padding:6px; width:100%;">
                            </td>
                            <td>
                                <input type="number" id="price_{{ $index }}" name="items[{{ $index }}][price]" class="form-control" value="{{ $item->price }}" readonly style="background:var(--surface-2); font-size:13px; padding:6px; width:100%;">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    window.calculateTotal = function(index) {
        const qty = parseFloat(document.getElementById(`qty_${index}`).value) || 0;
        const pricePerQty = parseFloat(document.getElementById(`price_per_qty_${index}`).value) || 0;
        const total = qty * pricePerQty;
        document.getElementById(`price_${index}`).value = total;
    }
</script>
@endpush