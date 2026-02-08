@extends('admin.layouts.app')

@section('title', 'Quản lý bộ sưu tập')
@section('page-title', 'Quản lý bộ sưu tập')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Bộ sưu tập</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách bộ sưu tập</h3>
        <div class="card-tools">
            <a href="{{ route('admin.collections.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm bộ sưu tập
            </a>
        </div>
    </div>
    
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên bộ sưu tập</th>
                    <th>Slug</th>
                    <th>Số sản phẩm</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($collections as $collection)
                <tr>
                    <td>{{ $collection->id }}</td>
                    <td><strong>{{ $collection->name }}</strong></td>
                    <td><code>{{ $collection->slug }}</code></td>
                    <td><span class="badge badge-info">{{ $collection->products_count }}</span></td>
                    <td>
                        @if($collection->is_active)
                            <span class="badge badge-success">Kích hoạt</span>
                        @else
                            <span class="badge badge-secondary">Vô hiệu</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.collections.edit', $collection->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.collections.destroy', $collection->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
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
                    <td colspan="6" class="text-center py-4">
                        <p class="text-muted">Không có bộ sưu tập nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($collections->hasPages())
    <div class="card-footer clearfix">
        {{ $collections->links() }}
    </div>
    @endif
</div>
@endsection
