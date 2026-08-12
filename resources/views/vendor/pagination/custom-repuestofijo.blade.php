@if ($paginator->hasPages())
    <nav class="d-flex align-items-center justify-content-between">
        <div class="d-flex gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="btn-pagination-disabled">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" class="btn-pagination">
                    <i class="fas fa-chevron-left"></i>
                </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="btn-pagination-dots">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="btn-pagination-active">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" class="btn-pagination">{{ $page }}</button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" class="btn-pagination">
                    <i class="fas fa-chevron-right"></i>
                </button>
            @else
                <span class="btn-pagination-disabled">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>

        <div class="text-white opacity-50" style="font-size: 0.8rem; font-weight: 600;">
            Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados
        </div>
    </nav>

    <style>
        .btn-pagination,
        .btn-pagination-active,
        .btn-pagination-disabled,
        .btn-pagination-dots {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: 0.3s;
            border: none;
            font-family: 'Syne', sans-serif;
        }

        .btn-pagination {
            background: var(--surface3);
            color: #fff;
            cursor: pointer;
        }

        .btn-pagination:hover {
            background: var(--accent-red);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--accent-red-glow);
        }

        .btn-pagination-active {
            background: var(--accent-red);
            color: #fff;
            box-shadow: 0 5px 15px var(--accent-red-glow);
        }

        .btn-pagination-disabled {
            background: var(--surface2);
            color: var(--muted);
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-pagination-dots {
            color: var(--muted);
            background: transparent;
        }
    </style>
@endif