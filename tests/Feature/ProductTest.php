<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use SebastianBergmann\Type\TrueType;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_products_works(): void
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
       //dd($response["error"]);
        $this->assertEquals(false, $response["error"]);
    }

    public function test_get_category_works(): void
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
        $response = $this->get('/api/categories');

        $response->assertStatus(200);
       //dd($response["error"]);
        $this->assertEquals(false, $response["error"]);
    }

    public function test_get_manufacturers_works(): void
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
        $response = $this->get('/api/manufacturers');

        $response->assertStatus(200);
       //dd($response["error"]);
        $this->assertEquals(false, $response["error"]);
    }

}
