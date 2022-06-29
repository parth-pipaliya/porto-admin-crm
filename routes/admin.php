<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('home');
// });
require('image.php');
Route::get('/donotadmintouch', function () {
    return redirect()->route('admin.dashboard');
});


// Route::get('/dashboard', function () {
//     return view('admin.home');
// })->name('dashboard');

// Auth::routes();

// Route::group(function(){

    Route::namespace('Auth')->group(function(){
        Route::get('/signin', 'LoginController@showAdminLoginForm')->name('signin');
        Route::get('/otp', 'LoginController@showOtpForm')->name('otp');
        // Route::get('/register', 'RegisterController@showAdminRegisterForm')->name('register');
        Route::get('/lockscreen', 'LockScreenController@showAdminLockScreenForm')->name('lockscreen');

        Route::post('/admin/signin', 'LoginController@adminLogin')->name('signinPost');
        Route::post('/admin/otp', 'LoginController@validateOtp')->name('otpPost');
        // Route::post('/admin/register', 'RegisterController@createAdmin');
        Route::post('/admin/lockscreen', 'LockScreenController@adminLockscreen')->name('lockscreenPost');

        Route::middleware('auth:admin')->group(function(){
            Route::post('/logout', 'LoginController@logout')->name('logout');
        });
    });

    
    Route::middleware(['auth:admin','lock'])->group(function(){
       
        // permission No access 
        Route::get('permission_not_access', function(){
            return view('errors.permission_not_access');
        })->name('permission_not_access');

        // Main Dashoard
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        // permission category routes 
        Route::resource('permission_category', 'PermissionCategoryController', ['names' => 'permission_category'])->middleware('role-permission-resource:permission_category-list,permission_category-add,permission_category-edit,permission_category-delete');

        // permission routes 
        Route::resource('permission', 'PermissionController', ['names' => 'permission'])->middleware('role-permission-resource:permission-list,permission-add,permission-edit,permission-delete');
        
        // role routes 
        Route::resource('role', 'RoleController', ['names' => 'role'])->middleware('role-permission-resource:role-list,role-add,role-edit,role-delete');
   
        // Admin User routes        
        Route::resource('admin/users', 'AdminController', ['names' => 'admin_user'])->middleware('role-permission-resource:admin-list,admin-add,admin-edit,admin-delete');
        
        // User routes        
        Route::resource('users', 'UserController', ['names' => 'user'])->middleware('role-permission-resource:user-list,user-add,user-edit,user-delete');
        
        // User routes        
        Route::resource('category', 'CategoryController', ['names' => 'category'])->middleware('role-permission-resource:category-list,category-add,category-edit,category-delete');

        // static pages routes 
        Route::resource('static_pages', 'StaticPagesController', ['names' => 'static_pages'])->middleware('role-permission-resource:static_page-list,static_page-add,static_page-edit,static_page-delete');

        // support request routes 
        Route::get('support_request/close_request/{id?}', 'SupportRequestController@closeSupportRequest')->name('support_request_close')->middleware('role-permission:support_request-edit');
        Route::resource('support_request', 'SupportRequestController', ['names' => 'support_request'])->middleware('role-permission-resource:support_request-list,support_request-add,support_request-edit,support_request-delete');        
        
        // support chat routes 
        Route::get('support_request/chat_msg/{id?}', 'SupportRequestController@getChatMessages')->name('support_request_chat')->middleware('role-permission:support_request-list');
        Route::resource('support_chat', 'SupportChatController', ['names' => 'support_chat'])->middleware('role-permission-resource:support_request-list,support_request-add,support_request-edit,support_request-delete');        
    
    });

// });