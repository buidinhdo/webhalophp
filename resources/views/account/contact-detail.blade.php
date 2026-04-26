@extends('layouts.app')

@section('title', 'Chi tiết liên hệ - HaloShop')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-envelope-open-text"></i> Chi tiết liên hệ</h2>
        <a href="{{ route('account.contacts') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Nội dung bạn đã gửi</h5>
                </div>
                <div class="card-body">
                    <p><strong>Tiêu đề:</strong> {{ $contact->subject }}</p>
                    <p><strong>Ngày gửi:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</p>
                    <hr>
                    <p class="mb-0" style="white-space: pre-line;">{{ $contact->message }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header {{ $contact->admin_reply ? 'bg-success text-white' : 'bg-light' }}">
                    <h5 class="mb-0">Phản hồi từ admin</h5>
                </div>
                <div class="card-body">
                    @if($contact->admin_reply)
                        <p class="text-muted mb-2">Phản hồi lúc: {{ optional($contact->replied_at)->format('d/m/Y H:i') }}</p>
                        <div class="p-3 bg-light rounded" style="white-space: pre-line;">
                            {{ $contact->admin_reply }}
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            Liên hệ này chưa có phản hồi từ admin. Vui lòng quay lại sau.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Trạng thái</h6>
                </div>
                <div class="card-body">
                    @if($contact->status === 'replied')
                        <span class="badge bg-success">Đã phản hồi</span>
                    @elseif($contact->status === 'closed')
                        <span class="badge bg-secondary">Đã đóng</span>
                    @else
                        <span class="badge bg-warning text-dark">Đang chờ xử lý</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
