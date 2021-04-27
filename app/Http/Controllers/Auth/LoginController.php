<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Allow;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Contracts\User as GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Http\RedirectResponse as RedirectResponseToApp;

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


    /**
     * Redirect the user to the Google authentication page.
     *
     * @return RedirectResponse
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return string
     */
    public function handleGoogleCallback(): string
    {
        $user = Socialite::driver('google')->user();
        if ($this->registerOrLoginUser($user)) {
            // Return home after login.
            Toastr::success('Congratulations, ' . $user->getName() . '. You have successfully entered our application.', 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('home');
        }
        Toastr::error('You are not allowed to enter!', 'Error', ["positionClass" => "toast-top-right"]);
        return redirect()->route('main');
    }

    /**
     * Login or register depending on whether there is such a user in the database.
     *
     * @param GoogleUser $user
     * @return boolean
     */
    private function registerOrLoginUser(GoogleUser $user): bool
    {
        $teacher = User::where('email', '=', $user->getEmail())->first();
        if (!$teacher) {
            if (!Allow::where('email', '=' ,$user->getEmail())->first()) {
                return false;
            }
            $registerController = new RegisterController();
            $teacher = $registerController->create($user);
        } elseif ($teacher->blocked) {
            return false;
        }
        Auth::login($teacher);
        return true;
    }

}
