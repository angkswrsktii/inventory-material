@extends('layouts.app')
@section('title', 'Buat Kartu Pengambilan')
@section('topbar-title', 'Kartu Pengambilan')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('withdrawal-cards.index') }}">Kartu Pengambilan</a>
    <span class="sep">/</span>
    <span>Buat Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Buat Kartu Pengambilan</div>
        <div class="page-subtitle">No. Dokumen: <strong style="color:var(--accent)">{{ $documentNo }}</strong></div>
    </div>
</div>

<form action="{{ route('withdrawal-cards.store') }}" method="POST" id="withdrawalForm">
@csrf

<div style="display:grid; grid-template-columns:1fr 360px; gap:20px; align-items:start;">

    {{-- LEFT: Item Material --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-boxes-stacked" style="color:var(--accent);margin-right:8px;"></i>
                Material yang Diambil
            </span>
            <button type="button" class="btn btn-primary btn-sm" onclick="addRow()">
                <i class="fas fa-plus"></i> Tambah Material
            </button>
        </div>
        <div class="table-wrap">
            <table id="itemsTable">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Stok Tersedia</th>
                        <th class="text-right">Jumlah</th>
                        <th>Catatan</th>
                        <th class="text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    <tr id="row_0">
                        <td style="min-width:220px;">
                            <select name="items[0][material_id]" class="form-control material-select" required onchange="updateStock(this, 0)">
                                <option value="">-- Pilih Material --</option>
                                @foreach($materials as $m)
                                    <option value="{{ $m->id }}" data-stock="{{ $m->current_stock }}" data-unit="{{ $m->unit }}">
                                        [{{ $m->code }}] {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <span id="stock_0" style="font-size:12px; color:var(--text-muted);">—</span>
                        </td>
                        <td style="min-width:110px;">
                            <input type="number" name="items[0][quantity]" class="form-control text-right"
                                   min="0.01" step="0.01" placeholder="0" required>
                        </td>
                        <td style="min-width:140px;">
                            <input type="text" name="items[0][notes]" class="form-control" placeholder="Opsional">
                        </td>
                        <td class="text-center">
                            <button type="button" onclick="removeRow(0)" class="btn btn-xs btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="padding:14px 20px; border-top:1px solid var(--border); background:var(--surface-2);">
            <div style="font-size:12px; color:var(--text-muted);">
                <i class="fas fa-info-circle"></i>
                Setiap item yang disimpan akan otomatis mengurangi stok material
            </div>
        </div>
    </div>

    {{-- RIGHT: Header Info --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-file-invoice" style="color:var(--accent-2);margin-right:8px;"></i>
                Informasi Pengambilan
            </span>
        </div>
        <div class="card-body">

            <div class="form-group">
                <label class="form-label">No. Dokumen</label>
                <input type="text" class="form-control" value="{{ $documentNo }}" disabled
                       style="opacity:0.6; font-family:monospace; color:var(--accent);">
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Pengambilan <span class="required">*</span></label>
                <input type="date" name="withdrawal_date" class="form-control"
                       value="{{ old('withdrawal_date', date('Y-m-d')) }}" required>
                @error('withdrawal_date') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">PIC (Person In Charge) <span class="required">*</span></label>
                <input type="text" name="pic" class="form-control"
                       value="{{ old('pic') }}" placeholder="Nama pengambil" required>
                @error('pic') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Line Produksi <span class="required">*</span></label>
                <input type="text" name="line" class="form-control"
                       value="{{ old('line') }}" placeholder="Contoh: Line A, Line 1" required>
                @error('line') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Part Name <span class="required">*</span></label>
                <input type="text" name="part_name" class="form-control"
                       value="{{ old('part_name') }}" placeholder="Nama part/produk" required>
                @error('part_name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Work Order / No. WO</label>
                <input type="text" name="work_order" class="form-control"
                       value="{{ old('work_order') }}" placeholder="Opsional">
                @error('work_order') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control" rows="2"
                          placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
            </div>

            <div class="divider"></div>

            <button type="submit" class="btn btn-primary" style="width:100%;">
                <i class="fas fa-save"></i> Simpan & Kurangi Stok
            </button>
            <a href="{{ route('withdrawal-cards.index') }}" class="btn btn-ghost" style="width:100%; margin-top:8px; justify-content:center;">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </div>

</div>
</form>
@endsection

@push('scripts')
<script>
let rowCount = 1;

const materialsData = @json($materials->keyBy('id'));

function updateStock(select, idx) {
    const id = select.value;
    const el = document.getElementById('stock_' + idx);
    if (id && materialsData[id]) {
        const m = materialsData[id];
        el.innerHTML = '<strong style="color:' + (m.current_stock <= 0 ? 'var(--danger)' : 'var(--success)') + '">'
            + parseFloat(m.current_stock).toFixed(2) + ' ' + m.unit + '</strong>';
    } else {
        el.textContent = '—';
    }
}

function addRow() {
    const idx = rowCount++;
    const materials = @json($materials);
    let options = '<option value="">-- Pilih Material --</option>';
    materials.forEach(m => {
        options += `<option value="${m.id}" data-stock="${m.current_stock}" data-unit="${m.unit}">[${m.code}] ${m.name}</option>`;
    });

    const row = `<tr id="row_${idx}">
        <td><select name="items[${idx}][material_id]" class="form-control" required onchange="updateStock(this,${idx})">${options}</select></td>
        <td><span id="stock_${idx}" style="font-size:12px;color:var(--text-muted);">—</span></td>
        <td><input type="number" name="items[${idx}][quantity]" class="form-control text-right" min="0.01" step="0.01" placeholder="0" required></td>
        <td><input type="text" name="items[${idx}][notes]" class="form-control" placeholder="Opsional"></td>
        <td class="text-center"><button type="button" onclick="removeRow(${idx})" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button></td>
    </tr>`;
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
}

function removeRow(idx) {
    const rows = document.getElementById('itemsBody').querySelectorAll('tr');
    if (rows.length <= 1) { alert('Minimal harus ada 1 item material.'); return; }
    const row = document.getElementById('row_' + idx);
    if (row) row.remove();
}
</script>
@endpush