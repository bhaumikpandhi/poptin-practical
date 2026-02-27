@extends('layouts.admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">Dashboard</h1>
                    <p class="text-muted">Welcome back, {{ Auth::user()->name }}!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total Polls</p>
                            <h3 class="h4 fw-bold mb-0">{{ $dashboardData['total_polls'] }}</h3>
                        </div>
                        <i class="bi bi-bar-chart text-primary" style="font-size: 1.75rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Top Polls</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="text-muted fw-600" style="font-size: 0.875rem;">Question</th>
                                    <th class="text-muted fw-600" style="font-size: 0.875rem;">Votes</th>
                                    <th class="text-muted fw-600" style="font-size: 0.875rem;">Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dashboardData['top_polls'] as $poll)
                                    <tr>
                                        <td>{{ $poll->question }}</td>
                                        <td>{{ $poll->votes_count }}</td>
                                        <td>{{ $poll->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
