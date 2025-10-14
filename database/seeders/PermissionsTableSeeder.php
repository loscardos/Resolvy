<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'customer_module_access',
            ],
            [
                'id'    => 18,
                'title' => 'ticket_module_access',
            ],
            [
                'id'    => 19,
                'title' => 'customer_create',
            ],
            [
                'id'    => 20,
                'title' => 'customer_edit',
            ],
            [
                'id'    => 21,
                'title' => 'customer_show',
            ],
            [
                'id'    => 22,
                'title' => 'customer_delete',
            ],
            [
                'id'    => 23,
                'title' => 'customer_access',
            ],
            [
                'id'    => 24,
                'title' => 'service_package_create',
            ],
            [
                'id'    => 25,
                'title' => 'service_package_edit',
            ],
            [
                'id'    => 26,
                'title' => 'service_package_show',
            ],
            [
                'id'    => 27,
                'title' => 'service_package_delete',
            ],
            [
                'id'    => 28,
                'title' => 'service_package_access',
            ],
            [
                'id'    => 29,
                'title' => 'subscription_create',
            ],
            [
                'id'    => 30,
                'title' => 'subscription_edit',
            ],
            [
                'id'    => 31,
                'title' => 'subscription_show',
            ],
            [
                'id'    => 32,
                'title' => 'subscription_delete',
            ],
            [
                'id'    => 33,
                'title' => 'subscription_access',
            ],
            [
                'id'    => 34,
                'title' => 'ticket_category_create',
            ],
            [
                'id'    => 35,
                'title' => 'ticket_category_edit',
            ],
            [
                'id'    => 36,
                'title' => 'ticket_category_show',
            ],
            [
                'id'    => 37,
                'title' => 'ticket_category_delete',
            ],
            [
                'id'    => 38,
                'title' => 'ticket_category_access',
            ],
            [
                'id'    => 39,
                'title' => 'ticket_create',
            ],
            [
                'id'    => 40,
                'title' => 'ticket_edit',
            ],
            [
                'id'    => 41,
                'title' => 'ticket_show',
            ],
            [
                'id'    => 42,
                'title' => 'ticket_delete',
            ],
            [
                'id'    => 43,
                'title' => 'ticket_access',
            ],
            [
                'id'    => 44,
                'title' => 'ticket_status_history_create',
            ],
            [
                'id'    => 45,
                'title' => 'ticket_status_history_edit',
            ],
            [
                'id'    => 46,
                'title' => 'ticket_status_history_show',
            ],
            [
                'id'    => 47,
                'title' => 'ticket_status_history_delete',
            ],
            [
                'id'    => 48,
                'title' => 'ticket_status_history_access',
            ],
            [
                'id'    => 49,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 50,
                'title' => 'ticket_assign_pics',
            ],
            [
                'id'    => 51,
                'title' => 'ticket_update_status_close',
            ],
        ];

        Permission::insert($permissions);
    }
}
