@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('pages.title') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data" id="jquery-val-form"
          action="{{ isset($item) ? route('admin.pages.update', $item->id) : route('admin.pages.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="file-text" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('pages.actions.edit') : __('pages.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right"></div>
            </div>
        </div>
        <div class="content-body">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm mb-2">
                        <div class="card-body">
                            <div class="row g-4 align-items-start">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="title">{{ __('pages.fields.title') }}</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ $item->title ?? old('title') }}" required/>
                                    @error('title')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="slug">{{ __('pages.fields.slug') }}</label>
                                    <input type="text" name="slug" id="slug" class="form-control" value="{{ $item->slug ?? old('slug') }}" required/>
                                    @error('slug')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="type">{{ __('pages.fields.type') }}</label>
                                    <select name="type" id="type" class="form-select" required>
                                        <option value="static" {{ old('type', $item->type ?? '') == 'static' ? 'selected' : '' }}>{{ __('pages.types.static') }}</option>
                                        <option value="dynamic" {{ old('type', $item->type ?? '') == 'dynamic' ? 'selected' : '' }}>{{ __('pages.types.dynamic') }}</option>
                                    </select>
                                    @error('type')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="status">{{ __('pages.fields.status') }}</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="published" {{ old('status', $item->status ?? '') == 'published' ? 'selected' : '' }}>{{ __('pages.status.published') }}</option>
                                        <option value="draft" {{ old('status', $item->status ?? '') == 'draft' ? 'selected' : '' }}>{{ __('pages.status.draft') }}</option>
                                    </select>
                                    @error('status')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold" for="meta">{{ __('pages.fields.meta') }}</label>
                                    <textarea name="meta" id="meta" class="form-control" rows="3">{{ old('meta', isset($item) ? json_encode($item->meta) : '') }}</textarea>
                                    <small class="text-muted">{{ __('pages.hints.meta_json') }}</small>
                                    @error('meta')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> {{ __('pages.actions.save') }}
                                    </button>
                                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary ms-2">{{ __('pages.actions.cancel') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
