@extends('master')

@section('title')
    {{ __('SHOP') }}
@endsection

@section('content')
<div class="pages-hero">
    <div class="pages-hero-diagonal"></div>
    <div class="container">
        <div class="pages-title">
            <h1>Home Shop</h1>
            <p>Shopping with style</p>
        </div>
    </div>
</div>
    <!-- CONTENT START -->
    <section>
        <div class="container mt-5 mb-5">
            <div class="shop-carousel">
                <div class="owl-carousel owl-theme">
                    <div class="item">
                        <div class="banner-item" style="background-image: url('{{ asset('images/commons/shop-slider-banner-3.jpg') }}')">
                            <div class="banner-caption">
                                <h5>FEATURED PRODUCTS</h5>
                                <h2>Power Tools <br /> For Your Project</h2>
                                <p>Building relationships one house at a time.</p>
                                <a class="btn btn-default" href="#" role="button">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="banner-item" style="background-image: url('{{ asset('images/commons/shop-banner.jpg') }}')">
                            <div class="banner-caption">
                                <h5>FEATURED PRODUCTS</h5>
                                <h2>The Best Tools <br /> For Your Project</h2>
                                <p>Developing our people, Growing our business.</p>
                                <a class="btn btn-default" href="#" role="button">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="banner-item" style="background-image: url('{{ asset('images/commons/shop-slider-banner-2.jpg') }}')">
                            <div class="banner-caption">
                                <h5>FEATURED PRODUCTS</h5>
                                <h2>The Best Tools <br /> For Your Project</h2>
                                <p>Helping you and your house become better acquainted.</p>
                                <a class="btn btn-default" href="#" role="button">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="owl-theme">
                    <div class="owl-controls">
                        <div class="custom-nav owl-nav"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-5 mb-5">
            <div class="row">
                <div class="col-lg-6">
                    <div class="shop-banner-thumb" style="background-image: url('{{ asset('images/commons/shop-banner-thumb.jpg') }}')">
                        <div class="banner-thumb-caption">
                            <h5>Wooster Brush R017-9</h5>
                            <h3>On Sale!</h3>
                            <a class="btn btn-default" href="#" role="button">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 spacing-md">
                    <div class="shop-banner-thumb" style="background-image: url('{{ asset('images/commons/shop-banner-thumb-2.jpg') }}')">
                        <div class="banner-thumb-caption">
                            <h5>Household Hand Tool</h5>
                            <h3>On Sale!</h3>
                            <a class="btn btn-default" href="#" role="button">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- POPULAR PRODUCTS CAROUSEL START -->
        <div class="container mt-5 mb-5">
            <div class="popular-product-header">
                <h3>POPULAR <span>PRODUCTS</span></h3>
                <hr>
            </div>
            <div class="products-carousel">
                <div class="owl-carousel owl-theme">
                    <div class="item">
                        <div class="item-box">
                            <figure class="product-img">
                                <a href="{{url('membership')}}"><img src="{{ asset('images/commons/product-1.jpg') }}" alt=""></a>
                            </figure>
                            <div class="item-caption">
                                <h5>Power Drill</h5>
                                <h4><a href="{{url('membership')}}">Power Tool Lithium Ion Cordless Drill</a></h4>
                                <div class="item-review">
                                    <ul>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star-half-alt"></i></li>
                                        <li class="review-count">(3 reviews)</li>
                                    </ul>
                                </div>
                                <div class="item-bottom">
                                    <div class="item-price">
                                        <h3>$195.00</h3>
                                    </div>
                                    <div class="item-cart">
                                        <figure class="cart-icon">
                                            <a href="#"><img class="default-icon" src="{{ asset('images/icons/shopping-cart-default.png') }}" alt=""></a>
                                            <a href="#"><img class="active-icon" src="{{ asset('images/icons/shopping-cart.png') }}" alt=""></a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item-box">
                            <figure class="product-img">
                                <a href="{{url('membership')}}"><img src="{{ asset('images/commons/product-2.jpg') }}" alt=""></a>
                            </figure>
                            <div class="item-caption">
                                <h5>Mechanics Tools</h5>
                                <h4><a href="{{url('membership')}}">Power Cordless Rotary Tool Blue</a> </h4>
                                <div class="item-review">
                                    <ul>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star-half-alt"></i></li>
                                        <li class="review-count">(3 reviews)</li>
                                    </ul>
                                </div>
                                <div class="item-bottom">
                                    <div class="item-price">
                                        <h3>$210.00</h3>
                                    </div>
                                    <div class="item-cart">
                                        <figure class="cart-icon">
                                            <a href="#"><img class="default-icon" src="{{ asset('images/icons/shopping-cart-default.png') }}" alt=""></a>
                                            <a href="#"><img class="active-icon" src="{{ asset('images/icons/shopping-cart.png') }}" alt=""></a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item-box">
                            <figure class="product-img">
                                <a href="{{url('membership')}}"><img src="{{ asset('images/commons/product-3.jpg') }}" alt=""></a>
                            </figure>
                            <div class="item-caption">
                                <h5>Tool Set</h5>
                                <h4><a href="{{url('membership')}}">Power Tire Inflator Air Compressor</a> </h4>
                                <div class="item-review">
                                    <ul>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star-half-alt"></i></li>
                                        <li class="review-count">(3 reviews)</li>
                                    </ul>
                                </div>
                                <div class="item-bottom">
                                    <div class="item-price">
                                        <h3>$185.00</h3>
                                    </div>
                                    <div class="item-cart">
                                        <figure class="cart-icon">
                                            <a href="#"><img class="default-icon" src="{{ asset('images/icons/shopping-cart-default.png') }}" alt=""></a>
                                            <a href="#"><img class="active-icon" src="{{ asset('images/icons/shopping-cart.png') }}" alt=""></a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item-box">
                            <figure class="product-img">
                                <a href="{{url('membership')}}"><img src="{{ asset('images/commons/product-4.jpg') }}" alt=""></a>
                            </figure>
                            <div class="item-caption">
                                <h5>Mechanics Tools</h5>
                                <h4><a href="{{url('membership')}}">Vacuum Cleaner 2 in 1 Handheld</a> </h4>
                                <div class="item-review">
                                    <ul>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star-half-alt"></i></li>
                                        <li class="review-count">(3 reviews)</li>
                                    </ul>
                                </div>
                                <div class="item-bottom">
                                    <div class="item-price">
                                        <h3>$200.00</h3>
                                    </div>
                                    <div class="item-cart">
                                        <figure class="cart-icon">
                                            <a href="#"><img class="default-icon" src="{{ asset('images/icons/shopping-cart-default.png') }}" alt=""></a>
                                            <a href="#"><img class="active-icon" src="{{ asset('images/icons/shopping-cart.png') }}" alt=""></a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="owl-theme">
                    <div class="owl-controls">
                        <div class="custom-nav owl-nav"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- POPULAR PRODUCTS CAROUSEL END -->

        <!-- SHOP BANNER START -->
        <div class="container mt-5 mb-5">
            <div class="shop-banner">
                <div class="row banner-content">
                    <div class="col-md-12 col-lg-6">
                        <h3>Power Tools For Your Project</h3>
                        <p>Have the assurance in knowing that we'll get your project going.</p>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="sb-btn d-flex flex-row-reverse">
                            <a class="btn btn-default" href="#" role="button">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- SHOP BANNER END -->

        <!-- TOP RATE PRODUCTS START -->
        <div class="container mt-5 mb-5">
            <div class="popular-product-header">
                <h3>TOP RATE <span>PRODUCTS</span></h3>
                <hr>
            </div>
            <div class="products-carousel-2">
                <div class="owl-carousel owl-theme">
                    <div class="item">
                        <div class="product-box">
                            <div class="media">
                                <img src="{{ asset('images/commons/product-1.jpg') }}" class="mr-3" alt="...">
                                <div class="media-body">
                                    <h6>Power Dril</h6>
                                    <h5 class="mt-0">Lithium Ion Cordless Drill</h5>
                                    <div class="item-review">
                                        <ul>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star-half-alt"></i></li>
                                            <li class="review-count">(3 reviews)</li>
                                        </ul>
                                    </div>
                                    <h3 class="product-price">$200.00</h3>
                                    <div class="item-cart">
                                        <figure class="cart-icon">
                                            <a href="#"><img class="default-icon" src="{{ asset('images/icons/shopping-cart-default.png') }}" alt=""></a>
                                            <a href="#"><img class="active-icon" src="{{ asset('images/icons/shopping-cart.png') }}" alt=""></a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="product-box">
                            <div class="media">
                                <img src="{{ asset('images/commons/product-2.jpg') }}" class="mr-3" alt="...">
                                <div class="media-body">
                                    <h6>Mechanics Tools</h6>
                                    <h5 class="mt-0">Tire Inflator Air Compressor</h5>
                                    <div class="item-review">
                                        <ul>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star-half-alt"></i></li>
                                            <li class="review-count">(3 reviews)</li>
                                        </ul>
                                    </div>
                                    <h3 class="product-price">$185.00</h3>
                                    <div class="item-cart">
                                        <figure class="cart-icon">
                                            <a href="#"><img class="default-icon" src="{{ asset('images/icons/shopping-cart-default.png') }}" alt=""></a>
                                            <a href="#"><img class="active-icon" src="{{ asset('images/icons/shopping-cart.png') }}" alt=""></a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="product-box">
                            <div class="media">
                                <img src="{{ asset('images/commons/product-4.jpg') }}" class="mr-3" alt="...">
                                <div class="media-body">
                                    <h6>Tool Set</h6>
                                    <h5 class="mt-0">Vacuum Cleaner 2 in 1 Handheld</h5>
                                    <div class="item-review">
                                        <ul>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star-half-alt"></i></li>
                                            <li class="review-count">(3 reviews)</li>
                                        </ul>
                                    </div>
                                    <h3 class="product-price">$200.00</h3>
                                    <div class="item-cart">
                                        <figure class="cart-icon">
                                            <a href="#"><img class="default-icon" src="{{ asset('images/icons/shopping-cart-default.png') }}" alt=""></a>
                                            <a href="#"><img class="active-icon" src="{{ asset('images/icons/shopping-cart.png') }}" alt=""></a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="product-box">
                            <div class="media">
                                <img src="{{ asset('images/commons/product-3.jpg') }}" class="mr-3" alt="...">
                                <div class="media-body">
                                    <h6>Tool Set</h6>
                                    <h5 class="mt-0">Power Tire Inflator Air Compressor</h5>
                                    <div class="item-review">
                                        <ul>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star-half-alt"></i></li>
                                            <li class="review-count">(3 reviews)</li>
                                        </ul>
                                    </div>
                                    <h3 class="product-price">$190.00</h3>
                                    <div class="item-cart">
                                        <figure class="cart-icon">
                                            <a href="#"><img class="default-icon" src="{{ asset('images/icons/shopping-cart-default.png') }}" alt=""></a>
                                            <a href="#"><img class="active-icon" src="{{ asset('images/icons/shopping-cart.png') }}" alt=""></a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="owl-theme">
                    <div class="owl-controls">
                        <div class="custom-nav owl-nav"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TOP RATE PRODUCTS END -->
    </section>
    <!-- CONTENT END -->

@endsection
