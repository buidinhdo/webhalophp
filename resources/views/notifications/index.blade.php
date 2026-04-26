@extends('layouts.app')

@section('title', 'Thông báo - HaloShop')

@section('content')
<style>
    .notification-link {
        display: block;
        transition: opacity 0.2s;
    }
    .notification-link:hover {
        opacity: 0.8;
        text-decoration: none !important;
    }
    .notification-link h6,
    .notification-link p {
        text-decoration: none !important;
    }
</style>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-bell me-2"></i> Thông báo</h2>
                @if($notifications->where('is_read', false)->count() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-check-double"></i> Đánh dấu tất cả đã đọc
                    </button>
                </form>
                @endif
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($notifications->count() > 0)
            <div class="card">
                <div class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                    <div class="list-group-item {{ !$notification->is_read ? 'bg-light' : '' }}">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                @if($notification->type == 'order')
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                @elseif($notification->type == 'contact')
                                    <div class="bg-light text-dark border rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-envelope-open-text"></i>
                                    </div>
                                @else
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-info"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                @if($notification->link)
                                <a href="{{ route('notifications.read', $notification->id) }}" class="notification-link text-decoration-none text-dark">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">
                                            @if(!$notification->is_read)
                                                <span class="badge bg-primary me-2">Mới</span>
                                            @endif
                                            {{ $notification->title }}
                                        </h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-2">{{ $notification->message }}</p>
                                </a>
                                @else
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">
                                        @if(!$notification->is_read)
                                            <span class="badge bg-primary me-2">Mới</span>
                                        @endif
                                        {{ $notification->title }}
                                    </h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-2">{{ $notification->message }}</p>
                                @endif
                                <div class="d-flex gap-2">
                                    @if(!$notification->is_read && !$notification->link)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-check"></i> Đánh dấu đã đọc
                                    </a>
                                    @endif
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa thông báo này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
            @endif
            @else
            <div class="alert alert-info text-center">
                <i class="fas fa-bell-slash fa-3x mb-3 d-block"></i>
                <h5>Chưa có thông báo nào</h5>
                <p class="mb-0">Các thông báo về đơn hàng và hệ thống sẽ hiển thị tại đây</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
