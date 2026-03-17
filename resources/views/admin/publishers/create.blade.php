@extends('admin.layouts.app')

@section('title', 'Thêm nhà phát hành')
@section('page-title', 'Thêm nhà phát hành')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.publishers.index') }}">Nhà phát hành</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin nhà phát hành</h3>
            </div>

            <form action="{{ route('admin.publishers.store') }}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên nhà phát hành <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="VD: Nintendo, EA, Ubisoft..." required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Tên nhà phát hành phải khớp chính xác với trường nhà phát hành trong sản phẩm.</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active"
                                   {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                    <a href="{{ route('admin.publishers.index') }}" class="btn btn-default">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hướng dẫn</h3>
            </div>
            <div class="card-body">
                <p><strong>Tên nhà phát hành:</strong> Nhập đúng tên như trên sản phẩm để hệ thống liên kết chính xác.</p>
                <p class="mb-0">Khi đổi tên, hệ thống sẽ <strong>tự động cập nhật</strong> tất cả sản phẩm liên quan.</p>
            </div>
        </div>
    </div>
</div>
@endsection
