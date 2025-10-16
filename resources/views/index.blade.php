@extends('master')
@section('title')
{{ __('Inicio') }}
@endsection
@section('content')
<section>
    <!-- HOME BOX SERVICES START -->
    <div class="container mt-5 mb-5">
        <div class="front-carousel">
            <div class="owl-carousel owl-theme">
                <div class="item">
                    <div class="box-layer">
                        <div class="icon-box">
                            <figure class="bl-icon">
                                <img src="{{ asset('images/icons/innovation-white.png') }}" alt="">
                            </figure>
                        </div>
                        <h4>Innovation Tecnology</h4>
                        <p>The Industrial is responsible for minor and the codes security in hotel.</p>
                        <h6><a href="agriculture-engineering.html">Learn more <i
                                    class="fas fa-angle-double-right"></i></a></h6>
                    </div>
                </div>
                <div class="item">
                    <div class="box-layer">
                        <div class="icon-box">
                            <figure class="bl-icon">
                                <img src="{{ asset('images/icons/medal-2-white.png') }}" alt="">
                            </figure>
                        </div>
                        <h4>Awarded Company</h4>
                        <p>We are responsible to keep the house clean giving our customers back the time.</p>
                        <h6><a href="agriculture-engineering.html">Learn more <i
                                    class="fas fa-angle-double-right"></i></a></h6>
                    </div>
                </div>
                <div class="item">
                    <div class="box-layer">
                        <div class="icon-box">
                            <figure class="bl-icon">
                                <img src="{{ asset('images/icons/mechanical-white.png') }}" alt="">
                            </figure>
                        </div>
                        <h4>Certified Process</h4>
                        <p>We has met the demands of a growing world farmers has a tremendous.</p>
                        <h6><a href="agriculture-engineering.html">Learn more <i
                                    class="fas fa-angle-double-right"></i></a></h6>
                    </div>
                </div>
                <div class="item">
                    <div class="box-layer">
                        <div class="icon-box">
                            <figure class="bl-icon">
                                <img src="{{ asset('images/icons/shield-white.png') }}" alt="">
                            </figure>
                        </div>
                        <h4>Extended Warranty</h4>
                        <p>We put the extra in your ordinary, restoring balance to your life.</p>
                        <h6><a href="agriculture-engineering.html">Learn more <i
                                    class="fas fa-angle-double-right"></i></a></h6>
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
    <!-- HOME BOX SERVICES END -->
    <div class="service-wrapper mt-5 mb-5" id="solution">
        <div class="container">
            <div class="section-title">
                <h2>Our Services</h2>
                <p>Industra is a global community of practice that facilitates dialogue, information exchange and
                    use of information.</p>
            </div>
            <div class="service-grid">
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="grid-service-box">
                            <figure class="grid-service-icon">
                                <img src="images/icons/agriculture.png" alt="">
                            </figure>
                            <h4>Agricultural Engineering</h4>
                            <p>Homes and thoroughly launder them between usage, We give our teams.</p>
                            <div class="service-btn d-flex justify-content-center">
                                <a href="#" class="btn-default btn-outline btn-lg">Learn more</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="grid-service-box">
                            <figure class="grid-service-icon">
                                <img src="images/icons/energy.png" alt="">
                            </figure>
                            <h4>Power &amp; Energy</h4>
                            <p>We are closely monitoring national, state and local health agencies.</p>
                            <div class="service-btn d-flex justify-content-center">
                                <a href="#" class="btn-default btn-outline btn-lg">Learn more</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="grid-service-box">
                            <figure class="grid-service-icon">
                                <img src="images/icons/chemical.png" alt="">
                            </figure>
                            <h4>Chemical Research</h4>
                            <p>Follow these tips from the CDC to help prevent the spread of the seasonal.</p>
                            <div class="service-btn d-flex justify-content-center">
                                <a href="#" class="btn-default btn-outline btn-lg">Learn more</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="grid-service-box">
                            <figure class="grid-service-icon">
                                <img src="images/icons/mechanical.png" alt="">
                            </figure>
                            <h4>Mechanical Engineering</h4>
                            <p>Industra plays a large role in the comfort of your home, but many.</p>
                            <div class="service-btn d-flex justify-content-center">
                                <a href="#" class="btn-default btn-outline btn-lg">Learn more</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="grid-service-box">
                            <figure class="grid-service-icon">
                                <img src="images/icons/oil.png" alt="">
                            </figure>
                            <h4>Oil &amp; Gas</h4>
                            <p>We realize that every family has their own preferences, so we accommodate.</p>
                            <div class="service-btn d-flex justify-content-center">
                                <a href="#" class="btn-default btn-outline btn-lg">Learn more</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="grid-service-box">
                            <figure class="grid-service-icon">
                                <img src="images/icons/material.png" alt="">
                            </figure>
                            <h4>Material Engineering</h4>
                            <p>While some cleaning companies use rotating cleaning plans equipped.</p>
                            <div class="service-btn d-flex justify-content-center">
                                <a href="#" class="btn-default btn-outline btn-lg">Learn more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HOME ABOUT START -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-lg-6">
                <figure class="about-front">
                    <img src="{{ asset('images/commons/engineer-workers.jpg') }}" alt="">
                    <div class="mission-layer">
                        <figure class="ml-icon">
                            <img src="{{ asset('images/icons/handshake.png') }}" alt="">
                        </figure>
                        <h4>Our Mission</h4>
                        <p>World’s leading industrial based supply chain management companies.</p>
                    </div>
                </figure>
            </div>
            <div class="col-lg-6 spacing-md">
                <div class="about-layer">
                    <h5 class="subtitle">Who we are</h5>
                    <h2>We Are Industra for <br /> Surveying Solutions.</h2>
                    <p><strong>We offer reasonable pricing health care plans, <br /> insurance packages to
                            clients.</strong></p>
                    <p>We are a company dedicated to giving our customers back the time they deserve to enjoy the
                        things they love. We put the extrain your ordinary, restoring balance to your life by taking
                        care of your business.</p>
                    <h6><a href="agriculture-engineering.html">Learn more <i
                                class="fas fa-angle-double-right"></i></a></h6>
                    <div class="signature-layer">
                        <figure class="signature-img">
                            <img src="{{ asset('images/commons/signature.png') }}" alt="">
                        </figure>
                        <div class="front-contact-layer">
                            <h4><a href="tel:123-456-7890"> 123-456-7890</a></h4>
                            <p>Call Us for Service</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HOME ABOUT END -->

    <!-- WIDE SECTION START -->
    <div class="ws-wrapper mt-5">
        <div class="row no-gutters">
            <div class="col-lg-6">
                <div class="ws-feature-left-alt">
                    <div class="label-layer">
                        <figure class="label-icon">
                            <img src="{{ asset('images/icons/engineer-hat.png') }}" alt="">
                        </figure>
                        <div class="label-layer-caption">
                            <h4>Safety, Quality, Distinction</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ws-feature-right-alt">
                    <div class="feature-right-content-alt">
                        <div class="layer-box-alt">
                            <div class="span-title">
                                <h5>Why Us</h5>
                                <h2>The Feature Power</h2>
                            </div>
                            <p>We are a company dedicated to giving our customers back the time they deserve to
                                enjoy the things they love.
                                We put the extra in your ordinary, restoring balance to your life by taking care of
                                your home..</p>
                        </div>
                        <div class="layer-box-alt">
                            <div class="media">
                                <div class="rectangle-layer">
                                    <img src="{{ asset('images/icons/industrial.png') }}" class="mr-3" alt="...">
                                </div>
                                <div class="media-body">
                                    <h4 class="mt-0">Quality Assurance</h4>
                                    <p>We serve with best superiority and service your toolkit for business
                                        originality are brings cheers to put your solid hat on.</p>
                                </div>
                            </div>
                        </div>
                        <div class="layer-box-alt">
                            <div class="media">
                                <div class="rectangle-layer">
                                    <img src="{{ asset('images/icons/phone-services.png') }}" class="mr-3" alt="...">
                                </div>
                                <div class="media-body">
                                    <h4 class="mt-0">24/7 Support</h4>
                                    <p>Both of us take a lot of time in getting cleaned and beautified Clean
                                        Home. Professional Service, cleaned right the first time.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- WIDE SECTION END -->

    <!-- COUNTER START -->
    <div class="counter-layer-alt mb-5">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="counter-col-layer">
                        <div class="counter" data-count="1120">0</div>
                        <h4>PROJECTS LAUNCHED</h4>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="counter-col-layer">
                        <div class="counter" data-count="3788">0</div>
                        <h4>COMPLETE GOALS</h4>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="counter-col-layer">
                        <div class="counter" data-count="487">0</div>
                        <h4>QUALIFY STAFF</h4>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="counter-col-layer">
                        <div class="counter" data-count="8444">0</div>
                        <h4>AWARD RECEIVED</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- COUNTER END -->

    <!-- PROJECT GRID START -->
    <div class="container mt-5 mb-5">
        <div class="section-title">
            <h2>Recent Projects</h2>
            <p>Industra is a global community of practice that facilitates dialogue, information exchange and use of
                information.</p>
        </div>
        <div class="filter-container">
            <div class="row">
                <div class="col-sm-10 col-md-7 mx-auto">
                    <ul class="filter">
                        <li class="active" data-filter="*">All</li>
                        <li data-filter=".chemical">Chemical</li>
                        <li data-filter=".energy">Energy</li>
                        <li data-filter=".material">Material</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="grid" id="kehl-grid">
            <div class="grid-sizer"></div>
            <div class="grid-box chemical">
                <a href="project-single.html">
                    <figure class="project-thumbnail-img"><img src="{{ asset('images/commons/industra-img-16.jpg') }}" alt="" />
                        <figcaption><i class="ion-upload"></i>
                            <h5>Power & Energy</h5>
                            <h4>San Juan Electrical</h4>
                        </figcaption>
                    </figure>
                </a>
            </div>
            <div class="grid-box material energy">
                <a href="project-single.html">
                    <figure class="project-thumbnail-img"><img src="{{ asset('images/commons/industra-img-17.jpg') }}" alt="" />
                        <figcaption><i class="ion-upload"></i>
                            <h5>Agricultural</h5>
                            <h4>Ocoa Greenhouse</h4>
                        </figcaption>
                    </figure>
                </a>
            </div>
            <div class="grid-box energy">
                <a href="project-single.html">
                    <figure class="project-thumbnail-img"><img src="{{ asset('images/commons/industra-img-18.jpg') }}" alt="" />
                        <figcaption><i class="ion-upload"></i>
                            <h5>Welding</h5>
                            <h4>Atrom Metro City</h4>
                        </figcaption>
                    </figure>
                </a>
            </div>
            <div class="grid-box material">
                <a href="project-single.html">
                    <figure class="project-thumbnail-img"><img src="{{ asset('images/commons/industra-img-20.jpg') }}" alt="" />
                        <figcaption><i class="ion-upload"></i>
                            <h5>Chemical Research</h5>
                            <h4>Chemical University</h4>
                        </figcaption>
                    </figure>
                </a>
            </div>
            <div class="grid-box chemical">
                <a href="project-single.html">
                    <figure class="project-thumbnail-img"><img src="{{ asset('images/commons/industra-img-19.jpg') }}" alt="" />
                        <figcaption><i class="ion-upload"></i>
                            <h5>Power & Energy</h5>
                            <h4>Rosco Eolic Park</h4>
                        </figcaption>
                    </figure>
                </a>
            </div>
            <div class="grid-box chemical material">
                <a href="project-single.html">
                    <figure class="project-thumbnail-img"><img src="{{ asset('images/commons/industra-img-21.jpg') }}" alt="" />
                        <figcaption><i class="ion-upload"></i>
                            <h5>Oil & Gas</h5>
                            <h4>Haina Refinery Industry</h4>
                        </figcaption>
                    </figure>
                </a>
            </div>
        </div>
    </div>
    <!-- PROJECT GRID END -->

    <!-- TESTIMONIAL START -->
    <div class="front-testimonials">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="about-testimonials">
                        <h5>OUR CLIENTS</h5>
                        <h2>
                            <span-light>We Take Your</span-light> <br /> BUSINESS FURTHER
                        </h2>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="testimonial-carousel-alt">
                        <div class="owl-carousel owl-theme">
                            <div class="item">
                                <div class="testimonial-alt">
                                    <div class="media client-message">
                                        <i class="fas fa-quote-left"></i>
                                        <div class="media-body">
                                            <p>Wanted to share a quick note and let you know that you guys do a
                                                really good job. I’m glad I decided to work with you.</p>
                                        </div>
                                    </div>
                                    <div class="media client-info">
                                        <div class="media-body">
                                            <h5 class="mt-0">Sara Thomas</h5>
                                            <p>Marketing Director</p>
                                        </div>
                                        <img src="{{ asset('images/commons/avatar-1.jpg') }}" class="mr-3" alt="...">
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="testimonial-alt">
                                    <div class="media client-message">
                                        <i class="fas fa-quote-left"></i>
                                        <div class="media-body">
                                            <p>Start with your super fans. These are your happiest clients and
                                                customers. They may have already offered to be a reference.</p>
                                        </div>
                                    </div>
                                    <div class="media client-info">
                                        <div class="media-body">
                                            <h5 class="mt-0">Matt Cimino</h5>
                                            <p>Web Designer</p>
                                        </div>
                                        <img src="{{ asset('images/commons/avatar3.jpg') }}" class="mr-3" alt="...">
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="testimonial-alt">
                                    <div class="media client-message">
                                        <i class="fas fa-quote-left"></i>
                                        <div class="media-body">
                                            <p>An endorsement is typically a well-known influencer giving their
                                                public support for a brand. But a testimonial is from a customer.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="media client-info">
                                        <div class="media-body">
                                            <h5 class="mt-0">Jaret Smith</h5>
                                            <p>Sales Department</p>
                                        </div>
                                        <img src="{{ asset('images/commons/avatar6.jpg') }}" class="mr-3" alt="...">
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="testimonial-alt">
                                    <div class="media client-message">
                                        <i class="fas fa-quote-left"></i>
                                        <div class="media-body">
                                            <p>My testimonial is from a customer or client. They may be an unknown
                                                person to the reader, you need to be sure there isn't anything.</p>
                                        </div>
                                    </div>
                                    <div class="media client-info">
                                        <div class="media-body">
                                            <h5 class="mt-0">Luisa Jones</h5>
                                            <p>Marketing</p>
                                        </div>
                                        <img src="{{ asset('images/commons/avatar2.jpg') }}" class="mr-3" alt="...">
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
            </div>
        </div>
        <!-- <div class="container mt-4"></div> -->
    </div>
    <!-- TESTIMONIAL END -->

    <!-- BLOG NEWS START -->
    <div class="container mt-5 mb-5">
        <div class="section-title">
            <h2>Recent Projects</h2>
            <p>Industra is a global community of practice that facilitates dialogue, information exchange and use of
                information.</p>
        </div>
        <div class="news-carousel">
            <div class="owl-carousel owl-theme">
                <div class="item">
                    <div class="blog-grid">
                        <figure class="bg-thumbnail">
                            <a href="blog-single.html"><img src="{{ asset('images/commons/blog-grid-thumb-1.jpg') }}" alt=""></a>
                            <div class="span-date">
                                <h3>23</h3>
                                <p>Jan</p>
                            </div>
                        </figure>
                        <div class="bg-caption">
                            <h4><a href="blog-single.html">The Industrial Sector is Revolutionizing</a></h4>
                            <p> We are a company dedicated to giving our customers back the deserve.</p>
                            <div class="media">
                                <img src="{{ asset('images/commons/blog-avatar-1.jpg') }}" class="mr-3" alt="...">
                                <div class="media-body">
                                    <h5 class="mt-0">Sara Smith</h5>
                                    <p>Construction Engineer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="blog-grid">
                        <figure class="bg-thumbnail">
                            <a href="blog-single.html"><img src="{{ asset('images/commons/blog-grid-thumb-2.jpg') }}" alt=""></a>
                            <div class="span-date">
                                <h3>22</h3>
                                <p>Jan</p>
                            </div>
                        </figure>
                        <div class="bg-caption">
                            <h4><a href="blog-single.html">The Improvements in construction techniques</a></h4>
                            <p>Randomised words which don't look even slightly believable.</p>
                            <div class="media">
                                <img src="{{ asset('images/commons/blog-avatar-2.jpg') }}" class="mr-3" alt="...">
                                <div class="media-body">
                                    <h5 class="mt-0">Laura Jones</h5>
                                    <p>Electrical Engineer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="blog-grid">
                        <figure class="bg-thumbnail">
                            <a href="blog-single.html"><img src="{{ asset('images/commons/blog-grid-thumb-3.jpg') }}" alt=""></a>
                            <div class="span-date">
                                <h3>21</h3>
                                <p>Jan</p>
                            </div>
                        </figure>
                        <div class="bg-caption">
                            <h4><a href="blog-single.html">The Roth strategy we wish retirement</a></h4>
                            <p>We are making this the first true generator on the Internet.</p>
                            <div class="media">
                                <img src="{{ asset('images/commons/blog-avatar-3.jpg') }}" class="mr-3" alt="...">
                                <div class="media-body">
                                    <h5 class="mt-0">Robert Thomas</h5>
                                    <p>Electrical Engineer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="blog-grid">
                        <figure class="bg-thumbnail">
                            <a href="blog-single.html"><img src="{{ asset('images/commons/blog-grid-thumb-4.jpg') }}" alt=""></a>
                            <div class="span-date">
                                <h3>20</h3>
                                <p>Jan</p>
                            </div>
                        </figure>
                        <div class="bg-caption">
                            <h4><a href="blog-single.html">The advances in the mechanics sector</a></h4>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium.</p>
                            <div class="media">
                                <img src="{{ asset('images/commons/blog-avatar-4.jpg') }}" class="mr-3" alt="...">
                                <div class="media-body">
                                    <h5 class="mt-0">Maria Doe</h5>
                                    <p>Electrical Engineer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="blog-grid">
                        <figure class="bg-thumbnail">
                            <a href="blog-single.html"><img src="{{ asset('images/commons/blog-grid-thumb-5.jpg') }}" alt=""></a>
                            <div class="span-date">
                                <h3>18</h3>
                                <p>Jan</p>
                            </div>
                        </figure>
                        <div class="bg-caption">
                            <h4><a href="blog-single.html">Hit the Road as a Design Digital Nomad</a></h4>
                            <p>The vero eos et accusamus et iusto odio dignissimos ducimus praesentium.</p>
                            <div class="media">
                                <img src="{{ asset('images/commons/blog-avatar-5.jpg') }}" class="mr-3" alt="...">
                                <div class="media-body">
                                    <h5 class="mt-0">Clara Cimino</h5>
                                    <p>Construction</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BLOG NEWS END -->
</section>

@endsection
