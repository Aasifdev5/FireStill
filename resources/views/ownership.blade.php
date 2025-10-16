@extends('master')
@section('title')
    {{ __('Projects') }}
@endsection
@section('content')
<div class="pages-hero">
    <div class="pages-hero-diagonal"></div>
    <div class="container">
        <div class="pages-title">
            <h1>Project List</h1>
            <p>Reliable Technology</p>
        </div>
    </div>
</div>
<section>
    <!-- PROJECT LIST START -->
    <div class="container mt-5 mb-5 ">
        <div class="section-title">
            <h2>Recent Projects</h2>
            <p>Industra is a global community of practice that facilitates dialogue, information exchange and use of
                information.</p>
        </div>
        <div class="row hover-effects image-hover">
            <div class="col-md-6 col-lg-4">
                <div class="project-box">
                    <figure class="pb-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="{{ asset('images/commons/industra-img-16.jpg') }}" alt=""></a>
                        <div class="left-border">
                            <figure class="ib-icon">
                                <img src="{{ asset('images/icons/wireless-charging.png') }}" alt="">
                            </figure>
                        </div>
                    </figure>
                    <div class="pb-caption">
                        <h4><a href="{{ url('segundaFase')}}">San Juan Electrical</a></h4>
                        <h6>Power &amp; Energy</h6>
                        <p>The generated Lorem Ipsum is therefore always free from repetition injected humour.</p>
                        <div class="arrow-layer">
                            <i class="far fa-arrow-alt-circle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="project-box">
                    <figure class="pb-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="{{ asset('images/commons/industra-img-17.jpg') }}" alt=""></a>
                        <div class="left-border">
                            <figure class="ib-icon">
                                <img src="{{ asset('images/icons/agronomy.png') }}" alt="">
                            </figure>
                        </div>
                    </figure>
                    <div class="pb-caption">
                        <h4><a href="{{ url('segundaFase')}}">Ocoa Greenhouse</a></h4>
                        <h6>Agricultural</h6>
                        <p>Industra plays a large role in the comfort of your home, but many homeowners simply don’t
                            have.</p>
                        <div class="arrow-layer">
                            <i class="far fa-arrow-alt-circle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="project-box">
                    <figure class="pb-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="{{ asset('images/commons/industra-img-18.jpg') }}" alt=""></a>
                        <div class="left-border">
                            <figure class="ib-icon">
                                <img src="{{ asset('images/icons/weld.png') }}" alt="">
                            </figure>
                        </div>
                    </figure>
                    <div class="pb-caption">
                        <h4><a href="{{ url('segundaFase')}}">Atrom Metro City</a></h4>
                        <h6>Welding</h6>
                        <p>We are closely monitoring national, state and local health agencies for recent
                            developments.</p>
                        <div class="arrow-layer">
                            <i class="far fa-arrow-alt-circle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="project-box">
                    <figure class="pb-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="{{ asset('images/commons/industra-img-20.jpg') }}" alt=""></a>
                        <div class="left-border">
                            <figure class="ib-icon">
                                <img src="{{ asset('images/icons/test.png') }}" alt="">
                            </figure>
                        </div>
                    </figure>
                    <div class="pb-caption">
                        <h4><a href="{{ url('segundaFase')}}">Chemical University</a></h4>
                        <h6>Chemical Research</h6>
                        <p>Follow these tips from the CDC to help prevent the spread of the seasonal companies.</p>
                        <div class="arrow-layer">
                            <i class="far fa-arrow-alt-circle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="project-box">
                    <figure class="pb-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="{{ asset('images/commons/industra-img-19.jpg') }}" alt=""></a>
                        <div class="left-border">
                            <figure class="ib-icon">
                                <img src="{{ asset('images/icons/wind-mill.png') }}" alt="">
                            </figure>
                        </div>
                    </figure>
                    <div class="pb-caption">
                        <h4><a href="{{ url('segundaFase')}}">Rosco Eolic Park</a></h4>
                        <h6>Power &amp; Energy</h6>
                        <p>We realize that every family has their own preferences, so we accommodate all your your
                            specific requests.</p>
                        <div class="arrow-layer">
                            <i class="far fa-arrow-alt-circle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="project-box">
                    <figure class="pb-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="{{ asset('images/commons/industra-img-21.jpg') }}" alt=""></a>
                        <div class="left-border">
                            <figure class="ib-icon">
                                <img src="{{ asset('images/icons/oil-platform.png') }}" alt="">
                            </figure>
                        </div>
                    </figure>
                    <div class="pb-caption">
                        <h4><a href="{{ url('segundaFase')}}">Haina Refinery Industry</a></h4>
                        <h6>Oil &amp; Gas</h6>
                        <p>While some cleaning companies use rotating cleaning plans, we’re equipped to clean house.
                        </p>
                        <div class="arrow-layer">
                            <i class="far fa-arrow-alt-circle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PROJECT LIST END -->

    <!-- CLIENTS BANNER STAR -->
    <div class="client-banner mt-5">
        <div class="container">
            <div class="clients-carousel">
                <div class="owl-carousel owl-theme owl-loaded owl-drag">








                <div class="owl-stage-outer"><div class="owl-stage" style="transform: translate3d(-1120px, 0px, 0px); transition: all; width: 4032px;"><div class="owl-item cloned" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-4.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item cloned" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-5.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item cloned" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-6.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item cloned" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-7.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item cloned" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-8.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item active" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-1.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item active" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-2.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item active" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-3.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item active" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-4.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item active" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-5.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-6.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-7.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-8.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item cloned" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-1.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item cloned" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-2.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item cloned" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-3.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item cloned" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-4.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div><div class="owl-item cloned" style="width: 214px; margin-right: 10px;"><div class="item">
                        <div class="client-box">
                            <figure class="client-icon">
                                <img src="{{ asset('images/commons/client-5.png') }}" alt="">
                            </figure>
                        </div>
                    </div></div></div></div><div class="owl-dots"><button role="button" class="owl-dot active"><span></span></button><button role="button" class="owl-dot"><span></span></button></div></div>
            </div>
        </div>
    </div>
    <!-- CLIENTS BANNER END -->

    <!-- MAP START -->
    <div class="bottom-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d167616.99483399244!2d-74.08279002518668!3d40.67646407501496!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a560db693e3%3A0xb05e8b0bdf854b54!2sGowanus%2C+Brooklyn%2C+Nueva+York%2C+EE.+UU.!5e0!3m2!1ses-419!2sdo!4v1560863423970!5m2!1ses-419!2sdo" class="map-iframe-alt"></iframe>
        <div class="container">
            <div class="overmap-contact">
                <h5 class="subtitle">Focus On Excellence</h5>
                <h3>Get In Touch</h3>
                <p>We are a company dedicated to giving our customers back the time they deserve to enjoy the things
                    they love. We put
                    The Extra In Your Ordinary.</p>
                <a class="btn btn-default" href="{{ url('contact') }}" role="button">Contact Us</a>
            </div>
        </div>
    </div>
    <!-- MAP END -->

</section>
@endsection
