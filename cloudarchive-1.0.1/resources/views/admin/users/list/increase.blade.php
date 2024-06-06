@extends('layouts.app')

@section('page-header')
	<!-- EDIT PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Change Storage Capacity') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.user.dashboard') }}"> {{ __('User Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.user.list') }}">{{ __('User List') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Increase Storage') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<div class="row">
		<div class="col-xl-6 col-lg-6 col-sm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Change User Storage Capacity') }}</h3>
				</div>
				<div class="card-body">
					<form method="POST" action="{{ route('admin.user.increase', [$user->id]) }}" enctype="multipart/form-data">
						@csrf
						
						<div class="row">

							<div class="col-sm-12 col-md-12 mt-2">
								<div>
									<p class="fs-12 font-weight-800 mb-2">{{ __('Full Name') }}: <span class="font-weight-normal ml-2">{{ $user->name }}</span></p>
									<p class="fs-12 font-weight-800 mb-2">{{ __('Email Address') }}: <span class="font-weight-normal ml-2">{{ $user->email }}</span></p>
									<p class="fs-12 font-weight-800 mb-2">{{ __('User Type') }}: <span class="font-weight-normal ml-2">{{ ucfirst($user->group) }}</span></p>
									<p class="fs-12 font-weight-800">{{ __('Current Maximum Storage Limit') }}: <span class="font-weight-normal ml-2">{{ number_format($user->storage_total) }}MB</span></p>
								</div>
							</div>

							<div class="col-sm-12 col-md-12 mt-3">
								<div class="input-box">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-hard-drive mr-2 text-info"></i>{{ __('Type storage capacity in MB') }}</label>
										<input type="number" class="form-control @error('storage') is-danger @enderror" name="storage">
										<span class="text-muted fs-10">{{ __('Change user storage capacity by input above. You have to type only number e.g. value 1000 means, user will have 1GB of storage capacity.') }}</span>
										@error('storage')
											<p class="text-danger">{{ $errors->first('storage') }}</p>
										@enderror									
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer border-0 text-right pb-0 pr-0">							
							<a href="{{ route('admin.user.list') }}" class="btn btn-cancel mr-2">{{ __('Return') }}</a>
							<button type="submit" class="btn btn-primary">{{ __('Change Capacity') }}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
