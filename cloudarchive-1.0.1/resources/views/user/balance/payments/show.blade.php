@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Show Payment') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-badge-dollar mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.plans') }}"> {{ __('My Balance') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.balance.payments') }}"> {{ __('My Payments') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Show Payment') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')						
	<div class="row">
		<div class="col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Payment') }} ID: <span class="text-info">{{ $id->order_id }}</span></h3>
				</div>
				<div class="card-body pt-5">		

					<div class="row">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Transaction Date') }}: </h6>
							<span class="fs-14">{{ date_format($id->created_at, 'd M Y, H:i:s') }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Total') }}: </h6>
							<span class="fs-14">{!! config('payment.default_system_currency_symbol') !!}{{ ucfirst($id->price) }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Status') }}: </h6>
							<span class="fs-14">{{ ucfirst($id->status) }}</span>
						</div>
					</div>

					<div class="row pt-5">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Plan Name') }}: </h6>
							<span class="fs-14">{{ ucfirst($id->plan_name) }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Storage Size') }}: </h6>
							<span class="fs-14">{{ number_format($id->storage_size) }}MB</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Payment Gateway') }}: </h6>
							<span class="fs-14">{{ $id->gateway }}</span>
						</div>
					</div>	

					<div class="row pt-7">
						<div class="col-12">
							<a href="{{ route('user.payments.invoice.show', $id) }}" class="btn btn-primary w-110">{{ __('Get Invoice') }}</a>						
						</div>
					</div>	

					<!-- SAVE CHANGES ACTION BUTTON -->
					<div class="border-0 text-right mb-2 mt-7">
						<a href="{{ route('user.balance.payments') }}" class="btn btn-primary">{{ __('Return') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

