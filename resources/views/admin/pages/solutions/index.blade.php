@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('solutions.title') }}</title>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="briefcase" class="font-medium-2"></i>
                        <span>{{ __('solutions.title') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                @can('solutions.create')
                    <div class="dropdown">
                        <a class="btn btn-sm btn-outline-primary me-1 waves-effect" href="{{ route('admin.solutions.create') }}">
                            <i data-feather="plus"></i>
                            <span class="active-sorting text-primary">{{ __('solutions.actions.add') }}</span>
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
                        <th>{{ __('solutions.fields.name_ar') }}</th>
                        <th>{{ __('solutions.fields.name_en') }}</th>
                        <th>{{ __('solutions.fields.image') }}</th>
                        <th>{{ __('solutions.fields.is_active') }}</th>
                        @canany('solutions.edit','solutions.delete')
                            <th width="15%" class="text-center">{{ __('solutions.actions_label') }}</th>
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
                url: "{{ route('admin.solutions.list') }}",
                data: function (d) {
                    // يمكن إضافة فلاتر هنا مستقبلاً
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                {data: 'name_ar', name: 'name_ar',orderable: false},
                {data: 'name_en', name: 'name_en',orderable: false},
                {data: 'image', name: 'image', orderable: false, searchable: false},
                {data: 'is_active', name: 'is_active'},
                @canany('solutions.edit','solutions.delete')
                {data: 'actions',name: 'actions',orderable: false,searchable: false},
                @endcanany
            ],
            columnDefs: [
                @canany('solutions.edit','solutions.delete')
                {
                    "targets": -1,
                    "render": function (data, type, row) {
                        var editUrl = '{{ route("admin.solutions.edit", ":id") }}';
                        editUrl = editUrl.replace(':id', row.id);

                        var deleteUrl = '{{ route("admin.solutions.destroy", ":id") }}';
                        deleteUrl = deleteUrl.replace(':id', row.id);

                        return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @can('solutions.edit')
                        <a class="dropdown-item" href="`+editUrl+`">
                                        <i data-feather="edit-2" class="font-medium-2"></i>
                                            <span>{{ __('solutions.actions.edit') }}</span>
                                        </a>
                                        @endcan
                        @can('solutions.delete')
                        <a class="dropdown-item delete_item" data-url="`+deleteUrl+`" href="#">
                                            <i data-feather="trash" class="font-medium-2"></i>
                                             <span>{{ __('solutions.actions.delete') }}</span>
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
