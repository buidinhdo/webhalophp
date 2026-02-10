@extends('admin.layouts.app')

@section('title', 'Chi tiết Chat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Chi tiết Chat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.chats.index') }}">Chat</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <!-- Chat Messages -->
            <div class="col-md-12">
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header">
                        <h3 class="card-title">Cuộc trò chuyện</h3>
                        <div class="card-tools">
                            <span class="badge bg-info">{{ $messages->count() }} tin nhắn</span>
                            <form action="{{ route('admin.chats.destroy', $sessionId) }}" method="POST" class="d-inline ml-2" onsubmit="return confirm('Bạn có chắc muốn xóa cuộc trò chuyện này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-tool" title="Xóa">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="direct-chat-messages" style="height: 500px;">
                            @forelse($messages as $message)
                                @if($message->type === 'user')
                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-right">
                                                {{ $message->user ? $message->user->name : 'Khách hàng' }}
                                            </span>
                                            <span class="direct-chat-timestamp float-left">{{ $message->created_at->format('H:i d/m/Y') }}</span>
                                        </div>
                                        <img class="direct-chat-img" src="https://ui-avatars.com/api/?name={{ $message->user ? urlencode($message->user->name) : 'K' }}" alt="User">
                                        <div class="direct-chat-text">
                                            {{ $message->message }}
                                        </div>
                                    </div>
                                @elseif($message->type === 'bot')
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">HaloShop Bot</span>
                                            <span class="direct-chat-timestamp float-right">{{ $message->created_at->format('H:i d/m/Y') }}</span>
                                        </div>
                                        <img class="direct-chat-img" src="{{ asset('images/logo/logohalo.png') }}" alt="Bot">
                                        <div class="direct-chat-text bg-light">
                                            {{ $message->message }}
                                            @if($message->product)
                                                <div class="mt-2 p-2 border rounded">
                                                    <small class="d-block font-weight-bold">{{ $message->product->name }}</small>
                                                    <small class="text-danger">{{ number_format($message->product->price) }} ₫</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">
                                                <i class="fas fa-user-shield"></i> Admin ({{ $message->user ? $message->user->name : 'Quản trị viên' }})
                                            </span>
                                            <span class="direct-chat-timestamp float-right">{{ $message->created_at->format('H:i d/m/Y') }}</span>
                                        </div>
                                        <img class="direct-chat-img" src="https://ui-avatars.com/api/?name={{ $message->user ? urlencode($message->user->name) : 'A' }}&background=28a745&color=fff" alt="Admin">
                                        <div class="direct-chat-text bg-success text-white">
                                            {{ $message->message }}
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="text-center py-5">
                                    <p class="text-muted">Chưa có tin nhắn nào</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card-footer">
                        <form action="{{ route('admin.chats.reply', $sessionId) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" placeholder="Nhập tin nhắn của bạn..." rows="2" required></textarea>
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Gửi
                                    </button>
                                </span>
                            </div>
                            @error('message')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.direct-chat-messages {
    overflow-y: auto;
}
.direct-chat-text {
    margin: 5px 0 0 50px;
    border-radius: 10px;
}
.direct-chat-msg.right .direct-chat-text {
    margin-left: 0;
    margin-right: 50px;
}
</style>

<script>
// Auto scroll to bottom
document.addEventListener('DOMContentLoaded', function() {
    var chatMessages = document.querySelector('.direct-chat-messages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Auto refresh every 15 seconds to show new messages from users
    // Tăng lên 15 giây để admin có thời gian đọc và trả lời
    setInterval(function() {
        location.reload();
    }, 15000);
});
</script>
@endsection
