@extends('layouts.app')

@section('page-header')
	<!--PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Archive Dashboard') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-boxes-packing mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.archive.dashboard') }}"> {{ __('Archive Management') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Archive Dashboard') }}</a></li>
			</ol>
		</div>
	</div>
	<!--END PAGE HEADER -->
@endsection

@section('content')	
	<div class="row">
		<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="w-100">
							<p class=" mb-3 fs-12 font-weight-bold">{{ __('Glacier Storage Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
							<h2 class="mb-0"><span class="number-font-chars">{{ $usage_data['glacier_current_month'] }}</span></h2>									
						</div>
						<div class="text-right">
							<i class="fs-35 text-info fa-solid fa-box-archive" style="vertical-align: bottom;"></i>
						</div>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1">{{ __('Total Storage') }} ({{ __('Current Year') }}):</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i>{{ $usage_data['glacier_current_year'] }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="w-100">
							<p class=" mb-3 fs-12 font-weight-bold">{{ __('Deep Archive Storage Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
							<h2 class="mb-0"><span class="number-font-chars">{{ $usage_data['deep_archive_current_month'] }}</span></h2>									
						</div>
						<div class="text-right">
							<i class="fs-35 fa-solid fa-boxes-packing" style="vertical-align: bottom;"></i>
						</div>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1">{{ __('Total Storage') }} ({{ __('Current Year') }}):</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i>{{ $usage_data['deep_archive_current_year'] }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="w-100">
							<p class=" mb-3 fs-12 font-weight-bold">{{ __('Free Storage Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
							<h2 class="mb-0"><span class="number-font-chars">{{ $usage_data['free_storage_current_month'] }}</span></h2>									
						</div>
						<div class="text-right">
							<i class="fs-35 text-danger fa-solid fa-badge" style="vertical-align: bottom;"></i>
						</div>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1">{{ __('Total Storage') }} ({{ __('Current Year') }}):</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i>{{ $usage_data['free_storage_current_year'] }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="w-100">
							<p class=" mb-3 fs-12 font-weight-bold">{{ __('Paid Storage Used') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
							<h2 class="mb-0"><span class="number-font-chars">{{ $usage_data['paid_storage_current_month'] }}</span></h2>									
						</div>
						<div class="text-right">
							<i class="fs-35 text-success fa-solid fa-badge-dollar" style="vertical-align: bottom;"></i>
						</div>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1">{{ __('Total Storage') }} ({{ __('Current Year') }}):</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i>{{ $usage_data['paid_storage_current_year'] }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card mb-4 border-0">
				<div class="card-header d-inline border-0">
					<div>
						<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-hard-drive mr-4 text-info"></i>{{ __('Storage Usage ') }}</h3>
					</div>
					<div>
						<h3 class="card-title fs-24 font-weight-800">{{ $total_used }}</h3>
					</div>
					<div class="mb-3">
						<span class="fs-12 text-muted">{{ __('Out of Total ') }}{{ $total_allocated }}{{ __(' Allocated Storage Space') }}</span>
					</div>
				</div>
				<div class="card-body">
					<div class="progress mb-4">
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

	<!-- CURRENT YEAR USAGE ANALYTICS -->
	<div class="row mt-4">
		<div class="col-lg-12 col-md-12">
			<div class="card mb-4 overflow-hidden border-0">
				<div class="card-header d-inline border-0">
					<div>
						<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-cloud-arrow-up mr-4 text-info"></i>{{ __('Uploads ') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>
					</div>
					<div>
						<h3 class="card-title fs-24 font-weight-800">{{ $total_used_current_year }}</h3>
					</div>
					<div class="mb-3">
						<span class="fs-12 text-muted">{{ __('Total Archive Upload Size During Current Year') }}</span>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="">
								<canvas id="chart-storage-dashboard" class="h-400"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- CURRENT YEAR USAGE ANALYTICS -->

@endsection

@section('js')
	<!-- Chart JS -->
	<script src="{{URL::asset('plugins/chart/chart.min.js')}}"></script>
	<script type="text/javascript">
		$(function() {
	
			'use strict';
			
			let usageData = JSON.parse(`<?php echo $chart_data['storage_usage']; ?>`);
			let usageDataset = Object.values(usageData);
			let delayed;

			let ctx = document.getElementById('chart-storage-dashboard');
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
								stepSize: 5000,
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
		});		
	</script>
@endsection