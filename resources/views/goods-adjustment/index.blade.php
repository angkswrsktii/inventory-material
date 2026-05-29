@extends('layouts.app')
@section('title', __('app.goods_adjustment.title'))
@section('topbar-title', __('app.nav.inventory') . ' — ' . __('app.nav.goods_adjustment'))

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    .dataTables_wrapper { width: 100%; max-width: 100%; padding: 10px 0; }
    table.dataTable thead th, table.dataTable tbody td { border-bottom: 1px solid var(--border); padding: 12px 16px; vertical-align: middle; }
    .dataTables_wrapper .dataTables_filter input { border: 1px solid var(--border); border-radius: 4px; padding: 5px 10px; background: var(--surface-1); color: var(--text); }
    .inline-input {
        width: 80px; padding: 4px 8px; border: 1px solid var(--border); border-radius: 4px;
        background: var(--surface-1); color: var(--text); text-align: right; font-family: monospace; transition: border-color 0.3s;
    }
    .inline-input:focus { border-color: var(--accent); outline: none; }
    .inline-input::-webkit-outer-spin-button, .inline-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<div class="page-header">
    <div>
        <div class="page-title">{{ __('app.goods_adjustment.title') }}</div>
        <div class="page-subtitle">{{ __('app.goods_adjustment.subtitle') }}</div>
    </div>
    <a href="{{ route('goods-adjustment.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> {{ __('app.goods_adjustment.add') }}
    </a>
</div>

{{-- Tab Project --}}
<div style="margin-bottom: 20px; border-bottom: 2px solid var(--border); display:flex; gap:10px; overflow-x:auto;">
    @foreach($projects as $project)
        <a href="{{ route('goods-adjustment.index', ['project_id' => $project->id]) }}"
           style="padding: 10px 20px; text-decoration:none; font-weight:600; font-size:14px;
                  color: {{ $activeProjectId == $project->id ? 'var(--accent)' : 'var(--text-muted)' }};
                  border-bottom: 3px solid {{ $activeProjectId == $project->id ? 'var(--accent)' : 'transparent' }};">
            <i class="fas fa-folder-open" style="margin-right:6px;"></i> {{ $project->name ?? 'Project '.$project->id }}
        </a>
    @endforeach
</div>

{{-- Grid Table --}}
<div class="card" style="padding: 20px; width: 100%; max-width: 100%; overflow: hidden;">
    <table id="adjustmentTable" class="display nowrap" style="width:100%;">
        <thead style="background:var(--surface-2);">
            <tr>
                <th>{{ __('app.goods_adjustment.col_spec') }}</th>
                <th>{{ __('app.goods_adjustment.col_dimension') }}</th>
                <th>{{ __('app.common.supplier') }}</th>
                <th>{{ __('app.goods_adjustment.col_warehouse') }}</th>
                <th>{{ __('app.goods_adjustment.col_part') }}</th>
                <th>{{ __('app.goods_adjustment.col_customer') }}</th>
                <th class="text-right" style="color:var(--warning);">{{ __('app.goods_adjustment.col_bq') }}</th>
                <th class="text-right" style="color:var(--warning);">{{ __('app.goods_adjustment.col_cut') }}</th>
                <th class="text-right" style="color:var(--accent);">{{ __('app.goods_adjustment.col_stock') }}</th>
                <th class="text-center">{{ __('app.goods_adjustment.col_unit') }}</th>
                <th class="text-right">{{ __('app.goods_adjustment.col_allocation') }}</th>
                <th class="text-right">{{ __('app.goods_adjustment.col_lifetime') }}</th>
                <th class="text-right">{{ __('app.goods_adjustment.col_min') }}</th>
                <th class="text-right">{{ __('app.goods_adjustment.col_max') }}</th>
                <th class="text-center">{{ __('app.common.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
            <tr data-material-id="{{ $stock->calc->material_id }}" data-stock="{{ $stock->current_stock }}">
                <td style="font-weight:600;">{{ $stock->calc->spesifikasi }}</td>
                <td style="color:var(--text-muted); font-size:13px;">{{ $stock->calc->dimensi }}</td>
                <td>{{ $stock->calc->supplier }}</td>
                <td>{{ $stock->calc->warehouse }}</td>
                <td style="font-weight:500;">{{ $stock->calc->part_name }}</td>
                <td>{{ $stock->calc->customer }}</td>

                <td class="text-right">
                    <input type="number" class="inline-input input-bq" step="0.0001" value="{{ $stock->calc->bq }}">
                </td>
                <td class="text-right">
                    <input type="number" class="inline-input input-cut" step="0.01" min="0.01" value="{{ $stock->calc->cut_per_day }}">
                </td>

                <td class="text-right" style="font-weight:800; color:var(--accent);">{{ number_format($stock->current_stock, 2) }}</td>
                <td class="text-center" style="font-size:12px; color:var(--text-muted);">{{ $stock->calc->satuan }}</td>

                <td class="text-right val-alokasi" style="font-weight:500;">{{ number_format($stock->calc->alokasi, 2) }}</td>
                <td class="text-right val-lifetime" style="font-weight:500;">{{ number_format($stock->calc->lifetime, 1) }} hr</td>
                <td class="text-right val-min">{{ number_format($stock->calc->min_stock, 2) }}</td>
                <td class="text-right val-max">{{ number_format($stock->calc->max_stock, 2) }}</td>
                <td class="text-center val-status">
                    @if($stock->calc->status == 'Aman')
                        <span class="badge badge-success"><i class="fas fa-shield-alt"></i> {{ __('app.goods_adjustment.status_safe') }}</span>
                    @elseif($stock->calc->status == 'Warning')
                        <span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i> {{ __('app.goods_adjustment.status_warning') }}</span>
                    @elseif($stock->calc->status == 'Danger')
                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> {{ __('app.goods_adjustment.status_danger') }}</span>
                    @elseif($stock->calc->status == 'Over')
                        <span class="badge" style="background-color:#6366f1; color:white;"><i class="fas fa-arrow-up"></i> {{ __('app.goods_adjustment.status_over') }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    // Label status diterjemahkan dari server agar konsisten dengan bahasa aktif
    const statusLabels = {
        safe:    '<span class="badge badge-success"><i class="fas fa-shield-alt"></i> {{ __("app.goods_adjustment.status_safe") }}</span>',
        warning: '<span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i> {{ __("app.goods_adjustment.status_warning") }}</span>',
        danger:  '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> {{ __("app.goods_adjustment.status_danger") }}</span>',
        over:    '<span class="badge" style="background-color:#6366f1;color:white;"><i class="fas fa-arrow-up"></i> {{ __("app.goods_adjustment.status_over") }}</span>',
    };

    $(document).ready(function() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $('#adjustmentTable').DataTable({
            "scrollX": true,
            "autoWidth": false,
            "paging": true,
            "pageLength": 15,
            "ordering": false
        });

        $('#adjustmentTable tbody').on('change', '.input-bq, .input-cut', function() {
            let row       = $(this).closest('tr');
            let materialId = row.data('material-id');
            let stockQty  = parseFloat(row.data('stock')) || 0;
            let bq        = parseFloat(row.find('.input-bq').val()) || 0;
            let cut       = parseFloat(row.find('.input-cut').val()) || 0.1;
            if (cut <= 0) cut = 0.1;

            let alokasi  = stockQty * bq;
            let lifetime = stockQty / cut;
            let minStock = 10 * cut;
            let maxStock = 2 * minStock;

            row.find('.val-alokasi').text(alokasi.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            row.find('.val-lifetime').text(lifetime.toLocaleString('en-US', {minimumFractionDigits: 1, maximumFractionDigits: 1}) + ' hr');
            row.find('.val-min').text(minStock.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            row.find('.val-max').text(maxStock.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

            if      (stockQty > maxStock)                     row.find('.val-status').html(statusLabels.over);
            else if (stockQty < minStock)                     row.find('.val-status').html(statusLabels.danger);
            else if (Math.abs(stockQty - minStock) < 0.01)   row.find('.val-status').html(statusLabels.warning);
            else                                              row.find('.val-status').html(statusLabels.safe);

            let currentInput = $(this);
            currentInput.css('border-color', 'var(--success)');

            $.ajax({
                url: '{{ route('goods-adjustment.update-material') }}',
                type: 'POST',
                data: { material_id: materialId, bq: bq, cut_per_day: cut },
                success: function() {
                    setTimeout(() => currentInput.css('border-color', 'var(--border)'), 1000);
                },
                error: function() {
                    alert('{{ __("app.flash.error") }}');
                    currentInput.css('border-color', 'var(--danger)');
                }
            });
        });
    });
</script>
@endsection
