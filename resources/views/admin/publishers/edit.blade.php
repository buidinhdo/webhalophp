@extends('admin.layouts.app')

@section('title', 'Sửa nhà phát hành')
@section('page-title', 'Sửa nhà phát hành')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.publishers.index') }}">Nhà phát hành</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sửa nhà phát hành: <strong>{{ $publisher->name }}</strong></h3>
            </div>

            <form action="{{ route('admin.publishers.update', $publisher) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên nhà phát hành <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $publisher->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            Khi đổi tên, hệ thống sẽ tự động cập nhật nhà phát hành cho tất cả sản phẩm liên quan.
                        </small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active"
                                   {{ old('is_active', $publisher->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
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
                <h3 class="card-title">Thông tin</h3>
            </div>
            <div class="card-body">
                <dl>
                    <dt>Số sản phẩm:</dt>
                    <dd>
                        <span class="badge badge-info">
                            {{ \App\Models\Product::where('publisher', $publisher->name)->count() }} sản phẩm
                        </span>
                    </dd>
                    <dt>Ngày tạo:</dt>
                    <dd>{{ $publisher->created_at->format('d/m/Y H:i') }}</dd>
                    <dt>Cập nhật:</dt>
                    <dd>{{ $publisher->updated_at->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
