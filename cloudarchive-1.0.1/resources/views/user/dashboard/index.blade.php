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
			<h4 class="page-title mb-0">{{ __('My Dashboard') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-chart-tree-map mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{url('#')}}"> {{ __('My Dashboard') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<!-- USER PROFILE PAGE -->
	<div class="row">

		<div class="col-xl-4 col-lg-4 col-md-12">
			<div class="card border-0" id="dashboard-background">
				<div class="widget-user-image overflow-hidden mx-auto mt-5"><img alt="User Avatar" class="rounded-circle" src="@if(auth()->user()->profile_photo_path){{ asset(auth()->user()->profile_photo_path) }} @else {{ URL::asset('img/users/avatar.jpg') }} @endif"></div>
				<div class="card-body text-center">
					<div>
						<h4 class="mb-1 mt-1 font-weight-800 fs-16">{{ auth()->user()->name }}</h4>
						<h6 class="text-muted fs-12">{{ auth()->user()->job_role }}</h6>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-8 col-lg-8 col-md-12">
			<div class="row">
				<div class="col-lg-6 col-sm-6 col-sm-12">
					<div class="card mb-4 overflow-hidden border-0">
						<div class="card-body d-flex">
							<div class="usage-info w-100">
								<p class=" mb-0 fs-12 font-weight-bold">{{ __('Current Subscription Plan') }}</p>
								<h2 class="mb-2 mt-3 fs-18 number-font-light font-weight-800">
									@if ($user_subscription != 'free')
										<span class="yellow font-weight-800">{{ $user_subscription->plan_name }}</span>
									@else 
										<span class="yellow font-weight-800">{{ __('Free Tier') }}</span>
									@endif	
								</h2>
							</div>
							<div class="usage-icon w-100 text-right">
								<i class="fa-solid fa-box-open"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-sm-6 col-sm-12">
					<div class="card mb-4 overflow-hidden border-0">
						<div class="card-body d-flex">
							<div class="usage-info w-100">
								<p class=" mb-0 fs-12 font-weight-bold">{{ __('Total Storage Space') }}</p>
								<h2 class="mb-2 mt-3 yellow number-font-light font-weight-800">{{ $user_storage_size }}</h2>
							</div>
							<div class="usage-icon w-100 text-right">
								<i class="fa-solid fa-hard-drive"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-sm-6 col-sm-12">
					<div class="card mb-4 overflow-hidden border-0">
						<div class="card-body d-flex">
							<div class="usage-info w-100">
								<p class=" mb-0 fs-12 font-weight-bold">{{ __('Ready to Download Archive Files') }}</p>
								<h2 class="mb-2 mt-3 number-font-light font-weight-800">{{ number_format($storage['downloadable'][0]['data']) }}</h2>
							</div>
							<div class="usage-icon w-100 text-right">
								<i class="fa-solid fa-cloud-arrow-down"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-sm-6 col-sm-12">
					<div class="card mb-4 overflow-hidden border-0">
						<div class="card-body d-flex">
							<div class="usage-info w-100">
								<p class=" mb-0 fs-12 font-weight-bold">{{ __('Active Archive Retrieval Requests') }}</p>
								<h2 class="mb-2 mt-3 number-font-light font-weight-800">{{ number_format($storage['requested'][0]['data']) }}</h2>
							</div>
							<div class="usage-icon w-100 text-right">
								<i class="fa-solid fa-bars-progress"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mt-2">
			<div class="card border-0">				
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">				
							<div class="d-inline border-0">
								<div>
									<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-hard-drive mr-4 text-info"></i>{{ __('Storage Usage') }}</h3>
								</div>
								<div>
									<h3 class="card-title fs-24 font-weight-800">{{ $storage_used }}</h3>
								</div>
								<div class="mb-3">
									<span class="fs-12 text-muted">{{ __('Total of ') }}{{ $user_storage_size }}{{ __(' Used') }}</span>
								</div>
							</div>
							<div>
								<div class="progress mb-4 mt-4">
									<div class="progress-bar progress-bar-striped progress-bar-animated zip-bar" role="progressbar" style="width: {{ $progress['zip'] }}%" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning documents-bar" role="progressbar" style="width: {{ $progress['document'] }}%" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar progress-bar-striped progress-bar-animated bg-success images-bar" role="progressbar" style="width: {{ $progress['media'] }}%" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar progress-bar-striped progress-bar-animated bg-danger media-bar" role="progressbar" style="width: {{ $progress['other'] }}%" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="progress-info d-flex overflow-x-auto">
									<div class="label mr-5">
										<span class="label-dot zip"></span>
										<b class="label-title">{{ __('Zip Files') }}</b>
									</div>
									<div class="label mr-5">
										<span class="label-dot documents"></span>
										<b class="label-title">{{ __('Documents') }}</b>
									</div>
									<div class="label mr-5">
										<span class="label-dot images"></span>
										<b class="label-title">{{ __('Media') }}</b>
									</div>
									<div class="label mr-5">
										<span class="label-dot media"></span>
										<b class="label-title">{{ __('Other') }}</b>
									</div>
									<div class="label mr-5">
										<span class="label-dot empty"></span>
										<b class="label-title">{{ __('Empty') }}</b>
									</div>
								</div>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mt-2">
			<div class="card border-0">				
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="d-inline border-0">
								<div>
									<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-box-open mr-4 text-info"></i>{{ __('Subscription') }}</h3>
								</div>
								@if ($user_subscription == 'free')
									<div>
										<h3 class="card-title fs-24 font-weight-800">{{ __('Active Forever') }}</h3>
									</div>
									<div class="mb-1">
										<span class="fs-12 text-muted">{{ __('Free Tier Plan ') }} / {!! config('payment.default_system_currency_symbol') !!}0.00 {{ __('Per Month') }}</span>
									</div>
								@else
									<div>
										<h3 class="card-title fs-24 font-weight-800">@if ($user_subscription->payment_frequency == 'monthly') {{ __('Monthly Subscription') }} @else {{ __('Yearly Subscription') }} @endif</h3>
									</div>
									<div class="mb-1">
										<span class="fs-12 text-muted">{{ $user_subscription->plan_name }} Plan / {!! config('payment.default_system_currency_symbol') !!}{{ $user_subscription->price }} @if ($user_subscription->payment_frequency == 'monthly') {{ __('Per Month') }} @else {{ __('Per Year') }} @endif</span>
									</div>
								@endif
							</div>
							<div>								
								<div class="progress mb-4 mt-4">
									<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning subscription-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress['subscription'] }}%"></div>
								</div>
								<div class="progress-info d-flex overflow-x-auto">
									<div class="label mr-5">
										<span class="label-dot documents"></span>
										<b class="label-title">{{ __('Used Storage') }}</b>
										<span class="fs-12 text-muted ml-2">{{ __('Total ') }} {{ $storage_used }} of {{ $user_storage_size }} {{ __('Storage Space is Used') }}</span>
									</div>
								</div>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 mt-2">
			<div class="card border-0">
				<div class="card-body">
					<a id="refresh-downloadable" class="refresh-button" href="#" data-tippy-content="Refresh Table"><i class="fa-solid fa-arrows-rotate transcribe-result"></i></a>
					<div class="d-inline border-0">
						<div>
							<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-cloud-arrow-down mr-4 text-info"></i>{{ __('Downloadable Archives') }}</h3>
						</div>
					</div>
					<!-- SET DATATABLE -->
					<table id='downloadableTable' class='table' width='100%'>
							<thead>
								<tr>
									<th width="20%">{{ __('File Name') }}</th>
									<th width="10%">{{ __('Archive Type') }}</th>
									<th width="5%">{{ __('Downloadable') }}</th>
									<th width="7%">{{ __('Format') }}</th>		
									<th width="7%">{{ __('Size') }}</th>																																           	   
									<th width="10%">{{ __('Archived On') }}</th>  						           	
									<th width="5%">{{ __('Actions') }}</th>
								</tr>
							</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 mt-2">
			<div class="card border-0">
				<div class="card-body">
					<a id="refresh-requested" class="refresh-button" href="#" data-tippy-content="Refresh Table"><i class="fa-solid fa-arrows-rotate transcribe-result"></i></a>
					<div class="d-inline border-0">						
						<div>
							<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-bars-progress mr-4 text-info"></i>{{ __('Download Requested Archives') }}</h3>
						</div>
					</div>
					<!-- SET DATATABLE -->
					<table id='processingTable' class='table' width='100%'>
							<thead>
								<tr>
									<th width="20%">{{ __('File Name') }}</th>
									<th width="10%">{{ __('Archive Type') }}</th>
									<th width="5%">{{ __('Requested') }}</th>
									<th width="7%">{{ __('Format') }}</th>		
									<th width="7%">{{ __('Size') }}</th>																																           	   
									<th width="10%">{{ __('Archived On') }}</th>  						           	
									<th width="4%">{{ __('Actions') }}</th>
								</tr>
							</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>

		<div class="col-xl-12 col-lg-12 col-md-12 mt-2">
			<div class="card border-0">				
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="d-inline border-0">
								<div>
									<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-cloud-arrow-up mr-4 text-info"></i>{{ __('Uploads') }}</h3>
								</div>
								<div>
									<h3 class="card-title fs-24 font-weight-800">{{ $storage_used_current_year }}</h3>
								</div>
								<div class="mb-3">
									<span class="fs-12 text-muted">{{ __('Total Uploads During Current Year') }}</span>
								</div>
							</div>
							<div class="card-body">
								<div class="chartjs-wrapper-demo">
									<canvas id="chart-user-usage" class="h-330"></canvas>
								</div>
							</div>							
						</div>
					</div>
				</div>				
			</div>
		</div>
	</div>
	<!-- END USER PROFILE PAGE -->
@endsection

@section('js')
	<!-- Chart JS -->
	<script src="{{URL::asset('plugins/chart/chart.min.js')}}"></script>
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script src="{{URL::asset('plugins/tippy/popper.min.js')}}"></script>
	<script src="{{URL::asset('plugins/tippy/tippy-bundle.umd.min.js')}}"></script>
	<script>
		$(function() {
	
			'use strict';

			let download = $('#downloadableTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				colReorder: true,
				"order": [[ 5, "desc" ]],
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>No archives ready to download yet</div>",
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
				ajax: "{{ route('user.archive.list.download') }}",
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

			let process = $('#processingTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				colReorder: true,
				"order": [[ 5, "desc" ]],
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>No download requests are processing currently</div>",
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
				ajax: "{{ route('user.archive.list.request') }}",
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

			let usageData = JSON.parse(`<?php echo $chart_data['storage_usage']; ?>`);
			let usageDataset = Object.values(usageData);
			let delayed;

			let ctx = document.getElementById('chart-user-usage');
			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					datasets: [{
						label: 'Uploaded(MB)',
						data: usageDataset,
						backgroundColor: '#007bff',
						borderWidth: 1,
						borderRadius: 20,
						barPercentage: 0.5,
						fill: true
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false,
						labels: {
							display: false
						}
					},
					responsive: true,
					animation: {
						onComplete: () => {
							delayed = true;
						},
						delay: (context) => {
							let delay = 0;
							if (context.type === 'data' && context.mode === 'default' && !delayed) {
								delay = context.dataIndex * 50 + context.datasetIndex * 5;
							}
							return delay;
						},
					},
					scales: {
						y: {
							stacked: true,
							ticks: {
								beginAtZero: true,
								font: {
									size: 10
								},
								stepSize: 2000,
							},
							grid: {
								color: '#ebecf1',
								borderDash: [3, 2]                            
							}
						},
						x: {
							stacked: true,
							ticks: {
								font: {
									size: 10
								}
							},
							grid: {
								color: '#ebecf1',
								borderDash: [3, 2]                            
							}
						},
					},
					plugins: {
						tooltip: {
							cornerRadius: 10,
							padding: 15,
							backgroundColor: '#000000',
							titleColor: '#FF9D00',
							yAlign: 'bottom',
							xAlign: 'center',
						},
						legend: {
							position: 'bottom',
							labels: {
								boxWidth: 10,
								font: {
									size: 10
								}
							}
						}
					}
					
				}
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
							url: 'archive/download',
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


			// DELETE SYNTHESIZE RESULT
			$(document).on('click', '.deleteArchiveDownloadButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{__('Confirm Archive Deletion')}}',
					text: '{{__('It will permanently delete this archived file')}}',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: '{{__('Delete')}}',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'archive/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{__('Archive Deleted')}}', '{{__('Archived file has been successfully deleted')}}', 'success');	
									$("#downloadableTable").DataTable().ajax.reload();								
								} else {
									Swal.fire('{{__('Delete Failed')}}', '{{__('There was an error while deleting this archive')}}', 'error');
								}      
							},
							error: function(data) {
								Swal.fire('Oops...','Something went wrong!', 'error')
							}
						})
					} 
				})
			});


			// DELETE SYNTHESIZE RESULT
			$(document).on('click', '.deleteArchiveRequestButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{__('Confirm Archive Deletion')}}',
					text: '{{__('It will permanently delete this archived file')}}',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: '{{__('Delete')}}',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'archive/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{__('Archive Deleted')}}', '{{__('Archived file has been successfully deleted')}}', 'success');	
									$("#processingTable").DataTable().ajax.reload();								
								} else {
									Swal.fire('{{__('Delete Failed')}}', '{{__('There was an error while deleting this archive')}}', 'error');
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


			$('#refresh-requested').on('click', function(e){
				e.preventDefault();
				$("#processingTable").DataTable().ajax.reload();
			});


			$('#refresh-downloadable').on('click', function(e){
				e.preventDefault();
				$("#downloadableTable").DataTable().ajax.reload();
			});
		});
	</script>
@endsection
