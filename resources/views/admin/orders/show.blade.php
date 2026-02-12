@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng')
@section('page-title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('action-buttons')
    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Sửa đơn hàng
    </a>
    <a href="{{ route('admin.orders.export-pdf', $order->id) }}" class="btn btn-danger" target="_blank">
        <i class="fas fa-file-pdf"></i> Xuất PDF
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin đơn hàng</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Mã đơn hàng:</strong> #{{ $order->order_number }}<br>
                        <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                        <strong>Trạng thái:</strong> 
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
                    </div>
                    <div class="col-md-6">
                        <strong>Thanh toán:</strong> {{ $order->payment_method == 'cod' ? 'COD' : 'Chuyển khoản' }}<br>
                        <strong>Trạng thái TT:</strong> 
                        <span class="badge badge-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                            {{ $order->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
                    </div>
                </div>

                <hr>

                <h5>Sản phẩm</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="80">Ảnh</th>
                            <th>Sản phẩm</th>
                            <th width="100">Số lượng</th>
                            <th width="150">Đơn giá</th>
                            <th width="150">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                @include('components.order-item-image', ['item' => $item])
                            </td>
                            <td>{{ $item->product_name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->price) }}₫</td>
                            <td class="text-right"><strong>{{ number_format($item->price * $item->quantity) }}₫</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Tổng cộng:</strong></td>
                            <td class="text-right"><strong class="text-danger">{{ number_format($order->total_amount) }}₫</strong></td>
                        </tr>
                    </tfoot>
                </table>

                @if($order->notes)
                <div class="mt-3">
                    <strong>Ghi chú:</strong>
                    <p>{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin khách hàng</h3>
            </div>
            <div class="card-body">
                <p>
                    <strong>Họ tên:</strong><br>
                    {{ $order->customer_name }}
                </p>
                <p>
                    <strong>Email:</strong><br>
                    {{ $order->customer_email }}
                </p>
                <p>
                    <strong>Số điện thoại:</strong><br>
                    {{ $order->customer_phone }}
                </p>
                <p>
                    <strong>Địa chỉ:</strong><br>
                    {{ $order->customer_address }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">Thao tác</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-edit"></i> Sửa đơn hàng
                </a>
                <a href="{{ route('admin.orders.export-pdf', $order->id) }}" class="btn btn-danger btn-block mb-2" target="_blank">
                    <i class="fas fa-file-pdf"></i> Xuất PDF
                </a>
                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" 
                    onsubmit="return confirm('Đồng ý xóa đơn hàng #{{ $order->order_number }}? Hành động này không thể hoàn tác!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block">
                        <i class="fas fa-trash"></i> Xóa đơn hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
