<?php

namespace Database\Seeders;

use App\Models\ServicePackage;
use Illuminate\Database\Seeder;

class ServicePackagesTableSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            [
                'name'                  => 'Home Basic 10 Mbps',
                'bandwidth_mbps_down'   => 10,
                'bandwidth_mbps_up'     => 2,
                'price'                 => 150000,
                'description'           => 'Up to 10 Mbps download speed. Suitable for browsing and streaming.',
                'is_active'             => 'active',
            ],
            [
                'name'                  => 'Home Standard 25 Mbps',
                'bandwidth_mbps_down'   => 25,
                'bandwidth_mbps_up'     => 5,
                'price'                 => 250000,
                'description'           => 'Up to 25 Mbps download speed. Good for small families and HD streaming.',
                'is_active'             => 'active',
            ],
            [
                'name'                  => 'Home Premium 50 Mbps',
                'bandwidth_mbps_down'   => 50,
                'bandwidth_mbps_up'     => 10,
                'price'                 => 350000,
                'description'           => 'Up to 50 Mbps download speed. Ideal for gaming and 4K streaming.',
                'is_active'             => 'active',
            ],
            [
                'name'                  => 'Business Pro 100 Mbps',
                'bandwidth_mbps_down'   => 100,
                'bandwidth_mbps_up'     => 25,
                'price'                 => 500000,
                'description'           => 'Symmetrical high-speed internet for business needs.',
                'is_active'             => 'active',
            ],
        ];

        ServicePackage::insert($packages);
    }
}
