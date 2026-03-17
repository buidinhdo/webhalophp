@extends('admin.layouts.app')

@section('title', 'Quản lý phân loại ESRB')
@section('page-title', 'Quản lý phân loại ESRB')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Phân loại ESRB</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách phân loại ESRB</h3>
        <div class="card-tools">
            <a href="{{ route('admin.esrb-ratings.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm phân loại
            </a>
        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th width="60">STT</th>
                    <th width="80">Mã</th>
                    <th>Tên phân loại</th>
                    <th>Mô tả</th>
                    <th width="90">Tuổi tối thiểu</th>
                    <th width="130">Số sản phẩm</th>
                    <th width="110">Trạng thái</th>
                    <th width="80">Thứ tự</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ratings as $index => $rating)
                <tr>
                    <td>{{ $ratings->firstItem() + $index }}</td>
                    <td>
                        <span class="badge badge-dark" style="font-size: 0.9rem;">{{ $rating->code }}</span>
                    </td>
                    <td><strong>{{ $rating->name }}</strong></td>
                    <td class="text-wrap" style="max-width: 300px; white-space: normal;">
                        <small>{{ $rating->description ?? '—' }}</small>
                    </td>
                    <td>
                        @if($rating->min_age !== null)
                            <span class="badge badge-secondary">{{ $rating->min_age }}+</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-info">
                            {{ \App\Models\Product::where('esrb_rating', $rating->code)->count() }} sản phẩm
                        </span>
                    </td>
                    <td>
                        @if($rating->is_active)
                            <span class="badge badge-success">Hoạt động</span>
                        @else
                            <span class="badge badge-secondary">Tạm ẩn</span>
                        @endif
                    </td>
                    <td>{{ $rating->order }}</td>
                    <td>
                        <a href="{{ route('admin.esrb-ratings.edit', $rating) }}" class="btn btn-info btn-sm" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('admin.esrb-ratings.toggle', $rating) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $rating->is_active ? 'warning' : 'success' }} btn-sm"
                                    title="{{ $rating->is_active ? 'Ẩn' : 'Hiện' }}">
                                <i class="fas fa-{{ $rating->is_active ? 'eye-slash' : 'eye' }}"></i>
                            </button>
                        </form>

                        <form action="{{ route('admin.esrb-ratings.destroy', $rating) }}" method="POST" style="display: inline-block;"
                              onsubmit="return confirm('Bạn có chắc muốn xóa phân loại ESRB này?');">
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
                    <td colspan="9" class="text-center">Chưa có phân loại ESRB nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($ratings->hasPages())
    <div class="card-footer clearfix">
        {{ $ratings->links() }}
    </div>
    @endif
</div>
@endsection
