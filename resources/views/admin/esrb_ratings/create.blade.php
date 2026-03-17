@extends('admin.layouts.app')

@section('title', 'Thêm phân loại ESRB')
@section('page-title', 'Thêm phân loại ESRB')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.esrb-ratings.index') }}">Phân loại ESRB</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin phân loại ESRB</h3>
            </div>

            <form action="{{ route('admin.esrb-ratings.store') }}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label for="code">Mã ESRB <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code"
                               class="form-control @error('code') is-invalid @enderror"
                               value="{{ old('code') }}" placeholder="VD: E, E10+, T, M, AO, RP" required
                               style="text-transform: uppercase;">
                        @error('code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Mã phân loại ngắn, phải khớp với trường <code>esrb_rating</code> trong sản phẩm.</small>
                    </div>

                    <div class="form-group">
                        <label for="name">Tên phân loại <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="VD: Everyone, Teen, Mature 17+..." required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Mô tả nội dung phù hợp với phân loại này...">{{ old('description') }}</textarea>
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
                                       value="{{ old('min_age') }}" min="0" max="99" placeholder="Để trống nếu không giới hạn">
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
                                       value="{{ old('order', 0) }}" min="0">
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
                                   {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
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
                <h3 class="card-title">Các mã ESRB chuẩn</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Mã</th><th>Tên</th><th>Tuổi</th></tr></thead>
                    <tbody>
                        <tr><td><strong>RP</strong></td><td>Rating Pending</td><td>—</td></tr>
                        <tr><td><strong>E</strong></td><td>Everyone</td><td>6+</td></tr>
                        <tr><td><strong>E10+</strong></td><td>Everyone 10+</td><td>10+</td></tr>
                        <tr><td><strong>T</strong></td><td>Teen</td><td>13+</td></tr>
                        <tr><td><strong>M</strong></td><td>Mature 17+</td><td>17+</td></tr>
                        <tr><td><strong>AO</strong></td><td>Adults Only</td><td>18+</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
