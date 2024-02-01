@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedService->meta_title }}@stop

@section('meta_description'){{ $detailedService->meta_description }}@stop

@section('meta_keywords'){{ $detailedService->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedService->meta_title }}">
    <meta itemprop="description" content="{{ $detailedService->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedService->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedService->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedService->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedService->meta_img) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedService->meta_title }}"/>
    <meta property="og:type" content="og:product"/>
    <meta property="og:url" content="{{ route('product', $detailedService->slug) }}"/>
    <meta property="og:image" content="{{ uploaded_asset($detailedService->meta_img) }}"/>
    <meta property="og:description" content="{{ $detailedService->meta_description }}"/>
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}"/>
    <meta property="og:price:amount" content="{{ single_price($detailedService->unit_price) }}"/>
    <meta property="product:price:currency"
          content="{{ \App\Currency::findOrFail(\App\BusinessSetting::where('type', 'system_default_currency')->first()->value)->code }}"/>
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection

@section('content')
    <section class="mb-4 pt-3">
        <div class="container">
            <div class="bg-white shadow-sm rounded p-3">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 mb-4">
                        <div class="sticky-top z-3 row gutters-10">
                            <div class="col order-1 order-md-2">
                                <div class="aiz-carousel product-gallery" data-nav-for='.product-gallery-thumb'
                                     data-fade='true' data-auto-height='true'>
                                    <div class="carousel-box img-zoom rounded">
                                        <img
                                            class="img-fluid lazyload"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($detailedService->thumbnail_img) }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                        >
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-xl-7 col-lg-6">
                        <div class="text-left">
                            <h1 class="mb-2 fs-20 fw-600">
                                {{ $detailedService->name }} 
                            </h1>
                            @if(Session::has('success'))
                                <p class="my-2 alert alert-success alert-block">
                                    <button type="button" class="close m-1" data-dismiss="alert"></button>    
                                    <strong>{{ Session::get('success') }}</strong>
                                </p>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1)
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-soft-primary"
                                                onclick="show_chat_modal()">{{ translate('Message Seller')}}</button>
                                    </div>
                                </div>
                            @endif
                            <hr>
                            <div class="d-table width-100 mt-3">
                                <div class="d-table-cell">
                                    <div class="mw-100 overflow-hidden text-left">
                                        <?php echo $detailedService->description; ?>
                                    </div>
                                </div>
                            </div>


                            @php
                                $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                                $refund_sticker = \App\BusinessSetting::where('type', 'refund_sticker')->first();
                            @endphp
                            @if ($refund_request_addon != null && $refund_request_addon->activated == 1 && $detailedService->refundable)
                                <div class="row no-gutters mt-4">
                                    <div class="col-sm-2">
                                        <div class="opacity-50 my-2">{{ translate('Refund')}}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <a href="{{ route('returnpolicy') }}" target="_blank">
                                            @if ($refund_sticker != null && $refund_sticker->value != null)
                                                <img src="{{ uploaded_asset($refund_sticker->value) }}"
                                                     height="36">
                                            @else
                                                <img src="{{ static_asset('assets/img/refund-sticker.jpg') }}"
                                                     height="36">
                                            @endif
                                        </a>
                                        <a href="{{ route('returnpolicy') }}" class="ml-2"
                                           target="_blank">{{ translate('View Policy') }}</a>
                                    </div>
                                </div>
                            @endif
                            <div class="mt-3">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                    <i class="la la-shopping-cart"></i> {{ translate('Take an Appointment')}}
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form class="form form-horizontal mar-top" action="{{route('service.appoint')}}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="mb-0 h6 modal-title">{{translate('Apply for appointment.')}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        

                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-body">
                                                    @if(Auth::user())
                                                        <input type="text" name="id" value="({{Auth::user()->id}}) - {{Auth::user()->name}}" hidden>
                                                    @else
                                                        <input type="text" name="id" value="Guest" hidden>
                                                    @endif
                                                    <input type="text" name="service_id" value="{{$detailedService->id}}" hidden>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-from-label">{{translate('Name')}} <span class="text-danger">*</span></label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="name" placeholder="{{ translate('Full Name') }}" required>
                                                        </div>
                                                    </div>
                        
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-from-label">{{translate('Email')}}<span class="text-danger">*</span></label>
                                                        <div class="col-md-8">
                                                            <input type="email" class="form-control" name="email" placeholder="{{ translate('Email') }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-from-label">{{translate('Contact Number')}}<span class="text-danger">*</span></label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="phone" pattern="[0-9]+" placeholder="{{ translate('Contact Number') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                        </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row no-gutters mt-4">
                                <div class="col-sm-2">
                                    <div class="opacity-50 my-2">{{ translate('Share')}}:</div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="aiz-share"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                <div class="col-xl-3 order-1 order-xl-0">
                    <div class="bg-white shadow-sm mb-3">
                        <div class="position-relative p-3 text-left">
                            @if ($detailedService->added_by == 'seller' && \App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1 && $detailedProduct->user->seller->verification_status == 1)
                                <div class="absolute-top-right p-2 bg-white z-1">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                         xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve"
                                         viewBox="0 0 287.5 442.2" width="22" height="34">
                                        <polygon style="fill:#F8B517;"
                                                 points="223.4,442.2 143.8,376.7 64.1,442.2 64.1,215.3 223.4,215.3 "/>
                                        <circle style="fill:#FBD303;" cx="143.8" cy="143.8" r="143.8"/>
                                        <circle style="fill:#F8B517;" cx="143.8" cy="143.8" r="93.6"/>
                                        <polygon style="fill:#FCFCFD;" points="143.8,55.9 163.4,116.6 227.5,116.6 175.6,154.3 195.6,215.3 143.8,177.7 91.9,215.3 111.9,154.3
                                        60,116.6 124.1,116.6 "/>
                                    </svg>
                                </div>
                            @endif
                            <div class="opacity-50 fs-12 border-bottom">{{ translate('Sold By')}}</div>
                            @if($detailedService->added_by == 'seller' && \App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                                <a href="{{ route('shop.visit', $detailedService->user->shop->slug) }}"
                                   class="text-reset d-block fw-600">
                                    {{ $detailedService->user->shop->name }}
                                    @if ($detailedService->user->seller->verification_status == 1)
                                        <span class="ml-2"><i class="fa fa-check-circle" style="color:green"></i></span>
                                    @else
                                        <span class="ml-2"><i class="fa fa-times-circle" style="color:red"></i></span>
                                    @endif
                                </a>
                                <div class="location opacity-70">{{ $detailedService->user->shop->address }}</div>
                            @else
                                <div class="fw-600">{{ env("APP_NAME") }}</div>
                            @endif
                            @php
                                $total = 0;
                                $rating = 0;
                                foreach ($detailedService->user->products as $key => $seller_product) {
                                    $total += $seller_product->reviews->count();
                                    $rating += $seller_product->reviews->sum('rating');
                                }
                            @endphp

                            <div class="text-center border rounded p-2 mt-3">
                                <div class="rating">
                                    @if ($total > 0)
                                        {{ renderStarRating($rating/$total) }}
                                    @else
                                        {{ renderStarRating(0) }}
                                    @endif
                                </div>
                                <div class="opacity-60 fs-12">({{ $total }} {{ translate('customer reviews')}})</div>
                            </div>
                        </div>
                        @if($detailedService->added_by == 'seller' && \App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                            <div class="row no-gutters align-items-center border-top">
                                <div class="col">
                                    <a href="{{ route('shop.visit', $detailedService->user->shop->slug) }}"
                                       class="d-block btn btn-soft-primary rounded-0">{{ translate('Visit Store')}}</a>
                                </div>
                                <div class="col">
                                    <ul class="social list-inline mb-0">
                                        <li class="list-inline-item mr-0">
                                            <a href="{{ $detailedService->user->shop->facebook }}" class="facebook"
                                               target="_blank">
                                                <i class="lab la-facebook-f opacity-60"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item mr-0">
                                            <a href="{{ $detailedService->user->shop->google }}" class="google"
                                               target="_blank">
                                                <i class="lab la-google opacity-60"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item mr-0">
                                            <a href="{{ $detailedService->user->shop->twitter }}" class="twitter"
                                               target="_blank">
                                                <i class="lab la-twitter opacity-60"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="{{ $detailedService->user->shop->youtube }}" class="youtube"
                                               target="_blank">
                                                <i class="lab la-youtube opacity-60"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="bg-white rounded shadow-sm mb-3">
                        <div class="p-3 border-bottom fs-16 fw-600">
                            {{ translate('Top Selling Products')}}
                        </div>
                        <div class="p-3">
                            <ul class="list-group list-group-flush">
                                @foreach (filter_products(\App\Product::where('user_id', $detailedService->user_id)->orderBy('num_of_sale', 'desc'))->limit(6)->get() as $key => $top_product)
                                    <li class="py-3 px-0 list-group-item border-light">
                                        <div class="row gutters-10 align-items-center">
                                            <div class="col-5">
                                                <a href="{{ route('product', $top_product->slug) }}"
                                                   class="d-block text-reset">
                                                    <img
                                                        class="img-fit lazyload h-xxl-110px h-xl-80px h-120px"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ uploaded_asset($top_product->thumbnail_img) }}"
                                                        alt="{{ $top_product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    >
                                                </a>
                                            </div>
                                            <div class="col-7 text-left">
                                                <h4 class="fs-13 text-truncate-2">
                                                    <a href="{{ route('product', $top_product->slug) }}"
                                                       class="d-block text-reset">{{ $top_product->getTranslation('name') }}</a>
                                                </h4>
                                                <div class="rating rating-sm mt-1">
                                                    {{ renderStarRating($top_product->rating) }}
                                                </div>
                                                <div class="mt-2">
                                                    <span
                                                        class="fs-17 fw-600 text-primary">{{ home_discounted_base_price($top_product->id) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 order-0 order-xl-1">
                    <div class="bg-white mb-3 shadow-sm rounded">
                        <div class="nav border-bottom aiz-nav-tabs">
                            <a href="#tab_default_1" data-toggle="tab"
                               class="p-3 fs-16 fw-600 text-reset active show">{{ translate('Contact Info')}}</a>
                            @if($detailedProduct->video_link != null)
                                <a href="#tab_default_2" data-toggle="tab"
                                   class="p-3 fs-16 fw-600 text-reset">{{ translate('Video')}}</a>
                            @endif
                            @if($detailedProduct->pdf != null)
                                <a href="#tab_default_3" data-toggle="tab"
                                   class="p-3 fs-16 fw-600 text-reset">{{ translate('Downloads')}}</a>
                            @endif
                        </div>

                        <div class="tab-content pt-0">
                            <div class="tab-pane fade active show" id="tab_default_1">
                                <div class="p-4">
                                    <div class="mw-100 overflow-hidden text-left">
                                        <?php echo $detailedService->address; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_default_2">
                                <div class="p-4">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        @if ($detailedProduct->video_provider == 'youtube' && isset(explode('=', $detailedProduct->video_link)[1]))
                                            <iframe class="embed-responsive-item"
                                                    src="https://www.youtube.com/embed/{{ explode('=', $detailedProduct->video_link)[1] }}"></iframe>
                                        @elseif ($detailedProduct->video_provider == 'dailymotion' && isset(explode('video/', $detailedProduct->video_link)[1]))
                                            <iframe class="embed-responsive-item"
                                                    src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] }}"></iframe>
                                        @elseif ($detailedProduct->video_provider == 'vimeo' && isset(explode('vimeo.com/', $detailedProduct->video_link)[1]))
                                            <iframe
                                                src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $detailedProduct->video_link)[1] }}"
                                                width="500" height="281" frameborder="0" webkitallowfullscreen
                                                mozallowfullscreen allowfullscreen></iframe>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_default_3">
                                <div class="p-4 text-center ">
                                    <a href="{{ uploaded_asset($detailedProduct->pdf) }}"
                                       class="btn btn-primary">{{  translate('Download') }}</a>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_default_4">
                                <div class="p-4">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($detailedProduct->reviews as $key => $review)
                                            @if($review->user != null)
                                                <li class="media list-group-item d-flex">
                                                <span class="avatar avatar-md mr-3">
                                                    <img
                                                        class="lazyload"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                        @if($review->user->avatar_original !=null)
                                                        data-src="{{ uploaded_asset($review->user->avatar_original) }}"
                                                        @else
                                                        data-src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        @endif
                                                    >
                                                </span>
                                                    <div class="media-body text-left">
                                                        <div class="d-flex justify-content-between">
                                                            <h3 class="fs-15 fw-600 mb-0">{{ $review->user->name }}</h3>
                                                            <span class="rating rating-sm">
                                                            @for ($i=0; $i < $review->rating; $i++)
                                                                    <i class="las la-star active"></i>
                                                                @endfor
                                                                @for ($i=0; $i < 5-$review->rating; $i++)
                                                                    <i class="las la-star"></i>
                                                                @endfor
                                                        </span>
                                                        </div>
                                                        <div
                                                            class="opacity-60 mb-2">{{ date('d-m-Y', strtotime($review->created_at)) }}</div>
                                                        <p class="comment-text">
                                                            {{ $review->comment }}
                                                        </p>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>

                                    @if(count($detailedProduct->reviews) <= 0)
                                        <div class="text-center fs-18 opacity-70">
                                            {{  translate('There have been no reviews for this product yet.') }}
                                        </div>
                                    @endif

                                    @if(Auth::check())
                                        @php
                                            $commentable = false;
                                        @endphp
                                        @foreach ($detailedProduct->orderDetails as $key => $orderDetail)
                                            @if($orderDetail->order != null && $orderDetail->order->user_id == Auth::user()->id && $orderDetail->delivery_status == 'delivered' && \App\Review::where('user_id', Auth::user()->id)->where('product_id', $detailedProduct->id)->first() == null)
                                                @php
                                                    $commentable = true;
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if ($commentable)
                                            <div class="pt-4">
                                                <div class="border-bottom mb-4">
                                                    <h3 class="fs-17 fw-600">
                                                        {{ translate('Write a review')}}
                                                    </h3>
                                                </div>
                                                <form class="form-default" role="form"
                                                      action="{{ route('reviews.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id"
                                                           value="{{ $detailedProduct->id }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for=""
                                                                       class="text-uppercase c-gray-light">{{ translate('Your name')}}</label>
                                                                <input type="text" name="name"
                                                                       value="{{ Auth::user()->name }}"
                                                                       class="form-control" disabled required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for=""
                                                                       class="text-uppercase c-gray-light">{{ translate('Email')}}</label>
                                                                <input type="text" name="email"
                                                                       value="{{ Auth::user()->email }}"
                                                                       class="form-control" required disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="opacity-60">{{ translate('Rating')}}</label>
                                                        <div class="rating rating-input">
                                                            <label>
                                                                <input type="radio" name="rating" value="1">
                                                                <i class="las la-star"></i>
                                                            </label>
                                                            <label>
                                                                <input type="radio" name="rating" value="2">
                                                                <i class="las la-star"></i>
                                                            </label>
                                                            <label>
                                                                <input type="radio" name="rating" value="3">
                                                                <i class="las la-star"></i>
                                                            </label>
                                                            <label>
                                                                <input type="radio" name="rating" value="4">
                                                                <i class="las la-star"></i>
                                                            </label>
                                                            <label>
                                                                <input type="radio" name="rating" value="5">
                                                                <i class="las la-star"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="opacity-60">{{ translate('Comment')}}</label>
                                                        <textarea class="form-control" rows="4" name="comment"
                                                                  placeholder="{{ translate('Your review')}}"
                                                                  required></textarea>
                                                    </div>

                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-primary mt-3">
                                                            {{ translate('Submit review')}}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="bg-white rounded shadow-sm">
                        <div class="border-bottom p-3">
                            <h3 class="fs-16 fw-600 mb-0">
                                <span class="mr-4">{{ translate('Other Products')}}</span>
                            </h3>
                        </div>
                        <div class="p-3">
                            <div class="aiz-carousel gutters-5 half-outside-arrow" data-items="5" data-xl-items="3"
                                 data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2"
                                 data-arrows='true' data-infinite='true'>
                                @foreach (filter_products(\App\Product::where('category_id', $detailedProduct->category_id)->where('id', '!=', $detailedProduct->id))->limit(10)->get() as $key => $related_product)
                                    <div class="carousel-box">
                                        <div
                                            class="aiz-card-box border border-light rounded hov-shadow-md my-2 has-transition">
                                            <div class="">
                                                <a href="{{ route('product', $related_product->slug) }}"
                                                   class="d-block">
                                                    <img
                                                        class="img-fit lazyload mx-auto h-140px h-md-210px"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ uploaded_asset($related_product->thumbnail_img) }}"
                                                        alt="{{ $related_product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    >
                                                </a>
                                            </div>
                                            <div class="p-md-3 p-2 text-left">
                                                <div class="fs-15">
                                    <span
                                        class="fw-700 text-primary">{{ home_discounted_base_price($related_product->id) }}</span>
                                                </div>

                                                @if(home_base_price($related_product->id) != home_discounted_base_price($related_product->id))
                                                    <div class="fs-15">
                                                        <del
                                                            class="fw-600 opacity-50 mr-1">{{ home_base_price($related_product->id) }}</del>
                                                        @if($related_product->discount_type=='percent')
                                                            <span class="text-muted ml-1">{{$related_product->discount}}%</span>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="rating rating-sm mt-1">
                                                    {{ renderStarRating($related_product->rating) }}
                                                </div>
                                                <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                                    <a href="{{ route('product', $related_product->slug) }}"
                                                       class="d-block text-reset">{{ $related_product->getTranslation('name') }}</a>
                                                </h3>
                                                @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                                    <div
                                                        class="rounded px-2 mt-2 bg-soft-primary border-soft-primary border">
                                                        {{ translate('Club Point') }}:
                                                        <span
                                                            class="fw-700 float-right">{{ $related_product->earn_point }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('modal')
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3" name="title"
                                   value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                                   required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="8" name="message" required
                                      placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600"
                                data-dismiss="modal">{{ translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary fw-600">{{ translate('Send')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Login')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                    <input type="text"
                                           class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone')}}"
                                           name="email" id="email">
                                @else
                                    <input type="email"
                                           class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           value="{{ old('email') }}" placeholder="{{  translate('Email') }}"
                                           name="email">
                                @endif
                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                    <span class="opacity-60">{{  translate('Use country code before number') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg"
                                       placeholder="{{ translate('Password')}}">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class=opacity-60>{{  translate('Remember Me') }}</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('password.request') }}"
                                       class="text-reset opacity-60 fs-14">{{ translate('Forgot password?')}}</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit"
                                        class="btn btn-primary btn-block fw-600">{{  translate('Login') }}</button>
                            </div>
                        </form>

                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">{{ translate('Dont have an account?')}}</p>
                            <a href="{{ route('user.registration') }}">{{ translate('Register Now')}}</a>
                        </div>
                        @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                            <div class="separator mb-3">
                                <span class="bg-white px-3 opacity-60">{{ translate('Or Login With')}}</span>
                            </div>
                            <ul class="list-inline social colored text-center mb-5">
                                @if (\App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'facebook']) }}"
                                           class="facebook">
                                            <i class="lab la-facebook-f"></i>
                                        </a>
                                    </li>
                                @endif
                                @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                            <i class="lab la-google"></i>
                                        </a>
                                    </li>
                                @endif
                                @if (\App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'twitter']) }}"
                                           class="twitter">
                                            <i class="lab la-twitter"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            getVariantPrice();
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }

        function show_chat_modal() {
            @if (Auth::check())
            $('#chat_modal').modal('show');
            @else
            $('#login_modal').modal('show');
            @endif
        }

    </script>
@endsection
