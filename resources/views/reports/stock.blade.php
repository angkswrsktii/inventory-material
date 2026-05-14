@extends('layouts.app')
@section('title', 'Laporan Stok')
@section('topbar-title', 'Laporan Stok')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Laporan Stok Material</div>
        <div class="page-subtitle">Kondisi stok semua raw material saat ini</div>
    </div>
    <button onclick="window.print()" class="btn btn-ghost no-print">
        <i class="fas fa-print"></i> Print
    </button>
</div>

<!-- GRAFIK CHART.JS -->
<div class="card no-print" style="margin-bottom: 20px;">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-chart-pie" style="color:var(--accent);margin-right:8px;"></i>Grafik Top Stok Material</span>
    </div>
    <div class="card-body" style="height: 300px; padding: 20px;">
        <canvas id="stockChart"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-table" style="color:var(--accent);margin-right:8px;"></i>Rekap Stok per Material</span>
        <span style="font-size:12px; color:var(--text-muted);">Per tanggal: {{ now()->format('d M Y, H:i') }}</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Material</th>
                    <th>Supplier</th>
                    <th>Satuan</th>
                    <th class="text-right">Stok Min.</th>
                    <th class="text-right">Stok Saat Ini</th>
                    <th>Status</th>
                    <th class="text-center no-print">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $i => $m)
                <tr>
                    <td style="color:var(--text-dim);">{{ $i + 1 }}</td>
                    <td><span class="mono" style="color:var(--accent); font-size:11px;">{{ $m->code }}</span></td>
                    <td style="font-weight:500;">{{ $m->name }}</td>
                    <td style="font-size:12.5px;">{{ $m->supplier->name ?? '—' }}</td>
                    <td><span class="badge badge-muted">{{ $m->unit }}</span></td>
                    <td class="text-right" style="color:var(--text-muted);">{{ number_format($m->min_stock, 2) }}</td>
                    <td class="text-right">
                        <span style="font-weight:700; font-size:15px;
                            color:{{ $m->total_stock <= 0 ? 'var(--danger)' : ($m->total_stock <= $m->min_stock ? 'var(--warning)' : 'var(--success)') }}">
                            {{ number_format($m->total_stock, 2) }}
                        </span>
                    </td>
                    <td>
                        @if($m->total_stock <= 0)
                            <span class="badge badge-danger"><i class="fas fa-ban"></i> Kosong</span>
                        @elseif($m->total_stock <= $m->min_stock)
                            <span class="badge badge-warning"><i class="fas fa-triangle-exclamation"></i> Rendah</span>
                        @else
                            <span class="badge badge-success"><i class="fas fa-check"></i> Normal</span>
                        @endif
                    </td>
                    <td class="text-center no-print">
                        <a href="{{ route('reports.print.stock', $m) }}" target="_blank" class="btn btn-ghost btn-xs">
                            <i class="fas fa-print"></i> Kartu Stok
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state"><i class="fas fa-inbox"></i><h4>Belum Ada Material</h4></div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('stockChart').getContext('2d');
    const stockChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Total Stok Saat Ini',
                data: {!! json_encode($chartData) !!},
                backgroundColor: {!! json_encode($chartColors) !!},
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection