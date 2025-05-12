@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('solution_types.title') }}</title>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="tag" class="font-medium-2"></i>
                        <span>{{ __('solution_types.title') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                @can('solution_types.create')
                    <div class="dropdown">
                        <a class="btn btn-sm btn-outline-primary me-1 waves-effect" href="{{ route('admin.solution-types.create') }}">
                            <i data-feather="plus"></i>
                            <span class="active-sorting text-primary">{{ __('solution_types.actions.add') }}</span>
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-datatable">
                <table class="dt-multilingual table datatables-ajax" style="width:100%">
                    <thead>
                    <tr>
                        <th>{{ __('solution_types.fields.name_ar') }}</th>
                        <th>{{ __('solution_types.fields.name_en') }}</th>
                        <th>{{ __('solution_types.fields.solution') }}</th>
                        <th>{{ __('solution_types.fields.icon') }}</th>
                        <th>{{ __('solution_types.fields.is_active') }}</th>
                        @canany('solution_types.edit','solution_types.delete')
                            <th width="15%" class="text-center">{{ __('solution_types.actions_label') }}</th>
                        @endcanany
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<style>
    .table thead tr th {
        position: relative;
    }
    .table th.sorting:after,
    .table th.sorting:before {
        position: absolute;
        display: block;
        opacity: 0.25;
        right: 0.7rem;
    }
    .table th.sorting:before {
        content: '▲';
        top: 0.9rem;
    }
    .table th.sorting:after {
        content: '▼';
        bottom: 0.9rem;
    }
    .table th.sorting_asc:before {
        opacity: 1;
    }
    .table th.sorting_desc:after {
        opacity: 1;
    }
</style>
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
            search: "بحث : ",
            lengthMenu: "عرض _MENU_ عنصر",
            info: "إظهار _START_ إلى _END_ من أصل _TOTAL_ عنصر",
            infoEmpty: "لا يوجد نتائج",
            zeroRecords: "لا توجد بيانات مطابقة",
            loadingRecords: "جاري التحميل...",
            processing: "جاري المعالجة...",
            emptyTable: "لا توجد بيانات لعرضها",
            paginate: {
                previous: 'السابق',
                next: 'التالي',
                first: 'الأول',
                last: 'الأخير'
            },
            aria: {
                sortAscending: ": تفعيل لترتيب العمود تصاعدياً",
                sortDescending: ": تفعيل لترتيب العمود تنازلياً"
            }
        },
        ajax: {
            url: "{{ route('admin.solution-types.list') }}",
            data: function (d) {
                // يمكن إضافة فلاتر هنا مستقبلاً
            }
        },
        drawCallback: function (settings) {
            feather.replace();
        },
        columns: [
            /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
            {data: 'name_ar', name: 'name_ar', orderable: false},
            {data: 'name_en', name: 'name_en', orderable: false},
            {data: 'solution_name', name: 'solution_name', orderable: false, searchable: false},
            {data: 'icon', name: 'icon', orderable: false, searchable: false},
            {data: 'is_active', name: 'is_active'},
            @canany('solution_types.edit','solution_types.delete')
            {data: 'actions', name: 'actions', orderable: false, searchable: false},
            @endcanany
        ],
        order: [],
        dom: '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4"<"dt-action-buttons text-end"B>><"col-sm-12 col-md-4"f>><"table-responsive"t><"d-flex justify-content-between mx-2 row mb-1"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        buttons: [],
        columnDefs: [
            { className: "text-center", targets: [3] },
            @canany('solution_types.edit','solution_types.delete')
            {
                "targets": -1,
                "render": function (data, type, row) {
                    var editUrl = '{{ route("admin.solution-types.edit", ":id") }}';
                    editUrl = editUrl.replace(':id', row.id);

                    var deleteUrl = '{{ route("admin.solution-types.destroy", ":id") }}';
                    deleteUrl = deleteUrl.replace(':id', row.id);

                    return `
                           <div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                        <i data-feather="more-vertical" class="font-medium-2"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @can('solution_types.edit')
                    <a class="dropdown-item" href="`+editUrl+`">
                                        <i data-feather="edit-2" class="font-medium-2"></i>
                                            <span>{{ __('solution_types.actions.edit') }}</span>
                                        </a>
                                        @endcan
                    @can('solution_types.delete')
                    <a class="dropdown-item delete_item" data-url="`+deleteUrl+`" href="#">
                                            <i data-feather="trash" class="font-medium-2"></i>
                                             <span>{{ __('solution_types.actions.delete') }}</span>
                                        </a>
                                        @endcan
                    </div>
               </div>
                `;  
                }
            }
            @endcanany
        ],
    });
</script>
@endpush
