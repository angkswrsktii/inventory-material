@extends('layouts.app')
@section('title', __('app.good_issue.add'))
@section('topbar-title', __('app.nav.good_issue') . ' — ' . __('app.nav.good_issue'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('good-issues.index') }}">{{ __('app.good_issue.title') }}</a>
    <span class="sep">/</span>
    <span>{{ __("app.btn.add") }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ __('app.good_issue.title') }}</div>
        <div class="page-subtitle">{{ __('app.good_issue.subtitle') }}</div>
    </div>
</div>

<div style="max-width:980px;">
    <form action="{{ route('good-issues.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-invoice" style="color:var(--accent);margin-right:8px;"></i>{{ __('app.good_issue.info_header') }}</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('app.good_issue.no_gi') }} <span class="required">*</span></label>
                        <input type="text" name="gi_number" class="form-control" value="{{ old('gi_number', $autoNumber) }}" readonly style="background:var(--surface-2);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __("app.good_issue.issue_date") }} <span class="required">*</span></label>
                        <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', date('Y-m-d')) }}" required>
                        @error('issue_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('app.good_issue.part_target') }} <span class="required">*</span></label>
                        <select name="m_part_id" class="form-control" required>
                            <option value="">-- {{ __('app.good_issue.part_target') }} --</option>
                            @foreach($parts as $part)
                                <option value="{{ $part->id }}" {{ old('m_part_id') == $part->id ? 'selected' : '' }}>
                                    {{ $part->part_no }} - {{ $part->part_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('m_part_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('app.good_issue.pic_cutter') }} <span class="required">*</span></label>
                        <select name="m_pic_id" class="form-control" required>
                            <option value="">-- {{ __('app.good_issue.pic_cutter') }} --</option>
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
                        <label class="form-label">{{ __('app.common.project') }} <span class="required">*</span></label>
                        <select name="m_prjk_id" class="form-control" required>
                            <option value="">-- {{ __('app.common.project') }} --</option>
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
                    <label class="form-label">{{ __('app.good_issue.purpose_notes') }} <span class="required">*</span></label>
                    <textarea name="purpose" class="form-control" rows="2" placeholder="{{ __('app.good_issue.purpose_placeholder') }}" required>{{ old('purpose') }}</textarea>
                    @error('purpose') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <span class="card-title"><i class="fas fa-boxes-stacked" style="color:var(--warning);margin-right:8px;"></i>{{ __('app.good_issue.items_header') }}</span>
                <button type="button" class="btn btn-primary btn-sm" onclick="addItem()"><i class="fas fa-plus"></i> {{ __("app.btn.add_item") }}</button>
            </div>
            <div class="table-wrap">
                <table id="itemsTable">
                    <thead>
                        <tr>
                            <th>{{ __('app.common.item_name') }} <span class="required">*</span></th>
                            <th width="200">{{ __('app.good_issue.fifo_col') }} <span class="required">*</span></th>
                            <th class="text-right" width="120">{{ __('app.good_issue.col_batch_stock') }}</th>
                            <th class="text-right" width="150">{{ __('app.good_issue.col_qty_out') }} <span class="required">*</span></th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            @error('items') <div class="form-error" style="padding:10px 20px;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('app.good_issue.save_btn') }}</button>
            <a href="{{ route('good-issues.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> {{ __("app.btn.cancel") }}</a>
        </div>
    </form>
</div>

<select id="stockOptionsTemplate" style="display:none;">
    <option value="">-- {{ __('app.common.item_name') }} --</option>
    @foreach($stocks as $stock)
        @php
            $name      = $stock->material->name ?? $stock->part->part_name ?? '-';
            $code      = $stock->material->code ?? $stock->part->part_no ?? '-';
            $unit      = $stock->material->unit ?? 'Pcs';
            $warehouse = $stock->warehouse->name ?? '-';
            $text      = "$code - $name ($warehouse)";
        @endphp
        <option value="{{ $stock->id }}"
            data-qty="{{ $stock->current_stock }}"
            data-unit="{{ $unit }}"
            data-material-id="{{ $stock->m_material_id }}"
            data-warehouse-id="{{ $stock->m_warehouse_id }}">
            {{ $text }}
        </option>
    @endforeach
</select>
@endsection

@push('scripts')
<script>
    const allBatches      = @json($batches);
    const itemsTableBody  = document.querySelector('#itemsTable tbody');
    const stockOptionsTmpl = document.getElementById('stockOptionsTemplate').innerHTML;
    let itemIndex = 0;

    const txt = {
        selectMaterial: '{{ __("app.good_issue.select_material_first") }}',
        noBatch:        '{{ __("app.good_issue.no_batch") }}',
        selectBatch:    '{{ __("app.good_issue.batch_select_ph") }}',
    };

    function addItem() {
        const tr  = document.createElement('tr');
        const idx = itemIndex;
        tr.innerHTML = `
            <td>
                <select name="items[${idx}][m_stock_id]" class="form-control stock-select" onchange="onStockChange(this)" required>
                    ${stockOptionsTmpl}
                </select>
            </td>
            <td>
                <select name="items[${idx}][load_material_number]" class="form-control batch-select" onchange="onBatchChange(this)" disabled>
                    <option value="">${txt.selectMaterial}</option>
                </select>
            </td>
            <td class="text-right batch-remaining" style="color:var(--text-muted); font-weight:500;">-</td>
            <td>
                <div style="display:flex;align-items:center;gap:8px;">
                    <input type="number" name="items[${idx}][quantity]" class="form-control qty-input" value="" min="0.01" step="0.01" style="font-size:13px;padding:6px;width:100%;" required disabled>
                    <span class="unit-label" style="font-size:12px;color:var(--text-muted);"></span>
                </div>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-ghost btn-sm" style="color:var(--danger);" onclick="this.closest('tr').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        itemsTableBody.appendChild(tr);
        itemIndex++;
    }

    function onStockChange(stockSelect) {
        const tr          = stockSelect.closest('tr');
        const batchSelect = tr.querySelector('.batch-select');
        const remaining   = tr.querySelector('.batch-remaining');
        const qtyInput    = tr.querySelector('.qty-input');
        const unitLabel   = tr.querySelector('.unit-label');
        const opt         = stockSelect.options[stockSelect.selectedIndex];

        batchSelect.innerHTML = `<option value="">${txt.selectMaterial}</option>`;
        batchSelect.disabled  = true;
        remaining.textContent = '-';
        qtyInput.disabled     = true;
        qtyInput.value        = '';
        unitLabel.textContent = '';

        if (!opt.value) return;

        const materialId  = opt.getAttribute('data-material-id');
        const warehouseId = opt.getAttribute('data-warehouse-id');
        const unit        = opt.getAttribute('data-unit');
        unitLabel.textContent = unit;

        const filtered = allBatches.filter(
            b => String(b.m_material_id) === String(materialId) &&
                 String(b.m_warehouse_id) === String(warehouseId)
        );

        if (filtered.length === 0) {
            batchSelect.innerHTML = `<option value="">${txt.noBatch}</option>`;
            batchSelect.disabled  = true;
            const totalQty = parseFloat(opt.getAttribute('data-qty'));
            qtyInput.max      = totalQty;
            qtyInput.disabled = false;
            remaining.textContent = totalQty + ' ' + unit;
            return;
        }

        let opts = `<option value="">${txt.selectBatch}</option>`;
        filtered.forEach(b => {
            opts += `<option value="${b.load_material_number}" data-remaining="${b.remaining_quantity}" data-unit="${unit}">
                ${b.load_material_number} — Sisa: ${b.remaining_quantity} ${unit}
            </option>`;
        });
        batchSelect.innerHTML = opts;
        batchSelect.disabled  = false;
    }

    function onBatchChange(batchSelect) {
        const tr        = batchSelect.closest('tr');
        const remaining = tr.querySelector('.batch-remaining');
        const qtyInput  = tr.querySelector('.qty-input');
        const unit      = tr.querySelector('.unit-label').textContent;
        const opt       = batchSelect.options[batchSelect.selectedIndex];

        if (!opt.value) {
            remaining.textContent = '-';
            qtyInput.value        = '';
            qtyInput.disabled     = true;
            return;
        }

        const rem             = parseFloat(opt.getAttribute('data-remaining'));
        remaining.textContent = rem + ' ' + unit;
        remaining.style.color = rem > 0 ? 'var(--warning)' : 'var(--danger)';
        qtyInput.max          = rem;
        qtyInput.disabled     = false;
    }

    addItem();
</script>
@endpush
