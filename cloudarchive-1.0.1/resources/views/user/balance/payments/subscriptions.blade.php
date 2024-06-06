@extends('layouts.app')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('My Subscriptions') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-badge-dollar mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.plans') }}"> {{ __('My Balance') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('My Subscriptions') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Subscriptions List') }}</h3>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='mySubscriptionsTable' class='table' width='100%'>
							<thead>
								<tr>
									<th width="10%">{{ __('Plan Name') }}</th>
									<th width="10%">{{ __('Status') }}</th>
									<th width="10%">{{ __('Subscribed On') }}</th>											
									<th width="10%">{{ __('Subscription ID') }}</th>
									<th width="10%">{{ __('Paid By') }}</th>					
									<th width="10%">{{ __('Next Payment') }}</th>
									<th width="5%">{{ __('Actions') }}</th>
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
	<script type="text/javascript">
		$(function () {

			"use strict";
			
			// INITILIZE DATATABLE
			var table = $('#mySubscriptionsTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				colReorder: true,
				"order": [[ 0, "desc" ]],
				language: {
					"emptyTable": "<div><br>You don't have any subscriptions yet</div>",
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
				ajax: "{{ route('user.balance.subscriptions') }}",
				columns: [{
						data: 'custom-plan-name',
						name: 'custom-plan-name',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-status',
						name: 'custom-status',
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
						data: 'custom-subscription-id',
						name: 'custom-subscription-id',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-gateway',
						name: 'custom-gateway',
						orderable: true,
						searchable: true
					},					
					{
						data: 'custom-until',
						name: 'custom-until',
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


			// CANCEL SUBSCRIPTION
			$(document).on('click', '.cancelSubscriptionButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Subscription Cancellation') }}',
					text: '{{ __('It will cancel this subscription plan going forward') }}',
					icon: 'warning',
					showCancelButton: true,
					cancelButtonText: '{{ __('No Way') }}',
					confirmButtonText: '{{ __('Yes, I want to Cancel') }}',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'subscriptions/cancel',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{__('Subscription Cancelled')}}', '{{__('Your subscription plan has been successfully cancelled')}}', 'success');	
									$("#mySubscriptionsTable").DataTable().ajax.reload();								
								} else {
									Swal.fire('Cancellation Failed', '{{__('There was an error while cancelling this subscription')}}', 'error');
								}      
							},
							error: function(data) {
								Swal.fire('Oops...','Something went wrong!', 'error')
							}
						})
					} 
				})
			});

		});
	</script>
@endsection