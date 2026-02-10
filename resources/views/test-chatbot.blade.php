<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Chatbot - HaloShop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .test-area {
            border: 2px solid #667eea;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        input, button {
            padding: 10px;
            margin: 5px;
            font-size: 16px;
        }
        #result {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
    </style>
</head>
<body>
    <h1>🧪 Test Chatbot System</h1>
    
    <div class="test-area">
        <h2>Bước 1: Tạo Session ID</h2>
        <button onclick="createSession()">Tạo Session Mới</button>
        <p><strong>Session ID:</strong> <span id="sessionId">Chưa có</span></p>
    </div>
    
    <div class="test-area">
        <h2>Bước 2: Gửi Tin Nhắn Test</h2>
        <input type="text" id="message" placeholder="Nhập tin nhắn..." style="width: 60%;">
        <button onclick="sendMessage()">Gửi</button>
    </div>
    
    <div class="test-area">
        <h2>Bước 3: Kiểm tra Database</h2>
        <button onclick="checkDatabase()">Kiểm Tra Tin Nhắn</button>
    </div>
    
    <div id="result"></div>

    <script>
        let sessionId = null;
        
        function log(message, type = 'info') {
            const result = document.getElementById('result');
            const timestamp = new Date().toLocaleTimeString();
            result.innerHTML += `<div class="${type}">[${timestamp}] ${message}</div>`;
        }
        
        function createSession() {
            sessionId = 'test_session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            document.getElementById('sessionId').textContent = sessionId;
            log('✓ Session ID đã được tạo: ' + sessionId, 'success');
        }
        
        async function sendMessage() {
            if (!sessionId) {
                alert('Vui lòng tạo Session ID trước!');
                return;
            }
            
            const message = document.getElementById('message').value;
            if (!message) {
                alert('Vui lòng nhập tin nhắn!');
                return;
            }
            
            log('→ Đang gửi tin nhắn: "' + message + '"', 'info');
            
            try {
                const response = await fetch('/chatbot/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: message,
                        session_id: sessionId
                    })
                });
                
                log('← Status: ' + response.status, response.ok ? 'success' : 'error');
                
                const data = await response.json();
                log('← Response: ' + JSON.stringify(data, null, 2), data.success ? 'success' : 'error');
                
                if (data.success) {
                    log('✓ Bot trả lời: ' + data.bot_message, 'success');
                    if (data.products && data.products.length > 0) {
                        log('✓ Tìm thấy ' + data.products.length + ' sản phẩm', 'success');
                    }
                    document.getElementById('message').value = '';
                }
            } catch (error) {
                log('✗ Lỗi: ' + error.message, 'error');
            }
        }
        
        async function checkDatabase() {
            if (!sessionId) {
                alert('Vui lòng tạo Session ID và gửi tin nhắn trước!');
                return;
            }
            
            log('→ Đang kiểm tra database...', 'info');
            
            try {
                const response = await fetch('/chatbot/history?session_id=' + sessionId);
                const data = await response.json();
                
                if (data.success) {
                    log('✓ Tìm thấy ' + data.messages.length + ' tin nhắn trong database', 'success');
                    data.messages.forEach((msg, index) => {
                        log(`  ${index + 1}. [${msg.type}] ${msg.message}`, 'info');
                    });
                } else {
                    log('✗ Không tìm thấy tin nhắn', 'error');
                }
            } catch (error) {
                log('✗ Lỗi khi kiểm tra: ' + error.message, 'error');
            }
        }
    </script>
</body>
</html>
