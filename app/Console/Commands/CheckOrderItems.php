<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderItem;
use App\Models\Order;

class CheckOrderItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:order-items {order_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check order items data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        
        if ($orderId) {
            $order = Order::find($orderId);
            if (!$order) {
                $this->error("Order #{$orderId} not found!");
                return;
            }
            
            $this->info("Order #{$order->order_number}");
            $this->info("Items:");
            
            foreach ($order->items as $item) {
                $this->line("  - {$item->product_name}");
                $this->line("    Price: " . number_format($item->price));
                $this->line("    Quantity: {$item->quantity}");
                $this->line("    Total (DB): " . number_format($item->total));
                $this->line("    Total (Calc): " . number_format($item->price * $item->quantity));
                $this->line("");
            }
        } else {
            $items = OrderItem::latest()->take(5)->get();
            $this->info("Latest 5 Order Items:");
            
            foreach ($items as $item) {
                $calculated = $item->price * $item->quantity;
                $match = abs($item->total - $calculated) < 0.01 ? '✓' : '✗';
                
                $this->line("ID: {$item->id} | {$item->product_name}");
                $this->line("  Price: " . number_format($item->price) . " | Qty: {$item->quantity}");
                $this->line("  Total (DB): " . number_format($item->total) . " | Calc: " . number_format($calculated) . " {$match}");
                $this->line("");
            }
        }
    }
}
