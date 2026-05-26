@extends('layouts.app')
@section('title', 'Goods Adjustment & Stock Info')
@section('topbar-title', __('app.nav.inventory') . ' — ' . __('app.nav.goods_adjustment'))

@section('content')

{{-- Pastikan CSRF Token ada untuk proses AJAX --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    .dataTables_wrapper { width: 100%; max-width: 100%; padding: 10px 0; }
    table.dataTable thead th, table.dataTable tbody td { border-bottom: 1px solid var(--border); padding: 12px 16px; vertical-align: middle; }
    .dataTables_wrapper .dataTables_filter input { border: 1px solid var(--border); border-radius: 4px; padding: 5px 10px; background: var(--surface-1); color: var(--text); }
    
    /* Style untuk Input di dalam tabel */
    .inline-input {
        width: 80px;
        padding: 4px 8px;
        border: 1px solid var(--border);
        border-radius: 4px;
        background: var(--surface-1);
        color: var(--text);
        text-align: right;
        font-family: monospace;
        transition: border-color 0.3s;
    }
    .inline-input:focus { border-color: var(--accent); outline: none; }
    /* Hilangkan panah spinner pada input type number */
    .inline-input::-webkit-outer-spin-button, .inline-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<div class="page-header">
    <div>
        <div class="page-title">Goods Adjustment & Inventory Info</div>
        <div class="page-subtitle">Informasi stok dan update nilai B/Q serta Cut/Day secara langsung</div>
    </div>
    <a href="{{ route('goods-adjustment.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Input Adjustment Baru
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
                <th>Spesifikasi Material</th>
                <th>Dimensi Material</th>
                <th>Supplier</th>
                <th>Warehouse</th>
                <th>Part Name</th>
                <th>Customer Part</th>
                <th class="text-right" style="color:var(--warning);">B/Q (Edit)</th>
                <th class="text-right" style="color:var(--warning);">Cut/Day (Edit)</th>
                <th class="text-right" style="color:var(--accent);">Stock</th>
                <th class="text-center">Satuan</th>
                <th class="text-right">Alokasi</th>
                <th class="text-right">Lifetime</th>
                <th class="text-right">Min Stock</th>
                <th class="text-right">Max Stock</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
            {{-- Kita simpan data mentah di atribut <tr> agar JS mudah mengambilnya --}}
            <tr data-material-id="{{ $stock->calc->material_id }}" data-stock="{{ $stock->current_stock }}">
                <td style="font-weight:600;">{{ $stock->calc->spesifikasi }}</td>
                <td style="color:var(--text-muted); font-size:13px;">{{ $stock->calc->dimensi }}</td>
                <td>{{ $stock->calc->supplier }}</td>
                <td>{{ $stock->calc->warehouse }}</td>
                <td style="font-weight:500;">{{ $stock->calc->part_name }}</td>
                <td>{{ $stock->calc->customer }}</td>
                
                {{-- Input B/Q --}}
                <td class="text-right">
                    <input type="number" class="inline-input input-bq" step="0.0001" value="{{ $stock->calc->bq }}">
                </td>
                
                {{-- Input Cut / Day --}}
                <td class="text-right">
                    <input type="number" class="inline-input input-cut" step="0.01" min="0.01" value="{{ $stock->calc->cut_per_day }}">
                </td>
                
                <td class="text-right" style="font-weight:800; color:var(--accent);">{{ number_format($stock->current_stock, 2) }}</td>
                <td class="text-center" style="font-size:12px; color:var(--text-muted);">{{ $stock->calc->satuan }}</td>
                
                {{-- Area Target Update via JavaScript --}}
                <td class="text-right val-alokasi" style="font-weight:500;">{{ number_format($stock->calc->alokasi, 2) }}</td>
                <td class="text-right val-lifetime" style="font-weight:500;">{{ number_format($stock->calc->lifetime, 1) }} hr</td>
                <td class="text-right val-min">{{ number_format($stock->calc->min_stock, 2) }}</td>
                <td class="text-right val-max">{{ number_format($stock->calc->max_stock, 2) }}</td>
                <td class="text-center val-status">
                    @if($stock->calc->status == 'Aman')
                        <span class="badge badge-success"><i class="fas fa-shield-alt"></i> Aman</span>
                    @elseif($stock->calc->status == 'Warning')
                        <span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i> Warning</span>
                    @elseif($stock->calc->status == 'Danger')
                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Danger</span>
                    @elseif($stock->calc->status == 'Over')
                        <span class="badge" style="background-color:#6366f1; color:white;"><i class="fas fa-arrow-up"></i> Over</span>
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
    $(document).ready(function() {
        // Setup CSRF untuk AJAX Laravel
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Setup DataTables
        $('#adjustmentTable').DataTable({
            "scrollX": true,
            "autoWidth": false,
            "paging": true,
            "pageLength": 15,
            "ordering": false // Dimatikan agar tidak bentrok dengan event edit manual
        });

        // Event: Saat User selesai mengetik / fokus pindah (change) pada input BQ atau Cut/Day
        $('#adjustmentTable tbody').on('change', '.input-bq, .input-cut', function() {
            let row = $(this).closest('tr');
            let materialId = row.data('material-id');
            let stockQty = parseFloat(row.data('stock')) || 0;
            
            // Ambil nilai input terbaru
            let bq = parseFloat(row.find('.input-bq').val()) || 0;
            let cut = parseFloat(row.find('.input-cut').val()) || 0.1;
            if(cut <= 0) cut = 0.1; // proteksi pembagian nol

            // 1. LAKUKAN PERHITUNGAN MATEMATIKA REALTIME
            let alokasi = stockQty * bq;
            let lifetime = stockQty / cut;
            let minStock = 10 * cut;
            let maxStock = 2 * minStock;

            // 2. TAMPILKAN HASILNYA KE LAYAR (Update DOM)
            row.find('.val-alokasi').text(alokasi.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            row.find('.val-lifetime').text(lifetime.toLocaleString('en-US', {minimumFractionDigits: 1, maximumFractionDigits: 1}) + ' hr');
            row.find('.val-min').text(minStock.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            row.find('.val-max').text(maxStock.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

            let statusHtml = '';
            if (stockQty > maxStock) {
                statusHtml = '<span class="badge" style="background-color:#6366f1; color:white;"><i class="fas fa-arrow-up"></i> Over</span>';
            } else if (stockQty < minStock) {
                statusHtml = '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Danger</span>';
            } else if (Math.abs(stockQty - minStock) < 0.01) {
                statusHtml = '<span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i> Warning</span>';
            } else {
                statusHtml = '<span class="badge badge-success"><i class="fas fa-shield-alt"></i> Aman</span>';
            }
            row.find('.val-status').html(statusHtml);

            // Berikan efek visual hijau sesaat menandakan sedang di proses
            let currentInput = $(this);
            currentInput.css('border-color', 'var(--success)');

            // 3. SIMPAN KE DATABASE VIA AJAX
            $.ajax({
                url: '{{ route('goods-adjustment.update-material') }}',
                type: 'POST',
                data: {
                    material_id: materialId,
                    bq: bq,
                    cut_per_day: cut
                },
                success: function(response) {
                    // Kembalikan warna border normal setelah 1 detik
                    setTimeout(() => currentInput.css('border-color', 'var(--border)'), 1000);
                },
                error: function(xhr) {
                    alert('Gagal menyimpan data ke server. Silakan muat ulang halaman.');
                    currentInput.css('border-color', 'var(--danger)');
                }
            });
        });
    });
</script>
@endsection