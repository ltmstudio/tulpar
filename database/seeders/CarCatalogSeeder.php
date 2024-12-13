<?php

namespace Database\Seeders;

use App\Models\TxCatalogCar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CarCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('cars.json'));
        $cars = json_decode($json, true);

        foreach ($cars as $car) {
            $carModels = $car['models'];
            unset($car['models']);
            
            $car = TxCatalogCar::updateOrCreate(['id' => $car['id']], $car);
            foreach ($carModels as $carModel) {
                unset($carModel['path']);
                $car->models()->updateOrCreate(['id' => $carModel['id']], $carModel);
            }
        }
    }
}
