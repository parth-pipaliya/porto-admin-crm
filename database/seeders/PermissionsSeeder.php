<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('permissions')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        
	    Permission::insert([
                    [
                        'permission_category_id'  =>    1,
                        'permission_title'  =>    'List',
                        'permission_name'  =>    'permission_category-list',
                    ],
                    [
                        'permission_category_id'  =>    1,
                        'permission_title'  =>    'Add',
                        'permission_name'  =>    'permission_category-add',
                    ],
                    [
                        'permission_category_id'  =>    1,
                        'permission_title'  =>    'Edit',
                        'permission_name'  =>    'permission_category-edit',
                    ],
                    [
                        'permission_category_id'  =>    1,
                        'permission_title'  =>    'Delete',
                        'permission_name'  =>    'permission_category-delete',
                    ],
            
                    [
                        'permission_category_id'  =>    2,
                        'permission_title'  =>    'List',
                        'permission_name'  =>    'permission-list',
                    ],
                    [
                        'permission_category_id'  =>    2,
                        'permission_title'  =>    'Add',
                        'permission_name'  =>    'permission-add',
                    ],
                    [
                        'permission_category_id'  =>    2,
                        'permission_title'  =>    'Edit',
                        'permission_name'  =>    'permission-edit',
                    ],
                    [
                        'permission_category_id'  =>    2,
                        'permission_title'  =>    'Delete',
                        'permission_name'  =>    'permission-delete',
                    ],
                  
                    [
                        'permission_category_id'  =>    3,
                        'permission_title'  =>    'List',
                        'permission_name'  =>    'role-list',
                    ],
                    [
                        'permission_category_id'  =>    3,
                        'permission_title'  =>    'Add',
                        'permission_name'  =>    'role-add',
                    ],
                    [
                        'permission_category_id'  =>    3,
                        'permission_title'  =>    'Edit',
                        'permission_name'  =>    'role-edit',
                    ],
                    [
                        'permission_category_id'  =>    3,
                        'permission_title'  =>    'Delete',
                        'permission_name'  =>    'role-delete',
                    ],
          
                    [
                        'permission_category_id'  =>    4,
                        'permission_title'  =>    'List',
                        'permission_name'  =>    'admin-list',
                    ],
                    [
                        'permission_category_id'  =>    4,
                        'permission_title'  =>    'Add',
                        'permission_name'  =>    'admin-add',
                    ],
                    [
                        'permission_category_id'  =>    4,
                        'permission_title'  =>    'Edit',
                        'permission_name'  =>    'admin-edit',
                    ],
                    [
                        'permission_category_id'  =>    4,
                        'permission_title'  =>    'Delete',
                        'permission_name'  =>    'admin-delete',
                    ],
                   
                    [
                        'permission_category_id'  =>    5,
                        'permission_title'  =>    'List',
                        'permission_name'  =>    'user-list',
                    ],
                    [
                        'permission_category_id'  =>    5,
                        'permission_title'  =>    'Add',
                        'permission_name'  =>    'user-add',
                    ],
                    [
                        'permission_category_id'  =>    5,
                        'permission_title'  =>    'Edit',
                        'permission_name'  =>    'user-edit',
                    ],
                    [
                        'permission_category_id'  =>    5,
                        'permission_title'  =>    'Delete',
                        'permission_name'  =>    'user-delete',
                    ],

                    [
                        'permission_category_id'  =>    6,
                        'permission_title'  =>    'List',
                        'permission_name'  =>    'category-list',
                    ],
                    [
                        'permission_category_id'  =>    6,
                        'permission_title'  =>    'Add',
                        'permission_name'  =>    'category-add',
                    ],
                    [
                        'permission_category_id'  =>    6,
                        'permission_title'  =>    'Edit',
                        'permission_name'  =>    'category-edit',
                    ],
                    [
                        'permission_category_id'  =>    6,
                        'permission_title'  =>    'Delete',
                        'permission_name'  =>    'category-delete',
                    ],

                    [
                        'permission_category_id'  =>    7,
                        'permission_title'  =>    'List',
                        'permission_name'  =>    'static_page-list',
                    ],
                    [
                        'permission_category_id'  =>    7,
                        'permission_title'  =>    'Add',
                        'permission_name'  =>    'static_page-add',
                    ],
                    [
                        'permission_category_id'  =>    7,
                        'permission_title'  =>    'Edit',
                        'permission_name'  =>    'static_page-edit',
                    ],
                    [
                        'permission_category_id'  =>    7,
                        'permission_title'  =>    'Delete',
                        'permission_name'  =>    'static_page-delete',
                    ],

                    [
                        'permission_category_id'  =>    8,
                        'permission_title'  =>    'List',
                        'permission_name'  =>    'support_request-list',
                    ],
                    [
                        'permission_category_id'  =>    8,
                        'permission_title'  =>    'Add',
                        'permission_name'  =>    'support_request-add',
                    ],
                    [
                        'permission_category_id'  =>    8,
                        'permission_title'  =>    'Edit',
                        'permission_name'  =>    'support_request-edit',
                    ],
                    [
                        'permission_category_id'  =>    8,
                        'permission_title'  =>    'Delete',
                        'permission_name'  =>    'support_request-delete',
                    ],

                ]);
    }
}
