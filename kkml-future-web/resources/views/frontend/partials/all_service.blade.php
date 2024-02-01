@extends('frontend.layouts.app')

@section('content')
    {{-- Categories , Sliders . Today's deal --}}
    <div class="home-banner-area mb-4 pt-3">
        <div class="container">
            <div class="row gutters-10 position-relative">
                @foreach ($services as $key => $service)
                    <div class="col-6 col-sm-4 col-md-3">
                        <div class="aiz-card-box border border-light rounded hov-shadow-md my-2 has-transition">
                            <div class="position-relative">
                                <a href="{{ route('service.view', $service->id) }}" class="d-block">
                                    <img
                                        class="img-fit lazyload mx-auto h-140px h-md-210px"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($service->thumbnail_img) }}"
                                        alt="{{  $service->name  }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                    >
                                </a>
                            </div>
                            <div class="p-md-3 p-2 text-left">
                                <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                    <a href="{{ route('service.view', $service->id) }}"
                                    class="d-block text-reset">{{  $service->name  }}</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="aiz-pagination">
                    {{ $services->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
