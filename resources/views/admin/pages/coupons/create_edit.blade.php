@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('coupons.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.coupons.update', $item->id) : route('admin.coupons.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('coupons.actions.edit') : __('coupons.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('coupons.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-4  @error('code') is-invalid @enderror">
                            <label class="form-label" for="code">{{ __('coupons.code') }}</label>
                            <input type="text" name="code" id="code" class="form-control" placeholder=""
                                   value="{{ $item->code ?? old('code') }}" required/>
                            @error('code')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('value') is-invalid @enderror">
                            <label class="form-label" for="value">{{ __('coupons.value') }} %</label>
                            <input type="number" name="value" id="value" class="form-control" placeholder=""
                                   value="{{ $item->value ?? old('value') }}" required/>
                            @error('value')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('location_url') is-invalid @enderror">
                            <label class="form-label" for="location_url">{{ __('coupons.location_url') }}</label>
                            <input  name="location_url" id="location_url" class="form-control" placeholder=""
                                   value="{{ $item->location_url ?? old('location_url') }}" required/>
                            @error('location_url')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('end_date') is-invalid @enderror">
                            <label class="form-label" for="end_date">{{ __('coupons.end_date') }}</label>
                            <input type="text" name="end_date" id="end_date" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->end_date ?? old('end_date') }}" />
                            @error('end_date')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('category_id') is-invalid @enderror">
                            <label class="form-label" for="category_id">{{ __('coupons.category') }}</label>
                            <select name="category_id" id="category_id" class="form-control ajax_select2 extra_field"
                                    data-ajax--url="{{ route('admin.categories.select') }}"
                                    data-ajax--cache="true"  required >
                                @isset($item->category)
                                    <option value="{{ $item->category_id }}" selected>{{ $item->category->title }}</option>
                                @endisset
                            </select>
                            @error('category_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('city_id') is-invalid @enderror">
                            <label class="form-label" for="city_id">{{ __('admin.city') }}</label>
                            <select name="city_id" id="city_id" class="form-control ajax_select2 extra_field"
                                    data-ajax--url="{{ route('admin.cities.select') }}"
                                    data-ajax--cache="true"  required >
                                @isset($item->city)
                                    <option value="{{ $item->city_id }}" selected>{{ $item->city->title }}</option>
                                @endisset
                            </select>
                            @error('city_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('description_en') is-invalid @enderror">
                            <label class="form-label" for="description_en">{{ __('coupons.description_en') }}</label>
                            <input type="text" name="description_en" id="description_en" class="form-control" placeholder=""
                                   value="{{ $item->description_en ?? old('description_en') }}" />
                            @error('description_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('description_ar') is-invalid @enderror">
                            <label class="form-label" for="description_ar">{{ __('coupons.description_ar') }}</label>
                            <input type="text" name="description_ar" id="description_ar" class="form-control" placeholder=""
                                   value="{{ $item->description_ar ?? old('description_ar') }}" />
                            @error('description_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('brand_name') is-invalid @enderror">
                            <label class="form-label" for="brand_name">{{ __('coupons.brand_name') }}</label>
                            <input type="text" name="brand_name" id="brand_name" class="form-control" placeholder=""
                                   value="{{ $item->brand_name ?? old('brand_name') }}" />
                            @error('brand_name')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-6 @error('brand_logo') is-invalid @enderror">
                            <label class="form-label" for="brand_logo">{{ __('coupons.brand_logo') }}</label>
                            <input type="file" class="form-control input" name="brand_logo" id="brand_logo">
                            @error('brand_logo')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if(isset($item) && !empty($item->photo))
                                    <img src="{{ $item->photo }}"
                                         class="img-fluid img-thumbnail">
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
