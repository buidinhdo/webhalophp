@extends('admin.layouts.app')

@section('title', 'Sửa thể loại game')
@section('page-title', 'Sửa thể loại game')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.genres.index') }}">Thể loại game</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sửa thông tin thể loại: <strong>{{ $genre->name }}</strong></h3>
            </div>
            
            <form action="{{ route('admin.genres.update', $genre) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên thể loại <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $genre->name) }}" placeholder="VD: Shooting, Action, RPG..." required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            Khi đổi tên, hệ thống sẽ tự động cập nhật tên thể loại cho tất cả sản phẩm liên quan
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="order">Thứ tự</label>
                        <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" 
                               value="{{ old('order', $genre->order) }}" min="0">
                        @error('order')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Thứ tự hiển thị (số nhỏ sẽ hiện trước)</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active" 
                                   {{ old('is_active', $genre->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Kích hoạt thể loại</label>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
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
                <h3 class="card-title">Thông tin</h3>
            </div>
            <div class="card-body">
                <dl>
                    <dt>Số sản phẩm:</dt>
                    <dd>
                        <span class="badge badge-info">
                            {{ \App\Models\Product::where('genre', $genre->name)->count() }} sản phẩm
                        </span>
                    </dd>

                    <dt>Slug:</dt>
                    <dd><code>{{ $genre->slug }}</code></dd>

                    <dt>Ngày tạo:</dt>
                    <dd>{{ $genre->created_at->format('d/m/Y H:i') }}</dd>

                    <dt>Cập nhật:</dt>
                    <dd>{{ $genre->updated_at->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
