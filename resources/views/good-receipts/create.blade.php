@extends('layouts.app')
@section('title', 'Tambah Good Receipt')
@section('topbar-title', 'Tambah Good Receipt')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('good-receipts.index') }}">Good Receipt</a>
    <span class="sep">/</span>
    <span>Tambah Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Penerimaan Barang (GR)</div>
        <div class="page-subtitle">Catat penerimaan barang dari supplier berdasarkan Purchase Order</div>
    </div>
</div>

<div style="max-width:900px;">
    <form action="{{ route('good-receipts.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-invoice" style="color:var(--accent);margin-right:8px;"></i>Informasi Penerimaan</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">No. GR <span class="required">*</span></label>
                        <input type="text" name="gr_number" class="form-control" value="{{ old('gr_number', $autoNumber) }}" readonly style="background:var(--surface-2);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Terima <span class="required">*</span></label>
                        <input type="date" name="receipt_date" class="form-control" value="{{ old('receipt_date', date('Y-m-d')) }}" required>
                        @error('receipt_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Purchase Order (PO) <span class="required">*</span></label>
                        <select name="t_purchase_order_id" id="poSelect" class="form-control" required>
                            <option value="">-- Pilih PO --</option>
                            @foreach($purchaseOrders as $po)
                                <option value="{{ $po->id }}" data-items="{{ json_encode($po->items) }}" {{ old('t_purchase_order_id') == $po->id ? 'selected' : '' }}>
                                    {{ $po->po_number }} - {{ $po->supplier->name ?? 'Unknown Supplier' }}
                                </option>
                            @endforeach
                        </select>
                        @error('t_purchase_order_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gudang Penyimpanan <span class="required">*</span></label>
                        <select name="m_warehouse_id" class="form-control" required>
                            <option value="">-- Pilih Gudang --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ old('m_warehouse_id') == $wh->id ? 'selected' : '' }}>
                                    {{ $wh->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('m_warehouse_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">PIC Penerima <span class="required">*</span></label>
                        <select name="m_pic_id" class="form-control" required>
                            <option value="">-- Pilih PIC --</option>
                            @foreach($pics as $pic)
                                <option value="{{ $pic->id }}" {{ old('m_pic_id') == $pic->id ? 'selected' : '' }}>
                                    {{ $pic->name }} - {{ $pic->position }}
                                </option>
                            @endforeach
                        </select>
                        @error('m_pic_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catatan Tambahan</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="Opsional">
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:20px;" id="itemsCard" style="display:none;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-boxes-stacked" style="color:var(--warning);margin-right:8px;"></i>Item Diterima</span>
            </div>
            <div class="table-wrap">
                <table id="itemsTable">
                    <thead>
                        <tr>
                            <th>Material/Part</th>
                            <th class="text-right">Qty PO</th>
                            <th class="text-right" width="150">Qty Diterima <span class="required">*</span></th>
                            <th>Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Items will be populated by JS -->
                        <tr><td colspan="5" class="text-center" style="padding:30px;color:var(--text-muted);">Pilih Purchase Order terlebih dahulu</td></tr>
                    </tbody>
                </table>
            </div>
            @error('items') <div class="form-error" style="padding:10px 20px;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fas fa-save"></i> Simpan Good Receipt</button>
            <a href="{{ route('good-receipts.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const poSelect = document.getElementById('poSelect');
    const itemsTableBody = document.querySelector('#itemsTable tbody');
    const itemsCard = document.getElementById('itemsCard'); // tambahkan id itemsCard di html Anda
    const warehouses = @json($warehouses);

    function renderItems() {
        const selectedOption = poSelect.options[poSelect.selectedIndex];
        
        if(!selectedOption.value) {
            itemsTableBody.innerHTML = '<tr><td colspan="5" class="text-center" style="padding:30px;color:var(--text-muted);">Pilih Purchase Order terlebih dahulu</td></tr>';
            itemsCard.style.display = 'none'; // Sembunyikan jika kosong
            return;
        }

        const items = JSON.parse(selectedOption.getAttribute('data-items'));
        if(items.length === 0) {
            itemsTableBody.innerHTML = '<tr><td colspan="5" class="text-center" style="padding:30px;color:var(--text-muted);">Semua item pada PO ini sudah diterima (Fully Received).</td></tr>';
            itemsCard.style.display = 'block';
            return;
        }

        itemsCard.style.display = 'block';
        let html = '';

        items.forEach((item, index) => {
            const name = item.material ? item.material.name : (item.part ? item.part.part_name : '-');
            const code = item.material ? item.material.code : (item.part ? item.part.part_no : '-');
            const unit = item.material ? item.material.unit : 'Pcs';
            
            // Mengambil sisa yang dioper dari controller
            const remainingQty = parseFloat(item.remaining_quantity); 
            
            const matIdInput = item.m_material_id ? `<input type="hidden" name="items[${index}][m_material_id]" value="${item.m_material_id}">` : '';
            const partIdInput = item.m_part_id ? `<input type="hidden" name="items[${index}][m_part_id]" value="${item.m_part_id}">` : '';

            html += `
                <tr>
                    <td>
                        <div style="font-weight:500;">${name}</div>
                        <div style="font-size:11px;color:var(--text-muted);">${code}</div>
                        <div style="font-size:11px;color:var(--accent);">Sisa: ${remainingQty} dari Total PO: ${item.quantity}</div>
                        
                        <input type="hidden" name="items[${index}][t_purchase_order_item_id]" value="${item.id}">
                        ${matIdInput}
                        ${partIdInput}
                    </td>
                    <td class="text-right">
                        ${remainingQty} ${unit} <!-- Menampilkan sisa saja -->
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <!-- Set value dan max menjadi remainingQty -->
                            <input type="number" name="items[${index}][quantity]" class="form-control" value="${remainingQty}" max="${remainingQty}" min="0.01" step="0.01" style="font-size:13px;padding:6px;width:100px;" required>
                            <span style="font-size:12px;color:var(--text-muted);">${unit}</span>
                        </div>
                    </td>
                    <td>
                        <select name="items[${index}][condition]" class="form-control" style="font-size:12px;padding:6px;">
                            <option value="good">Good</option>
                            <option value="damaged">Damaged</option>
                        </select>
                    </td>
                </tr>
            `;
        });

        itemsTableBody.innerHTML = html;
    }

    poSelect.addEventListener('change', renderItems);
    
    // Initial render if old data exists
    if(poSelect.value) {
        renderItems();
    }
</script>
@endpush
