@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('admin.rent_orders') }}</title>
@endsection
@section('content')
         <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="box" class="font-medium-2"></i>
                        <span>{{ __('admin.rent_orders') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                <div class="dropdown">
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card mt-2">
            <div class="card-body p-0">
                <table class="datatables-ajax table table-responsive">
                    <thead>
                    <tr>
                        <th>{{ __('orders.code') }}</th>
                        <th>{{ __('orders.status') }}</th>
                        <th>{{ __('orders.address') }}</th>
                        <th>{{ __('orders.phone') }}</th>
                        <th>{{ __('orders.delivery_phone') }}</th>
                        <th>{{ __('orders.total') }}</th>
                        <th>{{ __('orders.user') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('company.pages.orders.modal')
@stop
@push('scripts')
    <script>
        var dt_ajax_table = $('.datatables-ajax');
        var dt_ajax = dt_ajax_table.dataTable({
            processing: true,
            serverSide: true,
            searching: true,
            paging: true,
            info: true,
            order: [[0, 'desc']],
            language: {
                paginate: {
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            drawCallback: function( settings ) {
                feather.replace();
            },
            ajax: {
                url: "{{ route('admin.reports.rentOrders') }}",
                data: function (d) {
                }
            },
              drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                {data: 'code', name: 'code'},
                {data: 'statusText', name: 'status'},
                {data: 'address', name: 'address'},
                {data: 'phone', name: 'phone'},
                {data: 'delivery_phone', name: 'delivery_phone'},
                {data: 'total', name: 'total'},
                {data: 'user', name: 'user_id'},

            ]
        });
        $('.btn_filter').click(function (){
            dt_ajax.DataTable().ajax.reload();
        });

    </script>
@endpush
