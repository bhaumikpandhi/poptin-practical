<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\DashboardRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardRepositoryInterface $dashboardRepository
    ) {}

    public function index()
    {
        $dashboardData = $this->dashboardRepository->getDashboardData();

        return view('admin.dashboard', compact('dashboardData'));
    }
}
