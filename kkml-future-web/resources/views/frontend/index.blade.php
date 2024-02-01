@extends('frontend.layouts.app')

@section('content')
    {{-- Start: Sliders --}}
    <div class="home-banner-area mb-3 mt-4">
        <div class="container">
            <div class="row gutters-10 position-relative">
                {{-- <div class="col-lg-3 position-static d-none d-lg-block">
                    @include('frontend.partials.category_menu')
                </div> --}}
                @php
                    $num_todays_deal = count(filter_products(\App\Product::where('published', 1)->where('todays_deal', 1))->get());
                    $featured_categories = \App\Category::where('featured', 1)->get();
                @endphp

                <div class="col-lg-12">
                    @if (get_setting('home_slider_images') != null)
                        <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height" data-arrows="true" data-dots="true"
                            data-autoplay="true" data-infinite="true" data-rows='0'>
                            @php $slider_images = json_decode(get_setting('home_slider_images'), true);  @endphp
                            @foreach ($slider_images as $key => $value)
                                <div class="carousel-box hero__banner">
                                    <a href="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}">
                                        <img class="d-block mw-100 img-fit rounded shadow-sm"
                                            src="{{ uploaded_asset($slider_images[$key]) }}"
                                            alt="{{ env('APP_NAME') }} promo"
                                            @if (count($featured_categories) == 0) @else
                                            {{-- 315 --}} @endif
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    {{-- @if (count($featured_categories) > 0)
                        <div class="list-unstyled  aiz-carousel mb-0 row gutters-5" data-items="6" data-xs-items="4"
                            data-sm-items="3" data-md-items="4" data-lg-items="4" data-arrows='false'
                            data-infinite='true'>
                            @foreach ($featured_categories as $key => $category)
                                <div class="carousel-box minw-0 mt-3">
                                    <a href="{{ route('products.category', $category->slug) }}"
                                        class="d-block rounded bg-white p-2 text-reset shadow-sm">
                                        <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($category->banner) }}"
                                            alt="{{ $category->getTranslation('name') }}" class="lazyload img-fit"
                                            height="78"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';" />
                                        <div class="text-truncate fs-12 fw-600 mt-2 opacity-70">
                                            {{ $category->getTranslation('name') }}</div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
    {{-- End: Sliders --}}
    {{-- Top 10 categories --}}
    @if (get_setting('top10_categories') != null)
        <div class="mb-4">
            <div class="container">
                <div class="px-3 py-3 bg-white shadow-sm rounded">
                    <div class="d-flex align-items-baseline border-bottom section-title">
                        <h3 class="h5 fw-700 mb-0">
                            <span
                                class="border-bottom border-primary border-width-2 pb-md-3 pb-2 d-inline-block">{{ translate('Top 10 Categories') }}</span>
                        </h3>
                       
                    </div>
                    <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="7" data-xl-items="6"
                        data-lg-items="5" data-md-items="4" data-sm-items="3" data-xs-items="2" data-arrows='true'
                        data-infinite='true'>
                        @php $top10_categories = json_decode(get_setting('top10_categories')); @endphp

                        @foreach ($top10_categories as $key => $value)
                            @php $category = \App\Category::find($value); @endphp
                            @if ($category != null && $category->products->count() != null)
                                <div class="carousel-box">
                                    <div class="kkml-cat aiz-card-box mt-4">
                                        <a href="{{ route('products.category', $category->slug) }}">
                                            <div class="kkml-cat__img">
                                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($category->banner) }}"
                                                    alt="{{ $category->getTranslation('name') }}"
                                                    class="img-fluid img lazyload h-60px"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                            </div>
                                            <p class="text-truncat-2 fs-16 ">
                                                {{ $category->getTranslation('name') }}</p>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Start: Today's Deal --}}
    @if ($num_todays_deal > 0)
        <div class="mb-4">
            <div class="container">
                <div class="px-3 py-2 px-md-4 py-md-3 kkml-hot-deal-bg">
                    <div class="row gutters-10">
                        <div class="col-lg-12">
                            <div class="rounded-top d-flex align-items-baseline justify-content-start">
                                <h3><span class="fw-700 fs-16 mr-2 text-truncate">
                                        {{ translate('Special Offer') }}
                                    </span>
                                    <span class="badge badge-danger badge-inline">{{ translate('Hot') }}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="kkml-deal rounded-bottom px-20px">
                        <div class="aiz-carousel half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"
                            data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='true'>
                            @foreach (filter_products(\App\Product::where('published', 1)->where('todays_deal', '1'))->get() as $key => $product)
                                @if ($product != null)
                                    <div class="kkml-deal__inner carousel-box">
                                        <a href="{{ route('product', $product->slug) }}"
                                            class="d-block p-2 text-reset h-100 rounded">
                                            <div class="kkml-deal__img position-relative">
                                                <img class="lazyload img-fit"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                                                @if (home_base_price($product->id) != home_discounted_base_price($product->id))
                                                    @if ($product->discount_type == 'percent')
                                                        <span class="offer-badge">{{ $product->discount }}%</span>
                                                    @endif
                                                @endif
                                            </div>
                                            <div
                                                class="kkml-deal__price @if (home_base_price($product->id) != home_discounted_base_price($product->id)) justify-content-between @else justify-content-center @endif">
                                                <p class="fw-700 text-primary fs-16">
                                                    {{ home_discounted_base_price($product->id) }}
                                                </p>
                                                @if (home_base_price($product->id) != home_discounted_base_price($product->id))
                                                    <div class="fs-14">
                                                        <del
                                                            class="fw-600 opacity-50 mr-1">{{ home_base_price($product->id) }}</del>
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- End: Today's Deal --}}

    {{-- Flash Deal --}}
    @php
        $flash_deal = \App\FlashDeal::where('status', 1)
            ->where('featured', 1)
            ->first();
    @endphp

    @if (
        $flash_deal != null &&
            strtotime(date('Y-m-d H:i:s')) >= $flash_deal->start_date &&
            strtotime(date('Y-m-d H:i:s')) <= $flash_deal->end_date)
        <section class="mb-4">
            <div class="container">
                <div class="px-3 py-3 bg-white shadow-sm rounded">
                    <div class="d-flex flex-wrap mb-3 align-items-baseline border-bottom section-title">
                        <h3 class="h5 fw-700 mb-0">
                            <span
                                class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Flash Sale') }}</span>
                        </h3>
                        <div class="aiz-count-down ml-auto ml-lg-3 align-items-center"
                            data-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                        <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                            class="ml-auto mr-0 btn btn-primary btn-sm shadow-md w-100 w-md-auto">{{ translate('View More') }}</a>
                    </div>

                    <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5"
                        data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'
                        data-infinite='true'>
                        @foreach ($flash_deal->flash_deal_products as $key => $flash_deal_product)
                            @php
                                $product = \App\Product::find($flash_deal_product->product_id);
                            @endphp
                            @if ($product != null && $product->published != 0)
                                <div class="carousel-box">
                                    <div
                                        class="aiz-card-box rounded hov-shadow-md my-2 has-transition">
                                        <div class="position-relative">
                                            <a href="{{ route('product', $product->slug) }}" class="d-block">
                                                <img class="img-fit lazyload mx-auto"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </a>
                                            <div class="absolute-top-right aiz-p-hov-icon">
                                                <a href="javascript:void(0)" onclick="addToWishList({{ $product->id }})"
                                                    data-toggle="tooltip" data-title="{{ translate('Add to wishlist') }}"
                                                    data-placement="left">
                                                    <i class="la la-heart-o"></i>
                                                </a>
                                                <a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})"
                                                    data-toggle="tooltip" data-title="{{ translate('Add to compare') }}"
                                                    data-placement="left">
                                                    <i class="las la-sync"></i>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    onclick="showAddToCartModal({{ $product->id }})"
                                                    data-toggle="tooltip" data-title="{{ translate('Add to cart') }}"
                                                    data-placement="left">
                                                    <i class="las la-shopping-cart"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="p-md-3 p-2 text-left">

                                            <div class="fs-15">
                                                <span
                                                    class="fw-700 text-primary">{{ home_discounted_base_price($product->id) }}</span>
                                            </div>

                                            @if (home_base_price($product->id) != home_discounted_base_price($product->id))
                                                <div class="fs-15">
                                                    <del
                                                        class="fw-600 opacity-50 mr-1">{{ home_base_price($product->id) }}</del>
                                                    @php
                                                        $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)
                                                            ->where('product_id', $product->id)
                                                            ->first();
                                                    @endphp

                                                    @if ($flash_deal_product->discount_type ?? '' == 'percent')
                                                        <span
                                                            class="text-muted ml-1">{{ $flash_deal_product->discount }}%</span>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="rating rating-sm mt-1">
                                                {{ renderStarRating($product->rating) }}
                                            </div>
                                            <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                                <a href="{{ route('product', $product->slug) }}"
                                                    class="d-block text-reset">{{ $product->getTranslation('name') }}</a>
                                            </h3>
                                            @if (
                                                \App\Addon::where('unique_identifier', 'club_point')->first() != null &&
                                                    \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                                <div class="rounded px-2 mt-2 bg-soft-primary border-soft-primary border">
                                                    {{ translate('Club Point') }}:
                                                    <span class="fw-700 float-right">{{ $product->earn_point }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif


    {{-- Featured Section --}}
    <div id="section_featured">

    </div>
    {{-- End: Featured Section --}}


    {{-- Start: Authors --}}
    @if (get_setting('top10_categories') != null)
        <section class="mb-4">
            <div class="container">
                <div class="row gutters-10">
                    <div class="col-lg-12">
                        <div class="section-title bg-soft-primary rounded-top p-3 d-flex align-items-center justify-content-between">
                            <span class="fw-600 fs-16 mr-2 text-truncate">{{ translate('Top 10 Authors') }}</span>
                            <a href="{{ route('brands.all') }}"
                                class="mr-0 btn btn-primary btn-sm shadow-none shadow-md">{{ translate('View All') }}</a>
                        </div>
                    </div>
                </div>
                <div class="kkml-deal bg-white rounded-bottom py-15px px-25px">
                    <div class="kkml-authors aiz-carousel gutters-10 half-outside-arrow"  data-items="6" data-xl-items="5" data-lg-items="4"
                    data-md-items="3" data-sm-items="3" data-xs-items="2" data-arrows='true'
                        data-infinite='true'>
                        @php $top10_brands = json_decode(get_setting('top10_brands')); @endphp
                        @foreach ($top10_brands as $key => $value)
                            @php $brand = \App\Brand::find($value); @endphp
                            @if ($brand != null)
                                <div class="carousel-box">
                                    <a href="{{ route('products.brand', $brand->slug) }}"
                                        class=" d-block text-reset rounded" title="{{ $brand->getTranslation('name') }}"
                                        data-toggle="tooltip">
                                        <div class="kkml-authors__img">
                                            <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ uploaded_asset($brand->logo) }}"
                                                alt="{{ $brand->getTranslation('name') }}"
                                                class="img-fluid img lazyload h-60px"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        </div>
                                        <div class="kkml-authors__name">
                                            <p class="text-truncate-2 fs-16 fw-600 mb-0">
                                                {{ $brand->getTranslation('name') }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
                {{-- <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                    
                    <div class="bg-soft-primary rounded-top p-3 d-flex align-items-center justify-content-between">
                        <h3 class="h5 fw-700 mb-0">
                            <span
                                class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Top 10 Authors') }}</span>
                        </h3>
                        <a href="{{ route('brands.all') }}"
                            class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{ translate('View All Authors') }}</a>
                    </div>
                    
                </div> --}}
            </div>
        </section>
    @endif
    {{-- End: Authors --}}


    {{-- Best Selling  --}}
    <div id="section_best_selling">

    </div>
    {{-- End: Best Selling --}}
    {{-- Start: Banner section 1 --}}
    @if (get_setting('home_banner1_images') != null)
        <div class="mb-4">
            <div class="container">
                <div class="row gutters-10 row-cols-1 row-cols-md-2 row-cols-lg-3 justify-content-center mt-n3 ">
                    @php $banner_1_imags = json_decode(get_setting('home_banner1_images')); @endphp
                    @foreach ($banner_1_imags as $key => $value)
                        <div class="col-md mt-3">
                            {{-- <div class="mt-3 mt-md-0"> --}}
                                <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}"
                                    class="d-block text-reset kkml-banner">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($banner_1_imags[$key]) }}"
                                        alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload">
                                </a>
                            {{-- </div> --}}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    {{-- End: Banner 2 --}}
    {{-- Slide by category --}}
    @foreach (\App\Category::where('parent_id', 0)->get() as $category)
        @if ($category->products()->count() > 0)
            <div id="section_slide_by_categories_{{ $category->id }}">
                @include('frontend.partials.slide_product_by_category', ['category' => $category])
            </div>
        @endif
    @endforeach


    {{-- Banner Section 2 --}}
    @if (get_setting('home_banner2_images') != null)
        <div class="mb-4">
            <div class="container">
                <div class="row gutters-10">
                    @php $banner_2_imags = json_decode(get_setting('home_banner2_images')); @endphp
                    @foreach ($banner_2_imags as $key => $value)
                        <div class="col-xl col-md-6">
                            <div class="mb-3 mb-lg-0">
                                <a href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}"
                                    class="d-block text-reset">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($banner_2_imags[$key]) }}"
                                        alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif



    {{-- Category wise Products --}}
    <div id="section_home_categories">

    </div>


    {{--    <div id="section_home_all_product"> --}}

    {{--    </div> --}}


    {{-- Classified Product --}}
    @if (\App\BusinessSetting::where('type', 'classified_product')->first()->value == 1)
        @php
            $classified_products = \App\CustomerProduct::where('status', '1')
                ->where('published', '1')
                ->take(10)
                ->get();
        @endphp
        @if (count($classified_products) > 0)
            <section class="mb-4">
                <div class="container">
                    <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                        <div class="d-flex mb-3 align-items-baseline border-bottom section-title">
                            <h3 class="h5 fw-700 mb-0">
                                <span
                                    class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Classified Ads') }}</span>
                            </h3>
                            <a href="{{ route('customer.products') }}"
                                class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{ translate('View More') }}</a>
                        </div>
                        <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5"
                            data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'
                            data-infinite='true'>
                            @foreach ($classified_products as $key => $classified_product)
                                <div class="carousel-box">
                                    <div
                                        class="aiz-card-box border border-light rounded hov-shadow-md my-2 has-transition">
                                        <div class="position-relative">
                                            <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                class="d-block">
                                                <img class="img-fit lazyload mx-auto h-140px h-md-210px"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($classified_product->thumbnail_img) }}"
                                                    alt="{{ $classified_product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </a>
                                            <div class="absolute-top-left pt-2 pl-2">
                                                @if ($classified_product->conditon == 'new')
                                                    <span
                                                        class="badge badge-inline badge-success">{{ translate('new') }}</span>
                                                @elseif($classified_product->conditon == 'used')
                                                    <span
                                                        class="badge badge-inline badge-danger">{{ translate('Used') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="p-md-3 p-2 text-left">
                                            <div class="fs-15 mb-1">
                                                <span
                                                    class="fw-700 text-primary">{{ single_price($classified_product->unit_price) }}</span>
                                            </div>
                                            <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                                <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                    class="d-block text-reset">{{ $classified_product->getTranslation('name') }}</a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif

    {{-- Banner Section 2 --}}
    @if (get_setting('home_banner3_images') != null)
        <div class="mb-4">
            <div class="container">
                <div class="row gutters-10">
                    @php $banner_3_imags = json_decode(get_setting('home_banner3_images')); @endphp
                    @foreach ($banner_3_imags as $key => $value)
                        <div class="col-xl col-md-6">
                            <div class="mb-3 mb-lg-0">
                                <a href="{{ json_decode(get_setting('home_banner3_links'), true)[$key] }}"
                                    class="d-block text-reset">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($banner_3_imags[$key]) }}"
                                        alt="{{ env('APP_NAME') }} promo" class="img-fluid w-100 lazyload">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Best Seller --}}
    @if (\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
        <div id="section_best_sellers">

        </div>
    @endif


@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $.post('{{ route('home.section.featured') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_featured').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.best_selling') }}', {
                    _token: '{{ csrf_token() }}'
                },
                function(data) {
                    $('#section_best_selling').html(data);
                    AIZ.plugins.slickCarousel();
                });
            $.post('{{ route('home.section.home_categories') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_home_categories').html(data);
                AIZ.plugins.slickCarousel();
            });

            {{-- $.post('{{ route('home.section.all') }}', {_token: '{{ csrf_token() }}'}, function (data) { --}}
            {{--    $('#section_home_all_product').html(data); --}}
            {{--    //AIZ.plugins.slickCarousel(); --}}
            {{-- }); --}}

            var page = 1;

            // Attach click event to the "Load More Products" button
            $('#loadMoreBtn').on('click', function() {
                loadMoreData();
            });

            // Load the first set of products when the page is ready
            $(document).ready(function() {
                loadMoreData();
            });

            function loadMoreData() {
                $.ajax({
                    url: '{{ route('home.section.ajax_all') }}?page=' + page,
                    type: "get",
                    beforeSend: function() {
                        $('#all_product_loader').show();
                        $('#loadMoreBtn').prop('disabled', true); // Disable the button during loading
                    }
                }).done(function(data) {
                    if (!data) {
                        $('#all_product_loader').html("No more Product found!");
                        $('#all_product_loader').removeClass('spinner-border').addClass('text-muted')
                            .show();
                        return;
                    }

                    $('#ajax_all_product').append(data);
                    page += 1;

                }).fail(function(jqXHR, ajaxOptions, thrownError) {
                    // Handle errors
                }).always(function() {
                    $('#all_product_loader').hide();
                    $('#loadMoreBtn').prop('disabled', false); // Re-enable the button after loading
                });
            }

            @if (\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                $.post('{{ route('home.section.best_sellers') }}', {
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    $('#section_best_sellers').html(data);
                    AIZ.plugins.slickCarousel();
                });
            @endif
        });
    </script>
@endsection
