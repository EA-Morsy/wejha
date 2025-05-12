@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ isset($item) ? __('articles.actions.edit') : __('articles.actions.add') }}</title>
@endsection
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h1 class="bold mb-0 mt-1 text-dark">
                    <i data-feather="file-text" class="font-medium-2"></i>
                    <span>{{ isset($item) ? __('articles.actions.edit') : __('articles.actions.add') }}</span>
                </h1>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="{{ isset($item) ? route('admin.articles.update', $item->id) : route('admin.articles.store') }}">
                @csrf
                @if(isset($item))
                    @method('POST')
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label>{{ __('articles.fields.title_ar') }}</label>
                            <input type="text" name="title_ar" class="form-control" value="{{ old('title_ar', $item->title_ar ?? '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label>{{ __('articles.fields.title_en') }}</label>
                            <input type="text" name="title_en" class="form-control" value="{{ old('title_en', $item->title_en ?? '') }}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label>{{ __('articles.fields.description_ar') }}</label>
                            <textarea name="description_ar" class="form-control">{{ old('description_ar', $item->description_ar ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label>{{ __('articles.fields.description_en') }}</label>
                            <textarea name="description_en" class="form-control">{{ old('description_en', $item->description_en ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label>{{ __('articles.fields.type') }}</label>
                            <select name="type" class="form-control" required>
                                <option value="">-- {{ __('articles.fields.type') }} --</option>
                                <option value="events" {{ old('type', $item->type ?? '') == 'events' ? 'selected' : '' }}>{{ __('articles.types.events') }}</option>
                                <option value="news" {{ old('type', $item->type ?? '') == 'news' ? 'selected' : '' }}>{{ __('articles.types.news') }}</option>
                                <option value="blogs" {{ old('type', $item->type ?? '') == 'blogs' ? 'selected' : '' }}>{{ __('articles.types.blogs') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            @php
                                $imageLabel = __('articles.fields.image');
                                $currentImage = isset($item) ? $item->image : null;
                            @endphp
                            @component('components.image-uploader', [
                                'label' => $imageLabel,
                                'name' => 'image',
                                'current' => $currentImage
                            ])
                            @endcomponent
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-2">
                            <label>{{ __('articles.fields.content_ar') }}</label>
                            <textarea name="content_ar" class="form-control editor" rows="8">{{ old('content_ar', $item->content_ar ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-2">
                            <label>{{ __('articles.fields.content_en') }}</label>
                            <textarea name="content_en" class="form-control editor" rows="8">{{ old('content_en', $item->content_en ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ old('is_active', $item->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">{{ __('articles.fields.is_active') }}</label>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">
                        {{ isset($item) ? __('articles.actions.edit') : __('articles.actions.add') }}
                    </button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
    <script>
        CKEDITOR.editorConfig = function( config ) {
            config.language = '{{ app()->getLocale() }}';
            config.height = 200;
            config.removeButtons = '';
        };
        var editor = CKEDITOR.replaceAll('editor');
    </script>
@endpush
