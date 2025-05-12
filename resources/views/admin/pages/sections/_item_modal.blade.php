<div class="modal fade" id="sectionItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@{{ modalTitle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="section-item-form" @submit.prevent="saveModalItem">
                    <div class="mb-3">
                        <label for="item-type" class="form-label">{{ __('sections.items.type') }}</label>
                        <select v-model="modalItem.type" id="item-type" class="form-select" required>
                            <option value="" disabled>{{ __('sections.items.select_type') }}</option>
                            <option value="text">{{ __('sections.items.types.text') }}</option>
                            <option value="image">{{ __('sections.items.types.image') }}</option>
                            <option value="gallery">{{ __('sections.items.types.gallery') }}</option>
                            <option value="article">{{ __('sections.items.types.article') }}</option>
                            <option value="video">{{ __('sections.items.types.video') }}</option>
                            <option value="link">{{ __('sections.items.types.link') }}</option>
                            <option value="business">{{ __('sections.items.types.business') }}</option>
                            <option value="list">{{ __('sections.items.types.list') }}</option>
                        </select>
                    </div>
                    
                    <!-- Image Content -->
                    <div v-if="modalItem.type === 'image'" class="mb-3">
                        <label for="item-image" class="form-label">{{ __('sections.items.image') }}</label>
                        <input type="file" ref="imageInput" class="form-control" id="item-image" accept="image/*">
                    </div>
                    
                    <!-- Gallery Content -->
                    <div v-if="modalItem.type === 'gallery'" class="mb-3">
                        <label for="item-gallery" class="form-label">{{ __('sections.items.gallery') }}</label>
                        <input type="file" ref="galleryInput" class="form-control" id="item-gallery" accept="image/*" multiple>
                    </div>
                    
                    <!-- Text Content -->
                    <div v-if="modalItem.type === 'text'" class="mb-3">
                        <label for="item-text" class="form-label">{{ __('sections.items.text') }}</label>
                        <textarea v-model="modalItem.content.text" id="item-text" class="form-control" rows="4"></textarea>
                    </div>
                    
                    <!-- Video Content -->
                    <div v-if="modalItem.type === 'video'" class="mb-3">
                        <label for="item-video" class="form-label">{{ __('sections.items.video_url') }}</label>
                        <input type="url" v-model="modalItem.content.url" id="item-video" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    
                    <!-- Link Content -->
                    <div v-if="modalItem.type === 'link'" class="mb-3">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="item-link-url" class="form-label">{{ __('sections.items.link_url') }}</label>
                                <input type="url" v-model="modalItem.content.url" id="item-link-url" class="form-control" placeholder="https://...">
                            </div>
                            <div class="col-md-4">
                                <label for="item-link-text" class="form-label">{{ __('sections.items.link_text') }}</label>
                                <input type="text" v-model="modalItem.content.text" id="item-link-text" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Article Content -->
                    <div v-if="modalItem.type === 'article'" class="mb-3">
                        <label for="item-article" class="form-label">{{ __('sections.items.articles') }}</label>
                        <div class="mb-2">
                            <div class="input-group">
                                <input type="text" v-model="articleSearch" class="form-control" placeholder="{{ __('sections.items.search_articles') }}" @input="searchArticles">
                                <button type="button" class="btn btn-outline-secondary" disabled><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div v-if="articleResults.length" class="article-search-results">
                            <div v-for="article in articleResults" :key="article.id" class="article-result-item" @click="selectArticle(article)">
                                @{{ article.text }}
                            </div>
                        </div>
                        <div v-if="modalItem.content.selected_articles && modalItem.content.selected_articles.length" class="selected-articles mt-2">
                            <div v-for="(article, idx) in modalItem.content.selected_articles" :key="article.id" class="selected-article-item">
                                <span class="article-title">@{{ article.text }}</span>
                                <button type="button" class="btn btn-sm btn-outline-danger" @click.prevent="removeArticle(idx)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- List Content -->
                    <div v-if="modalItem.type === 'list'" class="mb-3">
                        <label class="form-label">{{ __('sections.items.list_items') }}</label>
                        <div v-for="(item, idx) in modalItem.content.items" :key="idx" class="mb-2 list-item-row">
                            <div class="input-group">
                                <input type="text" v-model="modalItem.content.items[idx]" class="form-control">
                                <button type="button" class="btn btn-outline-danger" @click.prevent="modalItem.content.items.splice(idx, 1)"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" @click.prevent="addListItem">
                            <i class="fa fa-plus"></i> {{ __('sections.items.add_list_item') }}
                        </button>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('sections.actions.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('sections.actions.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
