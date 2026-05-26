@extends('layouts.app')
@section('title', 'Buat Purchase Request')
@section('topbar-title', __('app.nav.purchasing') . ' — ' . __('app.nav.purchase_request'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('purchase-requests.index') }}">Purchase Request</a>
    <span class="sep">/</span>
    <span>Buat Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Buat Purchase Request</div>
        <div class="page-subtitle">Ajukan permintaan pembelian material ke bagian Purchasing</div>
    </div>
</div>

<div style="max-width:900px;">
    <form action="{{ route('purchase-requests.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-invoice" style="color:var(--accent);margin-right:8px;"></i>Informasi PR</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">No. PR <span class="required">*</span></label>
                        <input type="text" name="pr_number" class="form-control" value="{{ old('pr_number', $autoNumber) }}" readonly style="background:var(--surface-2);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal PR <span class="required">*</span></label>
                        <input type="date" name="request_date" class="form-control" value="{{ old('pr_date', date('Y-m-d')) }}" required>
                        @error('request_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan Tambahan</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Catatan opsional...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <span class="card-title"><i class="fas fa-cubes" style="color:var(--warning);margin-right:8px;"></i>Item Permintaan</span>
                <button type="button" class="btn btn-primary btn-sm" onclick="addItem()"><i class="fas fa-plus"></i> Tambah Item</button>
            </div>
            <div class="table-wrap">
                <table id="itemsTable">
                    <thead>
                        <tr>
                            <th>Material / Material <span class="required">*</span></th>
                            <th class="text-right" width="150">Qty Diminta <span class="required">*</span></th>
                            <th>Tujuan / Keperluan <span class="required">*</span></th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Items populated by JS -->
                    </tbody>
                </table>
            </div>
            @error('items') <div class="form-error" style="padding:10px 20px;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan PR</button>
            <a href="{{ route('purchase-requests.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>

<!-- Template Select Options (Hidden) -->
<select id="materialOptionsTemplate" style="display:none;">
    <option value="">-- Pilih Material --</option>
    @foreach($materials as $material)
        <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">
            {{ $material->code }} - {{ $material->name }}
        </option>
    @endforeach
</select>
@endsection

@push('scripts')
<script>
    const itemsTableBody = document.querySelector('#itemsTable tbody');
    const materialOptionsTemplate = document.getElementById('materialOptionsTemplate').innerHTML;
    let itemIndex = 0;

    function addItem() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="items[${itemIndex}][m_material_id]" class="form-control" onchange="updateRow(this)" required>
                    ${materialOptionsTemplate}
                </select>
            </td>
            <td>
                <div style="display:flex;align-items:center;gap:8px;">
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control" value="" min="0.01" step="0.01" style="font-size:13px;padding:6px;width:100%;" required>
                    <span class="unit-label" style="font-size:12px;color:var(--text-muted); width:30px;"></span>
                </div>
            </td>
            <td>
                <input type="text" name="items[${itemIndex}][purpose]" class="form-control" placeholder="Contoh: Stok / Produksi..." required style="font-size:13px;padding:6px;">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-ghost btn-sm" style="color:var(--danger);" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button>
            </td>
        `;
        itemsTableBody.appendChild(tr);
        itemIndex++;
    }

    function updateRow(selectObj) {
        const tr = selectObj.closest('tr');
        const unitLabel = tr.querySelector('.unit-label');

        const selectedOption = selectObj.options[selectObj.selectedIndex];
        
        if (selectedOption.value) {
            const unit = selectedOption.getAttribute('data-unit');
            unitLabel.textContent = unit;
        } else {
            unitLabel.textContent = '';
        }
    }

    // Add one item by default
    addItem();
</script>
@endpush