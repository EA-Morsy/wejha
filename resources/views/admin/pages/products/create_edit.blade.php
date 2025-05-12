@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('products.title') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data" id="jquery-val-form"
          action="{{ isset($item) ? route('admin.products.update', $item->id) : route('admin.products.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('products.actions.edit') : __('products.actions.add') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary me-1">
                        <i data-feather="arrow-left"></i>
                        <span>{{ __('products.actions.cancel') }}</span>
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary waves-effect waves-float waves-light">
                        <i data-feather="save"></i>
                        <span>{{ __('products.actions.save') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('products.sections.basic_info') }}</h4>
                    <small class="text-muted">{{ __('products.notes.required_fields') }}</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="solution_type_id">
                                        {{ __('products.fields.solution_type_id') }} <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2-input @error('solution_type_id') is-invalid @enderror"
                                            id="solution_type_id" name="solution_type_id" required>
                                        <option value="">{{ __('products.placeholders.solution_type_id') }}</option>
                                        @foreach($solutionTypes as $solutionType)
                                            <option value="{{ $solutionType->id }}" 
                                                {{ (isset($item) && $item->solution_type_id == $solutionType->id) || old('solution_type_id') == $solutionType->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() == 'ar' ? $solutionType->name_ar : $solutionType->name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('solution_type_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="model">
                                        {{ __('products.fields.model') }}
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" id="model" class="form-control @error('model') is-invalid @enderror"
                                           name="model" placeholder="{{ __('products.placeholders.model') }}"
                                           value="{{ isset($item) ? $item->model : old('model') }}">
                                    @error('model')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="name_ar">
                                        {{ __('products.fields.name_ar') }} <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror"
                                           name="name_ar" placeholder="{{ __('products.placeholders.name_ar') }}"
                                           value="{{ isset($item) ? $item->name_ar : old('name_ar') }}" required>
                                    @error('name_ar')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="name_en">
                                        {{ __('products.fields.name_en') }} <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" id="name_en" class="form-control @error('name_en') is-invalid @enderror"
                                           name="name_en" placeholder="{{ __('products.placeholders.name_en') }}"
                                           value="{{ isset($item) ? $item->name_en : old('name_en') }}" required>
                                    @error('name_en')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="description_ar">
                                        {{ __('products.fields.description_ar') }}
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <textarea id="description_ar" class="form-control @error('description_ar') is-invalid @enderror"
                                              name="description_ar" placeholder="{{ __('products.placeholders.description_ar') }}" rows="4">{{ isset($item) ? $item->description_ar : old('description_ar') }}</textarea>
                                    @error('description_ar')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="description_en">
                                        {{ __('products.fields.description_en') }}
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <textarea id="description_en" class="form-control @error('description_en') is-invalid @enderror"
                                              name="description_en" placeholder="{{ __('products.placeholders.description_en') }}" rows="4">{{ isset($item) ? $item->description_en : old('description_en') }}</textarea>
                                    @error('description_en')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="image">
                                        {{ __('products.fields.image') }} <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <x-image-uploader 
                                        name="image"
                                        :current="isset($item) ? $item->image : null"
                                        label="{{ __('products.fields.image') }}"
                                        :required="!isset($item)"
                                    />
                                    <small class="text-muted">{{ __('products.notes.image_requirements') }}</small>
                                    @error('image')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="is_active">
                                        {{ __('products.fields.is_active') }}
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked($item->is_active ?? true) />
                                        <label class="form-check-label fw-bold" for="is_active">{{ __('products.active') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- المواصفات - Specifications -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('products.sections.specifications') }}</h4>
                </div>
                <div class="card-body">
                    <div id="specs-container">
                        @if(isset($item) && $item->specs->count() > 0)
                            @foreach($item->specs as $index => $spec)
                                <div class="spec-item mb-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-1">
                                                <label class="form-label">{{ __('products.fields.specs_key_ar') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="specs[{{ $index }}][key_ar]" 
                                                       placeholder="{{ __('products.placeholders.specs_key_ar') }}" 
                                                       value="{{ $spec->key_ar }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-1">
                                                <label class="form-label">{{ __('products.fields.specs_key_en') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="specs[{{ $index }}][key_en]" 
                                                       placeholder="{{ __('products.placeholders.specs_key_en') }}" 
                                                       value="{{ $spec->key_en }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-1">
                                                <label class="form-label">{{ __('products.fields.specs_value_ar') }}</label>
                                                <input type="text" class="form-control" name="specs[{{ $index }}][value_ar]" 
                                                       placeholder="{{ __('products.placeholders.specs_value_ar') }}" 
                                                       value="{{ $spec->value_ar }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-1">
                                                <label class="form-label">{{ __('products.fields.specs_value_en') }}</label>
                                                <input type="text" class="form-control" name="specs[{{ $index }}][value_en]" 
                                                       placeholder="{{ __('products.placeholders.specs_value_en') }}" 
                                                       value="{{ $spec->value_en }}">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="mb-1">
                                                <label class="form-label d-block">&nbsp;</label>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-spec">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="row mt-1">
                        <div class="col-12">
                            <button type="button" id="add-spec" class="btn btn-outline-primary btn-sm">
                                <i data-feather="plus"></i> {{ __('products.actions.add_spec') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معرض الصور - Gallery -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('products.sections.gallery') }}</h4>
                    <small class="text-muted">{{ __('products.notes.gallery') }}</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="gallery_images" class="form-label">{{ __('products.fields.gallery') }}</label>
                                <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml">
                                <small class="text-muted">{{ __('products.notes.gallery_requirements') }}</small>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($item) && $item->gallery->count() > 0)
                        <div class="row mt-2">
                            <div class="col-12">
                                <h5>{{ __('products.fields.gallery') }}</h5>
                            </div>
                            @foreach($item->gallery as $image)
                                <div class="col-md-3 mb-2">
                                    <div class="card">
                                        <img src="{{ asset($image->image) }}" class="card-img-top" alt="Gallery Image" style="height: 150px; object-fit: cover;">
                                        <div class="card-body p-1 text-center">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="delete_gallery[]" value="{{ $image->id }}" id="delete_gallery_{{ $image->id }}">
                                                <label class="form-check-label" for="delete_gallery_{{ $image->id }}">
                                                    {{ __('products.actions.remove_gallery') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- المنتجات ذات الصلة - Related Products -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('products.sections.related') }}</h4>
                    <small class="text-muted">{{ __('products.notes.related_products') }}</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="related_products" class="form-label">{{ __('products.fields.related_products') }}</label>
                                <select class="form-select select2-input" id="related_products" name="related_products[]" multiple>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                                {{ isset($item) && $item->relatedProducts->contains($product->id) ? 'selected' : '' }}>
                                            {{ app()->getLocale() == 'ar' ? $product->name_ar : $product->name_en }} ({{ $product->model }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let specIndex = {{ isset($item) ? $item->specs->count() : 0 }};
        
        // إضافة مواصفة جديدة
        $('#add-spec').on('click', function() {
            const template = `
                <div class="spec-item mb-2">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">{{ __('products.fields.specs_key_ar') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="specs[${specIndex}][key_ar]" 
                                       placeholder="{{ __('products.placeholders.specs_key_ar') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">{{ __('products.fields.specs_key_en') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="specs[${specIndex}][key_en]" 
                                       placeholder="{{ __('products.placeholders.specs_key_en') }}" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-1">
                                <label class="form-label">{{ __('products.fields.specs_value_ar') }}</label>
                                <input type="text" class="form-control" name="specs[${specIndex}][value_ar]" 
                                       placeholder="{{ __('products.placeholders.specs_value_ar') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">{{ __('products.fields.specs_value_en') }}</label>
                                <input type="text" class="form-control" name="specs[${specIndex}][value_en]" 
                                       placeholder="{{ __('products.placeholders.specs_value_en') }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="mb-1">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-spec">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#specs-container').append(template);
            feather.replace();
            specIndex++;
        });
        
        // حذف مواصفة
        $(document).on('click', '.remove-spec', function() {
            $(this).closest('.spec-item').remove();
        });
        
        // إذا لم يكن هناك مواصفات، أضف واحدة فارغة
        if ($('#specs-container').children().length === 0) {
            $('#add-spec').click();
        }
    });
</script>
@endpush
