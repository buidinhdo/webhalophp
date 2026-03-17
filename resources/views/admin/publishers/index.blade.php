@extends('admin.layouts.app')

@section('title', 'Quản lý nhà phát hành')
@section('page-title', 'Quản lý nhà phát hành')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Nhà phát hành</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách nhà phát hành</h3>
        <div class="card-tools">
            <a href="{{ route('admin.publishers.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm nhà phát hành
            </a>
        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th width="60">STT</th>
                    <th>Tên nhà phát hành</th>
                    <th width="130">Số sản phẩm</th>
                    <th width="110">Trạng thái</th>
                    <th width="100">Ngày thêm</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($publishers as $index => $publisher)
                <tr>
                    <td>{{ $publishers->firstItem() + $index }}</td>
                    <td><strong>{{ $publisher->name }}</strong></td>
                    <td>
                        <span class="badge badge-info">
                            {{ \App\Models\Product::where('publisher', $publisher->name)->count() }} sản phẩm
                        </span>
                    </td>
                    <td>
                        @if($publisher->is_active)
                            <span class="badge badge-success">Hoạt động</span>
                        @else
                            <span class="badge badge-secondary">Tạm ẩn</span>
                        @endif
                    </td>
                    <td>{{ $publisher->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.publishers.edit', $publisher) }}" class="btn btn-info btn-sm" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('admin.publishers.toggle', $publisher) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $publisher->is_active ? 'warning' : 'success' }} btn-sm"
                                    title="{{ $publisher->is_active ? 'Ẩn' : 'Hiện' }}">
                                <i class="fas fa-{{ $publisher->is_active ? 'eye-slash' : 'eye' }}"></i>
                            </button>
                        </form>

                        <form action="{{ route('admin.publishers.destroy', $publisher) }}" method="POST" style="display: inline-block;"
                              onsubmit="return confirm('Bạn có chắc muốn xóa nhà phát hành này?');">
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
                    <td colspan="6" class="text-center">Chưa có nhà phát hành nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($publishers->hasPages())
    <div class="card-footer clearfix">
        {{ $publishers->links() }}
    </div>
    @endif
</div>
@endsection
