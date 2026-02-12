@extends('layouts.app')

@section('title', 'Thanh toán chuyển khoản - HaloShop')

@section('styles')
<style>
    .payment-container {
        max-width: 900px;
        margin: 0 auto;
    }
    .bank-card {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }
    .bank-card:hover {
        border-color: #007bff;
        box-shadow: 0 4px 8px rgba(0,123,255,0.2);
    }
    .bank-card.selected {
        border-color: #007bff;
        background-color: #e7f3ff;
    }
    .bank-logo {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin: 0 auto 10px;
        display: block;
        background: white;
        border-radius: 10px;
        padding: 5px;
    }
    .qr-container {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
    }
    .qr-code-image {
        max-width: 350px;
        width: 100%;
        height: auto;
        margin: 20px auto;
        border: 3px solid #007bff;
        border-radius: 10px;
        padding: 10px;
        background: white;
    }
    .info-box {
        background: #f8f9fa;
        border-left: 4px solid #007bff;
        padding: 20px;
        margin: 20px 0;
        border-radius: 5px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #dee2e6;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .copy-btn {
        cursor: pointer;
        color: #007bff;
        margin-left: 10px;
    }
    .copy-btn:hover {
        color: #0056b3;
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .qr-container {
            box-shadow: none;
        }
    }
</style>
@endsection

@section('content')
<div class="container my-5 payment-container">
    <div class="text-center mb-4">
        <h2 class="mb-2"><i class="fas fa-qrcode"></i> Thanh toán chuyển khoản</h2>
        <p class="text-muted">Đơn hàng #{{ $order->order_number }}</p>
    </div>

    <div class="alert alert-info no-print">
        <i class="fas fa-info-circle"></i> 
        <strong>Hướng dẫn:</strong> Quét mã QR bằng ứng dụng ngân hàng của bạn hoặc chuyển khoản thủ công theo thông tin bên dưới.
    </div>

    <!-- Chọn ngân hàng -->
    <div class="card mb-4 no-print">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-university"></i> Chọn ngân hàng</h5>
        </div>
        <div class="card-body">
            <div class="row g-3" id="bank-list">
                @foreach($banks as $code => $bank)
                <div class="col-6 col-md-3">
                    <div class="bank-card" data-bank-code="{{ $code }}" data-bank-bin="{{ $bank['bin'] }}" data-bank-name="{{ $bank['name'] }}">
                        <img src="{{ $bank['logo'] }}" alt="{{ $bank['name'] }}" class="bank-logo" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Ctext y=%22.9em%22 font-size=%2290%22%3E🏦%3C/text%3E%3C/svg%3E';">
                        <strong>{{ $bank['name'] }}</strong>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- QR Code và thông tin -->
    <div class="row">
        <div class="col-md-6">
            <div class="qr-container">
                <h5 class="mb-3">Mã QR thanh toán</h5>
                <div id="qr-code-wrapper">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-hand-pointer fa-3x mb-3"></i>
                        <p>Vui lòng chọn ngân hàng bên trên</p>
                    </div>
                </div>
                <div class="mt-3 no-print">
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> In mã QR
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin chuyển khoản</h5>
                </div>
                <div class="card-body">
                    <div class="info-box">
                        <div class="info-row">
                            <strong>Ngân hàng:</strong>
                            <span id="selected-bank-name">---</span>
                        </div>
                        <div class="info-row">
                            <strong>Số tài khoản:</strong>
                            <span>
                                <span id="account-number">{{ config('banks.account_number') }}</span>
                                <i class="fas fa-copy copy-btn" onclick="copyToClipboard('{{ config('banks.account_number') }}', 'Đã copy số tài khoản!')" title="Copy"></i>
                            </span>
                        </div>
                        <div class="info-row">
                            <strong>Chủ tài khoản:</strong>
                            <span id="account-name">{{ config('banks.account_name') }}</span>
                        </div>
                        <div class="info-row">
                            <strong>Số tiền:</strong>
                            <span class="text-danger fw-bold">
                                <span id="amount">{{ number_format($order->total_amount) }}</span>₫
                                <i class="fas fa-copy copy-btn" onclick="copyToClipboard('{{ $order->total_amount }}', 'Đã copy số tiền!')" title="Copy"></i>
                            </span>
                        </div>
                        <div class="info-row">
                            <strong>Nội dung CK:</strong>
                            <span>
                                <span id="transfer-content">{{ $order->order_number }}</span>
                                <i class="fas fa-copy copy-btn" onclick="copyToClipboard('{{ $order->order_number }}', 'Đã copy nội dung!')" title="Copy"></i>
                            </span>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Lưu ý:</strong> Vui lòng chuyển khoản đúng số tiền và ghi đúng nội dung để đơn hàng được xử lý nhanh nhất.
                    </div>

                    <!-- Thông tin đơn hàng -->
                    <div class="mt-4">
                        <h6><strong>Chi tiết đơn hàng:</strong></h6>
                        <ul class="list-unstyled">
                            @foreach($order->items as $item)
                            <li class="mb-2">
                                <i class="fas fa-box text-primary"></i>
                                {{ $item->product_name }} 
                                <span class="text-muted">(x{{ $item->quantity }})</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-4 d-grid gap-2 no-print">
                        <a href="{{ route('checkout.success', $order->id) }}" class="btn btn-success btn-lg">
                            <i class="fas fa-check-circle"></i> Tôi đã thanh toán
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home"></i> Về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    const orderAmount = {{ $order->total_amount }};
    const orderContent = '{{ $order->order_number }}';
    const accountNumber = '{{ config('banks.account_number') }}';
    const accountName = '{{ config('banks.account_name') }}';

    // Xử lý chọn ngân hàng
    document.querySelectorAll('.bank-card').forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            document.querySelectorAll('.bank-card').forEach(c => c.classList.remove('selected'));
            
            // Add selected class to clicked card
            this.classList.add('selected');
            
            const bankCode = this.getAttribute('data-bank-code');
            const bankBin = this.getAttribute('data-bank-bin');
            const bankName = this.getAttribute('data-bank-name');
            
            // Cập nhật tên ngân hàng
            document.getElementById('selected-bank-name').textContent = bankName;
            
            // Tạo QR code sử dụng VietQR API
            const qrUrl = `https://img.vietqr.io/image/${bankBin}-${accountNumber}-compact2.jpg?amount=${orderAmount}&addInfo=${orderContent}&accountName=${encodeURIComponent(accountName)}`;
            
            document.getElementById('qr-code-wrapper').innerHTML = `
                <img src="${qrUrl}" alt="QR Code" class="qr-code-image" />
                <p class="text-muted mt-2"><small>Quét mã QR để thanh toán tự động</small></p>
            `;
        });
    });

    // Copy to clipboard function
    function copyToClipboard(text, message) {
        navigator.clipboard.writeText(text).then(() => {
            alert(message || 'Đã copy!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    // Auto select first bank
    document.addEventListener('DOMContentLoaded', function() {
        const firstBank = document.querySelector('.bank-card');
        if (firstBank) {
            firstBank.click();
        }
    });
</script>
@endsection
@endsection
