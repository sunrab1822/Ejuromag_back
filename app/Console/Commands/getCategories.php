<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class getCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // getGategories ezt keresed xdd
    protected $signature = 'eju:getCategory';

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
        $data = json_decode(file_get_contents("zimportJsons/category.json"), true);

        foreach ($data as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                ['categoryName' => $category['categoryName']]
            );
        }
    }
}
