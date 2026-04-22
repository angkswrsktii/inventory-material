@extends('layouts.app')

@section('title', 'Dashboard')
@section('topbar-title', 'Dashboard')

@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Selamat Datang 👋</div>
        <div class="page-subtitle">Overview sistem manajemen persediaan raw material</div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-cube"></i></div>
        <div class="stat-value">{{ $stats['total_materials'] }}</div>
        <div class="stat-label">Total Material</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-arrow-right-to-bracket"></i></div>
        <div class="stat-value">{{ number_format($stats['today_in'], 0) }}</div>
        <div class="stat-label">Barang Masuk Hari Ini</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-arrow-right-from-bracket"></i></div>
        <div class="stat-value">{{ number_format($stats['today_out'], 0) }}</div>
        <div class="stat-label">Barang Keluar Hari Ini</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-icon"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="stat-value">{{ $stats['low_stock'] }}</div>
        <div class="stat-label">Stok Hampir Habis</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-ban"></i></div>
        <div class="stat-value">{{ $stats['empty_stock'] }}</div>
        <div class="stat-label">Stok Kosong</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon"><i class="fas fa-file-invoice"></i></div>
        <div class="stat-value">{{ $stats['monthly_withdrawals'] }}</div>
        <div class="stat-label">Pengambilan Bulan Ini</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
    <!-- Chart -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-line" style="color:var(--accent);margin-right:8px;"></i>Transaksi 7 Hari Terakhir</span>
        </div>
        <div class="card-body" style="padding: 20px;">
            <canvas id="txChart" height="180"></canvas>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-triangle-exclamation" style="color:var(--warning);margin-right:8px;"></i>Alert Stok Rendah</span>
            <a href="{{ route('materials.index') }}?status=low" class="btn btn-ghost btn-sm">Lihat Semua</a>
        </div>
        @if($lowStockMaterials->count())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th class="text-right">Stok</th>
                            <th class="text-right">Min.</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockMaterials as $m)
                        <tr>
                            <td>
                                <div style="font-weight:500;">{{ $m->name }}</div>
                                <div style="font-size:11px; color:var(--text-muted);">{{ $m->code }}</div>
                            </td>
                            <td class="text-right" style="font-weight:600; color: {{ $m->current_stock <= 0 ? 'var(--danger)' : 'var(--warning)' }}">
                                {{ number_format($m->current_stock, 2) }}
                            </td>
                            <td class="text-right" style="color:var(--text-muted);">{{ number_format($m->minimum_stock, 2) }}</td>
                            <td>
                                @if($m->current_stock <= 0)
                                    <span class="badge badge-danger">Kosong</span>
                                @else
                                    <span class="badge badge-warning">Rendah</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-check-circle" style="color:var(--success);"></i>
                <h4>Semua Stok Normal</h4>
                <p>Tidak ada material dengan stok rendah</p>
            </div>
        @endif
    </div>
</div>

<div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px;">
    <!-- Recent Transactions -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-right-left" style="color:var(--accent);margin-right:8px;"></i>Transaksi Terbaru</span>
            <a href="{{ route('stock-cards.index') }}" class="btn btn-ghost btn-sm">Lihat Semua</a>
        </div>
        @if($recentTransactions->count())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Material</th>
                            <th>Tipe</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $tx)
                        <tr>
                            <td style="white-space:nowrap; font-size:12px; color:var(--text-muted);">{{ $tx->transaction_date->format('d M Y') }}</td>
                            <td>{{ Str::limit($tx->material->name ?? '-', 25) }}</td>
                            <td>
                                @if($tx->type === 'in')
                                    <span class="badge badge-in"><i class="fas fa-arrow-down fa-xs"></i> Masuk</span>
                                @else
                                    <span class="badge badge-out"><i class="fas fa-arrow-up fa-xs"></i> Keluar</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($tx->type === 'in')
                                    <span class="stock-in">+{{ number_format($tx->quantity_in, 2) }}</span>
                                @else
                                    <span class="stock-out">-{{ number_format($tx->quantity_out, 2) }}</span>
                                @endif
                            </td>
                            <td class="text-right" style="font-weight:500;">{{ number_format($tx->balance, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state" style="padding:40px;">
                <i class="fas fa-inbox"></i>
                <h4>Belum Ada Transaksi</h4>
            </div>
        @endif
    </div>

    <!-- Recent Withdrawals -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-file-invoice" style="color:var(--accent-2);margin-right:8px;"></i>Pengambilan Terbaru</span>
            <a href="{{ route('withdrawal-cards.index') }}" class="btn btn-ghost btn-sm">Lihat Semua</a>
        </div>
        @if($recentWithdrawals->count())
        <div style="padding: 12px;">
            @foreach($recentWithdrawals as $w)
            <a href="{{ route('withdrawal-cards.show', $w) }}" style="text-decoration:none;">
                <div style="padding: 12px; background: var(--surface-2); border-radius: var(--radius-sm); margin-bottom: 8px; border: 1px solid var(--border); transition: border-color .2s;"
                     onmouseover="this.style.borderColor='var(--border-active)'" onmouseout="this.style.borderColor='var(--border)'">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                        <div>
                            <div style="font-size:12px; color: var(--accent); font-weight:600; font-family:'Syne',sans-serif;">{{ $w->document_no }}</div>
                            <div style="font-size:13px; color:var(--text); font-weight:500; margin-top:2px;">{{ $w->part_name }}</div>
                        </div>
                        <span class="badge badge-success">{{ ucfirst($w->status) }}</span>
                    </div>
                    <div style="margin-top:8px; display:flex; gap:16px; font-size:11px; color:var(--text-muted);">
                        <span><i class="fas fa-calendar-alt"></i> {{ $w->withdrawal_date->format('d M Y') }}</span>
                        <span><i class="fas fa-user"></i> {{ $w->pic }}</span>
                        <span><i class="fas fa-industry"></i> {{ $w->line }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
            <div class="empty-state" style="padding:40px;">
                <i class="fas fa-inbox"></i>
                <h4>Belum Ada Pengambilan</h4>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const chartData = @json($chartData);
const ctx = document.getElementById('txChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.map(d => d.date),
        datasets: [
            {
                label: 'Masuk',
                data: chartData.map(d => d.in),
                backgroundColor: 'rgba(52,211,153,0.7)',
                borderColor: '#34d399',
                borderWidth: 1,
                borderRadius: 4,
            },
            {
                label: 'Keluar',
                data: chartData.map(d => d.out),
                backgroundColor: 'rgba(248,113,113,0.7)',
                borderColor: '#f87171',
                borderWidth: 1,
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: { color: '#7b8299', font: { family: 'DM Sans', size: 12 } }
            }
        },
        scales: {
            x: {
                grid: { color: 'rgba(255,255,255,0.05)' },
                ticks: { color: '#7b8299', font: { family: 'DM Sans', size: 11 } }
            },
            y: {
                grid: { color: 'rgba(255,255,255,0.05)' },
                ticks: { color: '#7b8299', font: { family: 'DM Sans', size: 11 } }
            }
        }
    }
});
</script>
@endpush