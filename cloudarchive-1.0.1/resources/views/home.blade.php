@extends('layouts.guest')

@section('css')
    <link href="{{URL::asset('plugins/slick/slick.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/slick/slick-theme.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('plugins/aos/aos.css')}}" rel="stylesheet" />
@endsection

@section('content')

        <section id="main-wrapper">
            
            <div class="h-100vh justify-center min-h-screen" id="main-background">

                <div class="container-fluid" >

                    <div class="central-banner">
                        <div class="row text-center">
                            <div class="col-md-6 col-sm-12 pt-9 pl-9" data-aos="fade-left" data-aos-delay="300" data-aos-once="true" data-aos-duration="700">
                                <div class="text-container">
                                    <h1><span>{{ __('Cloud') }}</span> {{ __('Data Backup') }}</h1>
                                    <p class="fs-20">{{ __('Long-term, secure, durable storage solution for data archiving at the lowest cost.') }} <br> {{ __('Unmatched durability and scalability') }}</p>

                                    <a href="{{ route('register') }}" class="btn btn-primary special-action-button">{{ __('Start a Free Trial') }}</a>

                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12" data-aos="fade-right" data-aos-delay="300" data-aos-once="true" data-aos-duration="700">
                                <div class="image-container ">
                                    <img id="special-image-margin" src="{{ URL::asset('img/files/main-banner.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>

            </div>  
        </section>



        <!-- SECTION - FEATURES
        ========================================================-->
        @if (config('frontend.features_section') == 'on')
            <section id="features-wrapper">

                

                <div class="container">

                    <div class="row text-center mt-8 mb-8">
                        <div class="col-md-12 title">
                            <h6><span>{{ __('Data Archiving') }}</span> {{ __('Benefits') }}</h6>
                            <p>{{ __('Enjoy the full flexibility of the platform with ton of features') }}</p>
                        </div>
                    </div>
        
                        
                    <!-- LIST OF SOLUTIONS -->
                    <div class="row d-flex" id="solutions-list">
                        
                        <div class="col-md-4 col-sm-12">
                            <!-- SOLUTION -->
                            <div class="col-sm-12 mb-6">
                                    
                                
                                <div class="solution" data-aos="zoom-in" data-aos-delay="1000" data-aos-once="true" data-aos-duration="1000">                                                                          
                                    
                                    <div class="solution-content">
                                        
                                        <div class="solution-logo mb-3">
                                            <img src="{{ URL::asset('img/files/01.png') }}" alt="">
                                        </div>
                                    
                                        <h5>{{ __('Glacier & Deep Archive Tiers') }}</h5>
                                        
                                        <p>Lorem ipsum dolor sit amet est consectetur adipisicing elit. Ut aspernatur mollitia aliquid consectetur illo sapiente nemo obcaecati unde.</p>

                                    </div>                         

                                </div>

                            </div> <!-- END SOLUTION -->
                            
                            <!-- SOLUTION -->
                            <div class="col-sm-12 mb-6">
                                    
                                <div class="solution" data-aos="zoom-in" data-aos-delay="1500" data-aos-once="true" data-aos-duration="1500">
                                    
                                    <div class="solution-content">
                                        
                                        <div class="solution-logo mb-3">
                                            <img src="{{ URL::asset('img/files/09.png') }}" alt="">
                                        </div>
                                    
                                        <h5>{{ __('Lowest Cost for Storage') }}</h5>
                                        
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut aspernatur mollitia aliquid consectetur illo sapiente nemo obcaecati unde.</p>

                                    </div>

                                </div>

                            </div> <!-- END SOLUTION -->

                            <!-- SOLUTION -->
                            <div class="col-sm-12 mb-6">
                                    
                                <div class="solution" data-aos="zoom-in" data-aos-delay="2000" data-aos-once="true" data-aos-duration="2000">
                                    
                                    <div class="solution-content">
                                        
                                        <div class="solution-logo mb-3">
                                            <img src="{{ URL::asset('img/files/06.png') }}" alt="">
                                        </div>
                                    
                                        <h5>{{ __('Most Comprehensive Security') }}</h5>
                                        
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut aspernatur mollitia aliquid consectetur illo sapiente nemo obcaecati unde.</p>

                                    </div>

                                </div>

                            </div> <!-- END SOLUTION -->
                        </div>

                        <div class="col-md-4 col-sm-12 mt-7">
                            <!-- SOLUTION -->
                            <div class="col-sm-12 mb-6">
                                    
                                <div class="solution" data-aos="zoom-in" data-aos-delay="1000" data-aos-once="true" data-aos-duration="1000">
                                    
                                    <div class="solution-content">
                                        
                                        <div class="solution-logo mb-3">
                                            <img src="{{ URL::asset('img/files/05.png') }}" alt="">
                                        </div>
                                    
                                        <h5>{{ __('Instant Retrieval with Glacier Tier') }}</h5>
                                        
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut aspernatur mollitia aliquid consectetur illo sapiente nemo obcaecati unde.</p>

                                    </div>

                                </div>

                            </div> <!-- END SOLUTION -->


                            <!-- SOLUTION -->
                            <div class="col-sm-12 mb-6">
                                    
                                <div class="solution" data-aos="zoom-in" data-aos-delay="1500" data-aos-once="true" data-aos-duration="1500">
                                    
                                    <div class="solution-content">
                                        
                                        <div class="solution-logo mb-3">
                                            <img src="{{ URL::asset('img/files/03.png') }}" alt="">
                                        </div>
                                    
                                        <h5>{{ __('Long-term Backup Retention') }}</h5>
                                        
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut aspernatur mollitia aliquid consectetur illo sapiente nemo obcaecati unde.</p>

                                    </div>                                

                                </div>

                            </div> <!-- END SOLUTION -->


                            <!-- SOLUTION -->
                            <div class="col-sm-12 mb-6">
                                    
                                <div class="solution" data-aos="zoom-in" data-aos-delay="2000" data-aos-once="true" data-aos-duration="2000">
                                    
                                    <div class="solution-content">
                                        
                                        <div class="solution-logo mb-3">
                                            <img src="{{ URL::asset('img/files/04.png') }}" alt="">
                                        </div>
                                    
                                        <h5>{{ __('High Availability with Data Replication') }}</h5>
                                        
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ut aspernatur mollitia aliquid consectetur illo sapiente nemo obcaecati unde.</p>

                                    </div>

                                </div>

                            </div> <!-- END SOLUTION -->
                        </div>

                        <div class="col-md-4 col-sm-12 d-flex">

                            <div class="feature-text">
                                <div>
                                    <h4><span class="text-primary">{{ config('app.name') }}</span>{{ __(' Provides the most durable solution in the world') }}</h4>
                                </div>
                                
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi, quibusdam? Illum ad eius, molestiae placeat dicta quae, ab nihil omnis obcaecati reiciendis recusandae, voluptatem eos molestias aliquam saepe tenetur optio? Consectetur adipisicing elit. Ut aspernatur mollitia aliquid consectetur illo sapiente nemo obcaecati.</p>
                                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Unde ea et, error quisquam corporis, architecto minus doloremque aut facere itaque culpa eos molestias nulla reiciendis animi dolores, quod sunt illum.</p>
                            </div>
                            
                        </div>
                        
                    </div> <!-- END LIST OF SOLUTIONS -->
         

                </div>

            </section>
        @endif


        <!-- SECTION - CUSTOMER FEEDBACKS
        ========================================================-->
        @if (config('frontend.reviews_section') == 'on')
            <section id="feedbacks-wrapper">

                <div class="container pt-4 text-center">


                    <!-- SECTION TITLE -->
                    <div class="row mb-8">

                        <div class="title">
                            <h6>{{ __('Customer') }} <span>{{ __('Reviews') }}</span></h6>
                            <p>{{ __('We guarantee that you will be one of our happy customers as well') }}</p>
                        </div>

                    </div> <!-- END SECTION TITLE -->

                    @if ($review_exists)

                        <div class="row" id="feedbacks">
                            
                            @foreach ($reviews as $review)
                                <div class="feedback" data-aos="flip-down" data-aos-delay="500" data-aos-once="true" data-aos-duration="1000">					
                                    <!-- MAIN COMMENT -->
                                    <p class="comment"><sup><span class="fa fa-quote-left"></span></sup> {{ $review->text }} <sub><span class="fa fa-quote-right"></span></sub></p>

                                    <!-- COMMENTER -->
                                    <div class="feedback-image d-flex">
                                        <div>
                                            <img src="{{ URL::asset($review->image_url) }}" alt="Feedback" class="rounded-circle"><span class="small-quote fa fa-quote-left"></span>
                                        </div>

                                        <div class="pt-3">
                                            <p class="feedback-reviewer">{{ $review->name }}</p>
                                            <p class="fs-12">{{ $review->position }}</p>
                                        </div>
                                    </div>	
                                </div> 
                            @endforeach                                                       
                        </div>

                        <!-- ROTATORS BUTTONS -->
                        <div class="offers-nav">
                            <a class="offers-prev"><i class="fa fa-chevron-left"></i></a>
                            <a class="offers-next"><i class="fa fa-chevron-right"></i></a>                                
                        </div>

                    @else
                        <div class="row text-center">
                            <div class="col-sm-12 mt-6 mb-6">
                                <h6 class="fs-12 font-weight-bold text-center">{{ __('No customer reviews were published yet') }}</h6>
                            </div>
                        </div>
                    @endif

                    
                    
                </div> <!-- END CONTAINER -->
                
            </section> <!-- END SECTION CUSTOMER FEEDBACK -->
        @endif
        
        
         <!-- SECTION - BANNER
        ========================================================-->
        <section id="banner-wrapper">

            <div class="container">

                <!-- SECTION TITLE -->
                <div class="row mb-7 text-center">

                    <div class="title">
                        <h6>{{ __('Our') }} <span>{{ __('Partners') }}</span></h6>
                        <p class="mb-0">{{ __('Be among the many that trust us') }}</p>
                    </div>

                </div> <!-- END SECTION TITLE -->

                <div class="row" id="partners">
                            
                    <div class="partner" data-aos="flip-down" data-aos-delay="500" data-aos-once="true" data-aos-duration="1000">					
                        <div class="partner-image d-flex">
                            <div>
                                <img src="{{ URL::asset('img/files/c1.png') }}" alt="partner">
                            </div>
                        </div>	
                    </div>    
                    
                    <div class="partner" data-aos="flip-down" data-aos-delay="500" data-aos-once="true" data-aos-duration="1000">					
                        <div class="partner-image d-flex">
                            <div>
                                <img src="{{ URL::asset('img/files/c2.png') }}" alt="partner">
                            </div>
                        </div>	
                    </div> 

                    <div class="partner" data-aos="flip-down" data-aos-delay="500" data-aos-once="true" data-aos-duration="1000">					
                        <div class="partner-image d-flex">
                            <div>
                                <img src="{{ URL::asset('img/files/c7.png') }}" alt="partner">
                            </div>
                        </div>	
                    </div> 

                    <div class="partner" data-aos="flip-down" data-aos-delay="500" data-aos-once="true" data-aos-duration="1000">					
                        <div class="partner-image d-flex">
                            <div>
                                <img src="{{ URL::asset('img/files/c5.png') }}" alt="partner">
                            </div>
                        </div>	
                    </div> 

                    <div class="partner" data-aos="flip-down" data-aos-delay="500" data-aos-once="true" data-aos-duration="1000">					
                        <div class="partner-image d-flex">
                            <div>
                                <img src="{{ URL::asset('img/files/c6.png') }}" alt="partner">
                            </div>
                        </div>	
                    </div> 

                    <div class="partner" data-aos="flip-down" data-aos-delay="500" data-aos-once="true" data-aos-duration="1000">					
                        <div class="partner-image d-flex">
                            <div>
                                <img src="{{ URL::asset('img/files/c7.png') }}" alt="partner">
                            </div>
                        </div>	
                    </div> 

                    <div class="partner" data-aos="flip-down" data-aos-delay="500" data-aos-once="true" data-aos-duration="1000">					
                        <div class="partner-image d-flex">
                            <div>
                                <img src="{{ URL::asset('img/files/c2.png') }}" alt="partner">
                            </div>
                        </div>	
                    </div> 
                </div>
            </div>

        </section> <!-- END SECTION BANNER -->


        <!-- SECTION - PRICING
        ========================================================-->
        @if (config('frontend.pricing_section') == 'on')
            <section id="prices-wrapper">

                <div class="container pt-9">  
                    
                    <!-- SECTION TITLE -->
                    <div class="row text-center">

                        <div class="title">
                            <h6><span>{{ __('Subscription') }}</span> {{ __('Plans') }}</h6>
                            <p>{{ __('Most competitive prices are guaranteed') }}</p>
                        </div>

                    </div> <!-- END SECTION TITLE -->
                    
                    <div class="row">
                    <div class="card-body">			
                            
                        @if ($plan_monthly || $plan_yearly)

                            <div class="tab-menu-heading text-center">
                                <div class="tabs-menu">								
                                    <ul class="nav">
                                        @if ($plan_monthly)								
                                            <li><a href="#plans_monthly" class="@if (($plan_monthly && $plan_yearly) || ($plan_monthly && !$plan_yearly)) active @else '' @endif" data-bs-toggle="tab"> {{ __('Monthly Plans') }}</a></li>								
                                        @endif	
                                        @if ($plan_yearly)								
                                            <li><a href="#plans_yearly" class="@if (!$plan_monthly && $plan_yearly) active @else '' @endif" data-bs-toggle="tab"> {{ __('Yearly Plans') }}</a></li>								
                                        @endif												
                                    </ul>
                                </div>
                            </div>

                        
                            <div class="tabs-menu-body">
                                <div class="tab-content">

                                    @if ($plan_monthly)	
                                        <div class="tab-pane @if (($plan_monthly && $plan_yearly) || ($plan_monthly && !$plan_yearly)) active @else '' @endif" role="tabpanel" id="plans_monthly">

                                            @if ($monthly_plans->count())		

                                                <div class="row justify-content-md-center">

                                                    @foreach ( $monthly_plans as $plan )																			
                                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                                            <div class="pt-2 mb-7">
                                                                <div class="card border-0 p-4 pl-5 pr-5 pt-7 price-card @if ($plan->featured) price-card-border @endif">
                                                                    @if ($plan->featured)
                                                                        <span class="plan-featured">{{ __('Most Popular') }}</span>
                                                                    @endif
                                                                    <div class="plan">			
                                                                        <div class="plan-title text-center">{{ $plan->plan_name }}</div>		
                                                                        <p class="fs-12 text-center mb-3">{{ $plan->primary_heading }}</p>																					
                                                                        <p class="plan-cost text-center mb-0"><span class="plan-currency-sign"></span>{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$plan->price, 2) }}</p>
                                                                        <p class="fs-12 text-center mb-3">{{ $plan->currency }} / {{ __('Month') }}</p>
                                                                        <div class="text-center action-button mt-2 mb-5">
                                                                            <a href="{{ route('user.plan.subscribe', $plan->id) }}" class="btn btn-primary">{{ __('Subscribe Now') }}</a>                                                                            														
                                                                        </div>																
                                                                        <ul class="fs-12 pl-3">														
                                                                            @foreach ( (explode(',', $plan->plan_features)) as $feature )
                                                                                @if ($feature)
                                                                                    <li><i class="fa-solid fa-circle-small fs-10 text-muted"></i> {{ $feature }}</li>
                                                                                @endif																
                                                                            @endforeach															
                                                                        </ul>																
                                                                    </div>					
                                                                </div>	
                                                            </div>							
                                                        </div>										
                                                    @endforeach

                                                </div>	
                                            
                                            @else
                                                <div class="row text-center">
                                                    <div class="col-sm-12 mt-6 mb-6">
                                                        <h6 class="fs-12 font-weight-bold text-center">{{ __('No Monthly Subscriptions plans were set yet') }}</h6>
                                                    </div>
                                                </div>
                                            @endif					
                                        </div>	
                                    @endif

                                    @if ($plan_yearly)	
                                        <div class="tab-pane @if (!$plan_monthly && $plan_yearly) active @else '' @endif" role="tabpanel" id="plans_yearly">

                                            @if ($yearly_plans->count())	

                                                <div class="row justify-content-md-center">

                                                    @foreach ( $yearly_plans as $plan )																			
                                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                                            <div class="pt-2 mb-7">
                                                                <div class="card border-0 p-4 pl-5 pr-5 pt-7 price-card @if ($plan->featured) price-card-border @endif">
                                                                    @if ($plan->featured)
                                                                        <span class="plan-featured">{{ __('Most Popular') }}</span>
                                                                    @endif
                                                                    <div class="plan">			
                                                                        <div class="plan-title text-center">{{ $plan->plan_name }}</div>		
                                                                        <p class="fs-12 text-center mb-3">{{ $plan->primary_heading }}</p>																					
                                                                        <p class="plan-cost text-center mb-0"><span class="plan-currency-sign"></span>{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$plan->price, 2) }}</p>
                                                                        <p class="fs-12 text-center mb-3">{{ $plan->currency }} / {{ __('Year') }}</p>
                                                                        <div class="text-center action-button mt-2 mb-5">
                                                                            <a href="{{ route('user.plan.subscribe', $plan->id) }}" class="btn btn-primary">{{ __('Subscribe Now') }}</a>													
                                                                        </div>																
                                                                        <ul class="fs-12 pl-3">														
                                                                            @foreach ( (explode(',', $plan->plan_features)) as $feature )
                                                                                @if ($feature)
                                                                                    <li><i class="fa-solid fa-circle-small fs-10 text-muted"></i> {{ $feature }}</li>
                                                                                @endif																
                                                                            @endforeach															
                                                                        </ul>																
                                                                    </div>					
                                                                </div>	
                                                            </div>							
                                                        </div>										
                                                    @endforeach

                                                </div>	
                                            
                                            @else
                                                <div class="row text-center">
                                                    <div class="col-sm-12 mt-6 mb-6">
                                                        <h6 class="fs-12 font-weight-bold text-center">{{ __('No Yearly Subscriptions plans were set yet') }}</h6>
                                                    </div>
                                                </div>
                                            @endif					
                                        </div>	
                                    @endif

                                </div>
                            </div>
                        
                        @else
                            <div class="row text-center">
                                <div class="col-sm-12 mt-6 mb-6">
                                    <h6 class="fs-12 font-weight-bold text-center">{{ __('No Subscriptions Plans were set yet') }}</h6>
                                </div>
                            </div>
                        @endif
            
                    </div>
                </div>
                
                </div>
        
            </section>
        @endif


        <!-- SECTION - BLOGS
        ========================================================-->
        @if (config('frontend.blogs_section') == 'on')
            <section id="blog-wrapper">

                <div class="container pt-4 text-center">


                    <!-- SECTION TITLE -->
                    <div class="row mb-8">

                        <div class="title w-100">
                            <h6><span>{{ __('Latest') }}</span> {{ __('Blogs') }}</h6>
                            <p>{{ __('Read our unique blog articles about various data archiving solutions and secrets') }}</p>
                        </div>

                    </div> <!-- END SECTION TITLE -->

                    @if ($blog_exists)
                        
                        <!-- BLOGS -->
                        <div class="row" id="blogs">
                            @foreach ( $blogs as $blog )
                            <div class="blog" data-aos="zoom-in" data-aos-delay="500" data-aos-once="true" data-aos-duration="1000">			
                                <div class="blog-box">
                                    <div class="blog-img">
                                        <a href="{{ route('blogs.show', $blog->url) }}"><img src="{{ URL::asset($blog->image) }}" alt="Blog Image"></a>
                                    </div>
                                    <div class="blog-info">
                                        <h5 class="blog-title text-left">{{ $blog->title }}</h5>
                                        <h6 class="blog-date text-left"><i class="mdi mdi-alarm mr-2"></i>{{ date('F j, Y', strtotime($blog->created_at)) }}</h6>
                                    </div>
                                </div>                        
                            </div> 
                            @endforeach
                        </div> 
                        

                        <!-- ROTATORS BUTTONS -->
                        <div class="blogs-nav">
                            <a class="blogs-prev"><i class="fa fa-chevron-left"></i></a>
                            <a class="blogs-next"><i class="fa fa-chevron-right"></i></a>                                
                        </div>

                    @else
                        <div class="row text-center">
                            <div class="col-sm-12 mt-6 mb-6">
                                <h6 class="fs-12 font-weight-bold text-center">{{ __('No blog articles were published yet') }}</h6>
                            </div>
                        </div>
                    @endif

                </div> <!-- END CONTAINER -->
                
            </section> <!-- END SECTION BLOGS -->
        @endif


        <!-- SECTION - FAQ
        ========================================================-->
        @if (config('frontend.faq_section') == 'on')
            <section id="faq-wrapper">    
                <div class="container pt-7">

                    <div class="row text-center mb-8 mt-7">
                        <div class="col-md-12 title">
                            <h6>{{ __('Frequently Asked') }} <span>{{ __('Questions') }}</span></h6>
                            <p>{{ __('Got questions? We have you covered.') }}</p>
                        </div>
                    </div>

                    <div class="row justify-content-md-center">
        
                        @if ($faq_exists)

                            <div class="col-md-10">
        
                                @foreach ( $faqs as $faq )

                                    <div id="accordion" data-aos="fade-left" data-aos-delay="300" data-aos-once="true" data-aos-duration="700">
                                        <div class="card">
                                            <div class="card-header" id="heading{{ $faq->id }}">
                                                <h5 class="mb-0">
                                                <span class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $faq->id }}" aria-expanded="false" aria-controls="collapse-{{ $faq->id }}">
                                                    {{ $faq->question }}
                                                </span>
                                                </h5>
                                            </div>
                                        
                                            <div id="collapse-{{ $faq->id }}" class="collapse" aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#accordion">
                                                <div class="card-body">
                                                    {!! $faq->answer !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                    
        
                        @else
                            <div class="row text-center">
                                <div class="col-sm-12 mt-6 mb-6">
                                    <h6 class="fs-12 font-weight-bold text-center">{{ __('No FAQ answers were published yet') }}</h6>
                                </div>
                            </div>
                        @endif
            
                    </div>        
                </div>
        
            </section> <!-- END SECTION FAQ -->
        @endif

        
        <!-- SECTION - CONTACT US
        ========================================================-->
        @if (config('frontend.contact_section') == 'on')
            <section id="contact-wrapper">

                <div class="container pt-9">       
                    
                    <!-- SECTION TITLE -->
                    <div class="row mb-8 text-center">

                        <div class="title w-100">
                            <h6><span>{{ __('Contact') }}</span> {{ __('With Us') }}</h6>
                            <p>{{ __('Reach out to us for additional information') }}</p>
                        </div>

                    </div> <!-- END SECTION TITLE -->

                    
                    <div class="row">                
                        
                        <div class="col-md-6 col-sm-12" data-aos="fade-left" data-aos-delay="300" data-aos-once="true" data-aos-duration="700">
                            <img class="" src="{{ URL::asset('img/files/contact.png') }}" alt="">
                        </div>

                        <div class="col-md-6 col-sm-12" data-aos="fade-right" data-aos-delay="300" data-aos-once="true" data-aos-duration="700">
                            <form id="" action="{{ route('contact') }}" method="POST" enctype="multipart/form-data">
                                @csrf
        
                                <div class="row justify-content-md-center">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="input-box mb-4">                             
                                            <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="off" placeholder="First Name" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror                            
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="input-box mb-4">                             
                                            <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" autocomplete="off" placeholder="Last Name" required>
                                            @error('lastname')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror                            
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row justify-content-md-center">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="input-box mb-4">                             
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="off"  placeholder="Email Address" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror                            
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="input-box mb-4">                             
                                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="off"  placeholder="Phone Number" required>
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror                            
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row justify-content-md-center">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="input-box">							
                                            <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="10" required placeholder="Message"></textarea>
                                            @error('message')
                                                <p class="text-danger">{{ $errors->first('message') }}</p>
                                            @enderror	
                                        </div>
                                    </div>
                                </div>
        
                                <input type="hidden" name="recaptcha" id="recaptcha">
                                
                                <div class="row justify-content-md-center text-center">
                                    <!-- ACTION BUTTON -->
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary special-action-button">{{ __('Send Message') }}</button>							
                                    </div>
                                </div>
                            
                            </form>
        
                        </div>                   
                        
                    </div>
                
                </div>
        
            </section>
        @endif

@endsection

@section('js')
    <script src="{{URL::asset('plugins/slick/slick.min.js')}}"></script>  
    <script src="{{URL::asset('plugins/aos/aos.js')}}"></script> 
    <script src="{{URL::asset('js/frontend.js')}}"></script>   
    <script type="text/javascript">
		$(function () {

            AOS.init();

		});    
    </script>

    @if (config('services.google.recaptcha.enable') == 'on')
         <!-- Google reCaptcha JS -->
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.google.recaptcha.site_key') }}"></script>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.google.recaptcha.site_key') }}', {action: 'contact'}).then(function(token) {
                    if (token) {
                    document.getElementById('recaptcha').value = token;
                    }
                });
            });
        </script>
    @endif
@endsection
        
        
       
        
       
    

