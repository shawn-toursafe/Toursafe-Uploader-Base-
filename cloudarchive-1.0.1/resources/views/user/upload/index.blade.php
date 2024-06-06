@extends('layouts.app')
@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
	<!-- FilePond CSS -->
	<link href="{{URL::asset('plugins/filepond/filepond.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/tippy/scale-extreme.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/tippy/material.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
<!-- PAGE HEADER -->
<div class="page-header mt-5-7">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">{{ __('Upload Archive') }}</h4>
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-box-archive mr-2 fs-12"></i>{{ __('User') }}</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Upload Archive') }}</a></li>
		</ol>
	</div>
</div>
<!-- END PAGE HEADER -->
@endsection
@section('content')	
	<div class="row">

	
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-body">		
					<form id="multipartupload" action="" method="post" enctype="multipart/form-data" onsubmit="uploadFiles(event)">		
						@csrf

						<!-- DRAG & DROP FILES -->
						<div class="select-file">
							<input type="file" name="filepond[]" id="filepond" class="filepond" multiple required  />	
						</div>
						<div class="d-flex w-100 pt-4 pb-2">
							<div class="w-90">
								<div id="storage-type-radio" role="radiogroup">
									@if (config('settings.storage_type') == 'both')
										<div class="radio-control">
											<input type="radio" name="storage" class="input-control" id="glacier" value="GLACIER" style="vertical-align: middle;" checked>
											<label for="glacier" class="label-control fs-12">{{ __('Glacier') }}</label>
										</div>
										<div class="radio-control">
											<input type="radio" name="storage" class="input-control" id="deep_archive" value="DEEP_ARCHIVE" style="vertical-align: middle;">
											<label for="deep_archive" class="label-control fs-12">{{ __('Deep Archive') }}</label>
										</div>
									@elseif (config('settings.storage_type') == 'glacier')
										<div class="radio-control">
											<input type="radio" name="storage" class="input-control" id="glacier" value="GLACIER" style="vertical-align: middle;" checked>
											<label for="glacier" class="label-control fs-12">{{ __('Glacier') }}</label>
										</div>
									@elseif (config('settings.storage_type') == 'deep_archive')
										<div class="radio-control">
											<input type="radio" name="storage" class="input-control" id="deep_archive" value="DEEP_ARCHIVE" style="vertical-align: middle;" checked>
											<label for="deep_archive" class="label-control fs-12">{{ __('Deep Archive') }}</label>
										</div>
									@endif
															
								</div>
							</div>
							<div class="text-left w-100">
								<button type="submit" name="submit" class="btn btn-primary pl-6 pr-6">{{ __('Upload') }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<div class="col-lg-12 col-md-12 col-sm-12 mt-3" id="upload-container">
			<div class="card border-0">
				<div class="card-body">
					<div id="upload-process">
						<div id="upload-box">
							<div id="upload-status"></div>
						</div>		
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 mt-3">
			<div class="card border-0">
				<div class="card-body">
					<a id="refresh-button" class="refresh-button" href="#" data-tippy-content="Refresh Table"><i class="fa-solid fa-arrows-rotate transcribe-result"></i></a>
					<div class="d-inline border-0">
						<div>
							<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-cloud-arrow-up mr-4 text-info"></i>{{ __("Today's Uploads") }}</h3>							
						</div>						
					</div>
					<!-- SET DATATABLE -->
					<table id='resultsTable' class='table' width='100%'>
							<thead>
								<tr>
									<th width="20%">{{ __('File Name') }}</th>
									<th width="10%">{{ __('Archive Type') }}</th>
									<th width="5%">{{ __('Downloadable') }}</th>
									<th width="5%">{{ __('Requested') }}</th>
									<th width="7%">{{ __('Format') }}</th>		
									<th width="7%">{{ __('Size') }}</th>																																           	   
									<th width="10%">{{ __('Archived On') }}</th>  						           	
									<th width="7%">{{ __('Actions') }}</th>
								</tr>
							</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
<!-- Data Tables JS -->
<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
<script src="{{URL::asset('plugins/tippy/popper.min.js')}}"></script>
<script src="{{URL::asset('plugins/tippy/tippy-bundle.umd.min.js')}}"></script>
<!-- FilePond JS -->
<script src={{ URL::asset('plugins/filepond/filepond.min.js') }}></script>
<script src={{ URL::asset('plugins/filepond/filepond-plugin-file-validate-size.min.js') }}></script>
<script src={{ URL::asset('plugins/filepond/filepond-plugin-file-validate-type.min.js') }}></script>
<script src="{{URL::asset('js/upload.js')}}"></script>
<script type="text/javascript">
	$(function () {

		"use strict";
		

		let table = $('#resultsTable').DataTable({
			"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
			responsive: true,
			colReorder: true,
			"order": [[ 6, "desc" ]],
			language: {
				"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>No files were archived yet</div>",
				search: "<i class='fa fa-search search-icon'></i>",
				lengthMenu: '_MENU_ ',
				paginate : {
					first    : '<i class="fa fa-angle-double-left"></i>',
					last     : '<i class="fa fa-angle-double-right"></i>',
					previous : '<i class="fa fa-angle-left"></i>',
					next     : '<i class="fa fa-angle-right"></i>'
				}
			},
			pagingType : 'full_numbers',
			processing: true,
			serverSide: true,
			ajax: "{{ route('user.archive.upload') }}",
			columns: [
				{
					data: 'custom-name',
					name: 'custom-name',
					orderable: true,
					searchable: true
				},					
				{
					data: 'custom-type',
					name: 'custom-type',
					orderable: true,
					searchable: true
				},
				{
					data: 'custom-downloadable',
					name: 'custom-downloadable',
					orderable: true,
					searchable: true
				},
				{
					data: 'custom-request',
					name: 'custom-request',
					orderable: true,
					searchable: true
				},
				{
					data: 'custom-format',
					name: 'custom-format',
					orderable: true,
					searchable: true
				},			
				{
					data: 'custom-size',
					name: 'custom-size',
					orderable: true,
					searchable: true
				},
				{
					data: 'created-on',
					name: 'created-on',
					orderable: true,
					searchable: true
				},			
				{
					data: 'actions',
					name: 'actions',
					orderable: false,
					searchable: false
				},
			]
		});


		// DOWNLOAD ARCHIVE
		$(document).on('click', '.downloadArchiveButton', function(e) {

			e.preventDefault();

			Swal.fire({
				title: '{{ __('Download Archive') }}',
				html: '<span class="text-muted">{{ __('Make sure that your selected archive file is marked as downloadable first') }} <i class="fa-solid fa-circle-check table-info-button green fs-14"></i></span>',
				showCancelButton: true,
				confirmButtonText: '{{ __('Download') }}',
				reverseButtons: true,
			}).then((result) => {
				if (result.isConfirmed) {
					let formData = new FormData();
					formData.append("id", $(this).attr('id'));
					$.ajax({
						headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
						method: 'post',
						url: 'download',
						data: formData,
						processData: false,
						contentType: false,
						success: function (data) {
							if (data['status'] == 'success') {
								let link = document.createElement("a");
								link.href = data['url'];
								document.body.appendChild(link);
								link.click();
							} else {
								Swal.fire('{{ __('Download Issue') }}', data['message'], 'warning');
							}      
						},
						error: function(data) {
							Swal.fire('Oops...','Something went wrong!', 'error')
						}
					})
				}
			})
		});
		

		// SUBMIT ARCHIVE RETRIVAL REQUEST
		$(document).on('click', '.requestArchiveButton', function(e) {

			e.preventDefault();

			Swal.fire({
				title: '{{ __('Initiate Archive Retrieval Request') }}',
				html: '<span class="text-info">{{ __('All retrieved archives are downloadable during') }} {{ config('settings.download_days') }} {{ __('days') }}</span> <br> <span class="fs-12">({{ __('Expedited Retrieval Tier is only available for Glacier Storage') }})</span>',
				input: 'select',
				inputOptions: {
					'Expedited': 'Expedited ({{ __('Typically within 1-2 min if size < 250MB') }})',
					'Standard': 'Standard ({{ __('Typically within 3-5 hours') }})',
					'Bulk': 'Bulk ({{ __('Typically within 5-12 hours') }})'
				},
				inputPlaceholder: '{{ __('Select Archive Retrival Tier') }}',
				showCancelButton: true,
				confirmButtonText: '{{ __('Request') }}',
				reverseButtons: true,
			}).then((result) => {
				if (result.isConfirmed && (result.value != '')) {
					let formData = new FormData();
					formData.append("id", $(this).attr('id'));
					formData.append("tier", result.value);
					$.ajax({
						headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
						method: 'post',
						url: 'retrieve',
						data: formData,
						processData: false,
						contentType: false,
						success: function (data) {
							if (data['status'] == 'success') {
								Swal.fire('{{ __('Retrieval Request Submitted') }}', data['message'], 'success');	
								$("#resultsTable").DataTable().ajax.reload();								
							} else {
								Swal.fire('{{ __('Retrieval Request Failed') }}', data['message'], 'error');
							}      
						},
						error: function(data) {
							Swal.fire('Oops...','Something went wrong!', 'error')
						}
					})
				} else if (result.isConfirmed && (result.value == '')) {
					Swal.fire('{{ __('Retrieval Request Incorrect') }}', '{{ __('Make sure you select retrieval request type first') }}', 'warning');
				}
			})
		});


		// DELETE ARCHIVE
		$(document).on('click', '.deleteArchiveButton', function(e) {

			e.preventDefault();

			Swal.fire({
				title: '{{ __('Confirm Archive Deletion') }}',
				text: '{{ __('It will permanently delete this archived file') }}',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: '{{ __('Delete') }}',
				reverseButtons: true,
			}).then((result) => {
				if (result.isConfirmed) {
					let formData = new FormData();
					formData.append("id", $(this).attr('id'));
					$.ajax({
						headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
						method: 'post',
						url: 'delete',
						data: formData,
						processData: false,
						contentType: false,
						success: function (data) {
							if (data == 'success') {
								Swal.fire('{{ __('Archive Deleted') }}', '{{ __('Archived file has been successfully deleted') }}', 'success');	
								$("#resultsTable").DataTable().ajax.reload();								
							} else {
								Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this archive') }}', 'error');
							}      
						},
						error: function(data) {
							Swal.fire('Oops...','Something went wrong!', 'error')
						}
					})
				} 
			})
		});


		$(function () {
			tippy('[data-tippy-content]', {
				animation: 'scale-extreme',
				theme: 'material',
			});
		});


		$('#refresh-button').on('click', function(e){
			e.preventDefault();
			$("#resultsTable").DataTable().ajax.reload();
		});
	});
</script>
@endsection