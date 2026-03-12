@extends('admin.layouts.app')

@section('title', 'Quản lý mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-ticket-alt me-2"></i> Quản lý mã giảm giá</h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm mã giảm giá
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã</th>
                            <th>Mô tả</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Đơn tối thiểu</th>
                            <th>Đã dùng/Giới hạn</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td><strong class="text-primary">{{ $coupon->code }}</strong></td>
                            <td>{{ Str::limit($coupon->description, 30) }}</td>
                            <td>
                                @if($coupon->type == 'percentage')
                                    <span class="badge bg-info">Phần trăm</span>
                                @else
                                    <span class="badge bg-success">Số tiền</span>
                                @endif
                            </td>
                            <td>
                                @if($coupon->type == 'percentage')
                                    {{ number_format($coupon->value, 0) }}%
                                    @if($coupon->max_discount)
                                        <br><small class="text-muted">Max: {{ number_format($coupon->max_discount, 0, ',', '.') }}đ</small>
                                    @endif
                                @else
                                    {{ number_format($coupon->value, 0, ',', '.') }}đ
                                @endif
                            </td>
                            <td>{{ number_format($coupon->min_order_value, 0, ',', '.') }}đ</td>
                            <td>
                                <span class="badge {{ $coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit ? 'bg-danger' : 'bg-secondary' }}">
                                    {{ $coupon->used_count }}/{{ $coupon->usage_limit ?? '∞' }}
                                </span>
                            </td>
                            <td>
                                @if($coupon->start_date)
                                    <small>Từ: {{ $coupon->start_date->format('d/m/Y') }}</small><br>
                                @endif
                                @if($coupon->end_date)
                                    <small>Đến: {{ $coupon->end_date->format('d/m/Y') }}</small>
                                @endif
                                @if(!$coupon->start_date && !$coupon->end_date)
                                    <span class="text-muted">Không giới hạn</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.coupons.toggle', $coupon->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $coupon->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $coupon->is_active ? 'Hoạt động' : 'Tắt' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa mã này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted">Chưa có mã giảm giá nào</p>
                                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tạo mã giảm giá đầu tiên
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($coupons->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $coupons->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
