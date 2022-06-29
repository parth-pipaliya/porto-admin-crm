<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }
    
    public function showAdminLoginForm()
    {
        return view('admin_auth.signin', ['url' => 'donotadmintouch/dashboard']);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    public function logout(Request $request) {
        $admin=auth()->guard('admin')->user();
        $this->guard()->logout();
        return redirect()->route('admin.signin');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function adminLogin(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        return redirect()->route('admin.otp')->withInput();

        // $this->validate($request, [
        //     'email'   => 'required|email',
        //     'password' => 'required|min:6'
        // ]);

        // if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
        //     $admin =  Admin::find(Auth::guard('admin')->user()->id);
        //     $data = [
        //                 'timezone' => !empty($request->timezone) ? $request->timezone : 'UTC'
        //             ];
        //     $admin->update($data);  
        //     // return redirect()->intended('/donotadmintouch');
        //     return redirect()->route('admin.otp')->withInput();
        // }
        // return back()->withInput($request->only('email', 'remember'))->withError('These credentials do not match our records.');
    }

    private function generateOtpForAdmin(Admin $admin){
        
        if(!empty(config('app.debug')) && config('app.debug') == true){
            $otp = 111111;
        }else{
            $otp = rand(111111,999999);
        }
        $admin->update(['otp'=>$otp]);
        // try {
        //     Mail::to($admin->email)->send(new SecurityVerification($admin));
        // }catch (\Exception $exception){
        //     Log::error($exception->getMessage());
        // }
    }

    public function showOtpForm(Request $request){
        $email = $request->old('email');
        $password = $request->old('password');
        $timezone = !empty($request->timezone) ? $request->timezone : 'UTC';
        $admin = Admin::where('email',$email)->first();
        if(!empty($admin)){
            $checkPassword = Hash::check($password, $admin->password);
            if($checkPassword){
                Session::remove('otp-error');
                if(!Session::has('otp-error')){
                    $this->generateOtpForAdmin($admin);
                    $admin->update(['timezone' => $timezone]); 
                }
                return view('admin_auth.otp',['email'=>$email,'password'=>$password]);
            }
        }
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    public function validateOtp(Request $request){
        $admin = Admin::where('email',$request->email)->first();
        if ($admin->otp == $request->otp){
            Session::remove('otp-error');
            $admin->update(['otp'=>null]);
  
            if ($this->attemptLogin($request)) {
                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());
                }
            }
            return $this->sendLoginResponse($request);
        }
        Session::put('otp-error','Invalid OTP');
        return redirect()->route('admin.otp')->withInput()->withErrors(['otp'=>"Invalid OTP"]);
    }
}
