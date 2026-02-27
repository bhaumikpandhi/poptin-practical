<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Auth\RegisterRepositoryInterface;

class RegisterController extends Controller
{
    public function __construct(
        private RegisterRepositoryInterface $registerRepository
    ) {}

    public function index()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $this->registerRepository->register($request->validated());

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }
}
