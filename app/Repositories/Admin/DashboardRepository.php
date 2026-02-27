<?php

namespace App\Repositories\Admin;

use App\Models\Poll;
use Illuminate\Support\Facades\Auth;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getDashboardData()
    {
        return [
            'total_polls' => Poll::ByUser(Auth::id())->count(),
            'top_polls' => Poll::ByUser(Auth::id())->withCount('votes')->orderBy('votes_count', 'desc')->take(5)->get(),
        ];
    }
}