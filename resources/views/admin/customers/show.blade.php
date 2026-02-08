@extends('admin.layouts.app')

@section('title', 'Chi tiết khách hàng')
@section('page-title', 'Chi tiết khách hàng')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Khách hàng</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin khách hàng</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này? Tất cả đơn hàng liên quan sẽ bị ảnh hưởng.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <p>
                    <strong>Họ tên:</strong><br>
                    {{ $customer->name }}
                </p>
                <p>
                    <strong>Email:</strong><br>
                    {{ $customer->email }}
                </p>
                <p>
                    <strong>Số điện thoại:</strong><br>
                    {{ $customer->phone ?? 'Chưa cập nhật' }}
                </p>
                <p>
                    <strong>Địa chỉ:</strong><br>
                    {{ $customer->address ?? 'Chưa cập nhật' }}
                </p>
                <p>
                    <strong>Ngày đăng ký:</strong><br>
                    {{ $customer->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lịch sử đơn hàng</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->orders as $order)
                        <tr>
                            <td><strong>#{{ $order->order_number }}</strong></td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>{{ number_format($order->total_amount) }}₫</td>
                            <td>
                                @switch($order->order_status)
                                    @case('pending')
                                        <span class="badge badge-warning">Chờ xử lý</span>
                                        @break
                                    @case('processing')
                                        <span class="badge badge-info">Đang xử lý</span>
                                        @break
                                    @case('shipping')
                                        <span class="badge badge-primary">Đang giao</span>
                                        @break
                                    @case('completed')
                                        <span class="badge badge-success">Hoàn thành</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge badge-danger">Đã hủy</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <p class="text-muted">Chưa có đơn hàng nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
