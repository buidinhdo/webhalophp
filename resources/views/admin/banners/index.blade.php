@extends('admin.layouts.app')

@section('title', 'Quản lý Banner')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản lý Banner</h1>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Banner Mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($banners->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th style="width: 120px;">Ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Phụ đề</th>
                                <th style="width: 100px;">Thứ tự</th>
                                <th style="width: 120px;">Trạng thái</th>
                                <th style="width: 200px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($banners as $banner)
                            <tr>
                                <td>{{ $banner->id }}</td>
                                <td>
                                    @if($banner->image)
                                        <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" style="max-width: 100px; height: auto;">
                                    @else
                                        <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $banner->title }}</td>
                                <td>{{ $banner->subtitle ?? '-' }}</td>
                                <td>{{ $banner->order }}</td>
                                <td>
                                    <form action="{{ route('admin.banners.toggle-active', $banner) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-secondary' }}">
                                            {{ $banner->is_active ? 'Hoạt động' : 'Tắt' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có banner nào. Hãy thêm banner mới!</p>
                    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm Banner Đầu Tiên
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
