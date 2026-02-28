<aside class="sidebar">
    <nav class="sidebar-menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) active @endif">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('admin.polls.index') }}" class="@if(request()->routeIs('admin.polls*')) active @endif">
                <i class="bi bi-bar-chart"></i>
                Polls
            </a>
        </li>

        <hr class="sidebar-divider">

        <li>
            <form method="POST" action="{{ route('logout') }}" class="w-100">
                @csrf
                <button type="submit" class="w-100 text-start" style="background: none; border: none; padding: 0;">
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </a>
                </button>
            </form>
        </li>
    </nav>
</aside>
