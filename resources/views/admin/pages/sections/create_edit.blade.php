@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">
        {{ isset($item) ? __('sections.actions.edit') : __('sections.actions.create') }}
        <small class="text-muted">({{ $page->title }})</small>
    </h4>
    @include('admin.pages.partials.flash')
    <div class="card">
        <div class="card-body">
            @include('admin.pages.sections._form', ['page' => $page, 'item' => $item ?? null])
        </div>
    </div>
    @if(isset($item))
        <hr>
        <h5 class="mb-3">{{ __('sections.items.title') }}</h5>
        <div id="section-items-app">
            <div class="mb-3">
                <button class="btn btn-success" @click.prevent="addItem">
                    <i class="fa fa-plus"></i> {{ __('sections.items.add_item') }}
                </button>
            </div>
            @include('admin.pages.sections._items_table')

            @include('admin.pages.sections._item_modal')
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Main container */
    .section-items-wrapper {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-top: 20px;
        margin-bottom: 20px;
    }
    
    /* Table styles */
    .section-items-table {
        margin-bottom: 0;
        table-layout: fixed;
        width: 100%;
    }
    .section-items-table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 12px 15px;
        font-size: 14px;
        font-weight: 600;
        color: #495057;
    }
    .section-items-table td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }
    .section-items-table .item-row:hover {
        background-color: #f8f9fa;
    }
    
    /* Column widths */
    .handle-col {
        width: 50px;
    }
    .type-col {
        width: 120px;
    }
    .content-col {
        width: auto;
    }
    .actions-col {
        width: 100px;
    }
    
    /* Handle column */
    .item-handle {
        cursor: move;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        margin: 0 auto;
    }
    .item-handle i {
        color: #adb5bd;
    }
    .item-handle:hover {
        background-color: #e9ecef;
    }
    .item-handle:hover i {
        color: #495057;
    }
    
    /* Type badges */
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 30px;
        width: auto;
        white-space: nowrap;
    }
    .type-badge i {
        margin-right: 8px;
        font-size: 14px;
    }
    .type-label {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .type-badge-image {
        background-color: #e8f4f8;
        color: #0d6efd;
    }
    .type-badge-text {
        background-color: #f0f8f1;
        color: #198754;
    }
    .type-badge-article {
        background-color: #feeceb;
        color: #dc3545;
    }
    .type-badge-video {
        background-color: #fff3cd;
        color: #fd7e14;
    }
    .type-badge-link {
        background-color: #e9ecef;
        color: #6c757d;
    }
    .type-badge-gallery {
        background-color: #e7f1ff;
        color: #0dcaf0;
    }
    .type-badge-business {
        background-color: #f2ebff;
        color: #6f42c1;
    }
    .type-badge-list {
        background-color: #f0f9ff;
        color: #0d6efd;
    }
    
    /* Content containers */
    .content-container {
        max-width: 100%;
        padding: 5px 0;
    }
    
    /* Image content */
    .content-image {
        max-width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #dee2e6;
    }
    
    /* Content preview for text and URLs */
    .content-preview {
        max-height: 60px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        font-size: 13px;
        color: #495057;
        line-height: 1.5;
        max-width: 600px;
    }
    .content-preview i {
        color: #6c757d;
        margin-right: 5px;
    }
    
    /* Article styles */
    .article-container {
        width: 100%;
    }
    .article-info {
        display: flex;
        align-items: center;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .article-title {
        font-weight: 500;
        color: #333;
        font-size: 14px;
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
    }
    .article-badge {
        margin-left: 10px;
        font-size: 11px;
        font-weight: 600;
        color: #fff;
        background-color: #dc3545;
        border-radius: 50%;
        min-width: 20px;
        height: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
    }
    
    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }
    .btn-edit, .btn-delete {
        min-width: 70px;
        height: 34px;
        padding: 0 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        border: none;
        transition: all 0.2s;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
    .btn-edit {
        background-color: #e9f2ff;
        color: #0d6efd;
    }
    .btn-delete {
        background-color: #feeceb;
        color: #dc3545;
    }
    .btn-edit:hover {
        background-color: #0d6efd;
        color: #ffffff;
    }
    .btn-delete:hover {
        background-color: #dc3545;
        color: #ffffff;
    }
    .btn-edit i, .btn-delete i {
        margin-right: 5px;
    }
    /* Make actions column wider */
    .actions-col {
        width: 180px;
    }
</style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-select@3.20.2"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vue-select@3.20.2/dist/vue-select.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.24.3/vuedraggable.umd.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
    <script>
        CKEDITOR.editorConfig = function( config ) {
            config.language = '{{ app()->getLocale() }}';
            config.height = 200;
            config.removeButtons = '';
        };
        var editor = CKEDITOR.replaceAll('rich-editor');
    </script>
    <script>
    Vue.component('v-select', VueSelect.VueSelect);
    @if(isset($item))
    const sectionItemsApp = new Vue({
        el: '#section-items-app',
        delimiters: ['@{{', '}}'],  // Set custom delimiters to avoid conflicts with Blade
        data: function() {
            return {
                items: @json($item->items ?? []),
                modalTitle: '', 
                modalItem: {type: '', content: {}},
                articleResults: [], 
                businessResults: [],
                businessSearch: ''
            };
        },
        mounted() {
            console.log('Vue sectionItemsApp mounted');
            // Process article data to ensure proper structure
            this.items.forEach(item => {
                if (item.type === 'article' && item.content) {
                    // Ensure content is an object
                    if (typeof item.content === 'string') {
                        try {
                            item.content = JSON.parse(item.content);
                        } catch (e) {
                            console.error('Failed to parse item content', e);
                            // Set a default structure
                            item.content = { title: item.content };
                        }
                    }
                    
                    // Initialize selected_articles if missing
                    if (!item.content.selected_articles && item.content.article_ids) {
                        item.content.selected_articles = item.content.article_ids.map(id => {
                            return {
                                id: id,
                                text: item.content.title || 'مقالة ' + id
                            };
                        });
                    }
                }
            });
        },
        methods: {
            getTypeIcon(type) {
                // تحديد الأيقونة المناسبة لكل نوع
                switch(type) {
                    case 'image':
                        return 'fa fa-image';
                    case 'text':
                        return 'fa fa-align-left';
                    case 'article':
                        return 'fa fa-newspaper-o';
                    case 'video':
                        return 'fa fa-video-camera';
                    case 'link':
                        return 'fa fa-link';
                    case 'gallery':
                        return 'fa fa-images';
                    case 'business':
                        return 'fa fa-building';
                    case 'list':
                        return 'fa fa-list';
                    default:
                        return 'fa fa-file';
                }
            },
            
            editItem(item, idx) {
                this.modalTitle = "{{ __('sections.items.edit_item') }}";
                // Create a deep copy of the item to avoid direct references
                this.modalItem = JSON.parse(JSON.stringify(item));
                
                // Ensure proper structure for different content types
                if (this.modalItem.type === 'article' && this.modalItem.content) {
                    if (!this.modalItem.content.selected_articles) {
                        this.modalItem.content.selected_articles = [];
                    }
                }
                
                // Show the modal
                $('#sectionItemModal').modal('show');
            },
            
            openAddModal() {
                this.modalTitle = "{{ __('sections.items.add_item') }}";
                this.modalItem = {
                    type: '',
                    content: {
                        selected_articles: [], // كائنات المقالات المختارة مع النص والمعرف
                    },
                };
                this.articleResults = [];
                this.businessResults = [];
                $('#sectionItemModal').modal('show');
            },
            onTypeChange() {
                if(this.modalItem.type === 'list') {
                    this.modalItem.content.items = this.modalItem.content.items || [''];
                } else if(this.modalItem.type === 'gallery') {
                    this.modalItem.content.images = [];
                }
            },
            addListItem() {
                this.modalItem.content.items.push('');
            },
            removeListItem(idx) {
                this.modalItem.content.items.splice(idx, 1);
            },
            searchArticles(search, loading) {
                if (!search || search.length < 2) {
                    this.articleResults = [];
                    return;
                }
                loading(true);
                console.log('Searching for:', search);
                axios.get('/admin/pages/articles/search', { params: { q: search } })
                    .then(res => {
                        console.log('API Response:', res.data);
                        // الآن البيانات ترجع كمصفوفة مباشرة بدون كلمة 'results'
                        this.articleResults = res.data || [];
                        console.log('Assigned articleResults:', this.articleResults);
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                        this.articleResults = [];
                    })
                    .finally(() => loading(false));
            },
            searchBusinesses() {
                if(this.businessSearch.length < 2) return;
                axios.get('/admin/pages/businesses/search', {params: {q: this.businessSearch}})
                    .then(res => { this.businessResults = res.data.results; });
            },
            saveModalItem() {
                let formData = new FormData();
                formData.append('type', this.modalItem.type);
                
                // Check if it's an update or new item
                const isUpdating = !!this.modalItem.id;
                if (isUpdating) {
                    formData.append('_method', 'PUT');
                    formData.append('id', this.modalItem.id);
                }
                
                // Handle image upload
                if(this.modalItem.type === 'image') {
                    let fileInput = this.$refs.imageInput;
                    // If editing an item with existing image, the file input may be empty
                    if (isUpdating && (!fileInput || fileInput.files.length === 0)) {
                        // Using existing image, no need to validate
                    } else if (fileInput && fileInput.files.length > 0) {
                        formData.append('image', fileInput.files[0]);
                    } else {
                        toastr.error('يرجى اختيار صورة');
                        return;
                    }
                }
                
                // Handle gallery upload
                if(this.modalItem.type === 'gallery') {
                    let galleryInput = this.$refs.galleryInput;
                    // If editing an item with existing gallery, the file input may be empty
                    if (isUpdating && (!galleryInput || galleryInput.files.length === 0)) {
                        // Using existing gallery images, no need to validate
                    } else if (galleryInput && galleryInput.files.length > 0) {
                        for(let i=0; i<galleryInput.files.length; i++) {
                            formData.append('gallery[]', galleryInput.files[i]);
                        }
                    } else {
                        toastr.error('يرجى اختيار صور للمعرض');
                        return;
                    }
                }
                
                // Validate article selection
                if (this.modalItem.type === 'article' && (!this.modalItem.content.selected_articles || this.modalItem.content.selected_articles.length === 0)) {
                    toastr.error('يرجى اختيار مقالة واحدة على الأقل');
                    return;
                }
                
                // Process content for non-file items
                if(this.modalItem.type !== 'image' && this.modalItem.type !== 'gallery') {
                    // Process selected articles
                    if (this.modalItem.type === 'article' && this.modalItem.content.selected_articles) {
                        console.log('المقالات المختارة:', this.modalItem.content.selected_articles);
                        this.modalItem.content.article_ids = this.modalItem.content.selected_articles.map(article => article.id);
                    }
                    formData.append('content', JSON.stringify(this.modalItem.content));
                }
                
                // Determine the URL for submission
                let url;
                if (isUpdating) {
                    // Use the direct URL to the update endpoint
                    url = `{{ url('/admin/pages/sections/items') }}/${this.modalItem.id}`;
                } else {
                    url = "{{ route('admin.pages.sections.items.store', [$page->id, $item->id]) }}";
                }
                
                // Submit the form
                axios.post(url, formData, {
                    headers: {'Content-Type': 'multipart/form-data'}
                }).then(res => {
                    if(res.data.success) {
                        if (isUpdating) {
                            // Find and update the existing item
                            const index = this.items.findIndex(item => item.id === this.modalItem.id);
                            if (index !== -1) {
                                this.items.splice(index, 1, res.data.item);
                                toastr.success('تم تحديث العنصر بنجاح');
                            }
                        } else {
                            // Add the new item
                            this.items.push(res.data.item);
                            toastr.success('تمت إضافة العنصر بنجاح');
                        }
                        $('#sectionItemModal').modal('hide');
                    }
                }).catch(error => {
                    console.error('Error saving item:', error);
                    toastr.error('حدث خطأ أثناء حفظ العنصر');
                });
            },
            deleteItem(item, idx) {
                if(item.id && confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                    // Use the direct URL for delete
                    axios.delete(`{{ url('/admin/pages/sections/items') }}/${item.id}`)
                        .then(res => {
                            if(res.data.success) {
                                this.items.splice(idx, 1);
                                toastr.success('تم حذف العنصر');
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting item:', error);
                            toastr.error('حدث خطأ أثناء حذف العنصر');
                        });
                } else if(!item.id) {
                    this.items.splice(idx, 1);
                }
            },
        },
    });
    @endif
    </script>
@endpush
