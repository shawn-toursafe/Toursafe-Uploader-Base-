@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Archive Details') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-boxes-packing mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.archive.dashboard') }}"> {{ __('Archive Management') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Archive Details') }}</a></li>
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
					<h3 class="card-title">{{ __('View Archive Details') }}</h3>
				</div>
				<div class="card-body pt-5">

					<div class="row">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Plan Type') }}: </h6>
							<span class="fs-14">{{ ucfirst($id->subscription) }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('User Name') }}:</h6>
							<span class="fs-14">{{ $user->name }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('User Email') }}: </h6>
							<span class="fs-14">{{ $user->email }}</span>
						</div>
					</div>
		

					<div class="row pt-7">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('File Name') }}: </h6>
							<span class="fs-14">{{ $id->file_name }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Format') }}: </h6>
							<span class="fs-14">{{ strtoupper($id->file_ext) }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Archive Type') }}: </h6>
							<span class="fs-14">@if ($id->archive_type == 'glacier') {{ __('Glacier') }} @else {{ __('Deep Archive') }} @endif</span>
						</div>
					</div>

					<div class="row pt-7">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Ready To Download') }}: </h6>
							<span class="fs-14">@if ($id->downloadable) {{ __('Ready') }} @else {{ __('Not Ready') }}@endif</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Download Requested') }}: </h6>
							<span class="fs-14">@if ($id->download_requested) {{ __('Requested') }} @else {{ __('Not Requested') }}@endif</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Uploaded On') }}: </h6>
							<span class="fs-14">{{ date_format($id->created_at, 'd M Y')}}</span>
						</div>
					</div>		 

					<!-- SAVE CHANGES ACTION BUTTON -->
					<div class="border-0 text-right mb-2 mt-8">
						<a href="{{ route('admin.archive.list') }}" class="btn btn-primary">{{ __('Return') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

