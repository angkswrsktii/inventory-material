@if ($paginator->hasPages())
<div style="display:flex; gap:4px; align-items:center;">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span style="padding:6px 10px; border-radius:var(--radius-sm); background:var(--surface-2); color:var(--text-dim); font-size:13px; cursor:not-allowed; opacity:0.5;">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="padding:6px 10px; border-radius:var(--radius-sm); background:var(--surface-2); border:1px solid var(--border); color:var(--text); font-size:13px; text-decoration:none; transition:background 0.15s;" onmouseover="this.style.background='var(--surface-3)'" onmouseout="this.style.background='var(--surface-2)'">‹</a>
    @endif

    {{-- Page Numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="padding:6px 8px; color:var(--text-dim); font-size:13px;">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="padding:6px 10px; border-radius:var(--radius-sm); background:var(--accent); color:#fff; font-size:13px; font-weight:600; min-width:32px; text-align:center;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding:6px 10px; border-radius:var(--radius-sm); background:var(--surface-2); border:1px solid var(--border); color:var(--text); font-size:13px; text-decoration:none; min-width:32px; text-align:center; transition:background 0.15s;" onmouseover="this.style.background='var(--surface-3)'" onmouseout="this.style.background='var(--surface-2)'">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="padding:6px 10px; border-radius:var(--radius-sm); background:var(--surface-2); border:1px solid var(--border); color:var(--text); font-size:13px; text-decoration:none; transition:background 0.15s;" onmouseover="this.style.background='var(--surface-3)'" onmouseout="this.style.background='var(--surface-2)'">›</a>
    @else
        <span style="padding:6px 10px; border-radius:var(--radius-sm); background:var(--surface-2); color:var(--text-dim); font-size:13px; cursor:not-allowed; opacity:0.5;">›</span>
    @endif
</div>
@endif
