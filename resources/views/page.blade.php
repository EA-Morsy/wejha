@extends('website.layouts.master')
@section('title')
    <title>{{ $page->title }}</title>
@endsection
@section('content')
    <div class="container py-4">
        <h1 class="mb-4">{{ $page->title }}</h1>
        @php $locale = app()->getLocale(); @endphp
        @foreach($page->sections()->where('is_active', 1)->orderBy('order')->get() as $section)
            <div class="mb-5 section-block section-{{ $section->section_type }}">
                <h2 class="h4">{{ $locale == 'ar' ? $section->title_ar : $section->title_en }}</h2>
                @if($locale == 'ar' ? $section->description_ar : $section->description_en)
                    <p class="text-muted">{{ $locale == 'ar' ? $section->description_ar : $section->description_en }}</p>
                @endif
                <div class="section-content">
                    {!! $locale == 'ar' ? $section->content_ar : $section->content_en !!}
                </div>
                @if($section->image)
                    <div class="section-image mt-3">
                        <img src="{{ $section->image }}" class="img-fluid" alt="Section Image">
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endsection
