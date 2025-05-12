@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('solutions.title') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.solutions.update', $item->id) : route('admin.solutions.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="briefcase" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('solutions.actions.edit') : __('solutions.actions.add') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm mb-2">
                        <div class="card-body">
                            <div class="row g-4 align-items-start">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="name_ar">{{ __('solutions.fields.name_ar') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name_ar" id="name_ar" class="form-control" value="{{ $item->name_ar ?? old('name_ar') }}" placeholder="أدخل اسم الحل بالعربية" required/>
                                    @error('name_ar')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="name_en">{{ __('solutions.fields.name_en') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name_en" id="name_en" class="form-control" value="{{ $item->name_en ?? old('name_en') }}" placeholder="Enter solution name in English" required/>
                                    @error('name_en')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="description_ar">{{ __('solutions.fields.description_ar') }}</label>
                                    <textarea name="description_ar" id="description_ar" class="form-control" rows="2" placeholder="أدخل وصف الحل بالعربية">{{ $item->description_ar ?? old('description_ar') }}</textarea>
                                    <small class="text-muted">اختياري</small>
                                    @error('description_ar')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="description_en">{{ __('solutions.fields.description_en') }}</label>
                                    <textarea name="description_en" id="description_en" class="form-control" rows="2" placeholder="Enter solution description in English">{{ $item->description_en ?? old('description_en') }}</textarea>
                                    <small class="text-muted">Optional</small>
                                    @error('description_en')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 d-flex flex-column align-items-center">
                                    <x-image-uploader
                                        name="image"
                                        :label="__('solutions.fields.image')"
                                        :current="isset($item) && $item->image ? $item->image : ''"
                                    />
                                    @error('image')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-4 align-items-center">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked($item->is_active ?? true )/>
                                        <label class="form-check-label fw-bold" for="is_active">{{ __('solutions.fields.is_active') }}</label>
                                    </div>
                                    @error('is_active')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 text-end">
                                    <button class="btn btn-primary px-4" type="submit">
                                        <i data-feather="save"></i>
                                        {{ isset($item) ? __('solutions.actions.edit') : __('solutions.actions.add') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
