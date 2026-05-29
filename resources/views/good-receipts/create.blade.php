@extends('layouts.app')
@section('title', __('app.good_receipt.add'))
@section('topbar-title', __('app.nav.good_receipt') . ' — ' . __('app.nav.good_receipt'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('good-receipts.index') }}">{{ __('app.good_receipt.title') }}</a>
    <span class="sep">/</span>
    <span>{{ __("app.btn.add") }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ __('app.good_receipt.title') }}</div>
        <div class="page-subtitle">{{ __('app.good_receipt.subtitle') }}</div>
    </div>
</div>

<div style="max-width:980px;">
    <form action="{{ route('good-receipts.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-invoice" style="color:var(--accent);margin-right:8px;"></i>{{ __('app.good_receipt.info_header') }}</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('app.good_receipt.no_gr') }} <span class="required">*</span></label>
                        <input type="text" name="gr_number" class="form-control" value="{{ old('gr_number', $autoNumber) }}" readonly style="background:var(--surface-2);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('app.good_receipt.receive_date') }} <span class="required">*</span></label>
                        <input type="date" id="receiptDate" name="receipt_date" class="form-control" value="{{ old('receipt_date', date('Y-m-d')) }}" required>
                        @error('receipt_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('app.common.po_number') }} <span class="required">*</span></label>
                        <select name="t_purchase_order_id" id="poSelect" class="form-control" required>
                            <option value="">-- {{ __('app.common.po_number') }} --</option>
                            @foreach($purchaseOrders as $po)
                                <option value="{{ $po->id }}"
                                    data-items="{{ json_encode($po->items) }}"
                                    data-supplier-code="{{ $po->supplier->code ?? '' }}"
                                    {{ old('t_purchase_order_id') == $po->id ? 'selected' : '' }}>
                                    {{ $po->po_number }} - {{ $po->supplier->name ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('t_purchase_order_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __("app.warehouse.title") }} <span class="required">*</span></label>
                        <select name="m_warehouse_id" class="form-control" required>
                            <option value="">-- {{ __('app.common.warehouse') }} --</option>
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
                        <label class="form-label">{{ __('app.good_receipt.pic_receiver') }} <span class="required">*</span></label>
                        <select name="m_pic_id" class="form-control" required>
                            <option value="">-- {{ __('app.good_receipt.pic_receiver') }} --</option>
                            @foreach($users as $u)
                                @if(auth()->user()->isKaryawan() && auth()->id() === $u->id)
                                    <option value="{{ $u->id }}" selected disabled>{{ $u->name }} ({{ $u->role_label }})</option>
                                @else
                                    <option value="{{ $u->id }}" {{ old('m_pic_id', auth()->user()->isKaryawan() ? auth()->id() : '') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->role_label }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('m_pic_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __("app.common.additional_notes") }}</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="{{ __('app.common.additional_notes') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:20px;" id="itemsCard" style="display:none;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-boxes-stacked" style="color:var(--warning);margin-right:8px;"></i>{{ __('app.good_receipt.items_title') }}</span>
            </div>
            <div class="table-wrap">
                <table id="itemsTable">
                    <thead>
                        <tr>
                            <th>{{ __('app.common.item_name') }}</th>
                            <th width="160">{{ __('app.good_receipt.col_load_number') }} <span class="required">*</span>
                                <div style="font-size:10px;font-weight:400;color:var(--text-muted);">{{ __('app.good_receipt.load_col_hint') }}</div>
                            </th>
                            <th class="text-right">{{ __('app.good_receipt.col_po_qty') }}</th>
                            <th class="text-right" width="150">{{ __('app.good_receipt.col_received_qty') }} <span class="required">*</span></th>
                            <th>{{ __('app.good_receipt.condition') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5" class="text-center" style="padding:30px;color:var(--text-muted);">-- {{ __('app.common.po_number') }} --</td></tr>
                    </tbody>
                </table>
            </div>
            @error('items') <div class="form-error" style="padding:10px 20px;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fas fa-save"></i> {{ __('app.good_receipt.save_btn') }}</button>
            <a href="{{ route('good-receipts.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> {{ __("app.btn.cancel") }}</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const poSelect       = document.getElementById('poSelect');
    const receiptDate    = document.getElementById('receiptDate');
    const itemsTableBody = document.querySelector('#itemsTable tbody');
    const itemsCard      = document.getElementById('itemsCard');

    function generateLoadNumber() {
        const dateVal      = receiptDate.value;
        const supplierCode = poSelect.options[poSelect.selectedIndex]?.getAttribute('data-supplier-code') || '';
        if (!dateVal || !supplierCode) return '';
        const d  = new Date(dateVal);
        const yy = String(d.getFullYear()).slice(-2);
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        return `${yy}${mm}${dd}${supplierCode}`;
    }

    function updateLoadNumbers() {
        const suggested = generateLoadNumber();
        document.querySelectorAll('.load-number-input').forEach(input => {
            if (!input.dataset.manuallyEdited) input.value = suggested;
        });
    }

    function renderItems() {
        const selectedOption = poSelect.options[poSelect.selectedIndex];
        if (!selectedOption.value) {
            itemsTableBody.innerHTML = '<tr><td colspan="5" class="text-center" style="padding:30px;color:var(--text-muted);">-- {{ __("app.common.po_number") }} --</td></tr>';
            itemsCard.style.display = 'none';
            return;
        }

        const items = JSON.parse(selectedOption.getAttribute('data-items'));
        if (items.length === 0) {
            itemsTableBody.innerHTML = '<tr><td colspan="5" class="text-center" style="padding:30px;color:var(--text-muted);">{{ __("app.common.no_data") }}</td></tr>';
            itemsCard.style.display = 'block';
            return;
        }

        itemsCard.style.display = 'block';
        const suggested = generateLoadNumber();
        let html = '';

        items.forEach((item, index) => {
            const name         = item.material ? item.material.name  : (item.part ? item.part.part_name : '-');
            const code         = item.material ? item.material.code  : (item.part ? item.part.part_no   : '-');
            const unit         = item.material ? item.material.unit  : 'Pcs';
            const remainingQty = parseFloat(item.remaining_quantity);

            const matIdInput  = item.m_material_id ? `<input type="hidden" name="items[${index}][m_material_id]" value="${item.m_material_id}">` : '';
            const partIdInput = item.m_part_id     ? `<input type="hidden" name="items[${index}][m_part_id]"     value="${item.m_part_id}">` : '';

            html += `
                <tr>
                    <td>
                        <div style="font-weight:500;">${name}</div>
                        <div style="font-size:11px;color:var(--text-muted);">${code}</div>
                        <input type="hidden" name="items[${index}][t_purchase_order_item_id]" value="${item.id}">
                        ${matIdInput}${partIdInput}
                    </td>
                    <td>
                        <input type="text" name="items[${index}][load_material_number]"
                               class="form-control load-number-input"
                               value="${suggested}" placeholder="260527S01"
                               maxlength="50" style="font-size:12px;padding:6px;"
                               oninput="this.dataset.manuallyEdited='1'" required>
                    </td>
                    <td class="text-right">${remainingQty} ${unit}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <input type="number" name="items[${index}][quantity]" class="form-control"
                                   value="${remainingQty}" max="${remainingQty}" min="0.01" step="0.01"
                                   style="font-size:13px;padding:6px;width:100px;" required>
                            <span style="font-size:12px;color:var(--text-muted);">${unit}</span>
                        </div>
                    </td>
                    <td>
                        <select name="items[${index}][condition]" class="form-control" style="font-size:12px;padding:6px;">
                            <option value="good">{{ __('app.good_receipt.condition') }} - Good</option>
                            <option value="damaged">{{ __('app.good_receipt.condition') }} - Damaged</option>
                        </select>
                    </td>
                </tr>
            `;
        });

        itemsTableBody.innerHTML = html;
    }

    poSelect.addEventListener('change', renderItems);
    receiptDate.addEventListener('change', updateLoadNumbers);
    if (poSelect.value) renderItems();
</script>
@endpush
