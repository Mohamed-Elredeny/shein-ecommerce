<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\models\City;
use App\Models\LandingPage;
use App\Models\LandingSection;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function showLoginForm()
    {
        return view('site.login');
    }

    public function UserLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            config()->set('auth.defaults.guard', 'admin');
            $status = auth()->user()->status;
            $this->id = auth()->user()->id;
            //return 1;
            // if successful, then redirect to their intended location
            return redirect()->route('admin.home');
        }
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            config()->set('auth.defaults.guard', 'web');
            //return 1;
            // if successful, then redirect to their intended location
            return redirect()->route('index');
        }


        return redirect()->route('auth.login')->withErrors(['msg' => 'تاكد من صحة البريد الالكتروني او كلمة المرور']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->back();
    }
}
