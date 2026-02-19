@extends('admin.layouts.app')

@section('title', 'Thêm sản phẩm')
@section('page-title', 'Thêm sản phẩm mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="card">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nền tảng</label>
                                <input type="text" name="platform" class="form-control" value="{{ old('platform') }}" placeholder="VD: PS5, Xbox, Nintendo Switch...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Thể loại game</label>
                                <select name="genre" class="form-control">
                                    <option value="">-- Chọn thể loại --</option>
                                    <option value="Action" {{ old('genre') == 'Action' ? 'selected' : '' }}>Action (Hành động)</option>
                                    <option value="Adventure" {{ old('genre') == 'Adventure' ? 'selected' : '' }}>Adventure (Phiêu lưu)</option>
                                    <option value="RPG" {{ old('genre') == 'RPG' ? 'selected' : '' }}>RPG (Nhập vai)</option>
                                    <option value="Shooting" {{ old('genre') == 'Shooting' ? 'selected' : '' }}>Shooting (Bắn súng)</option>
                                    <option value="Sports" {{ old('genre') == 'Sports' ? 'selected' : '' }}>Sports (Thể thao)</option>
                                    <option value="Racing" {{ old('genre') == 'Racing' ? 'selected' : '' }}>Racing (Đua xe)</option>
                                    <option value="Fighting" {{ old('genre') == 'Fighting' ? 'selected' : '' }}>Fighting (Đối kháng)</option>
                                    <option value="Simulation" {{ old('genre') == 'Simulation' ? 'selected' : '' }}>Simulation (Mô phỏng)</option>
                                    <option value="Strategy" {{ old('genre') == 'Strategy' ? 'selected' : '' }}>Strategy (Chiến thuật)</option>
                                    <option value="Horror" {{ old('genre') == 'Horror' ? 'selected' : '' }}>Horror (Kinh dị)</option>
                                    <option value="Puzzle" {{ old('genre') == 'Puzzle' ? 'selected' : '' }}>Puzzle (Giải đố)</option>
                                    <option value="Music" {{ old('genre') == 'Music' ? 'selected' : '' }}>Music (Âm nhạc)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Số người chơi</label>
                                <select name="players" class="form-control">
                                    <option value="">-- Chọn số người chơi --</option>
                                    <option value="1" {{ old('players') == '1' ? 'selected' : '' }}>1 người chơi</option>
                                    <option value="2" {{ old('players') == '2' ? 'selected' : '' }}>2 người chơi</option>
                                    <option value="3" {{ old('players') == '3' ? 'selected' : '' }}>3 người chơi</option>
                                    <option value="4" {{ old('players') == '4' ? 'selected' : '' }}>4 người chơi</option>
                                    <option value="5" {{ old('players') == '5' ? 'selected' : '' }}>5 người chơi</option>
                                    <option value="6" {{ old('players') == '6' ? 'selected' : '' }}>6 người chơi</option>
                                    <option value="7" {{ old('players') == '7' ? 'selected' : '' }}>7 người chơi</option>
                                    <option value="8" {{ old('players') == '8' ? 'selected' : '' }}>8 người chơi</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Giá gốc <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                                @error('price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Giá khuyến mãi</label>
                                <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tồn kho <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" required>
                                @error('stock')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Mô tả ngắn</label>
                        <textarea name="short_description" class="form-control" rows="2">{{ old('short_description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Hình ảnh</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Trạng thái <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Đang bán</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
                            <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Thuộc tính</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_featured">Sản phẩm nổi bật</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_new" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_new">Sản phẩm mới</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_preorder" name="is_preorder" value="1" {{ old('is_preorder') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_preorder">Đặt trước</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Danh mục nhanh</label>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_ps4" name="category_quick" value="ps4" data-category-name="PlayStation 4">
                            <label class="custom-control-label" for="cat_ps4"><i class="fab fa-playstation text-primary"></i> PlayStation 4</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_ps5" name="category_quick" value="ps5" data-category-name="PlayStation 5">
                            <label class="custom-control-label" for="cat_ps5"><i class="fab fa-playstation text-info"></i> PlayStation 5</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_nintendo" name="category_quick" value="nintendo-switch" data-category-name="Nintendo Switch">
                            <label class="custom-control-label" for="cat_nintendo"><img src="{{ asset('images/icons/nintendo-switch.svg') }}" alt="Nintendo" style="width: 18px; height: 18px;"> Nintendo Switch</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_xbox" name="category_quick" value="xbox" data-category-name="Xbox">
                            <label class="custom-control-label" for="cat_xbox"><i class="fab fa-xbox text-success"></i> Xbox</label>
                        </div>
                        <small class="form-text text-muted">Hoặc chọn từ danh mục bên trên</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Sync radio buttons with category dropdown
    $('.category-quick-select').on('change', function() {
        if ($(this).is(':checked')) {
            var categorySlug = $(this).val();
            var categoryName = $(this).data('category-name');
            
            // Find and select the matching category in dropdown
            $('select[name="category_id"] option').each(function() {
                if ($(this).text().trim() === categoryName) {
                    $('select[name="category_id"]').val($(this).val());
                    return false;
                }
            });
        }
    });
    
    // When dropdown changes, update radio button
    $('select[name="category_id"]').on('change', function() {
        var selectedText = $(this).find('option:selected').text().trim();
        $('.category-quick-select').prop('checked', false);
        
        $('.category-quick-select').each(function() {
            if ($(this).data('category-name') === selectedText) {
                $(this).prop('checked', true);
            }
        });
    });
    
    // Initialize on page load
    var currentCategoryText = $('select[name="category_id"]').find('option:selected').text().trim();
    $('.category-quick-select').each(function() {
        if ($(this).data('category-name') === currentCategoryText) {
            $(this).prop('checked', true);
        }
    });
});
</script>
@endpush
