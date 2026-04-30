<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderStockService
{
    public function deductStock(Order $order, bool $strict = false): bool
    {
        if ($order->stock_deducted) {
            return true;
        }

        $order->loadMissing('items');

        try {
            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    $affected = Product::where('id', $item->product_id)
                        ->where('stock', '>=', $item->quantity)
                        ->decrement('stock', $item->quantity);

                    if ($affected === 0) {
                        throw new \RuntimeException(
                            'Insufficient stock for product ID ' . $item->product_id . ' on order ID ' . $order->id
                        );
                    }
                }

                $order->update(['stock_deducted' => true]);
            });

            return true;
        } catch (\RuntimeException $exception) {
            if ($strict) {
                throw $exception;
            }

            Log::warning($exception->getMessage(), ['order_id' => $order->id]);
            return false;
        }
    }
}
