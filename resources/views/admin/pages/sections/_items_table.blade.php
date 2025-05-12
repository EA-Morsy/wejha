<div class="section-items-wrapper">
    <table class="table section-items-table" id="items-table">
        <thead>
            <tr>
                <th class="handle-col text-center"><i class="fa fa-arrows-alt text-muted"></i></th>
                <th class="type-col">{{ __('sections.items.type') }}</th>
                <th class="content-col">{{ __('sections.items.content') }}</th>
                <th class="actions-col text-center">{{ __('sections.items.actions') }}</th>
            </tr>
        </thead>
        <tbody is="sortable-list">
            <tr v-for="(item, idx) in items" :key="item.id || idx" :data-id="item.id" class="item-row">
                <!-- Handle Column -->
                <td class="handle-col cursor-move text-center">
                    <div class="item-handle"><i class="fa fa-bars text-muted"></i></div>
                </td>
                
                <!-- Type Column -->
                <td class="type-col">
                    <div class="type-badge" :class="'type-badge-' + item.type">
                        <i :class="getTypeIcon(item.type)"></i>
                        <span class="type-label" v-text="item.type"></span>
                    </div>
                </td>
                
                <!-- Content Column -->
                <td class="content-col">
                    <!-- Image Content -->
                    <div v-if="item.type === 'image'" class="content-container">
                        <img :src="item.content.url" alt="" class="content-image">
                    </div>
                    
                    <!-- Text Content -->
                    <div v-else-if="item.type === 'text'" class="content-container">
                        <div class="content-preview">
                            <span v-text="item.content.text"></span>
                        </div>
                    </div>
                    
                    <!-- Article Content -->
                    <div v-else-if="item.type === 'article'" class="content-container">
                        <div v-if="item.content && item.content.selected_articles && item.content.selected_articles.length > 0" class="article-container">
                            <div class="article-info">
                                <div class="article-title" v-text="item.content.selected_articles[0].text"></div>
                                <div v-if="item.content.selected_articles.length > 1" class="article-badge">
                                    <span v-text="'+' + (item.content.selected_articles.length - 1)"></span>
                                </div>
                            </div>
                        </div>
                        <div v-else class="article-container">
                            <div class="article-info">
                                <div class="article-title" v-text="item.content && item.content.title ? item.content.title : 'مقالة'"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Video Content -->
                    <div v-else-if="item.type === 'video'" class="content-container">
                        <div class="content-preview">
                            <i class="fa fa-video-camera"></i>
                            <span v-text="item.content.url"></span>
                        </div>
                    </div>
                    
                    <!-- Link Content -->
                    <div v-else-if="item.type === 'link'" class="content-container">
                        <div class="content-preview">
                            <i class="fa fa-link"></i>
                            <span v-text="item.content.url"></span>
                        </div>
                    </div>
                </td>
                
                <!-- Actions Column -->
                <td class="actions-col text-center">
                    <div class="action-buttons">
                        <button class="btn btn-edit" @click.prevent="editItem(item, idx)" title="{{ __('sections.items.edit') }}">
                            <i class="fa fa-edit"></i> تعديل
                        </button>
                        <button class="btn btn-delete" @click.prevent="deleteItem(item, idx)" title="{{ __('sections.items.delete') }}">
                            <i class="fa fa-trash"></i> حذف
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
