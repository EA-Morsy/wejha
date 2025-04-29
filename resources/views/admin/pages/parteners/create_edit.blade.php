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
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('cities.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-4  @error('name') is-invalid @enderror">
                            <label class="form-label" for="name">{{ __('admin.name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder=""
                                   value="{{ $item->name ?? old('name') }}" required/>
                            @error('name')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('logo') is-invalid @enderror">
                            <label class="form-label" for="logo">{{ __('admin.logo') }}</label>
                            <input type="file" name="logo" id="logo" class="form-control" placeholder=""
                                   value="{{ $item->logo ?? old('logo') }}" required/>
                            @error('logo')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-2  @error('is_active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active"
                                        value="1" id="is_active"
                                    @checked($item->is_active ?? false )/>
                                <label class="form-check-label" for="is_active">{{ __('parteners.is_active') }}</label>
                            </div>
                            @error('is_active')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
