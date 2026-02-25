@extends('admin.layouts.app')

@section('title', 'Quản lý sản phẩm')
@section('page-title', 'Quản lý sản phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Sản phẩm</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách sản phẩm</h3>
        <div class="card-tools">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm sản phẩm
            </a>
        </div>
    </div>
    
    <!-- Filter Form -->
    <div class="card-body border-bottom">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="category_id" class="form-control">
                    <option value="">-- Danh mục --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="genre" class="form-control">
                    <option value="">-- Thể loại --</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                            {{ $genre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">-- Trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang bán</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Lọc
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
    
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th width="80">Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Nổi bật</th>
                    <th>Trạng thái</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/60" alt="{{ $product->name }}" class="img-thumbnail">
                        @endif
                    </td>
                    <td>
                        <strong>{{ $product->name }}</strong>
                        <br>
                        <small class="text-muted">{{ $product->platform }}</small>
                    </td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>
                        @if($product->sale_price)
                            <span class="text-danger"><strong>{{ number_format($product->sale_price) }}₫</strong></span>
                            <br>
                            <small class="text-muted"><del>{{ number_format($product->price) }}₫</del></small>
                        @else
                            <strong>{{ number_format($product->price) }}₫</strong>
                        @endif
                    </td>
                    <td>
                        @if($product->stock > 10)
                            <span class="badge badge-success">{{ $product->stock }}</span>
                        @elseif($product->stock > 0)
                            <span class="badge badge-warning">{{ $product->stock }}</span>
                        @else
                            <span class="badge badge-danger">Hết hàng</span>
                        @endif
                    </td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input toggle-featured" 
                                   id="featured{{ $product->id }}" 
                                   data-id="{{ $product->id }}"
                                   {{ $product->is_featured ? 'checked' : '' }}>
                            <label class="custom-control-label" for="featured{{ $product->id }}"></label>
                        </div>
                    </td>
                    <td>
                        @switch($product->status)
                            @case('active')
                                <span class="badge badge-success">Đang bán</span>
                                @break
                            @case('inactive')
                                <span class="badge badge-secondary">Ngừng bán</span>
                                @break
                            @case('out_of_stock')
                                <span class="badge badge-danger">Hết hàng</span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-info" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <p class="text-muted">Không có sản phẩm nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    /* Làm nhỏ pagination buttons */
    .pagination {
        margin: 0;
    }
    .pagination .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 0.75rem;
    }
</style>
@endpush

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-featured').change(function() {
        var productId = $(this).data('id');
        var isChecked = $(this).is(':checked');
        
        $.post('{{ url("admin/products") }}/' + productId + '/toggle-featured', {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                toastr.success('Đã cập nhật trạng thái nổi bật');
            }
        });
    });
});
</script>
@endpush
