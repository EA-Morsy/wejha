/**
 * Section Items Manager - Vue Component for managing section items
 */
new Vue({
    el: '#section-items-app',
    delimiters: ['${', '}'],
    data: {
        items: [],
        modalItem: {
            type: '',
            content: {}
        },
        originalItem: null,
        editIndex: -1,
        isEdit: false,
        modalTitle: '',
        articleSearch: '',
        articleResults: [],
        articleTimeout: null,
        section_id: null,
        page_id: null,
        // Constants for API URLs
        urls: {
            store: '/admin/pages/{page_id}/sections/{section_id}/items',
            update: '/admin/pages/{page_id}/sections/{section_id}/items/{item_id}',
            delete: '/admin/pages/{page_id}/sections/{section_id}/items/{item_id}',
            search_articles: '/admin/articles/search'
        }
    },
    mounted() {
        // Get section items from the hidden input
        const itemsInput = document.getElementById('section-items-data');
        if (itemsInput && itemsInput.value) {
            try {
                this.items = JSON.parse(itemsInput.value);
                
                // Process items to ensure proper structure
                this.items.forEach(item => {
                    if (item.type === 'article' && item.content) {
                        // Make sure content.selected_articles exists
                        if (!item.content.selected_articles && item.content.article_ids) {
                            item.content.selected_articles = item.content.article_ids.map(id => {
                                return { id, text: 'مقالة ' + id };
                            });
                        } else if (!item.content.selected_articles) {
                            item.content.selected_articles = [];
                        }
                    }
                });
            } catch (e) {
                console.error('Error parsing section items', e);
                this.items = [];
            }
        }
        
        // Get section and page IDs
        const sectionIdInput = document.getElementById('section-id');
        const pageIdInput = document.getElementById('page-id');
        
        if (sectionIdInput) this.section_id = sectionIdInput.value;
        if (pageIdInput) this.page_id = pageIdInput.value;
        
        // Initialize sortable for reordering items
        if (this.items.length > 0) {
            this.initSortable();
        }
    },
    methods: {
        /**
         * Initialize sortable functionality for reordering items
         */
        initSortable() {
            const el = document.getElementById('items-table').querySelector('tbody');
            
            if (el) {
                new Sortable(el, {
                    handle: '.item-handle',
                    animation: 150,
                    onEnd: (evt) => {
                        // Get the new order from the DOM
                        const rows = Array.from(el.querySelectorAll('tr'));
                        const newItems = [];
                        
                        rows.forEach(row => {
                            const id = row.dataset.id;
                            const item = this.items.find(i => i.id == id);
                            if (item) newItems.push(item);
                        });
                        
                        this.items = newItems;
                        this.updateItemsInput();
                    }
                });
            }
        },
        
        /**
         * Update the hidden input with the current items data
         */
        updateItemsInput() {
            const input = document.getElementById('section-items-data');
            if (input) {
                input.value = JSON.stringify(this.items);
            }
        },
        
        /**
         * Open modal to add a new item
         */
        addItem() {
            this.isEdit = false;
            this.modalTitle = 'إضافة عنصر جديد';
            this.modalItem = {
                type: '',
                content: {}
            };
            this.editIndex = -1;
            this.articleSearch = '';
            this.articleResults = [];
            
            const modal = new bootstrap.Modal(document.getElementById('sectionItemModal'));
            modal.show();
        },
        
        /**
         * Open modal to edit an existing item
         */
        editItem(item, index) {
            this.isEdit = true;
            this.modalTitle = 'تعديل العنصر';
            this.editIndex = index;
            
            // Create a deep copy of the item to avoid modifying the original
            this.originalItem = JSON.parse(JSON.stringify(item));
            this.modalItem = JSON.parse(JSON.stringify(item));
            
            // Initialize content properly for different item types
            if (!this.modalItem.content) {
                this.modalItem.content = {};
            }
            
            // Initialize article specific properties
            if (this.modalItem.type === 'article') {
                if (!this.modalItem.content.selected_articles) {
                    this.modalItem.content.selected_articles = [];
                }
            }
            
            // Initialize list specific properties
            if (this.modalItem.type === 'list' && !this.modalItem.content.items) {
                this.modalItem.content.items = [];
            }
            
            const modal = new bootstrap.Modal(document.getElementById('sectionItemModal'));
            modal.show();
        },
        
        /**
         * Save the current modal item (add or update)
         */
        saveModalItem() {
            // For image type, get the file from the input
            if (this.modalItem.type === 'image' && this.$refs.imageInput && this.$refs.imageInput.files.length > 0) {
                // We'll handle file upload in the API request
            }
            
            if (this.isEdit) {
                this.updateItem();
            } else {
                // Add the new item to the items array
                this.items.push(this.modalItem);
                this.updateItemsInput();
                
                // Close the modal
                const modalEl = document.getElementById('sectionItemModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                
                // Show success message
                toastr.success('تمت إضافة العنصر بنجاح');
            }
        },
        
        /**
         * Update an existing item
         */
        updateItem() {
            if (!this.modalItem.id || !this.section_id || !this.page_id) {
                console.error('Missing required data for update');
                return;
            }
            
            const url = this.urls.update
                .replace('{page_id}', this.page_id)
                .replace('{section_id}', this.section_id)
                .replace('{item_id}', this.modalItem.id);
                
            const formData = new FormData();
            formData.append('_method', 'POST');
            formData.append('type', this.modalItem.type);
            
            // Handle different content types
            if (this.modalItem.type === 'image' && this.$refs.imageInput && this.$refs.imageInput.files.length > 0) {
                formData.append('image', this.$refs.imageInput.files[0]);
            } else if (this.modalItem.type === 'text') {
                formData.append('text', this.modalItem.content.text || '');
            } else if (this.modalItem.type === 'video') {
                formData.append('url', this.modalItem.content.url || '');
            } else if (this.modalItem.type === 'link') {
                formData.append('url', this.modalItem.content.url || '');
                formData.append('text', this.modalItem.content.text || '');
            } else if (this.modalItem.type === 'article') {
                const articleIds = this.modalItem.content.selected_articles.map(a => a.id);
                articleIds.forEach((id, index) => {
                    formData.append(`article_ids[${index}]`, id);
                });
            } else if (this.modalItem.type === 'list') {
                if (this.modalItem.content.items) {
                    this.modalItem.content.items.forEach((item, index) => {
                        formData.append(`items[${index}]`, item);
                    });
                }
            }
            
            // Send the update request
            axios.post(url, formData)
                .then(response => {
                    if (response.data.success) {
                        // Update the item in the items array
                        this.items[this.editIndex] = this.modalItem;
                        this.updateItemsInput();
                        
                        // Close the modal
                        const modalEl = document.getElementById('sectionItemModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();
                        
                        // Show success message
                        toastr.success('تم تحديث العنصر بنجاح');
                    } else {
                        toastr.error('حدث خطأ أثناء تحديث العنصر');
                    }
                })
                .catch(error => {
                    console.error('Error updating item', error);
                    toastr.error('حدث خطأ أثناء تحديث العنصر');
                });
        },
        
        /**
         * Delete an item
         */
        deleteItem(item, index) {
            if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                if (item.id) {
                    // Call API to delete the item
                    const url = this.urls.delete
                        .replace('{page_id}', this.page_id)
                        .replace('{section_id}', this.section_id)
                        .replace('{item_id}', item.id);
                    
                    axios.delete(url)
                        .then(response => {
                            if (response.data.success) {
                                this.items.splice(index, 1);
                                this.updateItemsInput();
                                toastr.success('تم حذف العنصر بنجاح');
                            } else {
                                toastr.error('حدث خطأ أثناء حذف العنصر');
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting item', error);
                            toastr.error('حدث خطأ أثناء حذف العنصر');
                        });
                } else {
                    // Remove from local array if it's a new item
                    this.items.splice(index, 1);
                    this.updateItemsInput();
                    toastr.success('تم حذف العنصر بنجاح');
                }
            }
        },
        
        /**
         * Search for articles
         */
        searchArticles() {
            if (this.articleTimeout) {
                clearTimeout(this.articleTimeout);
            }
            
            if (!this.articleSearch || this.articleSearch.length < 2) {
                this.articleResults = [];
                return;
            }
            
            this.articleTimeout = setTimeout(() => {
                axios.get(this.urls.search_articles, {
                    params: { q: this.articleSearch }
                })
                .then(response => {
                    this.articleResults = response.data.results || [];
                })
                .catch(error => {
                    console.error('Error searching articles', error);
                    this.articleResults = [];
                });
            }, 300);
        },
        
        /**
         * Select an article from search results
         */
        selectArticle(article) {
            if (!this.modalItem.content.selected_articles) {
                this.modalItem.content.selected_articles = [];
            }
            
            // Check if article already selected
            const exists = this.modalItem.content.selected_articles.some(a => a.id === article.id);
            if (!exists) {
                this.modalItem.content.selected_articles.push(article);
            }
            
            // Clear search
            this.articleSearch = '';
            this.articleResults = [];
        },
        
        /**
         * Remove a selected article
         */
        removeArticle(index) {
            this.modalItem.content.selected_articles.splice(index, 1);
        },
        
        /**
         * Add an empty item to the list for list type items
         */
        addListItem() {
            if (!this.modalItem.content.items) {
                this.modalItem.content.items = [];
            }
            this.modalItem.content.items.push('');
        },
        
        /**
         * Get the appropriate icon for an item type
         */
        getTypeIcon(type) {
            const icons = {
                'image': 'fa fa-image',
                'text': 'fa fa-font',
                'article': 'fa fa-newspaper-o',
                'video': 'fa fa-video-camera',
                'link': 'fa fa-link',
                'gallery': 'fa fa-images',
                'business': 'fa fa-briefcase',
                'list': 'fa fa-list'
            };
            
            return icons[type] || 'fa fa-question';
        }
    }
});
