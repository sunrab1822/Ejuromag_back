<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class getProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eju:getProducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = json_decode(file_get_contents("backupData/product.json"),true);
        foreach ($data as $product){
            print_r($product);
            Product::updateOrCreate(
            [
                'id' => $product['id'],
            ],
            [
                'category_id' => $product['category'],
                'manufacturer_id'=> $product['manufacturer_id'],
                'description' => $product['description'],
                'name' => $product['name'],
                'price' => $product['price'],
                'picture' => $product['picture']
            ]);
        }
    }
}
