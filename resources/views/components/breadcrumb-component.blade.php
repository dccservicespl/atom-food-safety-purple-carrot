@props(['page_title', 'breadcrumb_links', 'back_route' => null])
<div>
    <section class="topbar py-3 mb-5">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ $back_route??'' }}" class="back-btn pointer"><i
                        class="bi bi-arrow-left-circle-fill text-white fs-2"></i></a>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @foreach ($breadcrumb_links as $link)
                        @if ($link['route'])
                        <li class="breadcrumb-item"><a href="{{ $link['route'] }}" class="text-white">{{ $link['name']
                                }}</a></li>
                        @else
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $link['name'] }}</li>
                        @endif
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>
    </section>
</div>
