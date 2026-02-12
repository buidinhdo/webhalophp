@extends('admin.layouts.app')

@section('title', 'Sửa đơn hàng')
@section('page-title', 'Sửa đơn hàng #' . $order->order_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
<form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin đơn hàng</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mã đơn hàng</label>
                                <input type="text" class="form-control" value="{{ $order->order_number }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ngày đặt</label>
                                <input type="text" class="form-control" value="{{ $order->created_at->format('d/m/Y H:i') }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Thông tin khách hàng</h5>
                    
                    <div class="form-group">
                        <label>Tên khách hàng <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" 
                            value="{{ old('customer_name', $order->customer_name) }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" 
                                    value="{{ old('customer_email', $order->customer_email) }}" required>
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" 
                                    value="{{ old('customer_phone', $order->customer_phone) }}" required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Địa chỉ giao hàng <span class="text-danger">*</span></label>
                        <input type="text" name="customer_address" class="form-control @error('customer_address') is-invalid @enderror" 
                            value="{{ old('customer_address', $order->customer_address) }}" required>
                        @error('customer_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Sản phẩm trong đơn hàng</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th width="100">Số lượng</th>
                                <th width="150">Đơn giá</th>
                                <th width="150">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">{{ number_format($item->price) }}₫</td>
                                <td class="text-right"><strong>{{ number_format($item->price * $item->quantity) }}₫</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                                <td class="text-right"><strong class="text-danger">{{ number_format($order->total_amount) }}₫</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    <small class="text-muted"><i class="fas fa-info-circle"></i> Không thể sửa sản phẩm trong đơn hàng. Chỉ có thể cập nhật thông tin khách hàng và trạng thái.</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Trạng thái đơn hàng</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Trạng thái đơn hàng <span class="text-danger">*</span></label>
                        <select name="order_status" class="form-control @error('order_status') is-invalid @enderror" required>
                            <option value="pending" {{ old('order_status', $order->order_status) == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="processing" {{ old('order_status', $order->order_status) == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipping" {{ old('order_status', $order->order_status) == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                            <option value="completed" {{ old('order_status', $order->order_status) == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ old('order_status', $order->order_status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                        @error('order_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Trạng thái thanh toán <span class="text-danger">*</span></label>
                        <select name="payment_status" class="form-control @error('payment_status') is-invalid @enderror" required>
                            <option value="unpaid" {{ old('payment_status', $order->payment_status) == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Phương thức thanh toán</label>
                        <input type="text" class="form-control" value="{{ $order->payment_method == 'cod' ? 'COD' : 'Chuyển khoản' }}" readonly>
                    </div>
                    
                    <hr>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Cập nhật đơn hàng
                    </button>
                    
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
