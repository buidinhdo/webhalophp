<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixProductsShortDescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:products-short-description';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix products short_description column to TEXT type';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing products short_description column...');
        
        try {
            DB::statement('ALTER TABLE products MODIFY COLUMN short_description TEXT NULL');
            $this->info('✓ Successfully altered short_description column to TEXT type!');
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed to alter column: ' . $e->getMessage());
            return 1;
        }
    }
}
