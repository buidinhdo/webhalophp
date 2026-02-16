<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MoMo Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for MoMo e-wallet payment gateway integration
    |
    */

    // API Endpoint
    'endpoint' => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
    
    // Partner Code
    'partner_code' => env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'),
    
    // Access Key
    'access_key' => env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'),
    
    // Secret Key
    'secret_key' => env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'),
    
    // Return URL (sau khi thanh toán thành công)
    'return_url' => env('MOMO_RETURN_URL', env('APP_URL') . '/thanh-toan/momo/callback'),
    
    // Notify URL (IPN - Instant Payment Notification)
    'notify_url' => env('MOMO_NOTIFY_URL', env('APP_URL') . '/thanh-toan/momo/ipn'),
    
    // Request Type
    'request_type' => 'captureWallet',
    
    // MoMo Logo
    'logo' => 'https://developers.momo.vn/v3/img/logo.svg',
    
    // Thông tin người bán
    'store_name' => env('MOMO_STORE_NAME', 'HaloShop - Game & Console Store'),
    
    'store_address' => env('MOMO_STORE_ADDRESS', 'Hà Nội, Việt Nam'),
    
    'store_phone' => env('MOMO_STORE_PHONE', '0123456789'),
    
    'store_email' => env('MOMO_STORE_EMAIL', 'support@haloshop.vn'),
];
