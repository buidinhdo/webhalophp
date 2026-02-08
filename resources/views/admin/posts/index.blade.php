@extends('admin.layouts.app')

@section('title', 'Quản lý tin tức')
@section('page-title', 'Quản lý tin tức')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Tin tức</li>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách tin tức</h3>
        <div class="card-tools">
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm tin tức
            </a>
        </div>
    </div>
    
    <div class="card-body border-bottom">
        <form action="{{ route('admin.posts.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tiêu đề, nội dung..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã đăng</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
    
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Trạng thái</th>
                    <th>Ngày đăng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>
                        <strong>{{ $post->title }}</strong><br>
                        <small class="text-muted">{{ Str::limit($post->excerpt, 50) }}</small>
                    </td>
                    <td>
                        @if($post->is_published)
                            <span class="badge badge-success">Đã đăng</span>
                        @else
                            <span class="badge badge-secondary">Nháp</span>
                        @endif
                    </td>
                    <td>{{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <p class="text-muted">Chưa có tin tức nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($posts->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-center">
            {{ $posts->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    /* Làm nhỏ pagination buttons */
    .pagination {
        margin: 0;
    }
    .pagination .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 0.75rem;
    }
</style>
@endpush
@endsection
