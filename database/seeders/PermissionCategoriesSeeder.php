<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\PermissionCategory;

class PermissionCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('permission_categories')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

	    PermissionCategory::insert([
                    [
                        'name'  =>    'Permission Category',
                    ],
                    [
                        'name'  =>    'Permission',
                    ],
                    [
                        'name'  =>    'Role',
                    ],
                    [
                        'name'  =>    'Admin User',
                    ],
                    [
                        'name'  =>    'User',
                    ],
                    [
                        'name'  =>    'Category',
                    ],
                    [
                        'name'  =>    'Static Page',
                    ],
                    [
                        'name'  =>    'Support Request',
                    ],
                ]);
    }
}
