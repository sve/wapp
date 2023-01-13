<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Services\AuthenticationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use PasswordValidationRules;

    /**
     * @param AuthenticationService $authenticationService
     */
    public function __construct(
        protected AuthenticationService $authenticationService
    ) {

    }

    /**
     * @param $driver
     * @return RedirectResponse
     */
    public function redirect($driver): RedirectResponse
    {
        return $this->authenticationService->redirect($driver);
    }

    /**
     * @param $driver
     * @return RedirectResponse
     */
    public function callback($driver): RedirectResponse
    {
        return $this->authenticationService->callback($driver);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setPassword(Request $request)
    {
        return $this->authenticationService->setPassword($request);
    }
}
