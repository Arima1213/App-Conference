<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_attendance::log","view_any_attendance::log","create_attendance::log","update_attendance::log","restore_attendance::log","restore_any_attendance::log","replicate_attendance::log","reorder_attendance::log","delete_attendance::log","delete_any_attendance::log","force_delete_attendance::log","force_delete_any_attendance::log","view_conference","view_any_conference","create_conference","update_conference","restore_conference","restore_any_conference","replicate_conference","reorder_conference","delete_conference","delete_any_conference","force_delete_conference","force_delete_any_conference","view_educational::institution","view_any_educational::institution","create_educational::institution","update_educational::institution","restore_educational::institution","restore_any_educational::institution","replicate_educational::institution","reorder_educational::institution","delete_educational::institution","delete_any_educational::institution","force_delete_educational::institution","force_delete_any_educational::institution","view_membership","view_any_membership","create_membership","update_membership","restore_membership","restore_any_membership","replicate_membership","reorder_membership","delete_membership","delete_any_membership","force_delete_membership","force_delete_any_membership","view_participant","view_any_participant","create_participant","update_participant","restore_participant","restore_any_participant","replicate_participant","reorder_participant","delete_participant","delete_any_participant","force_delete_participant","force_delete_any_participant","view_payment","view_any_payment","create_payment","update_payment","restore_payment","restore_any_payment","replicate_payment","reorder_payment","delete_payment","delete_any_payment","force_delete_payment","force_delete_any_payment","view_speaker","view_any_speaker","create_speaker","update_speaker","restore_speaker","restore_any_speaker","replicate_speaker","reorder_speaker","delete_speaker","delete_any_speaker","force_delete_speaker","force_delete_any_speaker","view_sponsor","view_any_sponsor","create_sponsor","update_sponsor","restore_sponsor","restore_any_sponsor","replicate_sponsor","reorder_sponsor","delete_sponsor","delete_any_sponsor","force_delete_sponsor","force_delete_any_sponsor","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
