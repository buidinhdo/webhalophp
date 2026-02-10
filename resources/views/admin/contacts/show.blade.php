@extends('admin.layouts.app')

@section('title', 'Chi tiết liên hệ')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Chi tiết liên hệ</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin liên hệ</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Họ tên:</strong> {{ $contact->name }}
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong> {{ $contact->email }}
                        </div>
                        @if($contact->phone)
                        <div class="mb-3">
                            <strong>Số điện thoại:</strong> {{ $contact->phone }}
                        </div>
                        @endif
                        <div class="mb-3">
                            <strong>Tiêu đề:</strong> {{ $contact->subject }}
                        </div>
                        <div class="mb-3">
                            <strong>Nội dung:</strong>
                            <p class="mt-2 p-3 bg-light rounded">{{ $contact->message }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Ngày gửi:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                @if($contact->admin_reply)
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">Phản hồi của bạn</h3>
                    </div>
                    <div class="card-body">
                        <p>{{ $contact->admin_reply }}</p>
                        <small class="text-muted">Đã phản hồi lúc: {{ $contact->replied_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Gửi phản hồi</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Nội dung phản hồi</label>
                                <textarea name="admin_reply" class="form-control" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Gửi phản hồi
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Trạng thái</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.contacts.update-status', $contact->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <select name="status" class="form-control">
                                    <option value="pending" {{ $contact->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="replied" {{ $contact->status == 'replied' ? 'selected' : '' }}>Đã phản hồi</option>
                                    <option value="closed" {{ $contact->status == 'closed' ? 'selected' : '' }}>Đã đóng</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary btn-block">Cập nhật</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                <i class="fas fa-trash"></i> Xóa liên hệ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
