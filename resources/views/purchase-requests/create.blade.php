@extends('layouts.app')
@section('title', 'Buat Purchase Request')
@section('topbar-title', 'Purchase Request')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('purchase-requests.index') }}">Purchase Request</a>
    <span class="sep">/</span>
    <span>Buat Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Buat Purchase Request</div>
        <div class="page-subtitle">No. Dokumen: <strong style="color:var(--accent)">{{ $documentNo }}</strong></div>
    </div>
</div>

<form action="{{ route('purchase-requests.store') }}" method="POST" id="prForm">
@csrf

<div style="display:grid; grid-template-columns:1fr 360px; gap:20px; align-items:start;">

    {{-- LEFT: Item Material --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-boxes-stacked" style="color:var(--accent);margin-right:8px;"></i>
                Daftar Material yang Diminta
            </span>
            <button type="button" class="btn btn-primary btn-sm" onclick="addRow()">
                <i class="fas fa-plus"></i> Tambah Baris
            </button>
        </div>
        <div class="table-wrap">
            <table id="itemsTable">
                <thead>
                    <tr>
                        <th style="min-width:200px;">Material / Nama Barang</th>
                        <th style="min-width:100px;">Satuan</th>
                        <th style="min-width:80px;">Spesifikasi</th>
                        <th class="text-right" style="min-width:100px;">Qty Diminta</th>
                        <th class="text-right" style="min-width:130px;">Harga Est. (Rp)</th>
                        <th style="min-width:120px;">Catatan Item</th>
                        <th class="text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    <tr id="row_0">
                        {{-- Material select / input --}}
                        <td>
                            <select name="items[0][material_id]" class="form-control mat-select" onchange="fillFromMaterial(this, 0)">
                                <option value="">— Material Baru —</option>
                                @foreach($materials as $m)
                                    <option value="{{ $m->id }}"
                                        data-code="{{ $m->code }}"
                                        data-name="{{ $m->name }}"
                                        data-unit="{{ $m->unit }}"
                                        data-spec="{{ $m->specification }}">
                                        [{{ $m->code }}] {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="items[0][material_name]" class="form-control mat-name"
                                   placeholder="Nama material / barang" required style="margin-top:5px;">
                            <input type="hidden" name="items[0][material_code]" class="mat-code">
                        </td>
                        <td>
                            <select name="items[0][unit]" class="form-control unit-select" required style="min-width:90px;">
                                <option value="">--</option>
                                <option value="Pcs">Pcs</option>
                                <option value="Kg">Kg</option>
                                <option value="Gram">Gram</option>
                                <option value="Ltr">Ltr</option>
                                <option value="mL">mL</option>
                                <option value="Meter">Meter</option>
                                <option value="cm">cm</option>
                                <option value="mm">mm</option>
                                <option value="Roll">Roll</option>
                                <option value="Lembar">Lembar</option>
                                <option value="Dus">Dus</option>
                                <option value="Karton">Karton</option>
                                <option value="Lusin">Lusin</option>
                                <option value="Set">Set</option>
                                <option value="Unit">Unit</option>
                                <option value="Sak">Sak</option>
                                <option value="Batang">Batang</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="items[0][specification]" class="form-control" placeholder="Opsional">
                        </td>
                        <td>
                            <input type="number" name="items[0][quantity_requested]" class="form-control text-right"
                                   min="0.01" step="0.01" placeholder="0" required oninput="updateSubtotal(0)">
                        </td>
                        <td>
                            <input type="number" name="items[0][estimated_price]" class="form-control text-right est-price"
                                   min="0" step="1" placeholder="0" oninput="updateSubtotal(0)">
                            <div id="subtotal_0" style="font-size:11px;color:var(--text-muted);text-align:right;margin-top:3px;"></div>
                        </td>
                        <td>
                            <input type="text" name="items[0][item_notes]" class="form-control" placeholder="Opsional">
                        </td>
                        <td class="text-center">
                            <button type="button" onclick="removeRow(0)" class="btn btn-xs btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right;font-size:12px;color:var(--text-muted);padding:10px 16px;">
                            <strong>Total Estimasi:</strong>
                        </td>
                        <td style="padding:10px 16px;text-align:right;">
                            <strong id="grandTotal" style="color:var(--accent);font-size:13px;">Rp 0</strong>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="padding:14px 20px; border-top:1px solid var(--border); background:var(--surface-2);">
            <div style="font-size:12px; color:var(--text-muted);">
                <i class="fas fa-info-circle"></i>
                Pilih material dari master data atau ketik nama barang baru yang belum terdaftar.
                Harga estimasi bersifat opsional.
            </div>
        </div>
    </div>

    {{-- RIGHT: Header Info --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-file-invoice" style="color:var(--accent-2);margin-right:8px;"></i>
                Informasi Permintaan
            </span>
        </div>
        <div class="card-body">

            <div class="form-group">
                <label class="form-label">No. Dokumen</label>
                <input type="text" class="form-control" value="{{ $documentNo }}" disabled
                       style="opacity:0.6; font-family:monospace; color:var(--accent);">
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Request <span class="required">*</span></label>
                <input type="date" name="request_date" class="form-control"
                       value="{{ old('request_date', date('Y-m-d')) }}" required>
                @error('request_date') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nama Pemohon <span class="required">*</span></label>
                <input type="text" name="requested_by_name" class="form-control"
                       value="{{ old('requested_by_name', auth()->user()->name) }}"
                       placeholder="Nama lengkap pemohon" required>
                @error('requested_by_name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Departemen / Bagian</label>
                <input type="text" name="department" class="form-control"
                       value="{{ old('department') }}" placeholder="Contoh: Produksi, QC, Gudang">
                @error('department') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Keperluan / Tujuan Pembelian</label>
                <textarea name="purpose" class="form-control" rows="2"
                          placeholder="Untuk apa material ini dibutuhkan...">{{ old('purpose') }}</textarea>
                @error('purpose') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="notes" class="form-control" rows="2"
                          placeholder="Catatan lain...">{{ old('notes') }}</textarea>
            </div>

            <div class="divider"></div>

            {{-- Submit buttons --}}
            <div style="display:flex; flex-direction:column; gap:8px;">
                <button type="submit" name="action" value="submit" class="btn btn-primary" style="width:100%;">
                    <i class="fas fa-paper-plane"></i> Ajukan PR
                </button>
                <button type="submit" name="action" value="draft" class="btn btn-secondary" style="width:100%;">
                    <i class="fas fa-floppy-disk"></i> Simpan sebagai Draft
                </button>
                <a href="{{ route('purchase-requests.index') }}" class="btn btn-ghost" style="width:100%; justify-content:center;">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

        </div>
    </div>

</div>
</form>
@endsection

@push('scripts')
<script>
let rowCount = 1;
const materialsData = @json($materials->keyBy('id'));

function fillFromMaterial(select, idx) {
    const id = select.value;
    const row = document.getElementById('row_' + idx);
    const nameInput = row.querySelector('.mat-name');
    const codeInput = row.querySelector('.mat-code');
    const unitInput = row.querySelector('select[name="items[' + idx + '][unit]"]');
    const specInput = row.querySelector('input[name="items[' + idx + '][specification]"]');

    if (id && materialsData[id]) {
        const m = materialsData[id];
        nameInput.value = m.name;
        codeInput.value = m.code;
        // Set selected option yg sesuai dengan unit material
        if (unitInput) {
            const opts = unitInput.querySelectorAll('option');
            opts.forEach(o => { o.selected = (o.value === m.unit); });
            if (![...opts].some(o => o.value === m.unit) && m.unit) {
                const opt = document.createElement('option');
                opt.value = m.unit; opt.text = m.unit; opt.selected = true;
                unitInput.appendChild(opt);
            }
        }
        specInput.value = m.specification || '';
    } else {
        nameInput.value = '';
        codeInput.value = '';
    }
}

function updateSubtotal(idx) {
    const row = document.getElementById('row_' + idx);
    if (!row) return;
    const qty = parseFloat(row.querySelector('input[name="items[' + idx + '][quantity_requested]"]')?.value) || 0;
    const price = parseFloat(row.querySelector('.est-price')?.value) || 0;
    const sub = qty * price;
    const el = document.getElementById('subtotal_' + idx);
    if (el) el.textContent = sub > 0 ? 'Subtotal: Rp ' + sub.toLocaleString('id-ID') : '';
    updateGrandTotal();
}

function updateGrandTotal() {
    let total = 0;
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        const idx = row.id.replace('row_', '');
        const qty = parseFloat(row.querySelector('input[name="items[' + idx + '][quantity_requested]"]')?.value) || 0;
        const price = parseFloat(row.querySelector('.est-price')?.value) || 0;
        total += qty * price;
    });
    document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function addRow() {
    const idx = rowCount++;
    let options = '<option value="">— Material Baru —</option>';
    Object.values(materialsData).forEach(m => {
        options += `<option value="${m.id}" data-code="${m.code}" data-name="${m.name}" data-unit="${m.unit}" data-spec="${m.specification || ''}">[${m.code}] ${m.name}</option>`;
    });

    const row = `<tr id="row_${idx}">
        <td>
            <select name="items[${idx}][material_id]" class="form-control mat-select" onchange="fillFromMaterial(this,${idx})">${options}</select>
            <input type="text" name="items[${idx}][material_name]" class="form-control mat-name" placeholder="Nama material / barang" required style="margin-top:5px;">
            <input type="hidden" name="items[${idx}][material_code]" class="mat-code">
        </td>
        <td><select name="items[${idx}][unit]" class="form-control unit-select" required style="min-width:90px;">
                <option value="">--</option>
                <option value="Pcs">Pcs</option>
                <option value="Kg">Kg</option>
                <option value="Gram">Gram</option>
                <option value="Ltr">Ltr</option>
                <option value="mL">mL</option>
                <option value="Meter">Meter</option>
                <option value="cm">cm</option>
                <option value="mm">mm</option>
                <option value="Roll">Roll</option>
                <option value="Lembar">Lembar</option>
                <option value="Dus">Dus</option>
                <option value="Karton">Karton</option>
                <option value="Lusin">Lusin</option>
                <option value="Set">Set</option>
                <option value="Unit">Unit</option>
                <option value="Sak">Sak</option>
                <option value="Batang">Batang</option>
            </select></td>
        <td><input type="text" name="items[${idx}][specification]" class="form-control" placeholder="Opsional"></td>
        <td><input type="number" name="items[${idx}][quantity_requested]" class="form-control text-right" min="0.01" step="0.01" placeholder="0" required oninput="updateSubtotal(${idx})"></td>
        <td>
            <input type="number" name="items[${idx}][estimated_price]" class="form-control text-right est-price" min="0" step="1" placeholder="0" oninput="updateSubtotal(${idx})">
            <div id="subtotal_${idx}" style="font-size:11px;color:var(--text-muted);text-align:right;margin-top:3px;"></div>
        </td>
        <td><input type="text" name="items[${idx}][item_notes]" class="form-control" placeholder="Opsional"></td>
        <td class="text-center"><button type="button" onclick="removeRow(${idx})" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button></td>
    </tr>`;

    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
}

function removeRow(idx) {
    const rows = document.getElementById('itemsBody').querySelectorAll('tr');
    if (rows.length <= 1) { alert('Minimal harus ada 1 item material.'); return; }
    const row = document.getElementById('row_' + idx);
    if (row) { row.remove(); updateGrandTotal(); }
}
</script>
@endpush