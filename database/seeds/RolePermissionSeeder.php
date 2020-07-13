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
            ['name' => 'full-admin', 'label' => 'Full Admin'],
            ['name' => 'manage-roles', 'label' => 'Manage Roles'],
            ['name' => 'manage-permissions', 'label' => 'Manage Permissions'],
            ['name' => 'can-comment', 'label' => 'Can Comment'],
            ['name' => 'moderate-comments', 'label' => 'Moderate Comments'],
            ['name' => 'including-published-posts', 'label' => 'Including Published Posts'],
            ['name' => 'publish-any-posts', 'label' => 'Publish Any Posts'],
            ['name' => 'edit-any-posts', 'label' => 'Edit Any Posts'],
            ['name' => 'delete-any-posts', 'label' => 'Delete Any Posts'],
            ['name' => 'publish-own-posts', 'label' => 'Publish Own Posts'],
            ['name' => 'edit-own-posts', 'label' => 'Edit Own Posts'],
            ['name' => 'delete-own-posts', 'label' => 'Delete Own Posts'],
            ['name' => 'create-tags', 'label' => 'Create Tags'],
            ['name' => 'delete-tags', 'label' => 'Delete Tags']
        ];
        $rolesToCreate = [
            ['name' => 'admin', 'label' => 'Admin'],
            ['name' => 'publisher', 'label' => 'Publisher'],
            ['name' => 'author', 'label' => 'Author'],
            ['name' => 'user', 'label' => 'User'],
        ];
        $rolePermissions = [
            'admin' => [
                'full-admin',
            ],
            'publisher' => [
                'can-comment',
                'moderate-comments',
                'including-published-posts',
                'publish-any-posts',
                'edit-any-posts',
                'delete-any-posts',
                'create-tags',
                'delete-tags',
            ],
            'author' => [
                'can-comment',
                'moderate-comments',
                'edit-own-posts',
                'delete-own-posts',
            ],
            'user' => [
                'can-comment',
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
