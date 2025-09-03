<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleCategory;
use Illuminate\Support\Str;

class VehicleCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Arm Roll','Chain Arm Roll','Compactor','Dumper','Gully Sucker','Loader',
            'Mech Washer','Mini Dumper','Miscellaneous','Pickup','Rikshaw','Sweeper',
            'Tracker not Installed','Trailer','Water Bowser','Cater Pillar','Bolan','Bike',
            'HiLux Dala','Cultus','FAW','JEEP','Corolla XLI','Tractor Trolley',
            'Tractor Loader','Toyota Bus','Mini Van','Jimmy','Honda City','Corolla Altis',
            'Toyota Vigo','Toyota Yaris','Toyota Fortuner','Vacuum Sweeper',
        ];

        foreach ($categories as $name) {
            VehicleCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true]
            );
        }
    }
}
