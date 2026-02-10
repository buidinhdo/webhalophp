<!-- Chatbot Widget -->
<div id="chatbot-widget" class="chatbot-widget">
    <div class="chatbot-button" onclick="toggleChatbot()">
        <img src="{{ asset('images/logo/logohalo.png') }}" alt="HaloShop Chat" class="chatbot-logo">
        <span class="chatbot-badge" id="chat-badge" style="display: none;">0</span>
    </div>
    
    <div class="chatbot-container" id="chatbot-container" style="display: none;">
        <div class="chatbot-header">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/logo/logohalo.png') }}" alt="HaloShop" class="chatbot-header-logo me-2">
                <div>
                    <h6 class="mb-0">HaloShop Tư Vấn</h6>
                    <small class="text-white-50">Hỗ trợ 24/7</small>
                </div>
            </div>
            <button class="btn-close btn-close-white" onclick="toggleChatbot()"></button>
        </div>
        
        <div class="chatbot-messages" id="chatbot-messages">
            <div class="message bot-message">
                <div class="message-avatar">
                    <img src="{{ asset('images/logo/logohalo.png') }}" alt="Bot">
                </div>
                <div class="message-content">
                    Xin chào! Tôi là trợ lý ảo của HaloShop. Tôi có thể giúp bạn tìm kiếm và tư vấn về các sản phẩm game. Bạn đang tìm kiếm sản phẩm gì?
                </div>
            </div>
        </div>
        
        <div class="chatbot-products" id="chatbot-products" style="display: none;"></div>
        
        <div class="chatbot-input">
            <input type="text" id="chat-input" class="form-control" placeholder="Nhập tin nhắn..." onkeypress="handleChatKeyPress(event)">
            <button class="btn btn-primary" onclick="sendChatMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<style>
.chatbot-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
}

.chatbot-button {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    position: relative;
}

.chatbot-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.chatbot-logo {
    width: 35px;
    height: 35px;
    object-fit: contain;
}

.chatbot-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.chatbot-badge.pulse {
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
    }
    50% {
        transform: scale(1.1);
        box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
    }
}

.chatbot-container {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 380px;
    max-width: calc(100vw - 40px);
    height: 550px;
    max-height: calc(100vh - 120px);
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chatbot-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chatbot-header-logo {
    width: 32px;
    height: 32px;
    object-fit: contain;
    background: white;
    border-radius: 50%;
    padding: 3px;
}

.chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background: #f8f9fa;
}

.message {
    display: flex;
    margin-bottom: 15px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    margin-right: 10px;
}

.message-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.message-content {
    max-width: 75%;
    padding: 10px 14px;
    border-radius: 12px;
    word-wrap: break-word;
}

.bot-message .message-content,
.admin-message .message-content {
    background: white;
    color: #333;
    border: 1px solid #e0e0e0;
}

.user-message {
    flex-direction: row-reverse;
}

.user-message .message-avatar {
    margin-right: 0;
    margin-left: 10px;
}

.user-message .message-content {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.admin-message .message-content {
    background: #d4edda;
    border-color: #28a745;
}

.admin-content {
    display: flex;
    flex-direction: column;
}

.admin-label {
    color: #28a745;
    font-weight: 600;
    margin-bottom: 4px;
    display: block;
}

.chatbot-products {
    max-height: 200px;
    overflow-y: auto;
    border-top: 1px solid #e0e0e0;
    background: white;
    padding: 10px;
}

.product-card-mini {
    display: flex;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    color: inherit;
}

.product-card-mini:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.product-card-mini img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    margin-right: 10px;
}

.product-card-mini-info {
    flex: 1;
}

.product-card-mini-info h6 {
    font-size: 13px;
    margin: 0 0 5px 0;
    font-weight: 600;
}

.product-card-mini-info .price {
    color: #dc3545;
    font-weight: bold;
    font-size: 14px;
}

.product-card-mini-info .platform {
    font-size: 11px;
    color: #666;
}

.chatbot-input {
    display: flex;
    padding: 15px;
    border-top: 1px solid #e0e0e0;
    background: white;
}

.chatbot-input input {
    flex: 1;
    border: 1px solid #e0e0e0;
    border-radius: 20px;
    padding: 8px 15px;
    margin-right: 10px;
}

.chatbot-input button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.typing-indicator {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    background: white;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #999;
    margin: 0 2px;
    animation: typing 1.4s infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-10px);
    }
}

@media (max-width: 768px) {
    .chatbot-container {
        width: calc(100vw - 40px);
        height: calc(100vh - 120px);
        bottom: 90px;
        right: 20px;
    }
}
</style>

<script>
let chatSessionId = null;
let lastMessageId = 0;
let pollingInterval = null;

function getOrCreateSessionId() {
    if (!chatSessionId) {
        chatSessionId = localStorage.getItem('chat_session_id');
        if (!chatSessionId) {
            // Tạo session mới khi người dùng bắt đầu chat
            chatSessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('chat_session_id', chatSessionId);
        }
    }
    return chatSessionId;
}

function toggleChatbot() {
    const container = document.getElementById('chatbot-container');
    if (container.style.display === 'none') {
        container.style.display = 'flex';
        document.getElementById('chat-input').focus();
        
        // Tạo session khi mở chatbot lần đầu
        getOrCreateSessionId();
        
        // Load lịch sử nếu có
        if (chatSessionId) {
            loadChatHistory();
            hideNotificationBadge();
            startPolling();
        }
    } else {
        container.style.display = 'none';
        if (chatSessionId) {
            stopPolling();
            startBackgroundPolling();
        }
    }
}

function startBackgroundPolling() {
    // Chỉ poll nếu đã có session
    if (!chatSessionId) return;
    
    // Poll every 10 seconds when chatbot is closed
    if (pollingInterval) clearInterval(pollingInterval);
    pollingInterval = setInterval(checkNewMessages, 10000);
}

function startPolling() {
    // Chỉ poll nếu đã có session
    if (!chatSessionId) return;
    
    // Poll every 3 seconds when chatbot is open
    if (pollingInterval) clearInterval(pollingInterval);
    pollingInterval = setInterval(checkNewMessages, 3000);
}

function stopPolling() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }
}

function checkNewMessages() {
    // Chỉ check nếu đã có session
    if (!chatSessionId) return;
    
    fetch(`/chatbot/new-messages?session_id=${chatSessionId}&last_message_id=${lastMessageId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages.length > 0) {
                let hasAdminMessage = false;
                data.messages.forEach(msg => {
                    if (msg.type === 'admin') {
                        appendMessage('admin', msg.message, true, msg.user?.name || 'Admin');
                        hasAdminMessage = true;
                    } else if (msg.type === 'bot') {
                        appendMessage('bot', msg.message, true);
                    }
                    lastMessageId = msg.id;
                });
                
                // Show notification if chatbot is closed and admin replied
                const container = document.getElementById('chatbot-container');
                if (hasAdminMessage && container.style.display === 'none') {
                    showNotificationBadge();
                }
            }
        })
        .catch(error => {
            console.error('Error checking new messages:', error);
        });
}

function showNotificationBadge() {
    const badge = document.getElementById('chat-badge');
    if (badge) {
        badge.style.display = 'flex';
        badge.textContent = '!';
        // Animate badge
        badge.classList.add('pulse');
    }
}

function hideNotificationBadge() {
    const badge = document.getElementById('chat-badge');
    if (badge) {
        badge.style.display = 'none';
        badge.classList.remove('pulse');
    }
}

function loadChatHistory() {
    // Chỉ load nếu đã có session
    if (!chatSessionId) return;
    
    fetch('/chatbot/history?session_id=' + chatSessionId)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages.length > 1) {
                const messagesDiv = document.getElementById('chatbot-messages');
                messagesDiv.innerHTML = '';
                
                data.messages.forEach(msg => {
                    if (msg.type === 'user') {
                        appendMessage('user', msg.message, false);
                    } else if (msg.type === 'bot') {
                        appendMessage('bot', msg.message, false);
                    } else if (msg.type === 'admin') {
                        appendMessage('admin', msg.message, false, msg.user?.name || 'Admin');
                    }
                    lastMessageId = msg.id;
                });
                
                const messagesDiv2 = document.getElementById('chatbot-messages');
                messagesDiv2.scrollTop = messagesDiv2.scrollHeight;
            }
        });
}

function handleChatKeyPress(event) {
    if (event.key === 'Enter') {
        sendChatMessage();
    }
}

function sendChatMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Tạo session nếu chưa có (tin nhắn đầu tiên)
    const sessionId = getOrCreateSessionId();
    
    // Hiển thị tin nhắn user
    appendMessage('user', message);
    input.value = '';
    
    // Hiển thị typing indicator
    showTypingIndicator();
    
    // Gửi request
    fetch('/chatbot/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            message: message,
            session_id: sessionId
        })
    })
    .then(response => response.json())
    .then(data => {
        hideTypingIndicator();
        
        if (data.success) {
            // Hiển thị response của bot
            appendMessage('bot', data.bot_message);
            
            // Hiển thị sản phẩm nếu có
            if (data.products && data.products.length > 0) {
                displayProducts(data.products);
            } else {
                document.getElementById('chatbot-products').style.display = 'none';
            }
            
            // Bắt đầu polling sau tin nhắn đầu tiên
            if (document.getElementById('chatbot-container').style.display !== 'none') {
                startPolling();
            }
        }
    })
    .catch(error => {
        hideTypingIndicator();
        appendMessage('bot', 'Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.');
    });
}

function appendMessage(type, message, scroll = true, adminName = '') {
    const messagesDiv = document.getElementById('chatbot-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}-message`;
    
    let avatarSrc = '';
    let messageContent = '';
    
    if (type === 'user') {
        avatarSrc = '{{ auth()->check() ? "https://ui-avatars.com/api/?name=" . urlencode(auth()->user()->name) : "https://ui-avatars.com/api/?name=U" }}';
        messageContent = `
            <div class="message-avatar">
                <img src="${avatarSrc}" alt="User">
            </div>
            <div class="message-content">${message}</div>
        `;
    } else if (type === 'admin') {
        avatarSrc = `https://ui-avatars.com/api/?name=${encodeURIComponent(adminName)}&background=28a745&color=fff`;
        messageContent = `
            <div class="message-avatar">
                <img src="${avatarSrc}" alt="Admin">
            </div>
            <div class="message-content admin-content">
                <small class="admin-label"><i class="fas fa-user-shield"></i> ${adminName}</small>
                <div>${message}</div>
            </div>
        `;
    } else {
        avatarSrc = '{{ asset("images/logo/logohalo.png") }}';
        messageContent = `
            <div class="message-avatar">
                <img src="${avatarSrc}" alt="Bot">
            </div>
            <div class="message-content">${message}</div>
        `;
    }
    
    messageDiv.innerHTML = messageContent;
    messagesDiv.appendChild(messageDiv);
    
    if (scroll) {
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }
}

function showTypingIndicator() {
    const messagesDiv = document.getElementById('chatbot-messages');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'message bot-message';
    typingDiv.id = 'typing-indicator';
    typingDiv.innerHTML = `
        <div class="message-avatar">
            <img src="{{ asset('images/logo/logohalo.png') }}" alt="Bot">
        </div>
        <div class="typing-indicator">
            <span></span>
            <span></span>
            <span></span>
        </div>
    `;
    messagesDiv.appendChild(typingDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

function hideTypingIndicator() {
    const typingDiv = document.getElementById('typing-indicator');
    if (typingDiv) {
        typingDiv.remove();
    }
}

function displayProducts(products) {
    const productsDiv = document.getElementById('chatbot-products');
    productsDiv.innerHTML = '';
    
    products.forEach(product => {
        const productCard = document.createElement('a');
        productCard.className = 'product-card-mini';
        productCard.href = `/san-pham/${product.slug}`;
        productCard.target = '_blank';
        
        const imageSrc = product.image ? `/images/products/${product.image}` : '/images/no-image.jpg';
        
        productCard.innerHTML = `
            <img src="${imageSrc}" alt="${product.name}">
            <div class="product-card-mini-info">
                <h6>${product.name}</h6>
                <div class="platform">${product.platform || 'N/A'}</div>
                <div class="price">${new Intl.NumberFormat('vi-VN').format(product.price)} ₫</div>
            </div>
        `;
        
        productsDiv.appendChild(productCard);
    });
    
    productsDiv.style.display = 'block';
}
</script>
