<?php

namespace App\Services;

use App\Jobs\User\NewUserRegisteredJob;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthenticationService
{
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        protected UserRepository $userRepository
    ) {

    }

    /**
     * @param $driver
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * @param $driver
     * @return RedirectResponse
     */
    public function callback($driver)
    {
        $socialiteUser = Socialite::driver($driver)->user();

        $user = $this->userRepository
            ->by([
                'email' => $socialiteUser->email,
            ])
            ->orCreate([
                'name' => $socialiteUser->name,
                'google_id' => $socialiteUser->id,
                'ip' => \request()->ip(),
            ]);

        NewUserRegisteredJob::dispatch($user);

        Auth::login($user);

        return $this->redirectToHomepage();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setPassword(Request $request)
    {
        Validator::make($request->all(), [
            'password' => $this->passwordRules(),
        ])->validate();

        auth()->user()->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return $this->redirectToHomepage();
    }

    /**
     * @return RedirectResponse
     */
    public function redirectToHomepage(): RedirectResponse
    {
        return redirect(route('home'));
    }
}
