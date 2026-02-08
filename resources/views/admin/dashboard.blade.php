@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Stats Row -->
<div class="row">
    <!-- Total Products -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($totalProducts) }}</h3>
                <p>Tổng sản phẩm</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <a href="{{ route('admin.products.index') }}" class="small-box-footer">
                Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($totalOrders) }}</h3>
                <p>Tổng đơn hàng</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="small-box-footer">
                Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($totalCustomers) }}</h3>
                <p>Khách hàng</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.customers.index') }}" class="small-box-footer">
                Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($totalRevenue/1000000, 1) }}M</h3>
                <p>Tổng doanh thu</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="small-box-footer">
                Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Info Boxes Row -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="far fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Đơn chờ xử lý</span>
                <span class="info-box-number">{{ number_format($pendingOrders) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="far fa-calendar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Đơn hàng hôm nay</span>
                <span class="info-box-number">{{ number_format($todayOrders) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Doanh thu tháng</span>
                <span class="info-box-number">{{ number_format($monthlyRevenue/1000000, 1) }}M</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-percentage"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tỷ lệ hoàn thành</span>
                <span class="info-box-number">
                    @if($totalOrders > 0)
                        {{ number_format(($totalOrders - $pendingOrders) / $totalOrders * 100, 1) }}%
                    @else
                        0%
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Doanh thu 7 ngày gần đây
                </h3>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-fire mr-1"></i>
                    Sản phẩm bán chạy
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                    @forelse($topProducts as $product)
                    <li class="item">
                        <div class="product-img">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-size-50">
                            @else
                                <img src="https://via.placeholder.com/50" alt="{{ $product->name }}" class="img-size-50">
                            @endif
                        </div>
                        <div class="product-info">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="product-title">
                                {{ Str::limit($product->name, 30) }}
                                <span class="badge badge-success float-right">{{ number_format($product->total_sold) }} đã bán</span>
                            </a>
                            <span class="product-description">
                                {{ number_format($product->price) }}₫
                            </span>
                        </div>
                    </li>
                    @empty
                    <li class="item text-center py-3">
                        <p class="text-muted">Chưa có dữ liệu</p>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shopping-cart mr-1"></i>
                    Đơn hàng gần đây
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">
                        Xem tất cả
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td><strong>#{{ $order->order_number }}</strong></td>
                            <td>{{ $order->customer_name }}</td>
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
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    // Revenue Chart
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($last7Days) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($revenueChart) !!},
                backgroundColor: 'rgba(0, 217, 255, 0.2)',
                borderColor: 'rgba(0, 217, 255, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + '₫';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
