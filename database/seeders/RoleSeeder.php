<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        Role::create([
            'role_name' => 'Admin',
        ]);
    }
}
