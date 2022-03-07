<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers\Auth;

use Auth;
use ChuckCart;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Template;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected function redirectTo()
    { 
        return '/' . Auth::user()->roles()->first()->redirect;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $templateHintpath = config('chuckcms-module-order-form.auth.template.hintpath');
        $template = Template::where('active', 1)->where('hintpath', $templateHintpath)->first();
        $blade = $templateHintpath . '::templates.' . $template->slug .'.'. config('chuckcms-module-order-form.auth.template.login_blade');

        if (view()->exists($blade)) {
            return view($blade, compact('template'));
        }

        return view('chuckcms::auth.login');
    }

    protected function validateLogin(\Illuminate\Http\Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|exists:users,' . $this->username() . ',active,1',
            'password' => 'required',
        ], [
            $this->username() . '.exists' => 'The selected email is invalid or the account is not active.'
        ]);
    }
}
