<?php

namespace App\Http\Controllers\Auth;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Vender;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
        if(!file_exists(storage_path() . "/installed"))
        {
            header('location:install');
            die;
        }
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if($user->delete_status == 0)
        {
            auth()->logout();
        }

        if($user->is_active == 0)
        {
            auth()->logout();
        }

        if($user->type == 'company')
        {
            $free_plan = Plan::where('price', '=', '0.0')->first();
            if($user->plan != $free_plan->id)
            {
                if(date('Y-m-d') > $user->plan_expire_date)
                {
                    $user->plan             = $free_plan->id;
                    $user->plan_expire_date = null;
                    $user->save();

                    $users     = User::where('created_by', '=', \Auth::user()->creatorId())->get();
                    $customers = Customer::where('created_by', '=', \Auth::user()->creatorId())->get();
                    $venders   = Vender::where('created_by', '=', \Auth::user()->creatorId())->get();

                    if($free_plan->max_users == -1)
                    {
                        foreach($users as $user)
                        {
                            $user->is_active = 1;
                            $user->save();
                        }
                    }
                    else
                    {
                        $userCount = 0;
                        foreach($users as $user)
                        {
                            $userCount++;
                            if($userCount <= $free_plan->max_users)
                            {
                                $user->is_active = 1;
                                $user->save();
                            }
                            else
                            {
                                $user->is_active = 0;
                                $user->save();
                            }
                        }

                    }


                    if($free_plan->max_customers == -1)
                    {
                        foreach($customers as $customer)
                        {
                            $customer->is_active = 1;
                            $customer->save();
                        }
                    }
                    else
                    {
                        $customerCount = 0;
                        foreach($customers as $customer)
                        {
                            $customerCount++;
                            if($customerCount <= $free_plan->max_customers)
                            {
                                $customer->is_active = 1;
                                $customer->save();
                            }
                            else
                            {
                                $customer->is_active = 0;
                                $customer->save();
                            }
                        }
                    }

                    if($free_plan->max_venders == -1)
                    {
                        foreach($venders as $vender)
                        {
                            $vender->is_active = 1;
                            $vender->save();
                        }
                    }
                    else
                    {
                        $venderCount = 0;
                        foreach($venders as $vender)
                        {
                            $venderCount++;
                            if($venderCount <= $free_plan->max_venders)
                            {
                                $vender->is_active = 1;
                                $vender->save();
                            }
                            else
                            {
                                $vender->is_active = 0;
                                $vender->save();
                            }
                        }
                    }

                    return redirect()->route('home')->with('error', 'Your plan expired limit is over, please upgrade your plan');
                }
            }

        }
    }

    public function showCustomerLoginForm($lang = '')
    {
        if($lang == '')
        {
            $lang = \App\Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.customer_login', compact('lang'));
    }

    public function customerLogin(Request $request)
    {

        $this->validate(
            $request, [
                        'email' => 'required|email',
                        'password' => 'required|min:6',
                    ]
        );

        if(\Auth::guard('customer')->attempt(
            [
                'email' => $request->email,
                'password' => $request->password,
            ], $request->get('remember')
        ))
        {
            if(\Auth::guard('customer')->user()->is_active == 0)
            {
                \Auth::guard('customer')->logout();
            }

            return redirect()->route('customer.dashboard');
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function showVenderLoginForm($lang = '')
    {
        if($lang == '')
        {
            $lang = \App\Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.vender_login', compact('lang'));
    }

    public function venderLogin(Request $request)
    {
        $this->validate(
            $request, [
                        'email' => 'required|email',
                        'password' => 'required|min:6',
                    ]
        );
        if(\Auth::guard('vender')->attempt(
            [
                'email' => $request->email,
                'password' => $request->password,
            ], $request->get('remember')
        ))
        {
            if(\Auth::guard('vender')->user()->is_active == 0)
            {
                \Auth::guard('vender')->logout();
            }

            return redirect()->route('vender.dashboard');
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function showLoginForm($lang = '')
    {

        if($lang == '')
        {
            $lang = \App\Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.login', compact('lang'));
    }

    public function showLinkRequestForm($lang = '')
    {

        if($lang == '')
        {
            $lang = \App\Utility::getValByName('default_language');
        }


        \App::setLocale($lang);

        return view('auth.passwords.email', compact('lang'));
    }

    public function showCustomerLoginLang($lang = '')
    {
        if($lang == '')
        {
            $lang = \App\Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.customer_login', compact('lang'));
    }

    public function showVenderLoginLang($lang = '')
    {
        if($lang == '')
        {
            $lang = \App\Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.vender_login', compact('lang'));
    }
}
