@if (count($breadcrumbs))
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 lg:space-x-3 text-lg uppercase ">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($breadcrumb->url && !$loop->last)
                    <li class="breadcrumb-item flex items-center space-x-1 font-medium hover:text-blue-400">
                        @isset($breadcrumb->icon)
                            <span class="{{ $breadcrumb->icon }}"></span>
                        @endisset
                        <a class="load" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                    </li>
                    <span class="fas fa-angle-right"></span>
                @else
                    <li class="breadcrumb-item active  flex items-center space-x-1">
                        @isset($breadcrumb->icon)
                            <span class="{{ $breadcrumb->icon }}"></span>
                        @endisset
                        <a href="{{ $breadcrumb->url }}" class="load">{{ $breadcrumb->title }}</a>

                    </li>
                @endif
            @endforeach

        </ol>
    </nav>

   

@endif
