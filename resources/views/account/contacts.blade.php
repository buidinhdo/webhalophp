@extends('layouts.app')

@section('title', 'Liên hệ của tôi - HaloShop')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">
        <i class="fas fa-envelope"></i> Liên hệ của tôi
    </h2>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group">
                <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2"></i> Thông tin cá nhân
                </a>
                <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-box me-2"></i> Đơn hàng của tôi
                </a>
                <a href="{{ route('account.contacts') }}" class="list-group-item list-group-item-action active">
                    <i class="fas fa-envelope me-2"></i> Liên hệ của tôi
                </a>
            </div>
        </div>

        <div class="col-md-9">
            @if($contacts->count() > 0)
                @foreach($contacts as $contact)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="mb-0">{{ $contact->subject }}</h5>
                                @if($contact->status === 'replied')
                                    <span class="badge bg-success">Đã phản hồi</span>
                                @elseif($contact->status === 'closed')
                                    <span class="badge bg-secondary">Đã đóng</span>
                                @else
                                    <span class="badge bg-warning text-dark">Đang chờ</span>
                                @endif
                            </div>

                            <p class="text-muted mb-2">Gửi lúc: {{ $contact->created_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-3">{{ \Illuminate\Support\Str::limit($contact->message, 140) }}</p>

                            <a href="{{ route('account.contact-detail', $contact->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-center">
                    {{ $contacts->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <div>Bạn chưa gửi liên hệ nào.</div>
                    <a href="{{ route('contact') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-paper-plane me-2"></i> Gửi liên hệ mới
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
