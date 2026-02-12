@extends('admin.layouts.app')

@section('title', 'Chi tiết đánh giá')
@section('page-title', 'Chi tiết đánh giá #' . $review->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Đánh giá</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin đánh giá</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Sản phẩm:</strong>
                    <a href="{{ route('products.show', $review->product->slug) }}" target="_blank">
                        {{ $review->product->name }}
                    </a>
                </div>

                <div class="mb-3">
                    <strong>Người đánh giá:</strong> {{ $review->user->name ?? 'Khách hàng' }}
                    @if($review->user)
                        <span class="text-muted">({{ $review->user->email }})</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Đánh giá:</strong>
                    <span class="text-warning ms-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        ({{ $review->rating }}/5)
                    </span>
                </div>

                <div class="mb-3">
                    <strong>Nội dung:</strong>
                    <div class="p-3 bg-light rounded mt-2">
                        {{ $review->comment }}
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Ngày đánh giá:</strong> {{ $review->created_at->format('d/m/Y H:i') }}
                </div>

                @if($review->admin_reply)
                    <div class="mb-3">
                        <strong>Phản hồi của admin:</strong>
                        <div class="p-3 bg-info bg-opacity-10 rounded mt-2">
                            <i class="fas fa-reply"></i> {{ $review->admin_reply }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Form phản hồi -->
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">
                    <i class="fas fa-reply"></i> 
                    {{ $review->admin_reply ? 'Cập nhật phản hồi' : 'Phản hồi đánh giá' }}
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reviews.reply', $review->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Nội dung phản hồi <span class="text-danger">*</span></label>
                        <textarea name="admin_reply" class="form-control" rows="4" required placeholder="Nhập phản hồi của bạn...">{{ $review->admin_reply }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Gửi phản hồi
                    </button>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Trạng thái</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reviews.update-status', $review->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Trạng thái đánh giá</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save"></i> Cập nhật trạng thái
                    </button>
                </form>

                <hr>

                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Xác nhận xóa đánh giá này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Xóa đánh giá
                    </button>
                </form>
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Thông tin sản phẩm</h3>
            </div>
            <div class="card-body">
                @if($review->product->image)
                    <img src="{{ asset($review->product->image) }}" class="img-fluid rounded mb-3" alt="{{ $review->product->name }}">
                @endif
                <h5>{{ $review->product->name }}</h5>
                <p class="mb-2">
                    <strong>Giá:</strong> {{ number_format($review->product->price) }}₫
                </p>
                <p class="mb-2">
                    <strong>Danh mục:</strong> {{ $review->product->category->name ?? 'N/A' }}
                </p>
                <a href="{{ route('products.show', $review->product->slug) }}" class="btn btn-sm btn-info w-100" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Xem sản phẩm
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
