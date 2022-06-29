<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Session;

class LockScreenController extends Controller
{
    public function showAdminLockScreenForm()
    {
        if(Auth::guard('admin')->check()){
            Session::put('locked', true);
            return view('admin_auth.lock_screen');
        }
        return redirect()->route('admin.dashboard');
    }

    public function adminLockscreen(Request $request)
    {

        if(!Auth::guard('admin')->check()){            
            return redirect()->route('admin.signin');
        }
        $this->validate($request, [
            'password' => 'required|string',
        ]);

        if(Hash::check($request->password, Auth::guard('admin')->user()->password)){
            $request->session()->forget('locked');
            return redirect()->route('admin.dashboard');
        }
        return back()->withInput()->withErrors(['password' => 'Password does not match. Please try again.']);

    }
}
