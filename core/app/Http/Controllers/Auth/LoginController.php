<?php

namespace App\Http\Controllers\Auth;

use App\Models\SignIn;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @param $staff
     * @return void
     */
    protected function authenticated(Request $request, $staff)
    {
        if ($staff->is_enable == 0) {
            Session::flush();
            Auth::logout();
        }
        try {
            $agent = new Agent();
            $accessLog['who'] = 'staff';
            $accessLog['who_id'] = $staff->id;
            $accessLog['ip'] = $request->ip();
            $accessLog['is_mobile'] = $agent->isMobile() == true ? 1 : 0;
            $accessLog['device'] = $agent->device();
            $accessLog['os'] = $agent->platform() ?? '';
            $accessLog['os_ver'] = $agent->version($accessLog['os']) ?? '';
            $accessLog['browser'] = $agent->browser() ?? '';
            $accessLog['browser_ver'] = $agent->version($accessLog['browser']) ?? '';
            $accessLog['created_at'] = now();
            SignIn::insert($accessLog);
        } catch (\Exception $e) {
            Log::error('AccessLog cant create insert', [$e->getMessage()]);
        }
    }
}
