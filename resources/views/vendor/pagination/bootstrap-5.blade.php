@if ($paginator->hasPages())
    <div class="pagination-container">

        <div class="pagination-info">
            Menampilkan {{ $paginator->firstItem() }}
            - {{ $paginator->lastItem() }}
            dari {{ $paginator->total() }} data
        </div>

        <ul class="pagination-custom">
            @foreach ($elements as $element)

                @if (is_string($element))
                    <li class="disabled">
                        <span>{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)

                        @if ($page == $paginator->currentPage())
                            <li class="active">
                                <span>{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif

                    @endforeach
                @endif

            @endforeach
        </ul>

    </div>
@endif