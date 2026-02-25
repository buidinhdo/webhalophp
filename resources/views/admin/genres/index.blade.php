@extends('admin.layouts.app')

@section('title', 'Quản lý thể loại game')
@section('page-title', 'Quản lý thể loại game')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Thể loại game</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách thể loại game</h3>
        <div class="card-tools">
            <a href="{{ route('admin.genres.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm thể loại
            </a>
        </div>
    </div>
    
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th width="60">STT</th>
                    <th>Tên thể loại</th>
                    <th>Slug</th>
                    <th>Icon</th>
                    <th width="120">Số sản phẩm</th>
                    <th width="100">Trạng thái</th>
                    <th width="80">Thứ tự</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($genres as $index => $genre)
                <tr>
                    <td>{{ $genres->firstItem() + $index }}</td>
                    <td><strong>{{ $genre->name }}</strong></td>
                    <td><code>{{ $genre->slug }}</code></td>
                    <td>
                        @if($genre->icon)
                            <i class="{{ $genre->icon }}"></i> {{ $genre->icon }}
                        @else
                            <span class="text-muted">Chưa có</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-info">
                            {{ \App\Models\Product::where('genre', $genre->name)->count() }} sản phẩm
                        </span>
                    </td>
                    <td>
                        @if($genre->is_active)
                            <span class="badge badge-success">Hoạt động</span>
                        @else
                            <span class="badge badge-secondary">Tạm ẩn</span>
                        @endif
                    </td>
                    <td>{{ $genre->order }}</td>
                    <td>
                        <a href="{{ route('admin.genres.edit', $genre) }}" class="btn btn-info btn-sm" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <form action="{{ route('admin.genres.toggle', $genre) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $genre->is_active ? 'warning' : 'success' }} btn-sm" title="{{ $genre->is_active ? 'Ẩn' : 'Hiện' }}">
                                <i class="fas fa-{{ $genre->is_active ? 'eye-slash' : 'eye' }}"></i>
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thể loại này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Chưa có thể loại nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($genres->hasPages())
    <div class="card-footer clearfix">
        {{ $genres->links() }}
    </div>
    @endif
</div>
@endsection
