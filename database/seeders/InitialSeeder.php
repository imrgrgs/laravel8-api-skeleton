<?php

namespace Database\Seeders;

use App\Facades\TenantFacades;
use App\Facades\TenantService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Services\Generator\HashCode;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models = Config::get('permissions.models');
        if ($models === null) {
            $this->command->error("The configuration config/permissions.php has not been found. Did you have config/permissions.php file");
            $this->command->line('');
            return false;
        }

        $roles = Config::get('permissions.roles');
        if ($roles === null) {
            $this->command->error("The configuration config/permissions.php has not been found. Did you have config/permissions.php file");
            $this->command->line('');
            return false;
        }
        $tenant = $this->createTenant();

        $this->truncateLaratrustTables();

        $locales = Config::get('permissions.locales');
        $perms = Config::get('permissions.perms');
        $translates = Config::get('permissions.translates');
        $permissionsId = [];
        foreach ($models as $model) {

            foreach ($perms as $key => $perm) {
                $name = $model . '-' . $perm;
                $name = strtolower($name);
                $displayName = [];
                foreach ($locales as $locale) {
                    //  $model = 'users' $key = 'c' $perm = 'create' $locale = 'en'
                    $new = [$locale => ucwords($translates[$locale][$perm]) . ' ' . ucwords($translates[$locale][$model])];
                    array_push($displayName, $new);
                }
                $permissionsId[] = \App\Models\Permission::firstOrCreate(
                    ['name' => $name],
                    [
                        'display_name' => $displayName,
                        'description' => $displayName,
                    ]
                )->id;
                $showDisplayName = '';
                foreach ($displayName as $key => $values) {

                    foreach ($values as $key => $value) {
                        $showDisplayName .= '(' . $key . ')' . $value . ' ';
                    }
                }
                $this->command->info('Creating Permission ' . $name . ' description ' . $showDisplayName);
            }
        }


        // Create a new role


        $rolesId = [];
        foreach ($roles as $role) {
            $name = $role['name'];
            $name = strtolower($name);
            $displayName = [];
            foreach ($locales as $locale) {
                //  $role = 'superadmin' $locale = 'en'
                $new = [$locale => ucwords($translates[$locale][$name])];
                array_push($displayName, $new);
            }
            $role = \App\Models\Role::firstOrCreate(
                ['name' => $name],
                [
                    'display_name' => $displayName,
                    'description' => $displayName,
                ]
            );
            $showDisplayName = '';
            foreach ($displayName as $key => $values) {

                foreach ($values as $key => $value) {
                    $showDisplayName .= '(' . $key . ')' . $value . ' ';
                }
            }
            $this->command->info('Creating Role ' . $name . ' description ' . $showDisplayName);
            // Attach all permissions to the role
            $this->command->info('Attachimg all permissions to the role ' . strtoupper($name));
            $role->permissions()->sync($permissionsId);

            if (Config::get('permissions.create_users')) {
                $key = $role->name;
                $this->command->info("Creating '{$key}' user");
                // Create default user for each role
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $key . '@app.com',],
                    [
                        'tenant_id' => $tenant->id,
                        'name' => ucwords(str_replace('_', ' ', $key)),

                        'password' => bcrypt('password')
                    ]
                );
                $user->attachRole($role);
            }
        }
    }

    public function createTenant()
    {
        $tenant = TenantService::firstOrCreate(
            ['name' => 'Master Tenant'],
            [
                'code' => HashCode::make(),
                'is_master' => true,
                'is_active' => true,
            ]
        );


        return $tenant;
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return  void
     */
    public function truncateLaratrustTables()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        Schema::disableForeignKeyConstraints();

        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();

        if (Config::get('laratrust_seeder.truncate_tables')) {
            DB::table('roles')->truncate();
            DB::table('permissions')->truncate();

            if (Config::get('laratrust_seeder.create_users')) {
                $usersTable = (new \App\Models\User)->getTable();
                DB::table($usersTable)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
