@extends('layouts.app')

@section('css')
	<link href="{{URL::asset('plugins/tippy/scale-extreme.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/tippy/material.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER-->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Admin Dashboard') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-chart-tree-map mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Dashboard') }}</a></li>
			</ol>
		</div>
	</div>
	<!--END PAGE HEADER -->
@endsection

@section('content')						
	<!-- TOP BOX INFO -->
	<div class="row">
		<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div>
							<p class=" mb-3 fs-12 font-weight-bold">{{ __('Total New Users') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
							<h2 class="mb-0"><span class="number-font fs-20">{{ number_format($total_data_monthly['new_users_current_month'][0]['data']) }}</span><span class="ml-2 text-muted fs-11 data-percentage-change"><span id="users_change"></span> {{ __('this month') }}</span></h2>

						</div>
						<span class="fs-40 mt-m1"><i class="fa-solid fa-user-check"></i></span>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1">{{ __('Last Month') }}</span>
							<span class="number-font fs-12"><i class="fa fa-chain mr-1 text-success"></i>{{ number_format($total_data_monthly['new_users_past_month'][0]['data']) }}</span>
						</div>
						<div class="ml-auto">
							<span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total') }})</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-success"></i>{{ number_format($total_data_yearly['total_new_users'][0]['data']) }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div>
							<p class=" mb-3 fs-12 font-weight-bold">{{ __('Total New Subscribers') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
							<h2 class="mb-0"><span class="number-font fs-20">{{ number_format($total_data_monthly['new_subscribers_current_month'][0]['data']) }}</span><span class="ml-2 text-muted fs-11 data-percentage-change"><span id="subscribers_change"></span> {{ __('this month') }}</span></h2>
						</div>
						<span class="text-info fs-40 mt-m1"><i class="fa-solid fa-user-tie-hair"></i></span>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1">{{ __('Last Month') }}</span>
							<span class="number-font fs-12"><i class="fa fa-chain mr-1 text-success"></i>{{ number_format($total_data_monthly['new_subscribers_past_month'][0]['data']) }}</span>
						</div>
						<div class="ml-auto">
							<span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total') }})</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-success"></i>{{ number_format($total_data_yearly['total_new_subscribers'][0]['data']) }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div>
							<p class=" mb-3 fs-12 font-weight-bold">{{ __('Total Income') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
							<h2 class="mb-0"><span class="number-font fs-20">{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$total_data_monthly['income_current_month'][0]['data'], 2) }}</span><span class="ml-2 text-muted fs-11 data-percentage-change"><span id="income_change"></span> {{ __('this month') }}</span></h2>
						</div>
						<span class="text-success fs-40 mt-m1"><i class="fa-solid fa-badge-dollar"></i></span>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1">{{ __('Last Month') }}</span>
							<span class="number-font fs-12"><i class="fa fa-chain mr-1 text-success"></i>{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$total_data_monthly['income_past_month'][0]['data'], 2) }}</span>
						</div>
						<div class="ml-auto">
							<span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total') }})</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-success"></i>{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$total_data_yearly['total_income'][0]['data'], 2) }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div>
							<p class=" mb-3 fs-12 font-weight-bold">{{ __('Total Estimated Spending') }} <span class="text-muted">({{ __('Current Month') }})</span></p>
							<h2 class="mb-0"><span class="number-font fs-20">${{ number_format((float)$total_data_monthly['spending_current_month'], 2, '.', '') }}</span><span class="ml-2 text-muted fs-11 data-percentage-change"><span id="spending_change"></span> {{ __('this month') }}</span></h2>
						</div>
						<span class="text-secondary fs-40 mt-m1"><i class="fa-solid fa-badge-percent"></i></span>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1">{{ __('Last Month') }}</span>
							<span class="number-font fs-12"><i class="fa fa-chain mr-1 text-danger"></i>${{ number_format((float)$total_data_monthly['spending_past_month'], 2, '.', '') }}</span>
						</div>
						<div class="ml-auto">
							<span class="text-muted fs-12 mr-1">{{ __('Current Year') }} ({{ __('Total') }})</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-danger"></i>${{ number_format((float)$total_data_yearly['total_spending'], 2, '.', '') }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card mb-4 border-0">
				<div class="card-header d-inline border-0">
					<div>
						<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-hard-drive mr-4 text-info"></i>{{ __('Total Storage Usage ') }}</h3>
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
						<div class="progress-bar progress-bar-striped progress-bar-animated zip-bar" role="progressbar" style="width: {{ $progress['glacier'] }}%" aria-valuemin="0" aria-valuemax="100"></div>
						<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning documents-bar" role="progressbar" style="width: {{ $progress['deep_archive'] }}%" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<div class="progress-info d-flex overflow-x-auto">
						<div class="label mr-5">
							<span class="label-dot zip"></span>
							<b class="label-title">{{ __('Glacier Storage') }}</b>
						</div>
						<div class="label mr-5">
							<span class="label-dot documents"></span>
							<b class="label-title">{{ __('Deep Archive Storage') }}</b>
						</div>
						<div class="label mr-5">
							<span class="label-dot empty"></span>
							<b class="label-title">{{ __('Not Used') }}</b>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6 col-md-12 mt-3">
			<div class="card overflow-hidden border-0">
				<div class="card-header d-inline border-0">
					<div>
						<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-money-check-dollar-pen mr-4 text-info"></i>{{ __('Finance Overview') }}</h3>
					</div>
					<div class="row">
						<div class="col-xl-4 col-md-4 col-sm-12">
							<div>
								<h3 class="card-title fs-24 font-weight-800">{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$total_data_yearly['total_income'][0]['data'], 2, '.', '') }}</h3>
							</div>
							<div class="mb-3">
								<span class="fs-12 text-muted">{{ __('Total Earnings Current Year') }}</span>
							</div>
						</div>
						<div class="col-xl-4 col-md-4 col-sm-12">
							<div>
								<h3 class="card-title fs-24 font-weight-800">${{ number_format((float)$total_data_yearly['total_spending'], 2, '.', '') }}</h3>
							</div>
							<div class="mb-3">
								<span class="fs-12 text-muted">{{ __('Total Estimated Spending Current Year') }}</span>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="">
								<canvas id="chart-total-income" class="h-330"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-6 col-md-12 mt-3">
			<div class="card overflow-hidden border-0">
				<div class="card-header d-inline border-0">
					<div>
						<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-users-viewfinder mr-4 text-info"></i>{{ __('Total New Users ') }}</h3>
					</div>
					<div>
						<h3 class="card-title fs-24 font-weight-800">{{ number_format($total_data_yearly['total_new_users'][0]['data']) }}</h3>
					</div>
					<div class="mb-3">
						<span class="fs-12 text-muted">{{ __('Total New Registered Users Current Year') }}</span>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="">
								<canvas id="chart-total-users-year" class="h-330"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6 col-md-12 mt-3">
			<div class="card overflow-hidden border-0">
				<div class="card-header d-inline border-0">
					<div>
						<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-money-check-pen mr-4 text-info"></i>{{ __('Latest Registrations ') }}</h3>
						<a href="{{ route('admin.user.list') }}" class="" id="return-sound" data-tippy-content="{{ __('View All Registered Users') }}."><i class="fa-solid fa-bring-front"></i></a>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<table class="table" id="database-backup">
								<thead>
									<tr role="row">
									<th class="fs-12 font-weight-700 border-top-0">{{ __('User') }}</th>
									<th class="fs-12 font-weight-700 border-top-0">{{ __('Group') }}</th>
									<th class="fs-12 font-weight-700 border-top-0">{{ __('Status') }}</th>
									<th class="fs-12 font-weight-700 border-top-0">{{ __('Registered On') }}</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($result as $data)
										<tr>
										<td>@if ($data->profile_photo_path)
												<div class="d-flex">
													<div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="{{ $data->profile_photo_path }}"></div>
													<div class="widget-user-name"><span class="font-weight-bold">{{ $data->name }}</span><br><span class="text-muted">{{ $data->email }}</span></div>
												</div>
											@else
												<div class="d-flex">
													<div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" class="rounded-circle" src="{{ URL::asset('img/users/avatar.png') }}"></div>
													<div class="widget-user-name"><span class="font-weight-bold">{{ $data->name }}</span><br><span class="text-muted">{{ $data->email }}</span></div>
												</div>
										@endif</td>
										<td><span class="cell-box user-group-{{ $data->group }}">{{ ucfirst($data->group) }}</span></td>
										<td><span class="cell-box user-{{ $data->status }}">{{ ucfirst($data->status) }}</span></td>
										<td><span class="font-weight-bold">{{ date_format($data->created_at, 'd M Y') }}</span></td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-6 col-md-12 mt-3">
			<div class="card overflow-hidden border-0">
				<div class="card-header d-inline border-0">
					<div>
						<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-money-bill-transfer mr-4 text-info"></i>{{ __('Latest Transactions ') }}</h3>
						<a href="{{ route('admin.finance.transactions') }}" class="" id="return-sound" data-tippy-content="{{ __('View All Transactions') }}."><i class="fa-solid fa-bring-front"></i></a>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<table class="table" id="database-backup">
								<thead>
									<tr role="row">
									<th class="fs-12 font-weight-700 border-top-0" width="10%">{{ __('Paid By') }}</th>
									<th class="fs-12 font-weight-700 border-top-0" width="10%">{{ __('Status') }}</th>
									<th class="fs-12 font-weight-700 border-top-0" width="10%">{{ __('Total') }}</th>
									<th class="fs-12 font-weight-700 border-top-0" width="10%">{{ __('Gateway') }}</th>
									<th class="fs-12 font-weight-700 border-top-0" width="10%">{{ __('Paid On') }}</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($transaction as $data)
										<tr>
										<td>@if ($data->profile_photo_path)
												<div class="d-flex">
													<div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="{{ $data->profile_photo_path }}"></div>
													<div class="widget-user-name"><span class="font-weight-bold">{{ $data->name }}</span><br><span class="text-muted">{{ $data->email }}</span></div>
												</div>
											@else
												<div class="d-flex">
													<div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" class="rounded-circle" src="{{ URL::asset('img/users/avatar.png') }}"></div>
													<div class="widget-user-name"><span class="font-weight-bold">{{ $data->name }}</span><br><span class="text-muted">{{ $data->email }}</span></div>
												</div>
										@endif</td>
										<td><span class="cell-box payment-{{ strtolower($data->status) }}">{{ ucfirst($data->status) }}</span></td>
										<td><span class="font-weight-bold">{!! config('payment.default_system_currency_symbol') !!}{{ $data->price }}</span></td>
										<td>@if ($data->gateway == 'PayPal')
												<img alt="PayPal Gateway" class="w-60" src="{{ URL::asset('img/payments/paypal.svg') }}">
											@elseif ($data->gateway == 'Stripe')
												<img alt="Stripe Gateway" class="w-40" src="{{ URL::asset('img/payments/stripe.svg') }}">
											@elseif ($data->gateway == 'Razorpay')
												<img alt="Razorpay Gateway" class="w-60" src="{{ URL::asset('img/payments/razorpay.svg') }}"> 
											@elseif ($data->gateway == 'Paystack')
												<img alt="Paystack Gateway" class="w-60" src="{{ URL::asset('img/payments/paystack.svg') }}">	
											@elseif ($data->gateway == 'BankTransfer')
												<img alt="BankTransfer Gateway" class="w-60" src="{{ URL::asset('img/payments/bank-transfer.png') }}">
										@endif
										</td>
										<td><span class="font-weight-bold">{{ date_format($data->created_at, 'd M Y') }}</span></td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 mt-3">
			<div class="card overflow-hidden border-0">
				<div class="card-header d-inline border-0">
					<div>
						<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-users mr-4 text-info"></i>{{ __('New Registered Users') }}</h3>
					</div>
					<div class="mb-3">
						<span class="fs-12 text-muted">{{ __('Registered Users Current Month') }}</span>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="">
								<canvas id="chart-total-users-month" class="h-330"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<!-- Chart JS -->
	<script src="{{URL::asset('plugins/chart/chart.min.js')}}"></script>
	<script src="{{URL::asset('plugins/tippy/popper.min.js')}}"></script>
	<script src="{{URL::asset('plugins/tippy/tippy-bundle.umd.min.js')}}"></script>
	<script type="text/javascript">
		$(function() {

			"use strict";
			
			// Total Income Chart
			var incomeData = JSON.parse(`<?php echo $chart_data['total_income']; ?>`);
			var incomeDataset = Object.values(incomeData);
			var ctx = document.getElementById('chart-total-income');
			let delayed;

			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					datasets: [{
						label: '{{ __('Total Income') }} ({{ config('payment.default_system_currency') }}) ',
						data: incomeDataset,
						backgroundColor: '#FF9D00',
						borderWidth: 1,
						borderRadius: 20,
						barPercentage: 0.8,
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
								stepSize: 40,
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
						}
					},
					plugins: {
						tooltip: {
							cornerRadius: 10,
							xPadding: 10,
							yPadding: 10,
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

			// Total New User Analysis Chart
			var userMonthlyData = JSON.parse(`<?php echo $chart_data['monthly_new_users']; ?>`);
			var userMonthlyDataset = Object.values(userMonthlyData);
			var ctx = document.getElementById('chart-total-users-month');
			let delayed1;

			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
					datasets: [{
						label: '{{ __('New Registered Users') }} ',
						data: userMonthlyDataset,
						backgroundColor: '#007bff',
						borderWidth: 1,
						borderRadius: 20,
						barPercentage: 0.7,
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
							delayed1 = true;
						},
						delay: (context) => {
							let delay = 0;
							if (context.type === 'data' && context.mode === 'default' && !delayed1) {
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
								stepSize: 40,
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
						}
					},
					plugins: {
						tooltip: {
							cornerRadius: 10,
							xPadding: 10,
							yPadding: 10,
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

			// Total New User Analysis Chart
			var userYearlyData = JSON.parse(`<?php echo $chart_data['total_new_users']; ?>`);
			var userYearlyDataset = Object.values(userYearlyData);
			var ctx = document.getElementById('chart-total-users-year');
			let delayed3;

			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					datasets: [{
						label: '{{ __('Total New Registered Users') }} ',
						data: userYearlyDataset,
						backgroundColor: '#1e1e2d',
						borderWidth: 1,
						borderRadius: 20,
						barPercentage: 0.8,
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
							delayed3 = true;
						},
						delay: (context) => {
							let delay = 0;
							if (context.type === 'data' && context.mode === 'default' && !delayed3) {
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
								stepSize: 40,
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
						}
					},
					plugins: {
						tooltip: {
							cornerRadius: 10,
							xPadding: 10,
							yPadding: 10,
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

			// Percentage Difference First Row
			var users_current_month = JSON.parse(`<?php echo $percentage['users_current']; ?>`);	
			var users_past_month = JSON.parse(`<?php echo $percentage['users_past']; ?>`);
			var subscribers_current_month = JSON.parse(`<?php echo $percentage['subscribers_current']; ?>`);	
			var subscribers_past_month = JSON.parse(`<?php echo $percentage['subscribers_past']; ?>`);
			var income_current_month = JSON.parse(`<?php echo $percentage['income_current']; ?>`);	
			var income_past_month = JSON.parse(`<?php echo $percentage['income_past']; ?>`);
			var spending_current_month = JSON.parse(`<?php echo $percentage['spending_current']; ?>`);	
			var spending_past_month = JSON.parse(`<?php echo $percentage['spending_past']; ?>`);
			
			
			(users_current_month[0]['data'] == null) ? users_current_month = 0 : users_current_month = users_current_month[0]['data'];
			(users_past_month[0]['data'] == null) ? users_past_month = 0 : users_past_month = users_past_month[0]['data'];
			(subscribers_current_month[0]['data'] == null) ? subscribers_current_month = 0 : subscribers_current_month = subscribers_current_month[0]['data'];
			(subscribers_past_month[0]['data'] == null) ? subscribers_past_month = 0 : subscribers_past_month = subscribers_past_month[0]['data'];
			(income_current_month[0]['data'] == null) ? income_current_month = 0 : income_current_month = income_current_month[0]['data'];
			(income_past_month[0]['data'] == null) ? income_past_month = 0 : income_past_month = income_past_month[0]['data'];
			(spending_current_month == null) ? spending_current_month = 0.0 : spending_current_month = spending_current_month;
			(spending_past_month == null) ? spending_past_month = 0.0 : spending_past_month = spending_past_month;

			var users_current_total = parseInt(users_current_month);
			var users_past_total = parseInt(users_past_month);
			var subscribers_current_total = parseInt(subscribers_current_month);
			var subscribers_past_total = parseInt(subscribers_past_month);
			var income_current_total = parseInt(income_current_month);
			var income_past_total = parseInt(income_past_month);

			var users_change = mainPercentageDifference(users_past_month, users_current_month);
			var subscribers_change = mainPercentageDifference(subscribers_past_month, subscribers_current_month);
			var income_change = mainPercentageDifference(income_past_month, income_current_month);
			var spending_change = mainPercentageDifference(spending_past_month, spending_current_month);
			
			document.getElementById('users_change').innerHTML = users_change;
			document.getElementById('subscribers_change').innerHTML = subscribers_change;
			document.getElementById('income_change').innerHTML = income_change;
			document.getElementById('spending_change').innerHTML = spending_change;


			function characterPercentageDifference(past, current) {
				if (past == 0) {
					var change = (current == 0) ? '<span class="text-muted"> 0% No Change</span>' : '<span class="text-success"> 100% Increase</span>';   					
					return change;
				} else if(current == 0) {
					var change = (past == 0) ? '<span class="text-muted"> 0% No Change</span>' : '<span class="text-danger"> 100% Decrease</span>';
					return change;
				} else if(past == current) {
					var change = '<span class="text-muted"> 0% No Change</span>';
					return change; 
				}

				var difference = current - past;
    			var difference_value, result;

				var totalDifference = Math.abs(difference);
				var change = (totalDifference/past) * 100;				

				if (difference > 0) { result = '<span class="text-success">' + change.toFixed(1) + '% Increase</span>'; }
				else if(difference < 0) {result = '<span class="text-danger">' + change.toFixed(1) + '% Decrease</span>'; }
				else { difference_value = '<span class="text-muted">' + change.toFixed(1) + '% No Change</span>'; }				

				return result;
			}

			function mainPercentageDifference(past, current) {
				if (past == 0) {
					var change = (current == 0) ? '<span class="text-muted"> 0%</span>' : '<span class="text-success"><i class="fa fa-caret-up"></i> 100%</span>';   					
					return change;
				} else if(current == 0) {
					var change = (past == 0) ? '<span class="text-muted"> 0%</span>' : '<span class="text-danger"><i class="fa fa-caret-down"></i> 100%</span>';
					return change;
				} else if(past == current) {
					var change = '<span class="text-muted"> 0%</span>';
					return change; 
				}

				var difference = current - past;
    			var difference_value, result;

				var totalDifference = Math.abs(difference);
				var change = (totalDifference/past) * 100;				

				if (difference > 0) { result = '<span class="text-success"><i class="fa fa-caret-up"></i> ' + change.toFixed(1) + '%</span>'; }
				else if(difference < 0) {result = '<span class="text-danger"><i class="fa fa-caret-down"></i> ' + change.toFixed(1) + '%</span>'; }
				else { difference_value = '<span class="text-muted"> ' + change.toFixed(1) + '%</span>'; }				

				return result;
			}

			tippy('[data-tippy-content]', {
				animation: 'scale-extreme',
				theme: 'material',
			});
		});
	</script>
@endsection