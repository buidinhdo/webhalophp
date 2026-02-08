@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng')
@section('page-title', 'Quản lý đơn hàng')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Đơn hàng</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách đơn hàng</h3>
    </div>
    
    <div class="card-body border-bottom">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm đơn hàng..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-search"></i> Lọc
                </button>
            </div>
        </form>
    </div>
    
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>#{{ $order->order_number }}</strong></td>
                    <td>
                        {{ $order->customer_name }}<br>
                        <small>{{ $order->customer_phone }}</small>
                    </td>
                    <td><strong>{{ number_format($order->total_amount) }}₫</strong></td>
                    <td>
                        <span class="badge badge-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                            {{ $order->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
                    </td>
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
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <p class="text-muted">Không có đơn hàng nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
    <div class="card-footer clearfix">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
