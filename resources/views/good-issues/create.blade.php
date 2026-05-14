@extends('layouts.app')
@section('title', 'Tambah Good Issue')
@section('topbar-title', 'Tambah Good Issue')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('good-issues.index') }}">Good Issue</a>
    <span class="sep">/</span>
    <span>Tambah Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Pengeluaran Barang (GI)</div>
        <div class="page-subtitle">Catat pengeluaran barang dari gudang untuk produksi atau lainnya</div>
    </div>
</div>

<div style="max-width:900px;">
    <form action="{{ route('good-issues.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-invoice" style="color:var(--accent);margin-right:8px;"></i>Informasi Pengeluaran</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">No. GI <span class="required">*</span></label>
                        <input type="text" name="gi_number" class="form-control" value="{{ old('gi_number', $autoNumber) }}" readonly style="background:var(--surface-2);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Keluar <span class="required">*</span></label>
                        <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', date('Y-m-d')) }}" required>
                        @error('issue_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Part (Target Produksi) <span class="required">*</span></label>
                        <select name="m_part_id" class="form-control" required>
                            <option value="">-- Pilih Target Part --</option>
                            @foreach($parts as $part)
                                <option value="{{ $part->id }}" {{ old('m_part_id') == $part->id ? 'selected' : '' }}>
                                    {{ $part->part_no }} - {{ $part->part_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('m_part_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">PIC Pemotong <span class="required">*</span></label>
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
                        <label class="form-label">Project <span class="required">*</span></label>
                        <select name="m_prjk_id" class="form-control" required>
                            <option value="">-- Pilih Project --</option>
                            @foreach($project as $prjk)
                                <option value="{{ $prjk->id }}" {{ old('m_prjk_id') == $prjk->id ? 'selected' : '' }}>
                                    {{ $prjk->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('m_prjk_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tujuan / Catatan <span class="required">*</span></label>
                    <textarea name="purpose" class="form-control" rows="2" placeholder="Contoh: Produksi Part A, Line B..." required>{{ old('purpose') }}</textarea>
                    @error('purpose') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <span class="card-title"><i class="fas fa-boxes-stacked" style="color:var(--warning);margin-right:8px;"></i>Item Dikeluarkan</span>
                <button type="button" class="btn btn-primary btn-sm" onclick="addItem()"><i class="fas fa-plus"></i> Tambah Item</button>
            </div>
            <div class="table-wrap">
                <table id="itemsTable">
                    <thead>
                        <tr>
                            <th>Stok Item (Material/Part di Gudang) <span class="required">*</span></th>
                            <th class="text-right" width="120">Stok Tersedia</th>
                            <th class="text-right" width="150">Qty Dikeluarkan <span class="required">*</span></th>
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
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Good Issue</button>
            <a href="{{ route('good-issues.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>

<!-- Template Select Options (Hidden) -->
<select id="stockOptionsTemplate" style="display:none;">
    <option value="">-- Pilih Stok Item --</option>
    @foreach($stocks as $stock)
        @php
            $name = $stock->material->name ?? $stock->part->part_name ?? '-';
            $code = $stock->material->code ?? $stock->part->part_no ?? '-';
            $unit = $stock->material->unit ?? 'Pcs';
            $warehouse = $stock->warehouse->name ?? '-';
            $text = "$code - $name ($warehouse)";
        @endphp
        <option value="{{ $stock->id }}" data-qty="{{ $stock->current_stock }}" data-unit="{{ $unit }}">
            {{ $text }}
        </option>
    @endforeach
</select>
@endsection

@push('scripts')
<script>
    const itemsTableBody = document.querySelector('#itemsTable tbody');
    const stockOptionsTemplate = document.getElementById('stockOptionsTemplate').innerHTML;
    let itemIndex = 0;

    function addItem() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="items[${itemIndex}][m_stock_id]" class="form-control stock-select" onchange="updateRow(this)" required>
                    ${stockOptionsTemplate}
                </select>
            </td>
            <td class="text-right stock-available" style="color:var(--text-muted); font-weight:500;">
                -
            </td>
            <td>
                <div style="display:flex;align-items:center;gap:8px;">
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control qty-input" value="" min="0.01" step="0.01" style="font-size:13px;padding:6px;width:100%;" required disabled>
                    <span class="unit-label" style="font-size:12px;color:var(--text-muted);"></span>
                </div>
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
        const availableCell = tr.querySelector('.stock-available');
        const qtyInput = tr.querySelector('.qty-input');
        const unitLabel = tr.querySelector('.unit-label');

        const selectedOption = selectObj.options[selectObj.selectedIndex];
        
        if (selectedOption.value) {
            const maxQty = parseFloat(selectedOption.getAttribute('data-qty'));
            const unit = selectedOption.getAttribute('data-unit');

            availableCell.textContent = maxQty + ' ' + unit;
            qtyInput.max = maxQty;
            qtyInput.disabled = false;
            unitLabel.textContent = unit;
        } else {
            availableCell.textContent = '-';
            qtyInput.value = '';
            qtyInput.disabled = true;
            unitLabel.textContent = '';
        }
    }

    // Add one item by default
    addItem();
</script>
@endpush