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
                        <h3 class="card-title">
                            <i class="fas fa-user-circle mr-1"></i>{{ $customerName }}
                        </h3>
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
var lastMessageId = {{ $messages->isNotEmpty() ? $messages->last()->id : 0 }};
var chatBox   = document.querySelector('.direct-chat-messages');
var pollUrl   = '{{ route('admin.chats.new-messages', $sessionId) }}';
var replyUrl  = '{{ route('admin.chats.reply', $sessionId) }}';
var csrfToken = '{{ csrf_token() }}';

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function renderMessage(msg) {
    var product = msg.product
        ? '<div class="mt-2 p-2 border rounded"><small class="d-block font-weight-bold">' + escHtml(msg.product.name) + '</small>'
          + '<small>' + msg.product.price + ' ₫</small></div>'
        : '';

    if (msg.type === 'user') {
        var avatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(msg.user_name || 'K');
        return '<div class="direct-chat-msg right">'
            + '<div class="direct-chat-infos clearfix">'
            + '<span class="direct-chat-name float-right">' + escHtml(msg.user_name || 'Khách hàng') + '</span>'
            + '<span class="direct-chat-timestamp float-left">' + msg.created_at + '</span></div>'
            + '<img class="direct-chat-img" src="' + avatar + '" alt="User">'
            + '<div class="direct-chat-text">' + escHtml(msg.message) + product + '</div></div>';
    }
    if (msg.type === 'admin') {
        var avatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(msg.user_name || 'A') + '&background=28a745&color=fff';
        return '<div class="direct-chat-msg">'
            + '<div class="direct-chat-infos clearfix">'
            + '<span class="direct-chat-name float-left"><i class="fas fa-user-shield"></i> Admin (' + escHtml(msg.user_name || 'Quản trị viên') + ')</span>'
            + '<span class="direct-chat-timestamp float-right">' + msg.created_at + '</span></div>'
            + '<img class="direct-chat-img" src="' + avatar + '" alt="Admin">'
            + '<div class="direct-chat-text bg-success text-white">' + escHtml(msg.message) + product + '</div></div>';
    }
    // bot
    return '<div class="direct-chat-msg">'
        + '<div class="direct-chat-infos clearfix">'
        + '<span class="direct-chat-name float-left">HaloShop Bot</span>'
        + '<span class="direct-chat-timestamp float-right">' + msg.created_at + '</span></div>'
        + '<img class="direct-chat-img" src="{{ asset('images/logo/logohalo.png') }}" alt="Bot">'
        + '<div class="direct-chat-text bg-light">' + escHtml(msg.message) + product + '</div></div>';
}

// Kéo tin nhắn mới bằng AJAX — không reload trang
function pollNewMessages() {
    fetch(pollUrl + '?after=' + lastMessageId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.messages && data.messages.length > 0) {
            data.messages.forEach(function(msg) {
                chatBox.insertAdjacentHTML('beforeend', renderMessage(msg));
                if (msg.id > lastMessageId) lastMessageId = msg.id;
            });
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    })
    .catch(function() {});
}

// Gửi tin nhắn bằng AJAX — admin không bị mất nội dung đang gõ
document.querySelector('.card-footer form').addEventListener('submit', function(e) {
    e.preventDefault();
    var textarea = this.querySelector('textarea[name="message"]');
    var msg = textarea.value.trim();
    if (!msg) return;

    var btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;

    fetch(replyUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: '_token=' + encodeURIComponent(csrfToken) + '&message=' + encodeURIComponent(msg),
    })
    .then(function() {
        textarea.value = '';
        pollNewMessages();
        btn.disabled = false;
    })
    .catch(function() { btn.disabled = false; });
});

document.addEventListener('DOMContentLoaded', function() {
    chatBox.scrollTop = chatBox.scrollHeight;
    // Poll mỗi 10 giây, không bao giờ reload trang
    setInterval(pollNewMessages, 10000);
});
</script>
@endsection
