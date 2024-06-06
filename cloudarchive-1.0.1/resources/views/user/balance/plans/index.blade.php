@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Subscription Plans') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="fa-solid fa-badge-dollar mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.plans') }}"> {{ __('My Balance') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Subscription Plans') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')	
	<div class="card border-0 pt-2">
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
										
										<h6 class="font-weight-normal fs-12 text-center mb-6">{{ __('Select your Monthly Subscription Plan') }}</h6>

										<div class="row justify-content-md-center">

											@foreach ( $monthly_plans as $plan )																			
												<div class="col-lg-3 col-md-6 col-sm-12">
													<div class="pl-6 pr-6 pt-2 mb-7 prices-responsive">
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
																	@if (auth()->user()->plan_id == $plan->id)
																		<button type="button" class="btn btn-cancel">{{ __('Subscribed') }}</button> 
																	@else
																		<a href="{{ route('user.plan.subscribe', $plan->id) }}" class="btn btn-primary">{{ __('Subscribe Now') }}</a>
																	@endif															
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
										
										<h6 class="font-weight-normal fs-12 text-center mb-6">{{ __('Select your Yearly Subscription Plan') }}</h6>

										<div class="row justify-content-md-center">

											@foreach ( $yearly_plans as $plan )																			
												<div class="col-lg-3 col-md-6 col-sm-12">
													<div class="pl-6 pr-6 pt-2 mb-7 prices-responsive">
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
																	@if (auth()->user()->plan_id == $plan->id)
																		<button type="button" class="btn btn-cancel">{{ __('Subscribed') }}</button> 
																	@else
																	<a href="{{ route('user.plan.subscribe', $plan->id) }}" class="btn btn-primary">{{ __('Subscribe Now') }}</a>
																	@endif															
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
@endsection


