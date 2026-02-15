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

<!-- So sánh tăng trưởng -->
<div class="row">
    <div class="col-12">
        <h5 class="mb-3"><i class="fas fa-arrows-alt-v text-success"></i> So sánh tháng này vs tháng trước</h5>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Doanh thu</h5>
                        <h3 class="mb-0">{{ number_format($monthlyRevenue/1000000, 1) }}M ₫</h3>
                    </div>
                    <div class="text-right">
                        @if($revenueGrowth >= 0)
                            <span class="badge badge-success" style="font-size: 1.2rem;">
                                <i class="fas fa-arrow-up"></i> +{{ number_format($revenueGrowth, 1) }}%
                            </span>
                        @else
                            <span class="badge badge-danger" style="font-size: 1.2rem;">
                                <i class="fas fa-arrow-down"></i> {{ number_format($revenueGrowth, 1) }}%
                            </span>
                        @endif
                        <small class="d-block text-muted mt-1">Tháng trước: {{ number_format($lastMonthRevenue/1000000, 1) }}M</small>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 10px;">
                    <div class="progress-bar {{ $revenueGrowth >= 0 ? 'bg-success' : 'bg-danger' }}" 
                         style="width: {{ min(abs($revenueGrowth), 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-success card-outline">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Đơn hàng</h5>
                        <h3 class="mb-0">{{ number_format($thisMonthOrders) }}</h3>
                    </div>
                    <div class="text-right">
                        @if($ordersGrowth >= 0)
                            <span class="badge badge-success" style="font-size: 1.2rem;">
                                <i class="fas fa-arrow-up"></i> +{{ number_format($ordersGrowth, 1) }}%
                            </span>
                        @else
                            <span class="badge badge-danger" style="font-size: 1.2rem;">
                                <i class="fas fa-arrow-down"></i> {{ number_format($ordersGrowth, 1) }}%
                            </span>
                        @endif
                        <small class="d-block text-muted mt-1">Tháng trước: {{ number_format($lastMonthOrders) }}</small>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 10px;">
                    <div class="progress-bar {{ $ordersGrowth >= 0 ? 'bg-success' : 'bg-danger' }}" 
                         style="width: {{ min(abs($ordersGrowth), 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-warning card-outline">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Khách hàng</h5>
                        <h3 class="mb-0">{{ number_format($thisMonthCustomers) }}</h3>
                    </div>
                    <div class="text-right">
                        @if($customersGrowth >= 0)
                            <span class="badge badge-success" style="font-size: 1.2rem;">
                                <i class="fas fa-arrow-up"></i> +{{ number_format($customersGrowth, 1) }}%
                            </span>
                        @else
                            <span class="badge badge-danger" style="font-size: 1.2rem;">
                                <i class="fas fa-arrow-down"></i> {{ number_format($customersGrowth, 1) }}%
                            </span>
                        @endif
                        <small class="d-block text-muted mt-1">Tháng trước: {{ number_format($lastMonthCustomers) }}</small>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 10px;">
                    <div class="progress-bar {{ $customersGrowth >= 0 ? 'bg-success' : 'bg-danger' }}" 
                         style="width: {{ min(abs($customersGrowth), 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Chart with Filter -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Doanh thu, Đơn hàng & Khách hàng
                </h3>
                <div class="card-tools">
                    <!-- Time Filter Buttons - Modern Style -->
                    <style>
                        .filter-buttons {
                            display: inline-flex;
                            gap: 8px;
                            background: #f8f9fa;
                            padding: 4px;
                            border-radius: 8px;
                        }
                        .filter-buttons .btn-filter {
                            padding: 6px 16px;
                            border-radius: 6px;
                            border: none;
                            background: transparent;
                            color: #6c757d;
                            font-weight: 500;
                            font-size: 14px;
                            cursor: pointer;
                            transition: all 0.3s ease;
                        }
                        .filter-buttons .btn-filter:hover {
                            background: #e9ecef;
                            color: #495057;
                        }
                        .filter-buttons .btn-filter.active {
                            background: #007bff;
                            color: white;
                            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
                        }
                    </style>
                    <div class="filter-buttons">
                        <button type="button" class="btn-filter active" data-days="7">7 ngày</button>
                        <button type="button" class="btn-filter" data-days="14">14 ngày</button>
                        <button type="button" class="btn-filter" data-days="30">30 ngày</button>
                        <button type="button" class="btn-filter" data-days="90">90 ngày</button>
                    </div>
                    <!-- Export Button -->
                    <button type="button" class="btn btn-sm btn-success ml-2" id="exportBtn">
                        <i class="fas fa-download"></i> Export CSV
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" style="height: 350px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-fire mr-1"></i>
                    Sản phẩm bán chạy
                </h3>
            </div>
            <div class="card-body p-0" style="height: 420px; overflow-y: auto;">
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

<!-- Category & Status Charts -->
<div class="row">
    <!-- Category Pie Chart -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Top Danh mục bán chạy
                </h3>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Order Status Column Chart -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Doanh thu theo trạng thái đơn
                </h3>
            </div>
            <div class="card-body">
                <canvas id="statusChart" style="height: 300px;"></canvas>
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
    let currentDays = 7;
    let revenueChartInstance = null;
    
    // Initial Chart Data
    const initialData = {
        labels: {!! json_encode($chartData['labels']) !!},
        revenues: {!! json_encode($chartData['revenues']) !!},
        orderCounts: {!! json_encode($chartData['orderCounts']) !!},
        customerCounts: {!! json_encode($chartData['customerCounts']) !!},
        percentChanges: {!! json_encode($chartData['percentChanges']) !!}
    };
    
    // Triple-Dataset Chart: Revenue, Orders & Customers with Advanced Tooltip
    function createRevenueChart(data) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        if (revenueChartInstance) {
            revenueChartInstance.destroy();
        }
        
        revenueChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: data.revenues,
                    backgroundColor: 'rgba(0, 217, 255, 0.2)',
                    borderColor: 'rgba(0, 217, 255, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }, {
                    label: 'Số đơn hàng',
                    data: data.orderCounts,
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }, {
                    label: 'Số khách hàng mới',
                    data: data.customerCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            title: function(context) {
                                return 'Ngày: ' + context[0].label;
                            },
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.datasetIndex === 0) {
                                    label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + '₫';
                                } else if (context.datasetIndex === 1) {
                                    label += context.parsed.y + ' đơn';
                                } else {
                                    label += context.parsed.y + ' người';
                                }
                                return label;
                            },
                            afterLabel: function(context) {
                                if (context.datasetIndex === 0) {
                                    const percentChange = data.percentChanges[context.dataIndex];
                                    if (percentChange > 0) {
                                        return '↗ +' + percentChange + '% so với hôm trước';
                                    } else if (percentChange < 0) {
                                        return '↘ ' + percentChange + '% so với hôm trước';
                                    } else {
                                        return '→ Không đổi';
                                    }
                                }
                                return '';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (VNĐ)'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Đơn hàng & Khách hàng'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }
    
    // Initialize Revenue Chart
    createRevenueChart(initialData);
    
    // Time Filter Buttons - Modern Style
    $('.btn-filter').on('click', function() {
        $('.btn-filter').removeClass('active');
        $(this).addClass('active');
        currentDays = $(this).data('days');
        
        // AJAX Load Chart Data
        $.ajax({
            url: '{{ route("admin.dashboard.filter-chart") }}',
            method: 'GET',
            data: { days: currentDays },
            success: function(response) {
                createRevenueChart(response);
            },
            error: function() {
                alert('Có lỗi xảy ra khi tải dữ liệu!');
            }
        });
    });
    
    // Export CSV Button
    $('#exportBtn').on('click', function() {
        window.location.href = '{{ route("admin.dashboard.export-revenue") }}?days=' + currentDays;
    });
    
    // Category Pie Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = {!! json_encode($topCategories->pluck('name')) !!};
    const categoryRevenue = {!! json_encode($topCategories->pluck('revenue')) !!};
    
    new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: categoryData,
            datasets: [{
                data: categoryRevenue,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = new Intl.NumberFormat('vi-VN').format(context.parsed) + '₫';
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
    
    // Order Status Column Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = {!! json_encode($revenueByStatus) !!};
    
    new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: ['Chờ xử lý', 'Đang xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: [
                    statusData.pending,
                    statusData.processing,
                    statusData.shipping,
                    statusData.completed,
                    statusData.cancelled
                ],
                backgroundColor: [
                    'rgba(255, 193, 7, 0.7)',
                    'rgba(23, 162, 184, 0.7)',
                    'rgba(0, 123, 255, 0.7)',
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(220, 53, 69, 0.7)',
                ],
                borderColor: [
                    'rgba(255, 193, 7, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)',
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + '₫';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                        }
                    }
                }
            }
        }
    });
    

});
</script>
@endpush
