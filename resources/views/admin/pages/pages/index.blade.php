@extends('admin.layouts.master')

@section('title')
    <title>{{ config('app.name') }} | {{ __('pages.title') }}</title>
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="file-text" class="font-medium-2"></i>
                        <span>{{ __('pages.title') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                @can('pages.create')
                    <a class="btn btn-sm btn-outline-primary me-1 waves-effect" href="{{ route('admin.pages.create') }}">
                        <i data-feather="plus"></i>
                        <span class="active-sorting text-primary">{{ __('pages.actions.create') }}</span>
                    </a>
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
                        <th>{{ __('pages.fields.title') }}</th>
                        <th>{{ __('pages.fields.slug') }}</th>
                        <th>{{ __('pages.fields.type') }}</th>
                        <th>{{ __('pages.fields.status') }}</th>
                        <th>{{ __('pages.fields.created_at') }}</th>
                        @canany('pages.edit','pages.delete','sections.view')
                            <th width="20%" class="text-center">{{ __('pages.actions.actions') }}</th>
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
            url: "{{ route('admin.pages.list') }}",
            type: 'GET'
        },
        drawCallback: function (settings) {
            feather.replace();
        },
        columns: [
            { data: 'title', name: 'title' },
            { data: 'slug', name: 'slug' },
            { data: 'type', name: 'type' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            @canany('pages.edit','pages.delete','sections.view')
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' },
            @endcanany
        ],
        columnDefs: [
            @canany('pages.edit','pages.delete','sections.view')
            {
                "targets": -1,
                "render": function (data, type, row) {
                    var showUrl = '{{ route("admin.pages.show", ":id") }}';
                    showUrl = showUrl.replace(':id', row.id);
                    var editUrl = '{{ route("admin.pages.edit", ":id") }}';
                    editUrl = editUrl.replace(':id', row.id);
                    var deleteUrl = '{{ route("admin.pages.destroy", ":id") }}';
                    deleteUrl = deleteUrl.replace(':id', row.id);
                    var sectionsUrl = '{{ route("admin.pages.sections.index", ":id") }}';
                    sectionsUrl = sectionsUrl.replace(':id', row.id);
                    let actions = `<div class="dropdown">
                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                            <i data-feather="more-vertical" class="font-medium-2"></i>
                        </button>
                        <div class="dropdown-menu">
                            @can('pages.view')
                            <a class="dropdown-item" href="`+showUrl+`">
                                <i data-feather="eye" class="font-medium-2 text-info"></i>
                                <span>{{ __('pages.actions.show') }}</span>
                            </a>
                            @endcan
                            @can('pages.edit')
                            <a class="dropdown-item" href="`+editUrl+`">
                                <i data-feather="edit-2" class="font-medium-2 text-warning"></i>
                                <span>{{ __('pages.actions.edit') }}</span>
                            </a>
                            @endcan
                            @can('sections.view')
                            <a class="dropdown-item" href="`+sectionsUrl+`">
                                <i data-feather="list" class="font-medium-2 text-primary"></i>
                                <span>{{ __('sections.actions.sections') }}</span>
                            </a>
                            @endcan
                            @can('pages.delete')
                            <form action="`+deleteUrl+`" method="POST" style="display:inline;" onsubmit="return confirm('{{ __('pages.actions.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item w-100">
                                    <i data-feather="trash-2" class="font-medium-2 text-danger"></i>
                                    <span>{{ __('pages.actions.delete') }}</span>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>`;
                    return actions;
                }
            }
            @endcanany
        ]
    });
</script>
@endpush
