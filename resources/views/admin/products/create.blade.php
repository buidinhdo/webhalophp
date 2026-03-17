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
                                        <option value="{{ $category->id }}" data-slug="{{ $category->slug }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    @foreach($genres as $genre)
                                        <option value="{{ $genre }}" {{ old('genre') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Chỉ chọn thể loại nếu sản phẩm là <strong>game</strong>. Để trống nếu là máy game hoặc phụ kiện.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nhà phát hành</label>
                                <input type="text" name="publisher" class="form-control" value="{{ old('publisher') }}" placeholder="VD: Sony, Nintendo, Koei Tecmo...">
                                <small class="form-text text-muted">Để trống nếu không có thông tin.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phân loại ESRB</label>
                                <select name="esrb_rating" class="form-control">
                                    <option value="">-- Chọn phân loại --</option>
                                    <option value="E" {{ old('esrb_rating') == 'E' ? 'selected' : '' }}>E - Everyone (Mọi lứa tuổi)</option>
                                    <option value="E10+" {{ old('esrb_rating') == 'E10+' ? 'selected' : '' }}>E10+ - Everyone 10+</option>
                                    <option value="T" {{ old('esrb_rating') == 'T' ? 'selected' : '' }}>T - Teen (13+)</option>
                                    <option value="M" {{ old('esrb_rating') == 'M' ? 'selected' : '' }}>M - Mature (17+)</option>
                                    <option value="AO" {{ old('esrb_rating') == 'AO' ? 'selected' : '' }}>AO - Adults Only (18+)</option>
                                    <option value="RP" {{ old('esrb_rating') == 'RP' ? 'selected' : '' }}>RP - Rating Pending</option>
                                </select>
                                <small class="form-text text-muted">Chỉ chọn nếu sản phẩm là <strong>game</strong>. Để trống nếu là máy hoặc phụ kiện.</small>
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
                        <label>Hình ảnh chính <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" id="main-image-input">
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <div id="main-image-preview" class="mt-2" style="display: none;">
                            <img src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ảnh phụ (Gallery) <span class="badge badge-info">Nhiều ảnh</span></label>
                        <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple id="gallery-images-input">
                        <small class="form-text text-muted">Chọn nhiều ảnh cùng lúc (Ctrl+Click hoặc Shift+Click)</small>
                        <div id="gallery-preview" class="row mt-2"></div>
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
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_ps1" name="category_quick" value="playstation-1" data-category-name="PlayStation 1">
                            <label class="custom-control-label" for="cat_ps1"><i class="fab fa-playstation" style="color: #003087;"></i> PlayStation 1</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_ps2" name="category_quick" value="playstation-2" data-category-name="PlayStation 2">
                            <label class="custom-control-label" for="cat_ps2"><i class="fab fa-playstation" style="color: #003087;"></i> PlayStation 2</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_ps3" name="category_quick" value="playstation-3" data-category-name="PlayStation 3">
                            <label class="custom-control-label" for="cat_ps3"><i class="fab fa-playstation" style="color: #0051a8;"></i> PlayStation 3</label>
                        </div>
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
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_gamecube" name="category_quick" value="nintendo-gamecube" data-category-name="Nintendo GameCube">
                            <label class="custom-control-label" for="cat_gamecube"><img src="{{ asset('images/icons/gamecube.svg') }}" alt="GameCube" style="width: 24px; height: 24px; vertical-align: middle;"> Nintendo GameCube</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_wii" name="category_quick" value="wii" data-category-name="Wii">
                            <label class="custom-control-label" for="cat_wii"><img src="{{ asset('images/icons/wii.svg') }}" alt="Wii" style="width: 24px; height: 24px; vertical-align: middle;"> Wii</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input category-quick-select" id="cat_snes" name="category_quick" value="super-nintendo" data-category-name="Super Nintendo">
                            <label class="custom-control-label" for="cat_snes"><img src="{{ asset('images/icons/super-nintendo.svg') }}" alt="Super Nintendo" style="width: 60px; height: 18px; vertical-align: middle;"> Super Nintendo</label>
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
            var radioSlug = $(this).val();
            
            // Find and select the matching category in dropdown by slug
            $('select[name="category_id"] option').each(function() {
                var optionSlug = $(this).data('slug');
                var matched = false;
                
                // Handle slug variations
                if (radioSlug === 'super-nintendo' && (optionSlug === 'super-nintendo' || optionSlug === 'super-nintedo' || optionSlug === 'snes')) {
                    matched = true;
                } else if (radioSlug === 'playstation-1' && (optionSlug === 'playstation-1' || optionSlug === 'ps1')) {
                    matched = true;
                } else if (radioSlug === 'wii' && (optionSlug === 'wii' || optionSlug === 'nintendo-wii')) {
                    matched = true;
                } else if (radioSlug === optionSlug) {
                    matched = true;
                }
                
                if (matched) {
                    $('select[name="category_id"]').val($(this).val());
                    return false;
                }
            });
        }
    });
    
    // When dropdown changes, update radio button
    $('select[name="category_id"]').on('change', function() {
        var selectedSlug = $(this).find('option:selected').data('slug');
        $('.category-quick-select').prop('checked', false);
        
        $('.category-quick-select').each(function() {
            var radioSlug = $(this).val();
            // Handle slug variations (e.g., super-nintendo vs super-nintedo typo)
            if (radioSlug === 'super-nintendo' && (selectedSlug === 'super-nintendo' || selectedSlug === 'super-nintedo' || selectedSlug === 'snes')) {
                $(this).prop('checked', true);
            } else if (radioSlug === 'playstation-1' && (selectedSlug === 'playstation-1' || selectedSlug === 'ps1')) {
                $(this).prop('checked', true);
            } else if (radioSlug === 'wii' && (selectedSlug === 'wii' || selectedSlug === 'nintendo-wii')) {
                $(this).prop('checked', true);
            } else if (radioSlug === selectedSlug) {
                $(this).prop('checked', true);
            }
        });
    });
    
    // Trigger on page load to sync radio button with selected category
    $('select[name="category_id"]').trigger('change');
    
    // Preview main image
    $('#main-image-input').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#main-image-preview').show().find('img').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Preview gallery images
    $('#gallery-images-input').on('change', function(e) {
        $('#gallery-preview').empty();
        const files = e.target.files;
        
        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const col = $('<div class="col-3 mb-2"></div>');
                    const img = $('<img class="img-thumbnail" style="max-width: 100%; height: 80px; object-fit: cover;">');
                    img.attr('src', e.target.result);
                    col.append(img);
                    $('#gallery-preview').append(col);
                };
                
                reader.readAsDataURL(file);
            }
        }
    });
});
</script>
@endpush
