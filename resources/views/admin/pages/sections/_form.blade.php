<form action="{{ isset($item) ? route('admin.pages.sections.update', [$page->id, $item->id]) : route('admin.pages.sections.store', $page->id) }}" method="POST">
    @csrf
    @if(isset($item))
        @method('POST')
    @endif
    <div class="mb-3">
        <label for="section_type" class="form-label">{{ __('sections.fields.section_type') }}</label>
        <select name="section_type" id="section_type" class="form-control" required>
            @foreach(\App\Models\Section::getSectionTypes() as $key => $label)
                <option value="{{ $key }}" {{ old('section_type', $item->section_type ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="title_ar" class="form-label">{{ __('sections.fields.title_ar') }}</label>
        <input type="text" name="title_ar" id="title_ar" class="form-control" value="{{ old('title_ar', $item->title_ar ?? '') }}">
    </div>
    <div class="mb-3">
        <label for="title_en" class="form-label">{{ __('sections.fields.title_en') }}</label>
        <input type="text" name="title_en" id="title_en" class="form-control" value="{{ old('title_en', $item->title_en ?? '') }}">
    </div>
    <div class="mb-3">
        <label for="description_ar" class="form-label">{{ __('sections.fields.description_ar') }}</label>
        <textarea name="description_ar" id="description_ar" class="form-control" rows="2">{{ old('description_ar', $item->description_ar ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label for="description_en" class="form-label">{{ __('sections.fields.description_en') }}</label>
        <textarea name="description_en" id="description_en" class="form-control" rows="2">{{ old('description_en', $item->description_en ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label for="content_ar" class="form-label">{{ __('sections.fields.content_ar') }}</label>
        <textarea name="content_ar" id="content_ar" class="form-control rich-editor" rows="6">{{ old('content_ar', $item->content_ar ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label for="content_en" class="form-label">{{ __('sections.fields.content_en') }}</label>
        <textarea name="content_en" id="content_en" class="form-control rich-editor" rows="6">{{ old('content_en', $item->content_en ?? '') }}</textarea>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">{{ __('sections.fields.image') }}</label>
        <input type="text" name="image" id="image" class="form-control" value="{{ old('image', $item->image ?? '') }}">
    </div>
    <div class="mb-3">
        <label for="order" class="form-label">{{ __('sections.fields.order') }}</label>
        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $item->order ?? 0) }}" required>
    </div>
    <div class="mb-3">
        <label for="settings" class="form-label">{{ __('sections.fields.settings') }}</label>
        <textarea name="settings" id="settings" class="form-control" rows="2">{{ old('settings', isset($item) ? json_encode($item->settings) : '') }}</textarea>
        <small class="text-muted">{{ __('sections.hints.settings_json') }}</small>
    </div>
    <div class="mb-3">
        <label for="is_active" class="form-label">{{ __('sections.fields.is_active') }}</label>
        <select name="is_active" id="is_active" class="form-select" required>
            <option value="1" {{ old('is_active', $item->is_active ?? 1) == 1 ? 'selected' : '' }}>{{ __('sections.status.active') }}</option>
            <option value="0" {{ old('is_active', $item->is_active ?? 1) == 0 ? 'selected' : '' }}>{{ __('sections.status.inactive') }}</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">
        <i class="fa fa-save"></i> {{ __('sections.actions.save') }}
    </button>
    <a href="{{ route('admin.pages.sections.index', $page->id) }}" class="btn btn-secondary">{{ __('sections.actions.cancel') }}</a>
</form>
