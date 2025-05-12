@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">{{ __('sections.actions.show') }} <small class="text-muted">({{ $page->title }})</small></h4>
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">{{ __('sections.fields.section_type') }}</dt>
                <dd class="col-sm-9">{{ $item->section_type }}</dd>
                <dt class="col-sm-3">{{ __('sections.fields.order') }}</dt>
                <dd class="col-sm-9">{{ $item->order }}</dd>
                <dt class="col-sm-3">{{ __('sections.fields.is_active') }}</dt>
                <dd class="col-sm-9">
                    @if($item->is_active)
                        <span class="badge bg-success">{{ __('sections.status.active') }}</span>
                    @else
                        <span class="badge bg-secondary">{{ __('sections.status.inactive') }}</span>
                    @endif
                </dd>
                <dt class="col-sm-3">{{ __('sections.fields.content') }}</dt>
                <dd class="col-sm-9">
                    <pre>{{ json_encode($item->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </dd>
                <dt class="col-sm-3">{{ __('sections.fields.settings') }}</dt>
                <dd class="col-sm-9">
                    <pre>{{ json_encode($item->settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </dd>
                <dt class="col-sm-3">{{ __('sections.fields.created_at') }}</dt>
                <dd class="col-sm-9">{{ $item->created_at }}</dd>
            </dl>
            <a href="{{ route('admin.sections.edit', [$page->id, $item->id]) }}" class="btn btn-warning">
                <i class="fa fa-edit"></i> {{ __('sections.actions.edit') }}
            </a>
            <a href="{{ route('admin.sections.index', $page->id) }}" class="btn btn-secondary">
                {{ __('sections.actions.back') }}
            </a>
        </div>
    </div>
</div>
@endsection
