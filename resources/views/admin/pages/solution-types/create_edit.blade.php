@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('solution_types.title') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data" id="jquery-val-form"
          action="{{ isset($item) ? route('admin.solution-types.update', $item->id) : route('admin.solution-types.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="tag" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('solution_types.actions.edit') : __('solution_types.actions.add') }}</span>
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
                <div class="col-md-10">
                    <div class="card shadow-sm mb-2">
                        <div class="card-body">
                            <div class="row g-4 align-items-start">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="name_ar">{{ __('solution_types.fields.name_ar') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name_ar" id="name_ar" class="form-control" value="{{ $item->name_ar ?? old('name_ar') }}" placeholder="أدخل اسم نوع الحل بالعربية" required/>
                                    @error('name_ar')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="name_en">{{ __('solution_types.fields.name_en') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name_en" id="name_en" class="form-control" value="{{ $item->name_en ?? old('name_en') }}" placeholder="Enter solution type name in English" required/>
                                    @error('name_en')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="form-label fw-bold" for="solution_id">{{ __('solution_types.fields.solution') }}</label>
                                    <select name="solution_id" id="solution_id" class="form-select">
                                        <option value="">{{ __('solution_types.fields.select_solution') }}</option>
                                        @foreach($solutions as $solution)
                                            <option value="{{ $solution->id }}" {{ (isset($item) && $item->solution_id == $solution->id) || old('solution_id') == $solution->id ? 'selected' : '' }}>
                                                {{ app()->isLocale('ar') ? $solution->name_ar : $solution->name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">{{ __('solution_types.fields.select_solution') }}</small>
                                    @error('solution_id')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 d-flex flex-column align-items-center">
                                    <x-image-uploader 
                                        name="icon"
                                        :current="isset($item) ? $item->icon : null"
                                        label="{{ __('solution_types.fields.icon') }}"
                                    />
                                    @error('icon')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-4 align-items-center">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked($item->is_active ?? true )/>
                                        <label class="form-check-label fw-bold" for="is_active">{{ __('solution_types.fields.is_active') }}</label>
                                    </div>
                                    @error('is_active')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 text-end">
                                    <button class="btn btn-primary px-4" type="submit">
                                        <i data-feather="save"></i>
                                        {{ isset($item) ? __('solution_types.actions.edit') : __('solution_types.actions.add') }}
                                    </button>
                                    <a href="{{ route('admin.solution-types.index') }}" class="btn btn-outline-secondary ms-2">{{ __('solution_types.actions.cancel') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
