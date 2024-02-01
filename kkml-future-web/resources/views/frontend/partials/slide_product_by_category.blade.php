<section class="mb-4">
    <div class="container">
        <div class="px-3 py-3 section-title bg-white shadow-sm rounded">
            <div class="d-flex mb-3 align-items-baseline border-bottom">
                <h3 class="h5 fw-700 mb-0">
                    <span
                        class="border-bottom border-primary border-width-2 pb-md-3 pb-2 d-inline-block">{{ translate($category->name) }}</span>
                </h3>
            </div>
            <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"
                data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='true'>
                @foreach (filter_products_by_category(
        \App\Product::where('published', 1)->where('category_id', $category->id)->orderBy('num_of_sale', 'desc'),
    )->limit(12)->get() as $key => $product)
                    <div class="carousel-box">
                        <div class="aiz-card-box rounded hov-shadow-md my-2 has-transition" data-toggle="tooltip" data-placement="right" title="{{ $product->getTranslation('name') }}">
                            {{-- Product Image and Hover  --}}
                            <div class="position-relative">
                                <a href="{{ route('product', $product->slug) }}" class="position-relative">
                                    <img class="img-fit lazyload mx-auto"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                                    @if ($product->discount_type == 'percent')
                                        <span class=" offer-badge ">{{ $product->discount }}%</span>
                                    @endif
                                </a>
                                <div class="absolute-top-right kkml-card-hov-icon aiz-p-hov-icon">
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
                            {{-- Product Information Area --}}
                            <div class="p-md-3 p-1 text-center d-flex flex-column align-items-center">


                                <h3 class="fw-600 fs-16 text-truncate-2 lh-1-4 mb-0 ">
                                    <a href="{{ route('product', $product->slug) }}"
                                        class="d-block text-reset">{{ $product->getTranslation('name') }}</a>
                                </h3>
                                @if (isset($product->brand->name))
                                    <h5 class="fw-400 fs-12 text-truncate-2 lh-1-4 mb-0 text-center text-secondary">
                                        {{ $product->brand->name }}
                                    </h5>
                                @endif

                                @if (
                                    \App\Addon::where('unique_identifier', 'club_point')->first() != null &&
                                        \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                    <div class="rounded px-2 mt-2 bg-soft-primary border-soft-primary border">
                                        {{ translate('Club Point') }}:
                                        <span class="fw-700 float-right">{{ $product->earn_point }}</span>
                                    </div>
                                @endif


                                {{-- Product Rating   --}}
                                <div class="rating rating-lg mt-1">
                                    {{ renderStarRating($product->rating) }}
                                </div>


                                {{-- Price And Discount Price  --}}
                                <div class="fs-18">
                                    <span
                                        class="fw-700 text-primary">{{ home_discounted_base_price($product->id) }}</span>
                                </div>

                                @if (home_base_price($product->id) != home_discounted_base_price($product->id))
                                    <div class="fs-12">
                                        <del class="fw-400 opacity-50 mr-1">{{ home_base_price($product->id) }}</del>

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
