<?php

namespace App\Console\Commands;

use App\Models\Manufacturer;
use Illuminate\Console\Command;
use Psy\Readline\Hoa\Console;

class getManufacturers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eju:getManufacturers';

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
        $data = json_decode(file_get_contents("backupData/manufacturer.json"), true);

        foreach ( $data as $manuf){
            print_r($manuf);
            Manufacturer::updateOrCreate(
                ['id' => $manuf['id']],
                ['name' => $manuf['name']]
            );
        }
    }
}
