<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ChatMessage;

echo "=== KIỂM TRA DATABASE ===" . PHP_EOL;
echo "Total messages: " . ChatMessage::count() . PHP_EOL . PHP_EOL;

if (ChatMessage::count() > 0) {
    echo "--- 5 TIN NHẮN MỚI NHẤT ---" . PHP_EOL;
    ChatMessage::orderBy('created_at', 'desc')->take(5)->get()->each(function($m) {
        echo "ID: {$m->id}" . PHP_EOL;
        echo "Session: {$m->session_id}" . PHP_EOL;
        echo "Type: {$m->type}" . PHP_EOL;
        echo "Message: " . substr($m->message, 0, 100) . PHP_EOL;
        echo "Created: {$m->created_at}" . PHP_EOL;
        echo "---" . PHP_EOL;
    });
} else {
    echo "Không có tin nhắn nào trong database." . PHP_EOL;
    echo PHP_EOL;
    echo "HƯỚNG DẪN TEST:" . PHP_EOL;
    echo "1. Vào http://127.0.0.1:8000" . PHP_EOL;
    echo "2. Click icon chatbot góc phải dưới" . PHP_EOL;
    echo "3. Gửi tin nhắn bất kỳ" . PHP_EOL;
    echo "4. Chạy lại script này để kiểm tra" . PHP_EOL;
}
