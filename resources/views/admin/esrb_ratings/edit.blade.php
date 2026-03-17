@extends('admin.layouts.app')

@section('title', 'Sửa phân loại ESRB')
@section('page-title', 'Sửa phân loại ESRB')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.esrb-ratings.index') }}">Phân loại ESRB</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Sửa phân loại: <strong>{{ $esrbRating->code }}</strong> — {{ $esrbRating->name }}
                </h3>
            </div>

            <form action="{{ route('admin.esrb-ratings.update', $esrbRating) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="form-group">
                        <label for="code">Mã ESRB <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code"
                               class="form-control @error('code') is-invalid @enderror"
                               value="{{ old('code', $esrbRating->code) }}" required
                               style="text-transform: uppercase;">
                        @error('code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            Khi đổi mã, hệ thống sẽ tự động cập nhật cho tất cả sản phẩm liên quan.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="name">Tên phân loại <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $esrbRating->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $esrbRating->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="min_age">Tuổi tối thiểu</label>
                                <input type="number" name="min_age" id="min_age"
                                       class="form-control @error('min_age') is-invalid @enderror"
                                       value="{{ old('min_age', $esrbRating->min_age) }}" min="0" max="99">
                                @error('min_age')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="order">Thứ tự hiển thị</label>
                                <input type="number" name="order" id="order"
                                       class="form-control @error('order') is-invalid @enderror"
                                       value="{{ old('order', $esrbRating->order) }}" min="0">
                                @error('order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active"
                                   {{ old('is_active', $esrbRating->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                    <a href="{{ route('admin.esrb-ratings.index') }}" class="btn btn-default">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin</h3>
            </div>
            <div class="card-body">
                <dl>
                    <dt>Số sản phẩm:</dt>
                    <dd>
                        <span class="badge badge-info">
                            {{ \App\Models\Product::where('esrb_rating', $esrbRating->code)->count() }} sản phẩm
                        </span>
                    </dd>
                    <dt>Ngày tạo:</dt>
                    <dd>{{ $esrbRating->created_at->format('d/m/Y H:i') }}</dd>
                    <dt>Cập nhật:</dt>
                    <dd>{{ $esrbRating->updated_at->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
