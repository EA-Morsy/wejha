@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('parteners.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.parteners.update', $item->id) : route('admin.parteners.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('parteners.actions.edit') : __('parteners.actions.create') }}</span>
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
                                <div class="col-md-7">
                                    <label class="form-label fw-bold" for="name">{{ __('admin.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="أدخل اسم الشريك" value="{{ $item->name ?? old('name') }}" required/>
                                    @error('name')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-5 d-flex flex-column align-items-center">
                                    <x-image-uploader 
                                        name="logo"
                                        :current="isset($item) ? $item->logo : null"
                                        label="{{ __('admin.logo') }}"
                                    />
                                    <small class="text-muted mt-1">يمكنك رفع شعار الشريك (اختياري)</small>
                                    @error('logo')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-4 align-items-center">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked($item->is_active ?? false )/>
                                        <label class="form-check-label fw-bold" for="is_active">{{ __('parteners.is_active') }}</label>
                                    </div>
                                    @error('is_active')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 text-end">
                                    <button class="btn btn-primary px-4" type="submit">
                                        <i data-feather="save"></i>
                                        {{ __('parteners.actions.save') }}
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
