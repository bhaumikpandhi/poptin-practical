<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\Auth\LoginRepositoryInterface;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        private LoginRepositoryInterface $loginRepository
    ) {}

    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if ($this->loginRepository->attempt($credentials)) {
            $request->session()->regenerate();

            $user = $this->loginRepository->getAuthenticatedUser();
            
            if ($user->hasRole(RoleEnum::Admin->value)) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('polls.index'));
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        $this->loginRepository->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
