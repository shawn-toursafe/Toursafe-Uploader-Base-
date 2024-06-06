@extends('layouts.app')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/tippy/scale-extreme.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/tippy/material.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Archives') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-boxes-packing mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.archive.dashboard') }}"> {{ __('Archive Management') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Archives') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')	
	<!-- ALL USERS PROCESSED TEXT RESULTS -->
	<div class="row">
		<div class="col-lg-12 col-md-12 col-xm-12">
			<div class="card border-0">
				<div class="card-body pt-2">
					<a id="refresh-button" class="refresh-button" href="#" data-tippy-content="Refresh Table"><i class="fa-solid fa-arrows-rotate transcribe-result"></i></a>
					<div class="d-inline border-0">						
						<div>
							<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-boxes-packing mr-4 text-info"></i>{{ __('All User Archives') }}</h3>
						</div>
					</div>
					<!-- BOX CONTENT -->
					<div class="box-content">
							<!-- SET DATATABLE -->
							<table id='allResultsTable' class='table' width='100%'>
									<thead>
										<tr>
											<th width="5%">{{ __('Plan Type') }}</th>
											<th width="10%">{{ __('User') }}</th>
											<th width="15%">{{ __('File Name') }}</th>
											<th width="7%">{{ __('Archive Type') }}</th>
											<th width="4%">{{ __('Downloadable') }}</th>
											<th width="4%">{{ __('Requested') }}</th>
											<th width="4%">{{ __('Format') }}</th>		
											<th width="4%">{{ __('Size') }}</th>																																           	   
											<th width="6%">{{ __('Archived On') }}</th>  						           	
											<th width="8%">{{ __('Actions') }}</th>
										</tr>
									</thead>
							</table> <!-- END SET DATATABLE -->
					</div> <!-- END BOX CONTENT -->
				</div>
			</div>
		</div>
	</div>
	<!-- END ALL USERS PROCESSED TEXT RESULTS -->
@endsection

@section('js')
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script src="{{URL::asset('plugins/tippy/popper.min.js')}}"></script>
	<script src="{{URL::asset('plugins/tippy/tippy-bundle.umd.min.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			// INITILIZE DATATABLE
			let table = $('#allResultsTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				colReorder: true,
				"order": [[ 8, "desc" ]],
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('No synthesized text results yet') }}</div>",
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
				ajax: "{{ route('admin.archive.list') }}",
				columns: [{
						data: 'custom-plan-type',
						name: 'custom-plan-type',
						orderable: true,
						searchable: true
					},
					{
						data: 'user',
						name: 'user',
						orderable: true,
						searchable: true
					},
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
				$("#allResultsTable").DataTable().ajax.reload();
			});

		});
	</script>
@endsection