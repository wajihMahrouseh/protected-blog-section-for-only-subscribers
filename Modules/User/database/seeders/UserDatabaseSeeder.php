<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Modules\User\app\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $subscriberRole = Role::firstOrCreate(['name' => 'subscriber', 'guard_name' => 'api']);
        
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('admin'),
        ]);
        
        $user->assignRole($adminRole);
        
    }
}
