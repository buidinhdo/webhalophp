@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit me-2"></i> Chỉnh sửa mã giảm giá</h2>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $coupon->code) }}" placeholder="VD: SUMMER2026" required>
                            <small class="text-muted">Mã sẽ tự động chuyển thành chữ hoa</small>
                            @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $coupon->description) }}" placeholder="VD: Giảm giá mùa hè 2026">
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                            <select name="type" id="couponType" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định (đ)</option>
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Giá trị giảm <span class="text-danger">*</span></label>
                            <input type="number" name="value" id="couponValue" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $coupon->value) }}" min="0" step="0.01" placeholder="VD: 10 hoặc 50000" required>
                            <small class="text-muted" id="valueHint">Nhập % hoặc số tiền</small>
                            @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4" id="maxDiscountDiv">
                        <div class="mb-3">
                            <label class="form-label">Giảm tối đa (đ)</label>
                            <input type="number" name="max_discount" class="form-control @error('max_discount') is-invalid @enderror" value="{{ old('max_discount', $coupon->max_discount) }}" min="0" placeholder="VD: 100000">
                            <small class="text-muted">Chỉ áp dụng cho loại %</small>
                            @error('max_discount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Đơn hàng tối thiểu (đ) <span class="text-danger">*</span></label>
                            <input type="number" name="min_order_value" class="form-control @error('min_order_value') is-invalid @enderror" value="{{ old('min_order_value', $coupon->min_order_value) }}" min="0" placeholder="VD: 500000" required>
                            @error('min_order_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Giới hạn sử dụng</label>
                            <input type="number" name="usage_limit" class="form-control @error('usage_limit') is-invalid @enderror" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1" placeholder="Để trống = không giới hạn">
                            <small class="text-muted">Đã dùng: {{ $coupon->used_count }}</small>
                            @error('usage_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Giới hạn mỗi người <span class="text-danger">*</span></label>
                            <input type="number" name="usage_per_user" class="form-control @error('usage_per_user') is-invalid @enderror" value="{{ old('usage_per_user', $coupon->usage_per_user) }}" min="1" required>
                            @error('usage_per_user')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Ngày bắt đầu</label>
                            <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d\TH:i') : '') }}">
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Ngày kết thúc</label>
                            <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $coupon->end_date ? $coupon->end_date->format('Y-m-d\TH:i') : '') }}">
                            @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">
                            Kích hoạt mã
                        </label>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật mã giảm giá
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle max discount field based on coupon type
    document.getElementById('couponType').addEventListener('change', function() {
        const maxDiscountDiv = document.getElementById('maxDiscountDiv');
        const valueHint = document.getElementById('valueHint');
        
        if (this.value === 'percentage') {
            maxDiscountDiv.style.display = 'block';
            valueHint.textContent = 'Nhập % (VD: 10 = giảm 10%)';
        } else {
            maxDiscountDiv.style.display = 'none';
            valueHint.textContent = 'Nhập số tiền (VD: 50000)';
        }
    });

    // Trigger on load
    document.getElementById('couponType').dispatchEvent(new Event('change'));
</script>
@endsection
