@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('products.title') }}</title>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="box" class="font-medium-2"></i>
                        <span>{{ __('products.title') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                @can('products.create')
                    <div class="dropdown">
                        <a class="btn btn-sm btn-outline-primary me-1 waves-effect" href="{{ route('admin.products.create') }}">
                            <i data-feather="plus"></i>
                            <span class="active-sorting text-primary">{{ __('products.actions.add') }}</span>
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
                        <th>{{ __('products.fields.model') }}</th>
                        <th>{{ __('products.fields.name_ar') }}</th>
                        <th>{{ __('products.fields.name_en') }}</th>
                        <th>{{ __('products.fields.image') }}</th>
                        <th>{{ __('products.fields.solution_type_id') }}</th>
                        <th>{{ __('products.fields.is_active') }}</th>
                        @canany('products.edit','products.delete')
                            <th width="15%" class="text-center">{{ __('products.actions_label') }}</th>
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
                url: "{{ route('admin.products.list') }}",
                data: function (d) {
                    // يمكن إضافة فلاتر هنا مستقبلاً
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                {data: 'model', name: 'model',orderable: false},
                {data: 'name_ar', name: 'name_ar',orderable: false},
                {data: 'name_en', name: 'name_en',orderable: false},
                {data: 'image', name: 'image', orderable: false, searchable: false},
                {data: 'solution_name', name: 'solution_type.name_ar',orderable: false},
                {data: 'is_active', name: 'is_active'},
                
                @canany('products.edit','products.delete')
                {data: 'actions',name: 'actions',orderable: false,searchable: false},
                @endcanany
            ],
            columnDefs: [
                @canany('products.edit','products.delete')
                {
                    "targets": -1,
                    "render": function (data, type, row) {
                        var editUrl = '{{ route("admin.products.edit", ":id") }}';
                        editUrl = editUrl.replace(':id', row.id);

                        var deleteUrl = '{{ route("admin.products.destroy", ":id") }}';
                        deleteUrl = deleteUrl.replace(':id', row.id);

                        return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @can('products.edit')
                        <a class="dropdown-item" href="`+editUrl+`">
                                        <i data-feather="edit-2" class="font-medium-2"></i>
                                            <span>{{ __('products.actions.edit') }}</span>
                                        </a>
                                        @endcan
                        @can('products.delete')
                        <a class="dropdown-item delete_item" data-url="`+deleteUrl+`" href="#">
                                            <i data-feather="trash" class="font-medium-2"></i>
                                             <span>{{ __('products.actions.delete') }}</span>
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
        $('.btn_filter').click(function (){
            dt_ajax.DataTable().ajax.reload();
        });
    </script>
@endpush
