<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Danh sách ngân hàng hỗ trợ thanh toán
    |--------------------------------------------------------------------------
    */
    
    'default_bank' => env('DEFAULT_BANK', 'VCB'),
    
    'account_number' => env('BANK_ACCOUNT_NUMBER', '1234567890'),
    
    'account_name' => env('BANK_ACCOUNT_NAME', 'HALO SHOP'),
    
    'banks' => [
        'VCB' => [
            'name' => 'Vietcombank',
            'full_name' => 'Ngân hàng TMCP Ngoại Thương Việt Nam',
            'code' => 'VCB',
            'bin' => '970436',
            'logo' => 'https://api.vietqr.io/img/VCB.png',
            'swift_code' => 'BFTVVNVX',
        ],
        'TCB' => [
            'name' => 'Techcombank',
            'full_name' => 'Ngân hàng TMCP Kỹ Thương Việt Nam',
            'code' => 'TCB',
            'bin' => '970407',
            'logo' => 'https://api.vietqr.io/img/TCB.png',
            'swift_code' => 'VTCBVNVX',
        ],
        'ACB' => [
            'name' => 'ACB',
            'full_name' => 'Ngân hàng TMCP Á Châu',
            'code' => 'ACB',
            'bin' => '970416',
            'logo' => 'https://api.vietqr.io/img/ACB.png',
            'swift_code' => 'ASCBVNVX',
        ],
        'VTB' => [
            'name' => 'Vietinbank',
            'full_name' => 'Ngân hàng TMCP Công Thương Việt Nam',
            'code' => 'VTB',
            'bin' => '970415',
            'logo' => 'https://api.vietqr.io/img/ICB.png',
            'swift_code' => 'ICBVVNVX',
        ],
        'BIDV' => [
            'name' => 'BIDV',
            'full_name' => 'Ngân hàng TMCP Đầu tư và Phát triển Việt Nam',
            'code' => 'BIDV',
            'bin' => '970418',
            'logo' => 'https://api.vietqr.io/img/BIDV.png',
            'swift_code' => 'BIDVVNVX',
        ],
        'AGR' => [
            'name' => 'Agribank',
            'full_name' => 'Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam',
            'code' => 'AGR',
            'bin' => '970405',
            'logo' => 'https://api.vietqr.io/img/ABB.png',
            'swift_code' => 'VBAAVNVX',
        ],
        'MB' => [
            'name' => 'MBBank',
            'full_name' => 'Ngân hàng TMCP Quân Đội',
            'code' => 'MB',
            'bin' => '970422',
            'logo' => 'https://api.vietqr.io/img/MB.png',
            'swift_code' => 'MSCBVNVX',
        ],
        'VPB' => [
            'name' => 'VPBank',
            'full_name' => 'Ngân hàng TMCP Việt Nam Thịnh Vượng',
            'code' => 'VPB',
            'bin' => '970432',
            'logo' => 'https://api.vietqr.io/img/VPB.png',
            'swift_code' => 'VPBKVNVX',
        ],
        'TPB' => [
            'name' => 'TPBank',
            'full_name' => 'Ngân hàng TMCP Tiên Phong',
            'code' => 'TPB',
            'bin' => '970423',
            'logo' => 'https://api.vietqr.io/img/TPB.png',
            'swift_code' => 'TPBVVNVX',
        ],
        'STB' => [
            'name' => 'Sacombank',
            'full_name' => 'Ngân hàng TMCP Sài Gòn Thương Tín',
            'code' => 'STB',
            'bin' => '970403',
            'logo' => 'https://api.vietqr.io/img/STB.png',
            'swift_code' => 'SGTTVNVX',
        ],
    ],
];
