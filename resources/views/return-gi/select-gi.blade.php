@extends('layouts.app')
@section('title', 'Pilih Good Issue untuk Retur')
@section('topbar-title', 'Retur Good Issue')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('return-gi.index') }}">Retur GI</a>
    <span class="sep">/</span>
    <span>Buat Retur Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Pilih Good Issue</div>
        <div class="page-subtitle">Pilih Good Issue yang materialnya akan diretur kembali ke Gudang</div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. GI</th>
                    <th>Tanggal Keluar</th>
                    <th>Target Produksi (Part)</th>
                    <th>Tujuan / Catatan</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($availableGIs as $gi)
                <tr>
                    <td>
                        <span style="font-family:'Syne',sans-serif; font-size:12px; font-weight:600; color:var(--accent); background:var(--accent-glow); padding:3px 8px; border-radius:4px; white-space:nowrap;">
                            {{ $gi->gi_number }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted);">{{ $gi->issue_date->format('d M Y') }}</td>
                    <td style="font-weight:500;">{{ $gi->part->part_name ?? '-' }}</td>
                    <td style="font-size:13px; color:var(--text-muted);">{{ Str::limit($gi->purpose, 40) }}</td>
                    <td>
                        <form action="{{ route('return-gi.create') }}" method="GET">
                            <input type="hidden" name="t_good_issue_id" value="{{ $gi->id }}">
                            <button type="submit" class="btn btn-primary btn-sm">Retur</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state" style="padding: 60px 20px;">
                            <i class="fas fa-box-open"></i>
                            <h4>Tidak ada Good Issue</h4>
                            <p>Belum ada transaksi Good Issue yang bisa diretur.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
