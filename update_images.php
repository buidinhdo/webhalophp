<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$updates = [
    1 => 'images/products/sanpham2.jpg',
    2 => 'images/products/sanpham3.jpg',
    3 => 'images/products/sanpham4.jpg',
    4 => 'images/products/sanpham5.jpg',
    5 => 'images/products/sanpham6.jpg',
    6 => 'images/products/sanpham7.jpg',
    7 => 'images/products/sanpham8.jpg',
    8 => 'images/products/sanpham9.jpg',
    9 => 'images/products/sanpham10.jpg',
    10 => 'images/products/sanpham11.jpg',
];

foreach ($updates as $id => $image) {
    DB::table('products')->where('id', $id)->update(['image' => $image]);
    echo "Updated product ID $id with image: $image\n";
}

echo "\nAll product images updated successfully!\n";
