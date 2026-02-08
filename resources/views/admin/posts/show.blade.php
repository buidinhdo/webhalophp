@extends('admin.layouts.app')

@section('title', 'Chi tiết tin tức')
@section('page-title', 'Chi tiết tin tức')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Tin tức</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $post->title }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-info">
                <i class="fas fa-edit"></i> Sửa
            </a>
            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                @if($post->image)
                <div class="mb-3">
                    <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="img-fluid">
                </div>
                @endif

                @if($post->excerpt)
                <div class="mb-3">
                    <h5>Tóm tắt:</h5>
                    <p class="lead">{{ $post->excerpt }}</p>
                </div>
                @endif

                <div class="mb-3">
                    <h5>Nội dung:</h5>
                    <div>{!! nl2br(e($post->content)) !!}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin</h3>
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Trạng thái:</strong><br>
                            @if($post->is_published)
                                <span class="badge badge-success">Đã đăng</span>
                            @else
                                <span class="badge badge-secondary">Nháp</span>
                            @endif
                        </p>
                        <p>
                            <strong>Ngày đăng:</strong><br>
                            {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'Chưa đăng' }}
                        </p>
                        <p>
                            <strong>Ngày tạo:</strong><br>
                            {{ $post->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p>
                            <strong>Cập nhật:</strong><br>
                            {{ $post->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
