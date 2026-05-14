@extends('layouts.app')
@section('title', 'Buat Purchase Order')
@section('topbar-title', 'Buat Purchase Order')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('purchase-orders.index') }}">Purchase Order</a>
    <span class="sep">/</span>
    <span>Buat Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Buat Purchase Order (PO)</div>
        <div class="page-subtitle">Pesan material ke supplier</div>
    </div>
</div>

<div style="max-width:1000px;">
    <form action="{{ route('purchase-orders.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-invoice-dollar" style="color:var(--accent);margin-right:8px;"></i>Informasi PO</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">No. PO <span class="required">*</span></label>
                        <input type="text" name="po_number" class="form-control" value="{{ old('po_number', $autoNumber) }}" readonly style="background:var(--surface-2);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal PO <span class="required">*</span></label>
                        <input type="date" name="order_date" class="form-control" value="{{ old('order_date', date('Y-m-d')) }}" required>
                        @error('order_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Referensi PR <span class="required">*</span></label>
                        <select name="t_purchase_request_id" id="prSelect" class="form-control" required>
                            <option value="">-- Pilih Purchase Request --</option>
                            @foreach($purchaseRequests as $pr)
                                <option value="{{ $pr->id }}" data-items="{{ json_encode($pr->items) }}" {{ request('pr_id') == $pr->id ? 'selected' : '' }}>
                                    {{ $pr->pr_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Supplier <span class="required">*</span></label>
                        <select name="m_supplier_id" class="form-control" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Catatan PO...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <span class="card-title"><i class="fas fa-cubes" style="color:var(--warning);margin-right:8px;"></i>Item Pesanan (Berdasarkan PR)</span>
            </div>
            <div class="table-wrap">
                <table id="itemsTable">
                    <thead>
                        <tr>
                            <th>Item (Material)</th>
                            <th width="120">Qty</th>
                            <!-- Bintang required pindah ke Total Harga -->
                            <th width="200">Harga Satuan</th>
                            <th width="200">Total Harga <span class="required">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Items populated by JS -->
                        <tr id="emptyRow">
                            <td colspan="4" class="text-center" style="padding:20px; color:var(--text-muted);">Pilih Referensi PR untuk memuat item.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @error('items') <div class="form-error" style="padding:10px 20px;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan PO</button>
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const itemsTableBody = document.querySelector('#itemsTable tbody');
    const prSelect = document.getElementById('prSelect');
    let itemIndex = 0;

    function renderItemRow(index, itemData) {
        return `
            <tr>
                <td>
                    <input type="hidden" name="items[${index}][m_material_id]" value="${itemData.m_material_id}">
                    <input type="text" class="form-control" value="${itemData.material ? itemData.material.name : 'Unknown Material'}" readonly style="background:var(--surface-2); font-weight:500;">
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input type="number" id="qty_${index}" name="items[${index}][quantity]" class="form-control" value="${itemData.quantity}" readonly style="background:var(--surface-2); font-size:13px; padding:6px; width:100%;">
                        <span style="font-size:12px;color:var(--text-muted); width:30px;">${itemData.unit || 'Pcs'}</span>
                    </div>
                </td>
                <td>
                    <!-- Harga Satuan dibuat READONLY -->
                    <input type="number" id="price_per_qty_${index}" name="items[${index}][price_per_qty]" class="form-control" placeholder="0" readonly style="background:var(--surface-2); font-size:13px; padding:6px; width:100%;">
                </td>
                <td>
                    <!-- Total Harga dibuat BISA DIINPUT dan memicu JS oninput -->
                    <input type="number" id="price_${index}" name="items[${index}][price]" class="form-control" placeholder="0" min="0" step="0.01" oninput="calculateUnitPrice(${index})" required style="font-size:13px; padding:6px; width:100%;">
                </td>
            </tr>
        `;
    }

    // Fungsi JS untuk membagi Total Harga dengan Qty
    window.calculateUnitPrice = function(index) {
        const qty = parseFloat(document.getElementById(`qty_${index}`).value) || 0;
        const total = parseFloat(document.getElementById(`price_${index}`).value) || 0;
        let unitPrice = 0;
        
        if (qty > 0) {
            unitPrice = total / qty;
        }
        
        // Set ke field harga satuan
        document.getElementById(`price_per_qty_${index}`).value = unitPrice.toFixed(2);
    }

    prSelect.addEventListener('change', function() {
        itemsTableBody.innerHTML = '';
        itemIndex = 0;
        const selectedOption = prSelect.options[prSelect.selectedIndex];

        if(!selectedOption.value) {
            itemsTableBody.innerHTML = `<tr id="emptyRow"><td colspan="4" class="text-center" style="padding:20px; color:var(--text-muted);">Pilih Referensi PR untuk memuat item.</td></tr>`;
            return;
        }

        const items = JSON.parse(selectedOption.getAttribute('data-items'));
        items.forEach(item => {
            const tempDiv = document.createElement('table');
            tempDiv.innerHTML = renderItemRow(itemIndex, item);
            itemsTableBody.appendChild(tempDiv.querySelector('tr'));
            itemIndex++;
        });
    });

    if(prSelect.value) {
        prSelect.dispatchEvent(new Event('change'));
    }
</script>
@endpush