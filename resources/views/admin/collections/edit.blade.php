@extends('admin.layouts.app')

@section('title', 'Sửa bộ sưu tập')
@section('page-title', 'Sửa bộ sưu tập')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.collections.index') }}">Bộ sưu tập</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
<div class="card">
    <form action="{{ route('admin.collections.update', $collection->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Tên bộ sưu tập <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $collection->name) }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $collection->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>Chọn sản phẩm</label>
                <select name="products[]" class="form-control" multiple size="10">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ in_array($product->id, old('products', $collection->products->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $product->name }} - {{ number_format($product->price) }}₫
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Giữ Ctrl để chọn nhiều sản phẩm</small>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $collection->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Kích hoạt</label>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.collections.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>
@endsection
