@extends('admin.layouts.app')

@section('title', 'Thêm thể loại game')
@section('page-title', 'Thêm thể loại game')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.genres.index') }}">Thể loại game</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin thể loại</h3>
            </div>
            
            <form action="{{ route('admin.genres.store') }}" method="POST">
                @csrf
                
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên thể loại <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="VD: Shooting, Action, RPG..." required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Tên thể loại sẽ được sử dụng để lọc sản phẩm</small>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Mô tả ngắn về thể loại game này...">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="icon">Icon (Font Awesome)</label>
                        <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" 
                               value="{{ old('icon') }}" placeholder="VD: fas fa-gamepad">
                        @error('icon')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Nhập class Font Awesome. 
                            <a href="https://fontawesome.com/icons" target="_blank">Xem danh sách icon</a>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="order">Thứ tự</label>
                        <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" 
                               value="{{ old('order', 0) }}" min="0">
                        @error('order')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Thứ tự hiển thị (số nhỏ sẽ hiện trước)</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active" 
                                   {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Kích hoạt thể loại</label>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu thể loại
                    </button>
                    <a href="{{ route('admin.genres.index') }}" class="btn btn-default">
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
                <p><strong>Tên thể loại:</strong> Tên chính xác phải khớp với genre trong sản phẩm để lọc được.</p>
                <p><strong>Icon:</strong> Có thể để trống hoặc dùng Font Awesome icon.</p>
                <p class="mb-0"><strong>Thứ tự:</strong> Thể loại có thứ tự nhỏ sẽ hiển thị trước trong danh sách.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ví dụ Icon</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="fas fa-crosshairs"></i> <code>fas fa-crosshairs</code> - Shooting</li>
                    <li><i class="fas fa-fist-raised"></i> <code>fas fa-fist-raised</code> - Fighting</li>
                    <li><i class="fas fa-hat-wizard"></i> <code>fas fa-hat-wizard</code> - RPG</li>
                    <li><i class="fas fa-car"></i> <code>fas fa-car</code> - Racing</li>
                    <li><i class="fas fa-football-ball"></i> <code>fas fa-football-ball</code> - Sports</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
