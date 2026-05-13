@if ($paginator->hasPages())
<div class="pagination-wrapper">
    <div class="pagination-summary">
        Showing <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
        of <strong>{{ $paginator->total() }}</strong> results
    </div>

    <nav class="pagination-nav">
        <ul class="pagination-list">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-btn">&#8592; Prev</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-btn" href="{{ $paginator->previousPageUrl() }}">&#8592; Prev</a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-btn">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-btn">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-btn" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-btn" href="{{ $paginator->nextPageUrl() }}">Next &#8594;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-btn">Next &#8594;</span>
                </li>
            @endif

        </ul>
    </nav>
</div>

<style>
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-top: 1px solid #e5e7eb;
    flex-wrap: wrap;
    gap: 12px;
    background: #fff;
}
.pagination-summary {
    font-size: 13px;
    color: #6b7280;
}
.pagination-summary strong {
    color: #1a2e1a;
    font-weight: 700;
}
.pagination-nav { display: flex; justify-content: flex-end; }
.pagination-list {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 4px;
    align-items: center;
}
.page-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    border-radius: 8px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.18s ease;
    cursor: pointer;
    white-space: nowrap;
}
.page-btn:hover {
    border-color: #2d9b6f;
    background: #f0fdf4;
    color: #1a5c42;
}
.page-item.active .page-btn {
    background: linear-gradient(135deg, #1a5c42, #2d9b6f);
    border-color: transparent;
    color: #fff;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(45,155,111,0.3);
}
.page-item.disabled .page-btn {
    color: #c9cdd4;
    border-color: #f0f0f0;
    background: #fafafa;
    cursor: not-allowed;
    pointer-events: none;
}
@media (max-width: 640px) {
    .pagination-wrapper { flex-direction: column; align-items: center; }
    .pagination-summary { font-size: 12px; }
    .page-btn { min-width: 32px; height: 32px; font-size: 12px; padding: 0 8px; }
}
</style>
@endif