@extends('layouts.app')

@section('title', $post->title . ' - HaloShop')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <article class="card shadow-sm">
                @if($post->image)
                    <img src="{{ asset($post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="max-height: 400px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <h1 class="card-title mb-3">{{ $post->title }}</h1>
                    
                    <div class="text-muted mb-4">
                        <i class="far fa-calendar me-2"></i>
                        <span>{{ $post->published_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    @if($post->excerpt)
                    <div class="alert alert-light border">
                        <strong>{{ $post->excerpt }}</strong>
                    </div>
                    @endif
                    
                    <div class="post-content">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </article>
            
            <!-- Navigation Bài viết trước/sau -->
            <div class="mt-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('news.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i> Danh sách
                        </a>
                    </div>
                    <div class="col-md-4">
                        @if($previousPost)
                        <a href="{{ route('news.show', $previousPost->slug) }}" class="btn btn-outline-primary w-100" title="{{ $previousPost->title }}">
                            <i class="fas fa-chevron-left me-2"></i> Bài trước
                        </a>
                        @else
                        <button class="btn btn-outline-secondary w-100" disabled>
                            <i class="fas fa-chevron-left me-2"></i> Bài trước
                        </button>
                        @endif
                    </div>
                    <div class="col-md-4">
                        @if($nextPost)
                        <a href="{{ route('news.show', $nextPost->slug) }}" class="btn btn-outline-primary w-100" title="{{ $nextPost->title }}">
                            Bài sau <i class="fas fa-chevron-right ms-2"></i>
                        </a>
                        @else
                        <button class="btn btn-outline-secondary w-100" disabled>
                            Bài sau <i class="fas fa-chevron-right ms-2"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="far fa-newspaper me-2"></i> Tin tức liên quan
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($relatedPosts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($relatedPosts as $related)
                        <a href="{{ route('news.show', $related->slug) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ Str::limit($related->title, 60) }}</h6>
                            </div>
                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i>
                                {{ $related->published_at->format('d/m/Y') }}
                            </small>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center p-3">Chưa có tin tức liên quan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .post-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    .post-content p {
        margin-bottom: 1.5rem;
    }
</style>
@endsection
