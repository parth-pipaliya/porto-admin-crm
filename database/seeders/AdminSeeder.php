<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('admins')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        Admin::create([
            'name' => 'Super Admin',
            'email' => 'super.admin@admin.com',
            'password' => Hash::make("secret@321"),
            'is_superadmin' => 1,
            'is_active' => 1,
        ]);
        Admin::create([
            'name' => 'Super Admin Dublicate',
            'email' => 'dublicate-admin@admin.com',
            'password' => Hash::make("secret@321"),
            'is_superadmin' => 1,
            'is_active' => 1,
        ]);
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make("secret@123"),
            'is_superadmin' => 0,
            'is_active' => 1,
        ]);
    }
}
