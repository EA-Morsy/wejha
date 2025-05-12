@push('styles')
<style>
    .dropdown-menu .dropdown-item.w-100:hover,
    .dropdown-menu .dropdown-item.w-100:focus {
        background-color: #f8d7da !important;
        color: #842029 !important;
        width: 100%;
    }
    .dropdown-menu .dropdown-item.w-100:hover i,
    .dropdown-menu .dropdown-item.w-100:focus i {
        color: #842029 !important;
        width: 100%;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.css" />
<style>
    .sortable-ghost {
        opacity: 0.5;
    }
    .sortable-chosen {
        background: #f8f9fa;
    }
</style>
@endpush

@extends('admin.layouts.master')

@section('title')
    <title>{{ config('app.name') }} | {{ __('sections.plural') }}</title>
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="layers" class="font-medium-2"></i>
                        <span>{{ __('sections.plural') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                @can('sections.create')
                    <div class="dropdown">
                        <a class="btn btn-sm btn-outline-primary me-1 waves-effect" href="{{ route('admin.pages.sections.create', $page->id) }}">
                            <i data-feather="plus"></i>
                            <span class="active-sorting text-primary">{{ __('sections.actions.create') }}</span>
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-datatable">
                <table id="sections-table" class="dt-multilingual table datatables-ajax">
                    <thead>
                    <tr>
                        <th>{{ __('sections.fields.id') }}</th>
                        <th>{{ __('sections.fields.order') }}</th>
                        <th>{{ __('sections.fields.title_ar') }}</th>
                        <th>{{ __('sections.fields.title_en') }}</th>
                        <th>{{ __('sections.fields.section_type') }}</th>
                        <th>{{ __('sections.fields.is_active') }}</th>
                        @canany('sections.edit','sections.delete')
                            <th width="15%" class="text-center">{{ __('sections.actions.actions') }}</th>
                        @endcanany
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
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
        },
        ajax: {
            url: "{{ route('admin.pages.sections.list', $page->id) }}",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'order', name: 'order' },
            { data: 'title_ar', name: 'title_ar' },
            { data: 'title_en', name: 'title_en' },
            { data: 'section_type', name: 'section_type' },
            { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
            @canany('sections.edit','sections.delete')
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' },
            @endcanany
        ],
        rowCallback: function(row, data) {
            $(row).attr('data-id', data.id);
        },
        columnDefs: [
            @canany('sections.edit','sections.delete')
            {
                "targets": -1,
                "render": function (data, type, row) {
                    var editUrl = '{{ route("admin.pages.sections.edit", [':page_id', ':id']) }}';
                    editUrl = editUrl.replace(':page_id', row.page_id).replace(':id', row.id);

                    var deleteUrl = '{{ route("admin.pages.sections.destroy", [':page_id', ':id']) }}';
                    deleteUrl = deleteUrl.replace(':page_id', row.page_id).replace(':id', row.id);

                    return `
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                <i data-feather="more-vertical" class="font-medium-2"></i>
                            </button>
                            <div class="dropdown-menu">
                                @can('sections.edit')
                                <a class="dropdown-item" href="`+editUrl+`">
                                    <i data-feather="edit-2" class="font-medium-2"></i>
                                    <span>{{ __('sections.actions.edit') }}</span>
                                </a>
                                @endcan
                                @can('sections.delete')
                                <a class="dropdown-item delete_item" data-url="`+deleteUrl+`" href="#">
                                    <i data-feather="trash" class="font-medium-2"></i>
                                    <span>{{ __('sections.actions.delete') }}</span>
                                </a>
                                @endcan
                            </div>
                        </div>
                    `;
                }
            }
            @endcanany
        ],
        drawCallback: function(settings) {
            if (window.feather) {
                feather.replace();
            }
        }
    });
    function enableSectionSorting() {
        var tableBody = document.querySelector('#sections-table tbody');
        if (!tableBody) return;
        new Sortable(tableBody, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function (evt) {
                let order = [];
                tableBody.querySelectorAll('tr').forEach(function (row, idx) {
                    order.push({
                        id: row.getAttribute('data-id'),
                        order: idx
                    });
                });
                fetch("{{ route('admin.pages.sections.sort', $page->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({order: order})
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        toastr.success(data.message || 'تم تحديث الترتيب بنجاح');
                        dt_ajax.ajax.reload(null, false);
                    }
                });
            }
        });
    }
    $(document).ready(function(){
        $('#sections-table').on('draw.dt', function(){
            enableSectionSorting();
        });
        enableSectionSorting();
    });
</script>
@endpush
