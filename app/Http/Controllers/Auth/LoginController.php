<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\Client;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
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
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $client = new Client();
        $client->setPassword($request->get('password'));
        $client->setEmail($request->get('email'));

        try {
            $clientResponse = $client->init();

            $userData = collect([
                'first_name' => $clientResponse['user']['first_name'],
                'last_name' => $clientResponse['user']['last_name'],
                'gender' => $clientResponse['user']['gender']
            ]);

            session()->start();
            session()->put('userData', $userData);
            session()->put('token_key', $clientResponse['token_key']);
            session()->put('refresh_token_key', $clientResponse['refresh_token_key']);
            session()->put('token_time', date('Y-m-d'));

            return redirect('home')->withSuccess('Signed in');
        } catch (\Exception $e) {
            return redirect('login')->with('msg', 'Wrong Credentials Invalid Username or Password!');
        }


    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/login');
    }
}
