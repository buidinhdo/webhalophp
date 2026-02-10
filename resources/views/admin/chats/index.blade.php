@extends('admin.layouts.app')

@section('title', 'Quản lý Chat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Chat</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách cuộc trò chuyện</h3>
            </div>
            <div class="card-body p-0">
                @if($sessions->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có cuộc trò chuyện nào</p>
                    </div>
                @else
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 300px">Session ID</th>
                                <th>Số tin nhắn</th>
                                <th>Tin nhắn cuối</th>
                                <th style="width: 150px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $session)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.chats.show', $session->session_id) }}" class="text-decoration-none">
                                        <code>{{ Str::limit($session->session_id, 30) }}</code>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $session->message_count }} tin nhắn</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($session->last_message_at)->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('admin.chats.show', $session->session_id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <form action="{{ route('admin.chats.destroy', $session->session_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa cuộc trò chuyện này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            @if($sessions->isNotEmpty())
            <div class="card-footer clearfix">
                {{ $sessions->links() }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
