<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class FixProductJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:fix-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unescape Unicode characters in product extra_descriptions locally';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix product JSON data...');

        $products = Product::whereNotNull('extra_descriptions')->get();
        $bar = $this->output->createProgressBar(count($products));

        $bar->start();

        foreach ($products as $product) {
            // Laravel's Product model now has the asJson override with JSON_UNESCAPED_UNICODE.
            // Getting and setting the same data will force it to be re-encoded properly during save.
            $data = $product->extra_descriptions;
            
            // To ensure it actually saves even if Laravel thinks nothing changed:
            $product->extra_descriptions = []; 
            $product->save();
            
            $product->extra_descriptions = $data;
            $product->save();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Successfully fixed ' . count($products) . ' products.');
        $this->info('Now you can export the "products" table and import it to your server.');
    }
}
