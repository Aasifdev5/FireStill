<!DOCTYPE html>
<html lang="en-US" class="no-js">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @php
        $general_setting = \App\Models\Setting::pluck('option_value', 'option_key')->toArray();
        $category = getCategory();
        $adminNotifications = userNotifications();
    @endphp
    <title> {{ $general_setting['app_name'] ?? '' }} || @yield('title') </title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset($general_setting['app_fav_icon'] ?? '') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset($general_setting['app_fav_icon'] ?? '') }}" type="image/x-icon">

    <!-- Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('fonts/css/all.min.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('revolution/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}"
        type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('revolution/fonts/font-awesome/css/font-awesome.css') }}" type="text/css"
        media="all" />
    <link rel="stylesheet" href="{{ asset('revolution/css/settings.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('revolution/css/layers.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('revolution/css/navigation.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/lib/bootstrap.min.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/lib/flickity.min.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/lib/magnific-popup.min.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/lib/owl.carousel.min.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/lib/slick.min.css') }}" type="text/css" media="all" />
    <link property="stylesheet" rel="stylesheet" href="{{ asset('css/lib/aos.min.css') }}" type="text/css"
        media="all" />
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}" type="text/css" media="all">
    <link rel="stylesheet" href="{{ asset('css/stylesheet.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/rev-slider.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}" type="text/css" media="all" />

    <!-- MODERNIZR LIBRARY -->
    <script src="{{ asset('js/modernizr-custom.js') }}"></script>

</head>

<body>

    <!-- LOADER -->
    <div id="loader-wrapper">
        <div class="loader"></div>
    </div>
    <!-- LOADER -->

    <header>
        <!-- TOP HEADER START -->
        <div class="top-header-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="top-header-left">
                            <p class="address">837 Castle Hill Ave. Bronx NY 33195</p>
                            <p class="mail"><a href="mailto:info@industric.com">info@industric.com</a></p>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="top-header-right">
                            <p class="schedule">Office Hours: 9:00 AM - 6:00 PM</p>
                            <ul class="top-social">
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                            </ul>
                            <a class="btn btn-default" href="{{ url('contact') }}" role="button">Get a Quote</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TOP HEADER END -->

        <!-- NAV START -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a href="{{ url('/') }}" class="navbar-brand"><img src="{{ asset('images/logos/logo.png') }}" alt=""></a>

                <button type="button" class="navbar-toggler collapsed" data-toggle="collapse" data-target="#main-nav">
                    <span class="menu-icon-bar"></span>
                    <span class="menu-icon-bar"></span>
                    <span class="menu-icon-bar"></span>
                </button>

                <div id="main-nav" class="collapse navbar-collapse">
                    <ul class="navbar-nav ml-auto">
                        <li class="">
                            <a href="{{ url('/') }}" class="nav-item nav-link" >HOME</a>

                        </li>
                        <li class="">
                            <a href="{{ url('land') }}" class="nav-item nav-link" >SHOP</a>

                        </li>
                        <li class="">
                            <a href="{{ url('ownership') }}" class="nav-item nav-link" >Projects</a>

                        </li>

                        <li class="">
                            <a href="{{ url('about') }}" class="nav-item nav-link">ABOUT</a>

                        </li>
                        <li class="dropdown">
                            <a href="#" class="nav-item nav-link" data-toggle="dropdown">SOLUTIONS</a>
                            <div class="dropdown-menu">
                                <a href="{{ url('vacancy') }}" class="dropdown-item">Agricultural Engineering</a>
                                <a href="{{ url('vacancy') }}" class="dropdown-item">Chemical Research</a>
                                <a href="{{ url('vacancy') }}" class="dropdown-item">Power and Energy</a>
                                <a href="{{ url('vacancy') }}" class="dropdown-item">Petroleum and Gas</a>
                                <a href={{ url('vacancy') }}" class="dropdown-item">Material Engineering</a>
                                <a href="{{ url('vacancy') }}" class="dropdown-item">Mechanical Engineering</a>
                            </div>
                        </li>


                        <li class="">
                            <a href="{{ url('contact') }}" class="nav-item nav-link" >Contact</a>

                        </li>

                    </ul>
                </div>
            </div>
        </nav>
        <!-- NAV END -->
        @if (Route::currentRouteName() == 'home')


    <!--SLIDER START-->
    <div class="home-slider">
        <!-- SLIDER EXAMPLE -->
        <section class="example">
            <article class="slider-content">

                <div id="rev_slider_1164_1_wrapper" class="rev_slider_wrapper fullscreen-container"
                    data-alias="exploration-header" data-source="gallery"
                    style="background-color:transparent;padding:0px;">
                    <!-- START REVOLUTION SLIDER 5.4.1 fullscreen mode -->
                    <div id="rev_slider_1164_1" class="rev_slider fullscreenbanner" style="display:none;"
                        data-version="5.4.1">
                        <ul>
                            <!-- SLIDE  -->
                            <li data-index="rs-3204" data-transition="slideoververtical" data-slotamount="default"
                                data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default"
                                data-easeout="default" data-masterspeed="default"
                                data-thumb="{{ asset('images/news1-1-100x50.jpg') }}" data-rotate="0"
                                data-fstransition="fade" data-fsmasterspeed="2000" data-fsslotamount="7"
                                data-saveperformance="off" data-title="BUILDING YOUR DREAMS "
                                data-param1="We are a company dedicated to giving" data-param2="" data-param3=""
                                data-param4="" data-param5="" data-param6="" data-param7="" data-param8=""
                                data-param9="" data-param10="" data-description="">
                                <!-- MAIN IMAGE -->
                                <img src="{{ asset('images/home-slider-1.jpg') }}" alt=""
                                    data-lazyload="{{ asset('images/home-slider-1.jpg') }}" data-bgposition="center bottom"
                                    data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="off"
                                    class="rev-slidebg" data-no-retina>
                                <!-- LAYERS -->

                                <!-- LAYER NR. 1 -->
                                <div class="tp-caption  " id="slide-3204-layer-1"
                                    data-x="['left','left','left','left']" data-hoffset="['94','114','76','26']"
                                    data-y="['top','top','top','top']" data-voffset="['213','250','195','150']"
                                    data-fontsize="['60','45','35','25']" data-lineheight="['60','45','35','30']"
                                    data-width="['600','560','340','260']" data-height="none"
                                    data-whitespace="normal" data-type="text" data-basealign="slide"
                                    data-responsive_offset="off" data-responsive="off"
                                    data-frames='[{"from":"y:50px;opacity:0;","speed":1500,"to":"o:1;","delay":500,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[0,0,0,0]"
                                    style="z-index: 5; min-width: 600px; max-width: 600px; white-space: normal; font-size: 60px; line-height: 60px; font-weight: 700; color: rgba(255, 255, 255, 1.00);font-family:Roboto;border-width:0px;">
                                    BUILDING YOUR DREAMS</div>

                                <!-- LAYER NR. 2 -->
                                <div class="tp-caption tp-shape tp-shapewrapper " id="slide-3204-layer-2"
                                    data-x="['left','left','left','left']" data-hoffset="['50','70','50','0']"
                                    data-y="['top','top','top','top']" data-voffset="['220','257','200','155']"
                                    data-width="8" data-height="['104','77','59','48']" data-whitespace="normal"
                                    data-type="shape" data-basealign="slide" data-responsive_offset="off"
                                    data-responsive="off"
                                    data-frames='[{"from":"sY:0;","speed":1500,"to":"o:1;","delay":500,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[0,0,0,0]"
                                    style="z-index: 6;background-color:rgba(255, 204, 0, 1.00); border-color:rgba(0, 0, 0, 0);border-width:0px;">
                                </div>

                                <!-- LAYER NR. 3 -->
                                <div class="tp-caption  " id="slide-3204-layer-3"
                                    data-x="['left','left','left','left']" data-hoffset="['98','118','76','26']"
                                    data-y="['top','top','top','top']" data-voffset="['358','302','284','228']"
                                    data-width="['600','560','340','260']" data-height="none"
                                    data-whitespace="normal" data-type="text" data-basealign="slide"
                                    data-responsive_offset="off" data-responsive="off"
                                    data-frames='[{"from":"y:50px;opacity:0;","speed":1500,"to":"o:1;","delay":650,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[0,0,0,0]"
                                    style="z-index: 7; min-width: 600px; max-width: 600px; white-space: normal; font-size: 16px; line-height: 22px; font-weight: 500; color: rgba(255, 255, 255, 0.85);font-family:Roboto;border-width:0px;letter-spacing:2px;">
                                    We are a company dedicated to giving our customers back the time they deserve to
                                    enjoy the things they love. </div>

                                <!-- LAYER NR. 4 -->
                                <div class="tp-caption rev-btn " id="slide-3204-layer-4"
                                    data-x="['left','left','left','left']" data-hoffset="['50','70','50','26']"
                                    data-y="['top','top','top','top']" data-voffset="['465','401','385','350']"
                                    data-fontweight="['900','700','700','700']" data-width="none" data-height="none"
                                    data-whitespace="nowrap" data-type="button" data-basealign="slide"
                                    data-responsive_offset="off" data-responsive="off"
                                    data-frames='[{"from":"y:50px;opacity:0;","speed":1500,"to":"o:1;","delay":800,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"},{"frame":"hover","speed":"0","ease":"Power1.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0, 0, 0, 1.00);bg:rgba(255, 204, 0, 1.00);bw:2px 2px 2px 2px;"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[45,45,45,45]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[45,45,45,45]"
                                    style="z-index: 8; white-space: nowrap; font-size: 15px; line-height: 50px; font-weight: 900; color: rgba(255, 204, 0, 1.00) ;font-family:Roboto;background-color:rgba(0, 0, 0, 0);border-color:rgba(255, 204, 0, 1.00); border-style:solid;border-width:2px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;letter-spacing:3px;cursor:pointer;">
                                    REQUEST QUOTE<i style="font-size:13px;margin-left:10px;"
                                        class="fa-icon-chevron-right"></i> </div>
                            </li>
                            <!-- SLIDE  -->
                            <li data-index="rs-3205" data-transition="slideoververtical" data-slotamount="default"
                                data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default"
                                data-easeout="default" data-masterspeed="default"
                                data-thumb="{{ asset('images/news2-1-100x50.jpg') }}" data-rotate="0"
                                data-saveperformance="off" data-title="Innovating YOUR LIVING"
                                data-param1="Industra has met the demands of a growing" data-param2=""
                                data-param3="" data-param4="" data-param5="" data-param6="" data-param7=""
                                data-param8="" data-param9="" data-param10="" data-description="">
                                <!-- MAIN IMAGE -->
                                <img src="{{ asset('images/home-slider-2.jpg') }}" alt=""
                                    data-lazyload="assets/home-slider-2.jpg') }}" data-bgposition="center bottom"
                                    data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="off"
                                    class="rev-slidebg" data-no-retina>
                                <!-- LAYERS -->

                                <!-- LAYER NR. 5 -->
                                <div class="tp-caption  " id="slide-3205-layer-1"
                                    data-x="['left','left','left','left']" data-hoffset="['94','84','76','26']"
                                    data-y="['top','top','top','top']" data-voffset="['213','250','195','150']"
                                    data-fontsize="['60','45','35','25']" data-lineheight="['60','45','35','30']"
                                    data-width="['600','560','340','260']" data-height="none"
                                    data-whitespace="normal" data-type="text" data-basealign="slide"
                                    data-responsive_offset="off" data-responsive="off"
                                    data-frames='[{"from":"y:50px;opacity:0;","speed":1500,"to":"o:1;","delay":500,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[0,0,0,0]"
                                    style="z-index: 9; min-width: 600px; max-width: 600px; white-space: normal; font-size: 60px; line-height: 60px; font-weight: 700; color: rgba(255, 255, 255, 1.00);font-family:Roboto;border-width:0px;">
                                    INNOVATING YOUR LIVING</div>

                                <!-- LAYER NR. 6 -->
                                <div class="tp-caption tp-shape tp-shapewrapper " id="slide-3205-layer-2"
                                    data-x="['left','left','left','left']" data-hoffset="['50','40','50','0']"
                                    data-y="['top','top','top','top']" data-voffset="['220','257','200','155']"
                                    data-width="8" data-height="['104','77','59','48']" data-whitespace="normal"
                                    data-type="shape" data-basealign="slide" data-responsive_offset="off"
                                    data-responsive="off"
                                    data-frames='[{"from":"sY:0;","speed":1500,"to":"o:1;","delay":500,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[0,0,0,0]"
                                    style="z-index: 10;background-color:rgba(255, 204, 0, 1.00); border-color:rgba(0, 0, 0, 0);border-width:0px;">
                                </div>

                                <!-- LAYER NR. 7 -->
                                <div class="tp-caption  " id="slide-3205-layer-3"
                                    data-x="['left','left','left','left']" data-hoffset="['98','88','76','26']"
                                    data-y="['top','top','top','top']" data-voffset="['358','302','284','228']"
                                    data-width="['600','560','340','260']" data-height="none"
                                    data-whitespace="normal" data-type="text" data-basealign="slide"
                                    data-responsive_offset="off" data-responsive="off"
                                    data-frames='[{"from":"y:50px;opacity:0;","speed":1500,"to":"o:1;","delay":650,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[0,0,0,0]"
                                    style="z-index: 11; min-width: 600px; max-width: 600px; white-space: normal; font-size: 16px; line-height: 22px; font-weight: 500; color: rgba(255, 255, 255, 0.85);font-family:Roboto;border-width:0px;letter-spacing:2px;">
                                    Industra has met the demands of a growing world for development and
                                    implementation. </div>

                                <!-- LAYER NR. 8 -->
                                <div class="tp-caption rev-btn " id="slide-3205-layer-4"
                                    data-x="['left','left','left','left']" data-hoffset="['50','40','50','26']"
                                    data-y="['top','top','top','top']" data-voffset="['465','411','385','350']"
                                    data-fontweight="['900','700','700','700']" data-width="none" data-height="none"
                                    data-whitespace="nowrap" data-type="button" data-basealign="slide"
                                    data-responsive_offset="off" data-responsive="off"
                                    data-frames='[{"from":"y:50px;opacity:0;","speed":1500,"to":"o:1;","delay":800,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"},{"frame":"hover","speed":"0","ease":"Power1.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0, 0, 0, 1.00);bg:rgba(255, 204, 0, 1.00);bw:2px 2px 2px 2px;"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[45,45,45,45]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[45,45,45,45]"
                                    style="z-index: 12; white-space: nowrap; font-size: 15px; line-height: 50px; font-weight: 900; color: rgba(255, 204, 0, 1.00);font-family:Roboto;background-color:rgba(0, 0, 0, 0);border-color:rgba(255, 204, 0, 1.00);border-style:solid;border-width:2px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;letter-spacing:3px;cursor:pointer;">
                                    REQUEST QUOTE <i style="font-size:13px;margin-left:10px;"
                                        class="fa-icon-chevron-right"></i> </div>
                            </li>
                            <!-- SLIDE  -->
                            <li data-index="rs-3207" data-transition="slideoververtical" data-slotamount="default"
                                data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default"
                                data-easeout="default" data-masterspeed="default"
                                data-thumb="{{ asset('images/youtubecover-1-100x50.jpg') }}" data-rotate="0"
                                data-saveperformance="off" data-title="OUR PASSION MEETS RESULTS"
                                data-param1="We are a company dedicated to giving." data-param2="" data-param3=""
                                data-param4="" data-param5="" data-param6="" data-param7="" data-param8=""
                                data-param9="" data-param10="" data-description="">
                                <!-- MAIN IMAGE -->
                                <img src="{{ asset('images/home-slider-3.jpg') }}" alt=""
                                    data-lazyload="{{ asset('images/home-slider-3.jpg') }}" data-bgposition="center center"
                                    data-bgfit="cover" data-bgparallax="off" class="rev-slidebg" data-no-retina>
                                <!-- LAYERS -->

                                <!-- BACKGROUND VIDEO LAYER -->
                                <div class="rs-background-video-layer" data-forcerewind="on" data-volume="mute"
                                    data-ytid="xDMP3i36naA" data-videoattributes=";" data-videorate="1"
                                    data-videowidth="100%" data-videoheight="100%" data-videocontrols="none"
                                    data-videoloop="loop" data-forceCover="1" data-aspectratio="16:9"
                                    data-autoplay="true" data-autoplayonlyfirsttime="false"></div>
                                <!-- LAYER NR. 17 -->
                                <div class="tp-caption  " id="slide-3207-layer-1"
                                    data-x="['left','left','left','left']" data-hoffset="['94','84','76','26']"
                                    data-y="['top','top','top','top']" data-voffset="['213','193','195','150']"
                                    data-fontsize="['60','45','35','25']" data-lineheight="['60','45','35','30']"
                                    data-width="['600','560','340','260']" data-height="none"
                                    data-whitespace="normal" data-type="text" data-basealign="slide"
                                    data-responsive_offset="off" data-responsive="off"
                                    data-frames='[{"from":"y:50px;opacity:0;","speed":1500,"to":"o:1;","delay":500,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[0,0,0,0]"
                                    style="z-index: 21; min-width: 600px; max-width: 600px; white-space: normal; font-size: 60px; line-height: 60px; font-weight: 700; color: rgba(255, 255, 255, 1.00);font-family:Roboto;border-width:0px;">
                                    OUR PASSION MEETS RESULTS</div>

                                <!-- LAYER NR. 18 -->
                                <div class="tp-caption tp-shape tp-shapewrapper " id="slide-3207-layer-2"
                                    data-x="['left','left','left','left']" data-hoffset="['50','40','50','0']"
                                    data-y="['top','top','top','top']" data-voffset="['220','200','200','155']"
                                    data-width="8" data-height="['104','77','59','48']" data-whitespace="normal"
                                    data-type="shape" data-basealign="slide" data-responsive_offset="off"
                                    data-responsive="off"
                                    data-frames='[{"from":"sY:0;","speed":1500,"to":"o:1;","delay":500,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[0,0,0,0]"
                                    style="z-index: 22;background-color:rgba(255, 204, 0, 1.00);border-color:rgba(0, 0, 0, 0);border-width:0px;">
                                </div>

                                <!-- LAYER NR. 19 -->
                                <div class="tp-caption  " id="slide-3207-layer-3"
                                    data-x="['left','left','left','left']" data-hoffset="['98','88','76','26']"
                                    data-y="['top','top','top','top']" data-voffset="['358','302','284','228']"
                                    data-width="['600','560','340','260']" data-height="none"
                                    data-whitespace="normal" data-type="text" data-basealign="slide"
                                    data-responsive_offset="off" data-responsive="off"
                                    data-frames='[{"from":"y:50px;opacity:0;","speed":1500,"to":"o:1;","delay":650,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[0,0,0,0]"
                                    style="z-index: 23; min-width: 600px; max-width: 600px; white-space: normal; font-size: 16px; line-height: 22px; font-weight: 500; color: rgba(255, 255, 255, 0.85);font-family:Roboto;border-width:0px;letter-spacing:2px;">
                                    We are a company dedicated to giving our customers back the time they deserve to
                                    enjoy the things they love.</div>

                                <!-- LAYER NR. 20 -->
                                <a class="tp-caption rev-btn " href="#" target="_blank" id="slide-3207-layer-4"
                                    data-x="['left','left','left','left']" data-hoffset="['50','40','50','26']"
                                    data-y="['top','top','top','top']" data-voffset="['465','411','385','350']"
                                    data-fontweight="['900','700','700','700']" data-width="none" data-height="none"
                                    data-whitespace="nowrap" data-type="button" data-actions=''
                                    data-basealign="slide" data-responsive_offset="off" data-responsive="off"
                                    data-frames='[{"from":"y:50px;opacity:0;","speed":1500,"to":"o:1;","delay":800,"ease":"Power4.easeOut"},{"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"},{"frame":"hover","speed":"0","ease":"Power1.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0, 0, 0, 1.00);bg:rgba(255, 204, 0, 1.00);bw:2px 2px 2px 2px;"}]'
                                    data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]"
                                    data-paddingright="[45,45,45,45]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[45,45,45,45]"
                                    style="z-index: 24; white-space: nowrap; font-size: 15px; line-height: 50px; font-weight: 900; color: rgba(255, 204, 0, 1.00);font-family:Roboto;background-color:rgba(0, 0, 0, 0);border-color:rgba(255, 204, 0, 1.00);border-style:solid;border-width:2px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;letter-spacing:3px;cursor:pointer;">
                                    REQUEST QUOTE <i style="font-size:13px;margin-left:10px;"
                                        class="fa-icon-chevron-right"></i> </a>
                            </li>
                        </ul>
                        <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
                    </div>
                </div><!-- END REVOLUTION SLIDER -->

            </article>
        </section>
    </div>
    <!--SLIDER END-->
@endif

    </header>

    <!-- CONTENT START -->
    @yield('content')
    <!-- CONTENT END -->
    <!-- FOOTER START -->
    <footer>
        <div class="container mt-5">

            <div class="center-footer">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="footer-col footer-left-col">
                            <figure class="site-footer-logo">
                                <img src="{{ asset('images/logos/footer-logo.png') }}" alt="">
                            </figure>
                            <p>We are dedicated to the production and marketing of mass consumer products. Its business
                                units are the manufacture of chemicals
                                for household hygiene and personal care.</p>
                            <ul class="footer-social">
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="footer-col">
                            <h5>Quick Links</h5>
                            <ul class="quick-links">
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="{{ url('land') }}">Shop</a></li>
                                <li><a href="{{ url('ownership') }}">Projects</a></li>
                                <li><a href="{{ url('contact') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="footer-col">
                            <h5>About</h5>
                            <ul class="quick-links">
                                <li><a href="{{ url('about') }}">History</a></li>
                                <li><a href="{{ url('about') }}">Team</a></li>
                                <li><a href="{{ url('about') }}">Clients</a></li>
                                <li><a href="{{ url('signup') }}">FAQ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="footer-col">
                            <h5>Get in Touch</h5>
                            <ul class="footer-contact">
                                <li class="phone"><a href="tel:123-456-7890">+ 123-456-7890</a></li>
                                <li class="email"><a href="mailto:info@industric.com">info@industric.com</a></li>
                                <li>
                                    <div class="media">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div class="media-body">
                                            <p>837 Castle Hill Ave. Bronx NY 33195.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="footer-line">
            <div class="bottom-footer">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="footer-copyright">
                            <p>© {{ date('Y') }} <a href="#">All Copyright are Reserved</a></p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="footer-terms">
                            <li><a href="{{ url('about') }}">About</a></li>
                            <li><a href="#solution">Solutions</a></li>
                            <li><a href="{{ url('ownership') }}">Projects</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER END -->

    <!--SCROLL TOP START-->
    <a href="#0" class="cd-top">Top</a>
    <!--SCROLL TOP START-->

    <!-- JAVASCRIPTS -->
    <script src="{{ asset('js/lib/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('js/lib/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/lib/plugins.js') }}"></script>
    <script src="{{ asset('js/lib/nav.fixed.top.js') }}"></script>
    <script src="{{ asset('js/lib/contact.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <!-- JAVASCRIPTS END -->

    <!-- REVOLUTION JS FILES -->
    <script src="{{ asset('revolution/js/jquery.themepunch.tools.min.js') }}"></script>
    <script src="{{ asset('revolution/js/jquery.themepunch.revolution.min.js') }}"></script>

    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->
    <script src="{{ asset('revolution/js/extensions/revolution.extension.actions.min.js') }}"></script>
    <script src="{{ asset('revolution/js/extensions/revolution.extension.carousel.min.js') }}"></script>
    <script src="{{ asset('revolution/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
    <script src="{{ asset('revolution/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
    <script src="{{ asset('revolution/js/extensions/revolution.extension.migration.min.js') }}"></script>
    <script src="{{ asset('revolution/js/extensions/revolution.extension.navigation.min.js') }}"></script>
    <script src="{{ asset('revolution/js/extensions/revolution.extension.parallax.min.js') }}"></script>
    <script src="{{ asset('revolution/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
    <script src="{{ asset('revolution/js/extensions/revolution.extension.video.min.js') }}"></script>
    <script src="{{ asset('js/rev-sliders/slider-home-1.js') }}"></script>
    <script src="{{ asset('js/shop.js') }}"></script>
</body>

</html>
