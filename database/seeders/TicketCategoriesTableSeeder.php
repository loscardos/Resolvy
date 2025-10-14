<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;

class TicketCategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Connection Problem',
                'description' => 'Issues related to internet connectivity, such as slow speeds or disconnections.',
            ],
            [
                'name' => 'Device Damage',
                'description' => 'Reports of damaged equipment, like routers or modems.',
            ],
            [
                'name' => 'New Installation',
                'description' => 'Requests for new service installations or setup assistance.',
            ],
            [
                'name' => 'Other',
                'description' => 'General inquiries or issues that do not fit into other categories.',
            ],
        ];

        TicketCategory::insert($categories);
    }
}
