@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('View Plan') }}</h4>
			<ol class="breadcrumb mb-2">
				<ol class="breadcrumb mb-2">
					<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-sack-dollar mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.dashboard') }}"> {{ __('Finance Management') }}</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.plans') }}"> {{ __('Plans') }}</a></li>
					<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Plan') }}</a></li>
				</ol>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')						
	<div class="row">
		<div class="col-lg-6 col-md-6 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Plan Name') }}: <span class="text-info">{{ $id->plan_name }}</span> </h3>
				</div>
				<div class="card-body pt-5">		

					<div class="row">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Plan Name') }}: </h6>
							<span class="fs-14">{{ ucfirst($id->plan_name) }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Created Date') }}: </h6>
							<span class="fs-14">{{ date_format($id->created_at, 'd M Y') }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Status') }}: </h6>
							<span class="fs-14">{{ ucfirst($id->status) }}</span>
						</div>
					</div>

					<div class="row pt-5">						
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Price') }}: </h6>
							<span class="fs-14">{{ $id->price }} {{ $id->currency }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Payment Frequence') }}: </h6>
							<span class="fs-14">{{ ucfirst($id->payment_frequency) }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Storage Capacity') }}: </h6>
							<span class="fs-14">{{ $id->storage_total }} MB</span>
						</div>
					</div>

					<div class="row pt-5">						
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Expedited Retrieval') }}: </h6>
							<span class="fs-14">@if($id->expedited_request) {{ __('Included') }} @else {{ __('Not Included') }} @endif</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Standard Retrieval') }}: </h6>
							<span class="fs-14">@if($id->standard_request) {{ __('Included') }} @else {{ __('Not Included') }} @endif</span>
						</div>
					</div>
					
					<div class="row pt-8">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('PayPal Plan ID') }}: </h6>
							<span class="fs-14">{{ $id->paypal_gateway_plan_id }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Stripe Plan ID') }}: </h6>
							<span class="fs-14">{{ $id->stripe_gateway_plan_id }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Paystack Plan ID') }}: </h6>
							<span class="fs-14">{{ $id->paystack_gateway_plan_id }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12 pt-5">
							<h6 class="font-weight-bold mb-1">{{ __('Razorpay Plan ID') }}: </h6>
							<span class="fs-14">{{ $id->razorpay_gateway_plan_id }}</span>
						</div>
					</div>
					
					<div class="row pt-8">
						<div class="col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Primary Heading') }}: </h6>
							<span class="fs-14">{{ ucfirst($id->primary_heading) }}</span>
						</div>
					</div>

					<div class="row pt-5 pb-8">
						<div class="col-12">
							<h6 class="font-weight-bold mb-1">{{ ('Plan Features') }} <span class="text-muted">({{ __('Comma seperated') }})</span>: </h6>
							<span class="fs-14">{{ ucfirst($id->plan_features) }}</span>
						</div>
					</div>

					<!-- SAVE CHANGES ACTION BUTTON -->
					<div class="border-0 text-right mb-2 mt-7">
						<a href="{{ route('admin.finance.plans') }}" class="btn btn-cancel mr-2">{{ __('Cancel') }}</a>
						<a href="{{ route('admin.finance.plan.edit', $id) }}" class="btn btn-primary">{{ __('Edit Plan') }}</a>						
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
