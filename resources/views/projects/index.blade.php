@extends('layouts.app')
@section('title', 'Master Project')
@section('topbar-title', __('app.nav.master_data') . ' — ' . __('app.nav.data_project'))

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Database Project</div>
        <div class="page-subtitle">Kelola daftar project yang digunakan pada sistem</div>
    </div>
    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> {{ __("app.project.add") }}
    </a>
</div>


<div class="card" style="max-width: 800px;">
    <div class="table-wrap">
        <table>
            <thead style="background:var(--surface-2);">
                <tr>
                    <th width="60" class="text-center">No</th>
                    <th>Nama Project</th>
                    <th width="120" class="text-center">{{ __('app.common.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $index => $project)
                <tr>
                    <td class="text-center" style="color:var(--text-muted);">
                        {{ $projects->firstItem() + $index }}
                    </td>
                    <td style="font-weight: 500; font-size: 14px;">
                        {{ $project->name }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm" title="Edit Project">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center" style="padding: 40px; color:var(--text-muted);">
                        <i class="fas fa-folder-open" style="font-size:24px; margin-bottom:10px; display:block;"></i>
                        Belum ada data Project.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($projects->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
        {{ $projects->links() }}
    </div>
    @endif
</div>
@endsection