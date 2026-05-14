@extends('layouts.app')
@section('title', 'Input Retur GI')
@section('topbar-title', 'Retur Good Issue')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('return-gi.index') }}">Retur GI</a>
    <span class="sep">/</span>
    <span>Input Retur Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Input Retur Material</div>
        <div class="page-subtitle">Kembalikan material sisa/NG dari Work Order ke stok gudang</div>
    </div>
</div>

<div style="max-width:900px;">
    {{-- Form Pilih Work Order (QC) --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body">
            <form method="GET" action="{{ route('return-gi.create') }}">
                <label class="form-label" style="font-weight:600;">Pilih Work Order (QC) <span class="required">*</span></label>
                <div style="display:flex; gap:10px;">
                    <select name="t_production_qc_id" class="form-control" onchange="this.form.submit()" required>
                        <option value="">-- Silakan Pilih Work Order --</option>
                        @foreach($availableQcs as $availQc)
                            <option value="{{ $availQc->id }}" {{ request('t_production_qc_id') == $availQc->id ? 'selected' : '' }}>
                                {{ $availQc->wo_number }} | Part NG Retur: {{ number_format($availQc->quantity_failed_retur, 0) }} | GI: {{ $availQc->goodIssue->gi_number ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($qc)
    <form action="{{ route('return-gi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="t_production_qc_id" value="{{ $qc->id }}">

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-undo" style="color:var(--accent);margin-right:8px;"></i>Informasi Retur</span>
            </div>
            <div class="card-body">
                <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">No. Retur <span class="required">*</span></label>
                        <input type="text" name="return_number" class="form-control" value="{{ old('return_number', $returnNumber) }}" readonly style="background:var(--surface-2);">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Tanggal Retur <span class="required">*</span></label>
                        <input type="date" name="return_date" class="form-control" value="{{ old('return_date', date('Y-m-d')) }}" required>
                        @error('return_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                {{-- Info Referensi --}}
                <div style="padding: 12px; background: rgba(var(--accent-rgb), 0.05); border-radius: 8px; margin-bottom: 16px;">
                    <div style="font-size: 11px; color: var(--text-dim); text-transform: uppercase;">Informasi Referensi</div>
                    <div style="display:flex; gap: 20px; margin-top: 8px;">
                        <div>
                            <div style="font-size: 11px; color: var(--text-muted);">No. Work Order</div>
                            <div style="font-weight: 600; color:var(--accent);">{{ $qc->wo_number }}</div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: var(--text-muted);">No. GI Asal</div>
                            <div style="font-weight: 500;">{{ $qc->goodIssue->gi_number }}</div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: var(--text-muted);">Total NG Retur (Satuan Part)</div>
                            <div style="font-weight: 600; color:var(--warning);">{{ number_format($qc->quantity_failed_retur, 2) }} Part</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan Retur</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Alasan retur (NG, sisa produksi, dll)">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Input Material yang Diretur --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <span class="card-title"><i class="fas fa-boxes-stacked" style="color:var(--warning);margin-right:8px;"></i>Material yang Diretur (Berdasarkan Item GI)</span>
            </div>
            
            <div style="padding: 12px 20px; font-size: 13px; color: var(--text-muted); background:var(--surface-1); border-bottom:1px solid var(--border);">
                <i class="fas fa-info-circle" style="color:var(--accent);"></i> Input jumlah <b>material</b> yang diretur. Karena part yang rusak bisa dikonversi/diukur kembali menjadi bahan baku, masukkan angka qty sesuai satuan bahan bakunya. Biarkan angka <b>0</b> jika item tersebut tidak ada yang diretur.
            </div>

            <div class="table-wrap">
                <table id="itemsTable">
                    <thead>
                        <tr>
                            <th>Material Dikeluarkan (Dari GI)</th>
                            <th class="text-right" width="150">Total Qty di GI</th>
                            <th class="text-right" width="220">Qty Material Dikembalikan <span class="required">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($qc->goodIssue->items as $index => $item)
                        <tr>
                            <td>
                                <input type="hidden" name="items[{{ $index }}][m_material_id]" value="{{ $item->m_material_id }}">
                                <div style="font-weight: 500; font-size:14px;">{{ $item->material->name ?? '-' }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">Kode: {{ $item->material->code ?? '-' }}</div>
                            </td>
                            <td class="text-right" style="color:var(--text-muted); font-weight:500;">
                                {{ number_format($item->quantity, 2) }} {{ $item->unit ?? 'Pcs' }}
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;justify-content:flex-end;">
                                    <input type="number" name="items[{{ $index }}][quantity]" class="form-control text-right" 
                                           value="{{ old('items.'.$index.'.quantity', 0) }}" min="0" step="0.01" 
                                           style="font-size:15px; padding:8px; width:120px; border-color:var(--warning); font-weight:bold;">
                                    <span style="font-size:12px;color:var(--text-muted); width: 35px;">{{ $item->unit ?? 'Pcs' }}</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @error('items') <div class="form-error" style="padding:10px 20px;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Retur & Masukkan Stok</button>
            <a href="{{ route('return-gi.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
    @endif
</div>
@endsection