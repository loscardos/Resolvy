<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));

        $agent_noc_permissions = $admin_permissions->filter(function ($permission) {
            return in_array($permission->title, [
                'ticket_module_access',
                'ticket_access',
                'ticket_show',
                'ticket_edit_status',
                'customer_module_access',
                'customer_access',
                'customer_show',
                'profile_password_edit',
            ]);
        });
        Role::findOrFail(2)->permissions()->sync($agent_noc_permissions);

        $customer_service_permissions = $admin_permissions->filter(function ($permission) {
            return in_array($permission->title, [
                'customer_module_access',
                'customer_create',
                'customer_edit',
                'customer_show',
                'customer_delete',
                'customer_access',
                'ticket_module_access',
                'ticket_create',
                'ticket_show',
                'ticket_access',
                'ticket_assign_pics',
                'ticket_update_status_close',
                'ticket_edit_status',
                'profile_password_edit',
            ]);
        });

        Role::findOrFail(3)->permissions()->sync($customer_service_permissions);
    }
}
