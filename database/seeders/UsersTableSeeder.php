<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'approved'       => 1,
            ],
            [
                'id'             => 2,
                'name'           => 'Agent NOC',
                'email'          => 'agent@noc.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'approved'       => 1,
            ],
            [
                'id'             => 3,
                'name'           => 'Customer Service',
                'email'          => 'cs@service.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'approved'       => 1,
            ],
        ];

        User::insert($users);
    }
}
