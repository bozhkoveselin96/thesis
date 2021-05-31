<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Allow;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Laravel\Socialite\Contracts\User as GoogleUser;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  GoogleUser $user
     * @return User
     *
     */
    public function create(GoogleUser $user): User
    {
        $fields = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar(),
            'google_provider_id' => $user->getId()
        ];

        if ($user->getEmail() === config('auth.defaults.admin_email')) {
            $fields['is_admin'] = true;
        }

        \DB::transaction(function () use ($fields) {
            User::create($fields);
            Allow::where('email', $fields['email'])
                ->update(['used' => true]);
        });

        return User::where('email', $user->getEmail())->first();
    }
}
