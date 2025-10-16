@extends('master')

@section('title')
    {{ __('FAQ') }}
@endsection

@section('content')
<div class="pages-hero">
    <div class="pages-hero-diagonal"></div>
    <div class="container">
        <div class="pages-title">
            <h1>FAQ</h1>
            <p>Frequently Asked Questions</p>
        </div>
    </div>
</div>
<section>
    <!-- FAQ START -->
    <div class="container mt-5 mb-5">
        <div class="section-title">
            <h2>Frequently Asked Questions</h2>
            <p>Industra is a global community of practice that facilitates dialogue, information exchange and use of
                information.</p>
        </div>
        <div class="accordion-layer">
            <ul class="accordion">
                <li>
                    <a class="active">1) How can I contact you?</a>
                    <p style="display: block;">The vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium
                        voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi.</p>
                </li>
                <li>
                    <a>2) What is email address?</a>
                    <p>We denounce with righteous indignation and dislike men who are so beguiled and demoralized by
                        the charms of pleasure of the moment, so blinded by desire.</p>
                </li>
                <li>
                    <a>3) What are gas solutions?</a>
                    <p> when our power of choice is untrammelled and when nothing prevents our being able to do what
                        we like best, every pleasure is to be welcomed and every pain avoided. But in certain
                        circumstances and owing to the claims of duty or the obligations.</p>
                </li>
                <li>
                    <a>4) What is the best solution?</a>
                    <p>The vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium
                        voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi.</p>
                </li>
                <li>
                    <a>5) What is the best material?</a>
                    <p>We denounce with righteous indignation and dislike men who are so beguiled and demoralized by
                        the charms of pleasure of the moment, so blinded by desire.</p>
                </li>
                <li class="last-item">
                    <a>6) What are oil solutions?</a>
                    <p> when our power of choice is untrammelled and when nothing prevents our being able to do what
                        we like best, every pleasure is to be welcomed and every pain avoided. But in certain
                        circumstances and owing to the claims of duty or the obligations.</p>
                </li>
            </ul> <!-- / accordion -->
        </div>
    </div>
    <!-- FAQ END -->

    <!-- CONTACT BOX START -->
    <div class="bg-wrapper mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="faq-box">
                        <figure class="fb-icon">
                            <img src="{{ asset('images/icons/24-hours-phone-service.png') }}" alt="">
                        </figure>
                        <h4>Have a question? <br> <span>Contact us now</span> </h4>
                        <p><a href="tel:123-456-7890"> 123-456-7890</a></p>
                        <p><a href="tel:718-932-90500"> 718-932-9050</a></p>
                        <h6><a href="{{ url('contact') }}">Contact Us</a></h6>
                    </div>
                </div>
                <div class="col-lg-4 spacing-m-center">
                    <div class="faq-box">
                        <figure class="fb-icon">
                            <img src="{{ asset('images/icons/email.png') }}" alt="">
                        </figure>
                        <h4>Need help? <br> <span>Send us an email</span> </h4>
                        <p><a href="mailto:info@industric.com">info@industric.com</a></p>
                        <p><a href="mailto:support@industra.com">support@industra.com</a></p>
                        <h6><a href="{{ url('contact') }}">Email us</a></h6>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="faq-box">
                        <figure class="fb-icon">
                            <img src="{{ asset('images/icons/circular-clock.png') }}" alt="">
                        </figure>
                        <h4>Working Hours <br> <span>Visit us</span> </h4>
                        <p>Monday - Friday 8:30 to 18:00</p>
                        <p>Saturday - 9:00 to 14:00</p>
                        <h6><a href="#">VISIT OUR OFFICE</a></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTACT BOX END -->

</section>
@endsection
