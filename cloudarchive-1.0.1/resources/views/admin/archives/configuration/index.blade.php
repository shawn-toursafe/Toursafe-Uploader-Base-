@extends('layouts.app')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/tippy/scale-extreme.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/tippy/material.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Archive Settings') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-boxes-packing mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.archive.dashboard') }}"> {{ __('Archive Management') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Archive Settings') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection
@section('content')					
	<div class="row">
		<div class="col-lg-8 col-md-12 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Setup Archive Settings') }}</h3>
				</div>
				<div class="card-body">
				
					<form action="{{ route('admin.archive.configs.store') }}" method="POST" enctype="multipart/form-data">
						@csrf

						<div class="row">							

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">								
									<h6>{{ __('Default Storage Capacity') }} <span class="text-muted">({{ __('For New Users') }})</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" class="form-control @error('default-storage-size') is-danger @enderror" id="default-storage-size" name="default-storage-size" placeholder="Ex: 1000" value="{{ config('settings.default_storage_size') }}" required>
										<span class="text-muted fs-10">{{ __('You have to provide value in MB e.g. value 1024 means, user will have max storage size of 1GB') }}.</span>
										@error('default-storage-size')
											<p class="text-danger">{{ $errors->first('default-storage-size') }}</p>
									@enderror
									</div> 
								</div> 						
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">								
									<h6>{{ __('Maximum Upload Size') }} <span class="text-muted">({{ __('For Admin & Subscriber Groups') }})</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Maximum upload size limit for a single file for admin and subscriber groups. Provide size in MB.') }}"></i></h6>
									<div class="form-group">							    
										<input type="number" class="form-control @error('maximum-upload-limit-subscriber') is-danger @enderror" id="maximum-upload-limit-subscriber" name="maximum-upload-limit-subscriber" placeholder="Ex: 1000" value="{{ config('settings.upload_limit_subscriber') }}" required>
										<span class="text-muted fs-10">{{ __('You have to provide value in MB e.g. value 1024 means, user can upload a file up to 1GB in size.') }}</span>
										@error('maximum-upload-limit-subscriber')
											<p class="text-danger">{{ $errors->first('maximum-upload-limit-subscriber') }}</p>
										@enderror
									</div> 
								</div>							
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">								
									<h6>{{ __('Maximum Upload Quantity') }} <span class="text-muted">({{ __('For Admin & Subscriber Groups') }})</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Maximum parallel upload file quantity limit for admin and subscriber groups.') }}"></i></h6>
									<div class="form-group">							    
										<input type="number" class="form-control @error('maximum-upload-quantity-subscriber') is-danger @enderror" id="maximum-upload-quantity-subscriber" name="maximum-upload-quantity-subscriber" placeholder="Ex: 1" value="{{ config('settings.upload_quantity_subscriber') }}" required>
										@error('maximum-upload-quantity-subscriber')
											<p class="text-danger">{{ $errors->first('maximum-upload-quantity-subscriber') }}</p>
										@enderror
									</div> 
								</div>							
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">								
									<h6>{{ __('Multipart File Upload Chunk Size') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Each file upload will be split into chunks via Amazon Glacier native Multipart Upload feature to efficiently upload files. To upload large files provide large file chunks to avoid buffer issues. You have to provide value in MB. Each file will be split into that size and uploaded in parallel') }}"></i></h6>
									<div class="form-group">							    
										<input type="number" class="form-control @error('multipart-chunk-size') is-danger @enderror" id="multipart-chunk-size" name="multipart-chunk-size" placeholder="Ex: 64" value="{{ config('settings.multipart_chunk_size') }}" required>
										@error('multipart-chunk-size')
											<p class="text-danger">{{ $errors->first('multipart-chunk-size') }}</p>
										@enderror
									</div> 
								</div>							
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">	
									<h6>{{ __('Available Storage Type') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									  <select id="storage-type" name="storage-type" data-placeholder="{{ __('Select Available Storage Type for All') }}:">			
										<option value="glacier" @if ( config('settings.storage_type')  == 'glacier') selected @endif>{{ __('Glacier Storage Only') }}</option>
										<option value="deep_archive" @if ( config('settings.storage_type')  == 'deep_archive') selected @endif>{{ __('Deep Archive Storage Only') }}</option>
										<option value="both" @if ( config('settings.storage_type')  == 'both') selected @endif>{{ __('Both (Glacier and Deep Archive Storages)') }}</option>
									</select>
								</div>								
							</div>		

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">								
									<h6>{{ __('Retrieved Archives Available Days') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('How many days retrieved archives will be available for downloading. Minimum duration is 1 day. All retrieved Glacier and Galcier Deep Archive files will be stored on your S3 bucket as a separate copy, hence file will have storage charges for the duration of availability of copy and will be auto deleted upon expiry of days') }}"></i></h6>
									<div class="form-group">							    
										<input type="number" class="form-control @error('download-days') is-danger @enderror" id="download-days" name="download-days" placeholder="Ex: 5" value="{{ config('settings.download_days') }}" required>
										@error('download-days')
											<p class="text-danger">{{ $errors->first('download-days') }}</p>
										@enderror
									</div> 
								</div>							
							</div>
						</div>


						<div class="card border-0 special-shadow mb-7">							
							<div class="card-body">

								<h6 class="fs-12 font-weight-bold mb-4"><i class="fa fa-gift text-info fs-14 mr-2"></i>{{ __('Free Tier Options') }} <span class="text-muted">({{ __('User Group') }})</span></h6>

								<div class="row">									
									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">								
											<h6>{{ __('Maximum Upload Size') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Maximum upload size limit for a single file for free tier user group. Provide size in MB.') }}"></i></h6>
											<div class="form-group">							    
												<input type="number" class="form-control @error('maximum-upload-limit-user') is-danger @enderror" id="maximum-upload-limit-user" name="maximum-upload-limit-user" placeholder="Ex: 1000" value="{{ config('settings.upload_limit_user') }}" required>
												<span class="text-muted fs-10">{{ __('You have to provide value in MB e.g. value 1024 means, user can upload a file up to 1GB in size.') }}</span>
												@error('maximum-upload-limit-user')
													<p class="text-danger">{{ $errors->first('maximum-upload-limit-user') }}</p>
												@enderror
											</div> 
										</div>							
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">								
											<h6>{{ __('Maximum Upload Quantity') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Maximum parallel upload file quantity limit for free tier user group.') }}"></i></h6>
											<div class="form-group">							    
												<input type="number" class="form-control @error('maximum-upload-quantity-user') is-danger @enderror" id="maximum-upload-quantity-user" name="maximum-upload-quantity-user" placeholder="Ex: 1" value="{{ config('settings.upload_quantity_user') }}" required>
												@error('maximum-upload-quantity-user')
													<p class="text-danger">{{ $errors->first('maximum-upload-quantity-user') }}</p>
												@enderror
											</div> 
										</div>							
									</div>
								</div>	
							</div>
						</div>


						<div class="card border-0 special-shadow">							
							<div class="card-body">
								<h6 class="fs-12 font-weight-bold mb-4"><img src="{{URL::asset('img/csp/aws-sm.png')}}" class="fw-2 mr-2" alt="">{{ __('Amazon Web Services') }}</h6>

								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12">								
										<!-- ACCESS KEY -->
										<div class="input-box">								
											<h6>{{ __('AWS Access Key') }}  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">							    
												<input type="text" class="form-control @error('set-aws-access-key') is-danger @enderror" id="aws-access-key" name="set-aws-access-key" value="{{ config('services.aws.key') }}" autocomplete="off">
												@error('set-aws-access-key')
													<p class="text-danger">{{ $errors->first('set-aws-access-key') }}</p>
												@enderror
											</div> 
										</div> <!-- END ACCESS KEY -->
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<!-- SECRET ACCESS KEY -->
										<div class="input-box">								
											<h6>{{ __('AWS Secret Access Key') }}  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6> 
											<div class="form-group">							    
												<input type="text" class="form-control @error('set-aws-secret-access-key') is-danger @enderror" id="aws-secret-access-key" name="set-aws-secret-access-key" value="{{ config('services.aws.secret') }}" autocomplete="off">
												@error('set-aws-secret-access-key')
													<p class="text-danger">{{ $errors->first('set-aws-secret-access-key') }}</p>
												@enderror
											</div> 
										</div> <!-- END SECRET ACCESS KEY -->
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">								
										<!-- ACCESS KEY -->
										<div class="input-box">								
											<h6>{{ __('Amazon S3 Bucket Name') }}  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">							    
												<input type="text" class="form-control @error('set-aws-bucket') is-danger @enderror" id="aws-bucket" name="set-aws-bucket" value="{{ config('services.aws.bucket') }}" autocomplete="off">
												@error('set-aws-bucket')
													<p class="text-danger">{{ $errors->first('set-aws-bucket') }}</p>
												@enderror
											</div> 
										</div> <!-- END ACCESS KEY -->
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<!-- AWS REGION -->
										<div class="input-box">	
											<h6>{{ __('Set AWS Region') }}  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											  <select id="set-aws-region" name="set-aws-region" data-placeholder="Select Default AWS Region:">			
												<option value="us-east-1" @if ( config('services.aws.region')  == 'us-east-1') selected @endif>{{ __('US East (N. Virginia) us-east-1') }}</option>
												<option value="us-east-2" @if ( config('services.aws.region')  == 'us-east-2') selected @endif>{{ __('US East (Ohio) us-east-2') }}</option>
												<option value="us-west-1" @if ( config('services.aws.region')  == 'us-west-1') selected @endif>{{ __('US West (N. California) us-west-1') }}</option>
												<option value="us-west-2" @if ( config('services.aws.region')  == 'us-west-2') selected @endif>{{ __('US West (Oregon) us-west-2') }}</option>
												<option value="ap-east-1" @if ( config('services.aws.region')  == 'ap-east-1') selected @endif>{{ __('Asia Pacific (Hong Kong) ap-east-1') }}</option>
												<option value="ap-south-1" @if ( config('services.aws.region')  == 'ap-south-1') selected @endif>{{ __('Asia Pacific (Mumbai) ap-south-1') }}</option>
												<option value="ap-northeast-3" @if ( config('services.aws.region')  == 'ap-northeast-3') selected @endif>{{ __('Asia Pacific (Osaka-Local) ap-northeast-3') }}</option>
												<option value="ap-northeast-2" @if ( config('services.aws.region')  == 'ap-northeast-2') selected @endif>{{ __('Asia Pacific (Seoul) ap-northeast-2') }}</option>
												<option value="ap-southeast-1" @if ( config('services.aws.region')  == 'ap-southeast-1') selected @endif>{{ __('Asia Pacific (Singapore) ap-southeast-1') }}</option>
												<option value="ap-southeast-2" @if ( config('services.aws.region')  == 'ap-southeast-2') selected @endif>{{ __('Asia Pacific (Sydney) ap-southeast-2') }}</option>
												<option value="ap-northeast-1" @if ( config('services.aws.region')  == 'ap-northeast-1') selected @endif>{{ __('Asia Pacific (Tokyo) ap-northeast-1') }}</option>
												<option value="eu-central-1" @if ( config('services.aws.region')  == 'eu-central-1') selected @endif>{{ __('Europe (Frankfurt) eu-central-1') }}</option>
												<option value="eu-west-1" @if ( config('services.aws.region')  == 'eu-west-1') selected @endif>{{ __('Europe (Ireland) eu-west-1') }}</option>
												<option value="eu-west-2" @if ( config('services.aws.region')  == 'eu-west-2') selected @endif>{{ __('Europe (London) eu-west-2') }}</option>
												<option value="eu-south-1" @if ( config('services.aws.region')  == 'eu-south-1') selected @endif>{{ __('Europe (Milan) eu-south-1') }}</option>
												<option value="eu-west-3" @if ( config('services.aws.region')  == 'eu-west-3') selected @endif>{{ __('Europe (Paris) eu-west-3') }}</option>
												<option value="eu-north-1" @if ( config('services.aws.region')  == 'eu-north-1') selected @endif>{{ __('Europe (Stockholm) eu-north-1') }}</option>
												<option value="me-south-1" @if ( config('services.aws.region')  == 'me-south-1') selected @endif>{{ __('Middle East (Bahrain) me-south-1') }}</option>
												<option value="sa-east-1" @if ( config('services.aws.region')  == 'sa-east-1') selected @endif>{{ __('South America (São Paulo) sa-east-1') }}</option>
												<option value="ca-central-1" @if ( config('services.aws.region')  == 'ca-central-1') selected @endif>{{ __('Canada (Central) ca-central-1') }}</option>
												<option value="af-south-1" @if ( config('services.aws.region')  == 'af-south-1') selected @endif>{{ __('Africa (Cape Town) af-south-1') }}</option>
											</select>
										</div> <!-- END AWS REGION -->									
									</div>									
		
								</div>
	
							</div>
						</div>	
				

						<!-- SAVE CHANGES ACTION BUTTON -->
						<div class="border-0 text-right mb-2 mt-1">
							<a href="{{ route('admin.archive.dashboard') }}" class="btn btn-cancel mr-2">{{ __('Cancel') }}</a>
							<button type="submit" class="btn btn-primary">{{ __('Save') }}</button>							
						</div>				

					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<!-- Awselect JS -->
	<script src="{{URL::asset('plugins/awselect/awselect-custom.js')}}"></script>
	<script src="{{URL::asset('js/awselect.js')}}"></script>
	<script src="{{URL::asset('plugins/tippy/popper.min.js')}}"></script>
	<script src="{{URL::asset('plugins/tippy/tippy-bundle.umd.min.js')}}"></script>
	<script>
		 $(function () {
			tippy('[data-tippy-content]', {
				animation: 'scale-extreme',
				theme: 'material',
			});
		 });
	</script>
@endsection