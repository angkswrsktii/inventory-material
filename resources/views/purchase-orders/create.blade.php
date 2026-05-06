@extends('layouts.app')
@section('title', 'Buat Purchase Order')
@section('topbar-title', 'Purchase Order')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('purchase-orders.index') }}">Purchase Order</a>
    <span class="sep">/</span>
    <span>Buat Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Buat Purchase Order</div>
        <div class="page-subtitle">No. Dokumen: <strong style="color:var(--accent)">{{ $documentNo }}</strong></div>
    </div>
</div>

<form action="{{ route('purchase-orders.store') }}" method="POST" id="poForm">
@csrf

<div style="display:grid; grid-template-columns:1fr 360px; gap:20px; align-items:start;">

    {{-- Kiri: Pilih PR + Items --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Pilih PR --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <i class="fas fa-file-check" style="color:var(--accent);margin-right:8px;"></i>
                    Pilih Purchase Request
                </span>
            </div>
            <div class="card-body">
                @if($approvedPRs->isEmpty())
                    <div class="alert alert-warning">
                        <i class="fas fa-triangle-exclamation"></i>
                        Belum ada Purchase Request yang disetujui. Minta Pimpinan untuk menyetujui PR terlebih dahulu.
                    </div>
                @else
                <div class="form-group">
                    <label class="form-label">Purchase Request yang Disetujui <span class="required">*</span></label>
                    <select name="purchase_request_id" class="form-control" required id="prSelect" onchange="loadPRItems(this.value)">
                        <option value="">-- Pilih PR --</option>
                        @foreach($approvedPRs as $pr)
                            <option value="{{ $pr->id }}"
                                    data-items="{{ json_encode($pr->items) }}"
                                    {{ (request('pr_id') == $pr->id || ($selectedPR && $selectedPR->id == $pr->id)) ? 'selected' : '' }}>
                                {{ $pr->document_no }} — {{ $pr->requested_by_name }}
                                ({{ $pr->request_date->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>

        {{-- Items Material --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <i class="fas fa-boxes-stacked" style="color:var(--accent);margin-right:8px;"></i>
                    Item Material yang Dipesan
                </span>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addItem()">
                    <i class="fas fa-plus"></i> Tambah Item
                </button>
            </div>
            <div class="table-wrap">
                <table id="itemsTable">
                    <thead>
                        <tr>
                            <th>Nama Material</th>
                            <th>Spesifikasi</th>
                            <th>Satuan</th>
                            <th class="text-right">Qty Order</th>
                            <th class="text-right">Harga Satuan (Rp)</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr id="empty-row">
                            <td colspan="7" style="text-align:center; padding:24px; color:var(--text-dim);">
                                <i class="fas fa-arrow-up" style="margin-right:6px;"></i>
                                Pilih Purchase Request di atas untuk mengisi item otomatis
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="background:var(--surface-2);">
                            <td colspan="5" style="padding:12px 16px; font-weight:600; text-align:right; color:var(--text-muted);">
                                TOTAL ESTIMASI:
                            </td>
                            <td class="text-right" style="padding:12px 16px; font-weight:700; color:var(--accent); font-size:15px;">
                                Rp <span id="grandTotal">0</span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Kanan: Info PO --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-file-invoice" style="color:var(--accent-2);margin-right:8px;"></i>
                Informasi PO
            </span>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">No. Dokumen</label>
                <input type="text" class="form-control" value="{{ $documentNo }}" disabled
                       style="opacity:0.6; font-family:monospace; color:var(--accent);">
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Order <span class="required">*</span></label>
                <input type="date" name="order_date" class="form-control"
                       value="{{ old('order_date', date('Y-m-d')) }}" required>
                @error('order_date') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Estimasi Tanggal Terima</label>
                <input type="date" name="expected_date" class="form-control"
                       value="{{ old('expected_date') }}">
                @error('expected_date') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Supplier <span class="required">*</span></label>
                @if(isset($suppliers) && $suppliers->isEmpty())
                    <div class="alert alert-warning" style="font-size:13px;">
                        <i class="fas fa-triangle-exclamation"></i>
                        Belum ada supplier. <a href="{{ route('suppliers.create') }}" style="color:var(--accent);">Tambah supplier</a> terlebih dahulu.
                    </div>
                @else
                <select name="supplier_id" class="form-control" required id="supplierSelect"
                        onchange="fillSupplierContact(this)">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers ?? [] as $s)
                        <option value="{{ $s->id }}"
                                data-phone="{{ $s->phone }}"
                                data-contact="{{ $s->contact_person }}"
                                {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}{{ $s->phone ? ' — '.$s->phone : '' }}
                        </option>
                    @endforeach
                </select>
                @endif
                @error('supplier_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kontak Supplier</label>
                <input type="text" name="supplier_contact" id="supplierContact" class="form-control"
                       value="{{ old('supplier_contact') }}" placeholder="Terisi otomatis saat pilih supplier">
            </div>

            <div class="form-group">
                <label class="form-label">Termin Pembayaran</label>
                <select name="payment_terms" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="Cash" {{ old('payment_terms') === 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="NET 7"  {{ old('payment_terms') === 'NET 7'  ? 'selected' : '' }}>NET 7 hari</option>
                    <option value="NET 14" {{ old('payment_terms') === 'NET 14' ? 'selected' : '' }}>NET 14 hari</option>
                    <option value="NET 30" {{ old('payment_terms') === 'NET 30' ? 'selected' : '' }}>NET 30 hari</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Pengiriman</label>
                <textarea name="delivery_address" class="form-control" rows="2"
                          placeholder="Alamat gudang tujuan">{{ old('delivery_address') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control" rows="2"
                          placeholder="Instruksi khusus ke supplier...">{{ old('notes') }}</textarea>
            </div>

            <div class="divider"></div>

            <button type="submit" class="btn btn-primary" style="width:100%;">
                <i class="fas fa-save"></i> Buat Purchase Order
            </button>
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-ghost"
               style="width:100%; margin-top:8px; justify-content:center;">
                <i class="fas fa-times"></i> Batal
            </a>

            <div style="margin-top:14px; padding:12px; background:var(--surface-2); border-radius:var(--radius-sm); font-size:12px; color:var(--text-muted);">
                <i class="fas fa-info-circle" style="color:var(--accent);margin-right:4px;"></i>
                Setelah PO dibuat, status PR otomatis berubah menjadi <strong>"Sudah PO"</strong>.
                PO perlu dikonfirmasi oleh Pimpinan sebelum dikirim ke supplier.
            </div>
        </div>
    </div>

</div>
</form>
@endsection

@push('scripts')
<script>
let rowIndex = 0;

// Load items dari PR yang dipilih
function loadPRItems(prId) {
    if (!prId) {
        document.getElementById('itemsBody').innerHTML = `
            <tr id="empty-row">
                <td colspan="7" style="text-align:center; padding:24px; color:var(--text-dim);">
                    Pilih Purchase Request di atas untuk mengisi item otomatis
                </td>
            </tr>`;
        updateTotal();
        return;
    }

    const select  = document.getElementById('prSelect');
    const option  = select.options[select.selectedIndex];
    const items   = JSON.parse(option.dataset.items || '[]');

    document.getElementById('itemsBody').innerHTML = '';
    rowIndex = 0;

    if (items.length === 0) {
        document.getElementById('itemsBody').innerHTML = `
            <tr><td colspan="7" style="text-align:center; padding:20px; color:var(--text-dim);">PR ini tidak memiliki item.</td></tr>`;
        return;
    }

    items.forEach(item => {
        addRow({
            prItemId:      item.id,
            materialId:    item.material_id || '',
            materialName:  item.material_name,
            materialCode:  item.material_code || '',
            unit:          item.unit,
            specification: item.specification || '',
            qty:           item.quantity_approved || item.quantity_requested,
            price:         item.estimated_price || '',
            notes:         item.item_notes || '',
        });
    });

    updateTotal();
}

function addItem() {
    addRow({});
}

function addRow(data = {}) {
    const idx = rowIndex++;
    const row = `
    <tr id="row_${idx}">
        <td>
            <input type="hidden" name="items[${idx}][purchase_request_item_id]" value="${data.prItemId || ''}">
            <input type="hidden" name="items[${idx}][material_id]" value="${data.materialId || ''}">
            <input type="hidden" name="items[${idx}][material_code]" value="${data.materialCode || ''}">
            <input type="text" name="items[${idx}][material_name]" class="form-control"
                   value="${data.materialName || ''}" placeholder="Nama material" required style="min-width:160px;">
        </td>
        <td>
            <input type="text" name="items[${idx}][specification]" class="form-control"
                   value="${data.specification || ''}" placeholder="Spesifikasi" style="min-width:120px;">
        </td>
        <td>
<select name="items[${idx}][unit]" class="form-control unit-select" required style="width:90px;">
                <option value="">--</option>
                <option value="Pcs" ${data.unit==='Pcs'?'selected':''}>Pcs</option>
                <option value="Kg" ${data.unit==='Kg'?'selected':''}>Kg</option>
                <option value="Gram" ${data.unit==='Gram'?'selected':''}>Gram</option>
                <option value="Ltr" ${data.unit==='Ltr'?'selected':''}>Ltr</option>
                <option value="mL" ${data.unit==='mL'?'selected':''}>mL</option>
                <option value="Meter" ${data.unit==='Meter'?'selected':''}>Meter</option>
                <option value="cm" ${data.unit==='cm'?'selected':''}>cm</option>
                <option value="mm" ${data.unit==='mm'?'selected':''}>mm</option>
                <option value="Roll" ${data.unit==='Roll'?'selected':''}>Roll</option>
                <option value="Lembar" ${data.unit==='Lembar'?'selected':''}>Lembar</option>
                <option value="Dus" ${data.unit==='Dus'?'selected':''}>Dus</option>
                <option value="Karton" ${data.unit==='Karton'?'selected':''}>Karton</option>
                <option value="Lusin" ${data.unit==='Lusin'?'selected':''}>Lusin</option>
                <option value="Set" ${data.unit==='Set'?'selected':''}>Set</option>
                <option value="Unit" ${data.unit==='Unit'?'selected':''}>Unit</option>
                <option value="Sak" ${data.unit==='Sak'?'selected':''}>Sak</option>
                <option value="Batang" ${data.unit==='Batang'?'selected':''}>Batang</option>
            </select>
        </td>
        <td>
            <input type="number" name="items[${idx}][quantity_ordered]" class="form-control text-right"
                   value="${data.qty || ''}" min="0.01" step="0.01" placeholder="0"
                   required style="width:90px;" oninput="updateTotal()">
        </td>
        <td>
            <input type="number" name="items[${idx}][unit_price]" class="form-control text-right"
                   value="${data.price || ''}" min="0" step="100" placeholder="0"
                   style="width:130px;" oninput="updateTotal()">
        </td>
        <td class="text-right" style="font-weight:600; min-width:120px;">
            <span id="total_${idx}" style="color:var(--accent);">Rp 0</span>
        </td>
        <td class="text-center">
            <button type="button" onclick="removeRow(${idx})" class="btn btn-xs btn-danger">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>`;
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
    // Hitung total awal jika ada harga & qty
    if (data.qty && data.price) {
        document.getElementById(`total_${idx}`).textContent = 'Rp ' + formatNumber(data.qty * data.price);
    }
}

function removeRow(idx) {
    const rows = document.getElementById('itemsBody').querySelectorAll('tr');
    if (rows.length <= 1) { alert('Minimal harus ada 1 item.'); return; }
    document.getElementById('row_' + idx)?.remove();
    updateTotal();
}

function updateTotal() {
    let grand = 0;
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        const qty   = parseFloat(row.querySelector('input[name*="[quantity_ordered]"]')?.value) || 0;
        const price = parseFloat(row.querySelector('input[name*="[unit_price]"]')?.value) || 0;
        const total = qty * price;
        const idx   = row.id?.replace('row_', '');
        if (idx !== undefined) {
            const el = document.getElementById('total_' + idx);
            if (el) el.textContent = 'Rp ' + formatNumber(total);
        }
        grand += total;
    });
    document.getElementById('grandTotal').textContent = formatNumber(grand);
}

function formatNumber(n) {
    return n.toLocaleString('id-ID', { minimumFractionDigits: 0 });
}

function fillSupplierContact(sel) {
    const opt = sel.options[sel.selectedIndex];
    const contact = document.getElementById('supplierContact');
    if (contact) {
        contact.value = opt.dataset.phone || opt.dataset.contact || '';
    }
}

// Jika ada selectedPR dari URL parameter, load otomatis
document.addEventListener('DOMContentLoaded', function () {
    const prSelect = document.getElementById('prSelect');
    if (prSelect && prSelect.value) {
        loadPRItems(prSelect.value);
    }
});
</script>
@endpush