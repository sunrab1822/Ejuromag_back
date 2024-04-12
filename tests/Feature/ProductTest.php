<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_products(): void
    {
        Category::factory()->create([
            "categoryName" => "asdasd"
        ]);
        Manufacturer::factory()->create([
            "name" => "asdasdas"
        ]);
        $product = Product::factory()->create([
            "category_id" => 1,
            "manufacturer_id" => 1,
            "description" => "Core i5, 512 GB NVMe SSD, 8192 (DDR4) MB, Intel Iris Xe",
            "name" => "Acer Aspire 3",
            "price" => 164900,
            "picture" => "https://bgs.jedlik.eu/ejuromag/images/acer.jpg",
        ]);
        $response = $this->get('/api/products');

        $response->assertStatus(200);
       // dd($response["data"]);
        $response->assertJson([
                "error" => false,
                "data" =>[
                    [
                        "id" => 1,
                        "category_id" => 1,
                        "manufacturer_id" => 1,
                        "description" => "Core i5, 512 GB NVMe SSD, 8192 (DDR4) MB, Intel Iris Xe",
                        "name" => "Acer Aspire 3",
                        "price" => 164900,
                        "picture" => "https://bgs.jedlik.eu/ejuromag/images/acer.jpg",
                        "created_at" => "2024-04-07T18:23:44.000000Z",
                        "updated_at" => "2024-04-07T18:23:44.000000Z"
                    ]
                    ]

        ]);
    }
}
