@extends('layouts.shop')

@section('title', $page->title)

@if($page->meta_description)
    @section('meta_description', $page->meta_description)
@endif

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>
        
        <div class="prose prose-lg max-w-none">
            {!! nl2br(e($page->content)) !!}
        </div>
        
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Last updated: {{ $page->updated_at->format('F j, Y') }}
            </p>
        </div>
    </div>
</div>
@endsection