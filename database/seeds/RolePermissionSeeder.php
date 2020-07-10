<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionsToCreate = [
            ['name' => 'full_admin', 'label' => 'Full Admin'],
            ['name' => 'manage_roles', 'label' => 'Manage Roles'],
            ['name' => 'manage_permissions', 'label' => 'Manage Permissions'],
            ['name' => 'can_comment', 'label' => 'Can Comment'],
            ['name' => 'moderate_comments', 'label' => 'Moderate Comments'],
            ['name' => 'edit_published_posts', 'label' => 'Edit Published Posts'],
            ['name' => 'delete_published_posts', 'label' => 'Delete Published Posts'],
            ['name' => 'publish_any_posts', 'label' => 'Publish Any Posts'],
            ['name' => 'edit_any_posts', 'label' => 'Edit Any Posts'],
            ['name' => 'delete_any_posts', 'label' => 'Delete Any Posts'],
            ['name' => 'publish_own_posts', 'label' => 'Publish Own Posts'],
            ['name' => 'edit_own_posts', 'label' => 'Edit Own Posts'],
            ['name' => 'delete_own_posts', 'label' => 'Delete Own Posts'],
            ['name' => 'create_tags', 'label' => 'Create Tags'],
            ['name' => 'delete_tags', 'label' => 'Delete Tags']
        ];
        $rolesToCreate = [
            ['name' => 'admin', 'label' => 'Admin'],
            ['name' => 'publisher', 'label' => 'Publisher'],
            ['name' => 'author', 'label' => 'Author'],
            ['name' => 'user', 'label' => 'User'],
        ];
        $rolePermissions = [
            'admin' => [
                'full_admin',
            ],
            'publisher' => [
                'can_comment',
                'moderate_comments',
                'edit_published_posts',
                'delete_published_posts',
                'publish_any_posts',
                'edit_any_posts',
                'delete_any_posts',
                'create_tags',
                'delete_tags',
            ],
            'author' => [
                'can_comment',
                'moderate_comments',
                'edit_own_posts',
                'delete_own_posts',
            ],
            'user' => [
                'can_comment',
            ]
        ];

        foreach ($permissionsToCreate as $permission) {
            $permission['locked'] = 1;
            Permission::firstOrCreate($permission);
        }

        foreach ($rolesToCreate as $role) {
            $role['locked'] = 1;
            Role::firstOrCreate($role);
        }

        foreach ($rolePermissions as $roleName => $permissionList) {
            $role = Role::where(['name' => $roleName])
                ->firstOrFail();
            $permissions = [];
            foreach ($permissionList as $permissionName) {
                $permission = Permission::where(['name' => $permissionName])
                    ->firstOrFail();
                $permissions[$permission->id] = ['locked' => 1];
            }

            $role->permissions()
                ->sync($permissions);
        }
    }
}
