@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('pages.title') }}</title>
@endsection
@section('content')
    <div class="container-fluid">
        <h4 class="mb-3">{{ __('pages.actions.show') }}</h4>
        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">{{ __('pages.fields.title') }}</dt>
                    <dd class="col-sm-9">{{ $item->title }}</dd>

                    <dt class="col-sm-3">{{ __('pages.fields.slug') }}</dt>
                    <dd class="col-sm-9">{{ $item->slug }}</dd>

                    <dt class="col-sm-3">{{ __('pages.fields.type') }}</dt>
                    <dd class="col-sm-9">{{ __('pages.types.' . $item->type) }}</dd>

                    <dt class="col-sm-3">{{ __('pages.fields.status') }}</dt>
                    <dd class="col-sm-9">
                        @if($item->status == 'published')
                            <span class="badge bg-success">{{ __('pages.status.published') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('pages.status.draft') }}</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('pages.fields.meta') }}</dt>
                    <dd class="col-sm-9">
                        <pre>{{ json_encode($item->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </dd>

                    <dt class="col-sm-3">{{ __('pages.fields.created_at') }}</dt>
                    <dd class="col-sm-9">{{ $item->created_at }}</dd>
                </dl>
                <div class="d-flex justify-content-end">
                    @can('pages.edit')
                        <a href="{{ route('admin.pages.edit', $item->id) }}" class="btn btn-warning me-2">
                            <i class="fa fa-edit"></i> {{ __('pages.actions.edit') }}
                        </a>
                    @endcan
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                        {{ __('pages.actions.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
