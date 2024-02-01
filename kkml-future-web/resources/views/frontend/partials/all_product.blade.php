<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
            <div class="d-flex mb-3 align-items-baseline border-bottom">
                <h3 class="h5 fw-700 mb-0">
                    <span
                        class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Products for You') }}</span>
                </h3>
            </div>

            <div class="row">
                @foreach (filter_products(\App\Product::where('published', 1)->inRandomOrder())->limit(60)->get() as $key => $product)
                    <div class="col-6 col-sm-4 col-md-3">
                        <div class="aiz-card-box border border-light rounded hov-shadow-md my-2 has-transition">
                            <div class="position-relative">
                                <a href="{{ route('product', $product->slug) }}" class="d-block">
                                    <img
                                        class="img-fit lazyload mx-auto h-140px h-md-210px"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{  $product->getTranslation('name')  }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                    >
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
                                    <a href="javascript:void(0)" onclick="showAddToCartModal({{ $product->id }})"
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

                                @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                    <div class="fs-15">
                                        <del class="fw-600 opacity-50 mr-1">{{ home_base_price($product->id) }}</del>
                                        @if($product->discount_type=='percent')
                                            <span class="text-muted ml-1">{{$product->discount}}%</span>
                                        @endif
                                    </div>
                                @endif
                                <div class="rating rating-sm mt-1">
                                    {{ renderStarRating($product->rating) }}
                                </div>
                                <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                    <a href="{{ route('product', $product->slug) }}"
                                       class="d-block text-reset">{{  $product->getTranslation('name')  }}</a>
                                </h3>

                                @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                    <div class="rounded px-2 mt-2 bg-soft-primary border-soft-primary border">
                                        {{ translate('Club Point') }}:
                                        <span class="fw-700 float-right">{{ $product->earn_point }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
