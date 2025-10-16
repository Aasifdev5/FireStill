@extends('master')
@section('title')
{{ __(' Project Details') }}
@endsection
@section('content')
<div class="pages-hero">
    <div class="pages-hero-diagonal"></div>
    <div class="container">
        <div class="pages-title">
            <h1>Project Single</h1>
            <p>Reliable Technology</p>
        </div>
    </div>
</div>
<section>
    <div class="container mt-5 mb-5">
        <div class="row">
            <!-- PROJECT CONTENT START -->
            <div class="col-lg-8">
                <figure class="project-feature">
                    <img src="images/commons/single-project.jpg" alt="">
                </figure>
                <div class="project-caption">
                    <h3 class="project-title">Project Information</h3>
                    <p>The vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium
                        voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati
                        cupiditate non provident. Similique sunt in culpa qui officia deserunt mollitia animi, id
                        est laborum et dolorum fuga. Et harum quidem rerum</p>

                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                        laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore
                        veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem
                        quia voluptas sit aspernatur aut odit aut fugit</p>

                    <ul class="project-bullets">
                        <li>Developing Renewable Energy Sources.</li>
                        <li>Randomised words which don't look even</li>
                        <li>Therefore always free from repetition.</li>
                        <li>Sed ut perspiciatis unde omnis iste natus.</li>
                        <li>The Benefits pf Developing Renewable Energy.</li>
                    </ul>
                </div>
            </div>
            <!-- PROJECT CONTENT END -->

            <!-- PROJECT SIDEBAR START -->
            <div class="col-lg-4 spacing-md">
                <aside class="project-details">
                    <div class="project-block">
                        <div class="media">
                            <img src="images/icons/project-rocket.png" class="mr-3" alt="...">
                            <div class="media-body">
                                <h5 class="mt-0">Project Name</h5>
                                <p>Headquater Building</p>
                            </div>
                        </div>
                    </div>
                    <div class="project-block">
                        <div class="media">
                            <img src="images/icons/project-user.png" class="mr-3" alt="...">
                            <div class="media-body">
                                <h5 class="mt-0">Client Name</h5>
                                <p>Innova Technology</p>
                            </div>
                        </div>
                    </div>
                    <div class="project-block">
                        <div class="media">
                            <img src="images/icons/project-idea.png" class="mr-3" alt="...">
                            <div class="media-body">
                                <h5 class="mt-0">Categorie</h5>
                                <p>Construction</p>
                            </div>
                        </div>
                    </div>
                    <div class="project-block">
                        <div class="media">
                            <img src="images/icons/project-calendar.png" class="mr-3" alt="...">
                            <div class="media-body">
                                <h5 class="mt-0">Date</h5>
                                <p>15-12-2010</p>
                            </div>
                        </div>
                    </div>
                    <div class="inner-aside">
                        <figure class="promo-banner">
                            <img src="images/commons/banner.jpg" alt="">
                        </figure>
                    </div>
                </aside>
            </div>
            <!-- PROJECT SIDEBAR END -->
        </div>
    </div>

    <!-- COUNTER START -->
    <div class="counter-layer mt-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="counter-box">
                        <div class="counter-icon">
                            <img src="images/icons/project.png" alt="">
                        </div>
                        <div class="counter-statistics">
                            <div class="counter" data-count="1120">1120</div>
                            <h5>PROJECTS LAUNCHED</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 movil-view">
                    <div class="counter-box">
                        <div class="counter-icon">
                            <img src="images/icons/rocket.png" alt="">
                        </div>
                        <div class="counter-statistics">
                            <div class="counter" data-count="3788">3788</div>
                            <h5>COMPLETE GOALS</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 tablet-view">
                    <div class="counter-box">
                        <div class="counter-icon">
                            <img src="images/icons/engineer.png" alt="">
                        </div>
                        <div class="counter-statistics">
                            <div class="counter" data-count="487">487</div>
                            <h5>Qualify Staff</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 tablet-view">
                    <div class="counter-box">
                        <div class="counter-icon">
                            <img src="images/icons/medal-2.png" alt="">
                        </div>
                        <div class="counter-statistics">
                            <div class="counter" data-count="8444">8444</div>
                            <h5>AWARD RECEIVED</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COUNTER END -->

    <!-- OTHER PROJECTS START -->
    <div class="container mt-5 mb-5">
        <div class="section-title">
            <h2>Other Projects</h2>
            <p>Industra is a global community of practice that facilitates dialogue, information exchange and use of
                information.</p>
        </div>
        <div class="project-carousel">
            <div class="owl-carousel owl-theme owl-loaded owl-drag">





            <div class="owl-stage-outer"><div class="owl-stage" style="transform: translate3d(-1119px, 0px, 0px); transition: all; width: 4107px;"><div class="owl-item cloned" style="width: 363.333px; margin-right: 10px;"><div class="item">
                    <figure class="project-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="images/commons/project-solution-3.jpg" alt=""></a>
                        <div class="inner-caption">
                            <div class="project-categorie">
                                <h5>Welding</h5>
                            </div>
                            <div class="project-description">
                                <h4>Atrom Metro City</h4>
                                <p>Follow these tips from the CDC to help prevent the spread of the seasonal
                                    companies.</p>
                            </div>
                        </div>
                    </figure>
                </div></div><div class="owl-item cloned" style="width: 363.333px; margin-right: 10px;"><div class="item">
                    <figure class="project-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="images/commons/project-solution-5.jpg" alt=""></a>
                        <div class="inner-caption">
                            <div class="project-categorie">
                                <h5>Energy &amp; Power</h5>
                            </div>
                            <div class="project-description">
                                <h4>Rosco Eolic Park</h4>
                                <p>We realize that every family has their own preferences, so we accommodate all
                                    requests.</p>
                            </div>
                        </div>
                    </figure>
                </div></div><div class="owl-item cloned" style="width: 363.333px; margin-right: 10px;"><div class="item">
                    <figure class="project-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="images/commons/project-solution-4.jpg" alt=""></a>
                        <div class="inner-caption">
                            <div class="project-categorie">
                                <h5>Chemical</h5>
                            </div>
                            <div class="project-description">
                                <h4>Chemical University</h4>
                                <p>While some cleaning companies use rotating cleaning plans, we’re equipped to
                                    clean house.</p>
                            </div>
                        </div>
                    </figure>
                </div></div><div class="owl-item active" style="width: 363.333px; margin-right: 10px;"><div class="item">
                    <figure class="project-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="images/commons/project-solution-1.jpg" alt=""></a>
                        <div class="inner-caption">
                            <div class="project-categorie">
                                <h5>Agriculture</h5>
                            </div>
                            <div class="project-description">
                                <h4>Ocoa Greenhouse</h4>
                                <p>Industra plays a large role in the comfort of your home, but many homeowners.</p>
                            </div>
                        </div>
                    </figure>
                </div></div><div class="owl-item active" style="width: 363.333px; margin-right: 10px;"><div class="item">
                    <figure class="project-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="images/commons/project-solution-2.jpg" alt=""></a>
                        <div class="inner-caption">
                            <div class="project-categorie">
                                <h5>Power &amp; Energy</h5>
                            </div>
                            <div class="project-description">
                                <h4>San Juan Electrical</h4>
                                <p>We are closely monitoring national, state and local health agencies for
                                    developments..</p>
                            </div>
                        </div>
                    </figure>
                </div></div><div class="owl-item active" style="width: 363.333px; margin-right: 10px;"><div class="item">
                    <figure class="project-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="images/commons/project-solution-3.jpg" alt=""></a>
                        <div class="inner-caption">
                            <div class="project-categorie">
                                <h5>Welding</h5>
                            </div>
                            <div class="project-description">
                                <h4>Atrom Metro City</h4>
                                <p>Follow these tips from the CDC to help prevent the spread of the seasonal
                                    companies.</p>
                            </div>
                        </div>
                    </figure>
                </div></div><div class="owl-item" style="width: 363.333px; margin-right: 10px;"><div class="item">
                    <figure class="project-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="images/commons/project-solution-5.jpg" alt=""></a>
                        <div class="inner-caption">
                            <div class="project-categorie">
                                <h5>Energy &amp; Power</h5>
                            </div>
                            <div class="project-description">
                                <h4>Rosco Eolic Park</h4>
                                <p>We realize that every family has their own preferences, so we accommodate all
                                    requests.</p>
                            </div>
                        </div>
                    </figure>
                </div></div><div class="owl-item" style="width: 363.333px; margin-right: 10px;"><div class="item">
                    <figure class="project-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="images/commons/project-solution-4.jpg" alt=""></a>
                        <div class="inner-caption">
                            <div class="project-categorie">
                                <h5>Chemical</h5>
                            </div>
                            <div class="project-description">
                                <h4>Chemical University</h4>
                                <p>While some cleaning companies use rotating cleaning plans, we’re equipped to
                                    clean house.</p>
                            </div>
                        </div>
                    </figure>
                </div></div><div class="owl-item cloned" style="width: 363.333px; margin-right: 10px;"><div class="item">
                    <figure class="project-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="images/commons/project-solution-1.jpg" alt=""></a>
                        <div class="inner-caption">
                            <div class="project-categorie">
                                <h5>Agriculture</h5>
                            </div>
                            <div class="project-description">
                                <h4>Ocoa Greenhouse</h4>
                                <p>Industra plays a large role in the comfort of your home, but many homeowners.</p>
                            </div>
                        </div>
                    </figure>
                </div></div><div class="owl-item cloned" style="width: 363.333px; margin-right: 10px;"><div class="item">
                    <figure class="project-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="images/commons/project-solution-2.jpg" alt=""></a>
                        <div class="inner-caption">
                            <div class="project-categorie">
                                <h5>Power &amp; Energy</h5>
                            </div>
                            <div class="project-description">
                                <h4>San Juan Electrical</h4>
                                <p>We are closely monitoring national, state and local health agencies for
                                    developments..</p>
                            </div>
                        </div>
                    </figure>
                </div></div><div class="owl-item cloned" style="width: 363.333px; margin-right: 10px;"><div class="item">
                    <figure class="project-thumbnail">
                        <a href="{{ url('segundaFase')}}"><img src="images/commons/project-solution-3.jpg" alt=""></a>
                        <div class="inner-caption">
                            <div class="project-categorie">
                                <h5>Welding</h5>
                            </div>
                            <div class="project-description">
                                <h4>Atrom Metro City</h4>
                                <p>Follow these tips from the CDC to help prevent the spread of the seasonal
                                    companies.</p>
                            </div>
                        </div>
                    </figure>
                </div></div></div></div><div class="owl-dots"><button role="button" class="owl-dot active"><span></span></button><button role="button" class="owl-dot"><span></span></button></div></div>
        </div>
    </div>
    <!-- OTHER PROJECTS END -->
</section>
@endsection
