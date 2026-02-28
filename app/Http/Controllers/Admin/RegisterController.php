<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RegisterRequest;
use App\Repositories\Auth\RegisterRepositoryInterface;

class RegisterController extends Controller
{
    public function __construct(
        private RegisterRepositoryInterface $registerRepository
    ) {}

    public function index()
    {
        return view('admin.register');
    }

    public function register(RegisterRequest $request)
    {
        $this->registerRepository->register(array_merge($request->validated(), ['role' => RoleEnum::Admin->value]));

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }
}
