<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminRole;

class AdminRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('admin_roles')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        AdminRole::insert([
            [
                'admin_id'=>3,
                'role_id'=>1,
            ],
        ]);
    }
}
