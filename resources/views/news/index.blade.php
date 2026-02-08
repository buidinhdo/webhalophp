@extends('layouts.app')

@section('title', 'Tin tức - HaloShop')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">
                <i class="far fa-newspaper text-info me-2"></i> Tin tức
            </h1>
            
            <!-- Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('news.index') }}" method="GET" class="row g-3">
                        <div class="col-md-10">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tin tức..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @if($posts->count() > 0)
    <div class="row g-4">
        @foreach($posts as $post)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm">
                @if($post->image)
                    <img src="{{ asset($post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/400x200?text={{ urlencode($post->title) }}" class="card-img-top" alt="{{ $post->title }}">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="text-muted small mb-2">
                        <i class="far fa-calendar me-1"></i> {{ $post->published_at->format('d/m/Y') }}
                    </p>
                    <p class="card-text">{{ Str::limit($post->excerpt, 120) }}</p>
                    <a href="{{ route('news.show', $post->slug) }}" class="btn btn-primary btn-sm mt-auto">
                        Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $posts->links() }}
    </div>
    @endif
    @else
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle me-2"></i> Chưa có tin tức nào được đăng.
    </div>
    @endif
</div>
@endsection
