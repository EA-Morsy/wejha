@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('industries.title') }}</title>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="layers" class="font-medium-2"></i>
                        <span>{{ __('industries.title') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                @can('industries.create')
                    <div class="dropdown">
                        <a class="btn btn-sm btn-outline-primary me-1 waves-effect" href="{{ route('admin.industries.create') }}">
                            <i data-feather="plus"></i>
                            <span class="active-sorting text-primary">{{ __('industries.actions.add') }}</span>
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-datatable">
                <table class="dt-multilingual table datatables-ajax">
                    <thead>
                    <tr>
                        <th>{{ __('industries.fields.name_ar') }}</th>
                        <th>{{ __('industries.fields.name_en') }}</th>
                        <th>{{ __('industries.fields.logo') }}</th>
                        <th>{{ __('industries.fields.is_active') }}</th>
                        @canany('industries.edit','industries.delete')
                        <th>{{ __('industries.fields.actions') }}</th>
                        @endcanany
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop
@push('scripts')
<script>
    var dt_ajax_table = $('.datatables-ajax');
    var dt_ajax = dt_ajax_table.DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        paging: true,
        info: false,
        lengthMenu: [[10, 50, 100,500, -1], [10, 50, 100,500, "الكل"]],
        language: {
            url: '{{ asset('assets/admin/datatable-lang.json') }}',
        },
        ajax: {
            url: '{{ route('admin.industries.list') }}',
            type: 'GET',
        },
        columns: [
            {data: 'name_ar', name: 'name_ar'},
            {data: 'name_en', name: 'name_en'},
            {data: 'logo', name: 'logo', orderable: false, searchable: false},
            {data: 'is_active', name: 'is_active', orderable: false, searchable: false},
            @canany('industries.edit','industries.delete')
            {data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center'},
            @endcanany
        ],
    });
</script>
@endpush
