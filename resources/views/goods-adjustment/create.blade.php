@extends('layouts.app')
@section('title', __('app.goods_adjustment.create_title'))
@section('topbar-title', __('app.nav.inventory') . ' — ' . __('app.nav.goods_adjustment'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('goods-adjustment.index') }}">{{ __('app.goods_adjustment.title') }}</a>
    <span class="sep">/</span>
    <span>{{ __('app.goods_adjustment.add') }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ __('app.goods_adjustment.create_title') }}</div>
        <div class="page-subtitle">{{ __('app.goods_adjustment.create_subtitle') }}</div>
    </div>
</div>

<div style="max-width:700px;">
    <form action="{{ route('goods-adjustment.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-sliders-h" style="color:var(--accent);margin-right:8px;"></i>{{ __('app.goods_adjustment.form_title') }}</span>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label class="form-label">{{ __('app.common.warehouse') }} <span class="required">*</span></label>
                    <select name="m_warehouse_id" id="warehouseSelect" class="form-control" required>
                        <option value="">-- {{ __('app.common.warehouse') }} --</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('m_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.goods_adjustment.select_material') }} <span class="required">*</span></label>
                    <select name="m_material_id" id="materialSelect" class="form-control" required>
                        <option value="">-- {{ __('app.goods_adjustment.select_material') }} --</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}"
                                data-supplier-code="{{ $material->supplier->code ?? '' }}"
                                {{ old('m_material_id') == $material->id ? 'selected' : '' }}>
                                [{{ $material->code }}] {{ $material->name }} - {{ $material->specification }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="background:var(--surface-2); padding:16px; border-radius:8px;">
                    <label class="form-label">{{ __('app.goods_adjustment.type_label') }} <span class="required">*</span></label>
                    <div style="display:flex; gap:20px; margin-top:8px;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:600; color:var(--success);">
                            <input type="radio" name="type" id="typeIn" value="in" {{ old('type') == 'in' ? 'checked' : '' }} required onchange="onTypeChange()">
                            <i class="fas fa-arrow-down"></i> {{ __('app.goods_adjustment.type_in') }}
                        </label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:600; color:var(--danger);">
                            <input type="radio" name="type" id="typeOut" value="out" {{ old('type') == 'out' ? 'checked' : '' }} required onchange="onTypeChange()">
                            <i class="fas fa-arrow-up"></i> {{ __('app.goods_adjustment.type_out') }}
                        </label>
                    </div>
                    <div style="font-size:12px; color:var(--text-muted); margin-top:10px;">
                        {!! __('app.goods_adjustment.type_hint') !!}
                    </div>
                </div>

                {{-- Load Material untuk Masuk (text input) --}}
                <div id="loadInSection" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">{{ __('app.goods_adjustment.load_number_in') }}
                            <span style="font-size:11px; color:var(--text-muted); font-weight:400;">{{ __('app.goods_adjustment.load_format_hint') }}</span>
                        </label>
                        <input
                            type="text"
                            name="load_material_number"
                            id="loadNumberIn"
                            class="form-control"
                            value="{{ old('load_material_number') }}"
                            placeholder="260527S01"
                            maxlength="50"
                            style="font-family:monospace; max-width:220px;"
                            oninput="this.dataset.manuallyEdited='1'">
                        <div style="font-size:12px; color:var(--text-muted); margin-top:6px;">
                            {{ __('app.goods_adjustment.load_optional') }}
                        </div>
                    </div>
                </div>

                {{-- Load Material untuk Keluar (dropdown batch) --}}
                <div id="loadOutSection" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">{{ __('app.goods_adjustment.load_number_out') }} <span class="required">*</span></label>
                        <select name="load_material_number" id="batchDropdown" class="form-control" style="max-width:340px;" disabled>
                            <option value="">{{ __('app.goods_adjustment.select_first') }}</option>
                        </select>
                        <div id="batchInfo" style="font-size:12px; color:var(--warning); margin-top:6px;"></div>
                        <div style="font-size:12px; color:var(--text-muted); margin-top:4px;">
                            {{ __('app.goods_adjustment.fifo_hint') }}
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.goods_adjustment.qty_label') }} <span class="required">*</span></label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}" min="0.01" step="0.01" required style="font-size:16px; font-weight:bold; max-width:200px;">
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.goods_adjustment.reason') }} <span class="required">*</span></label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="{{ __('app.goods_adjustment.reason_placeholder') }}" required>{{ old('notes') }}</textarea>
                </div>

            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('app.btn.save') }}</button>
            <a href="{{ route('goods-adjustment.index') }}" class="btn btn-ghost">{{ __('app.btn.cancel') }}</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const allBatches    = @json($batches);
    const materialSel   = document.getElementById('materialSelect');
    const warehouseSel  = document.getElementById('warehouseSelect');
    const loadInSection = document.getElementById('loadInSection');
    const loadOutSection= document.getElementById('loadOutSection');
    const batchDropdown = document.getElementById('batchDropdown');
    const batchInfo     = document.getElementById('batchInfo');
    const loadNumberIn  = document.getElementById('loadNumberIn');

    const txt = {
        selectFirst:   '{{ __("app.goods_adjustment.select_first") }}',
        noBatch:       '{{ __("app.goods_adjustment.no_batch") }}',
        selectBatch:   '{{ __("app.goods_adjustment.select_batch") }}',
        batchRemaining:'{{ __("app.goods_adjustment.batch_remaining") }}',
    };

    function onTypeChange() {
        const type = document.querySelector('input[name="type"]:checked')?.value;
        loadInSection.style.display  = type === 'in'  ? 'block' : 'none';
        loadOutSection.style.display = type === 'out' ? 'block' : 'none';

        if (type === 'in') {
            loadNumberIn.disabled  = false;
            batchDropdown.disabled = true;
            suggestLoadNumber();
        } else if (type === 'out') {
            loadNumberIn.disabled  = true;
            batchDropdown.disabled = false;
            updateBatchDropdown();
        } else {
            loadNumberIn.disabled  = true;
            batchDropdown.disabled = true;
        }
    }

    function suggestLoadNumber() {
        if (loadNumberIn.dataset.manuallyEdited) return;
        const opt  = materialSel.options[materialSel.selectedIndex];
        const code = opt?.getAttribute('data-supplier-code') ?? '';
        const now  = new Date();
        const yy   = String(now.getFullYear()).slice(-2);
        const mm   = String(now.getMonth() + 1).padStart(2, '0');
        const dd   = String(now.getDate()).padStart(2, '0');
        loadNumberIn.value = code ? `${yy}${mm}${dd}${code}` : '';
    }

    function updateBatchDropdown() {
        const materialId  = materialSel.value;
        const warehouseId = warehouseSel.value;

        if (!materialId || !warehouseId) {
            batchDropdown.innerHTML = `<option value="">${txt.selectFirst}</option>`;
            batchInfo.textContent   = '';
            return;
        }

        const filtered = allBatches.filter(
            b => String(b.m_material_id) === String(materialId) &&
                 String(b.m_warehouse_id) === String(warehouseId)
        );

        if (filtered.length === 0) {
            batchDropdown.innerHTML = `<option value="">${txt.noBatch}</option>`;
            batchInfo.textContent   = '';
            return;
        }

        batchInfo.textContent = '';
        let opts = `<option value="">${txt.selectBatch}</option>`;
        filtered.forEach(b => {
            opts += `<option value="${b.load_material_number}">${b.load_material_number} — Sisa: ${b.remaining_quantity}</option>`;
        });
        batchDropdown.innerHTML = opts;

        const oldVal = '{{ old('load_material_number') }}';
        if (oldVal) batchDropdown.value = oldVal;
    }

    function onBatchSelect() {
        const opt = batchDropdown.options[batchDropdown.selectedIndex];
        if (!opt.value) { batchInfo.textContent = ''; return; }
        const found = allBatches.find(b => b.load_material_number === opt.value);
        if (found) batchInfo.textContent = `${txt.batchRemaining} ${found.remaining_quantity}`;
    }

    materialSel.addEventListener('change', function () {
        const type = document.querySelector('input[name="type"]:checked')?.value;
        if (type === 'in')  suggestLoadNumber();
        if (type === 'out') updateBatchDropdown();
    });
    warehouseSel.addEventListener('change', function () {
        const type = document.querySelector('input[name="type"]:checked')?.value;
        if (type === 'out') updateBatchDropdown();
    });
    batchDropdown.addEventListener('change', onBatchSelect);

    onTypeChange();
</script>
@endpush
