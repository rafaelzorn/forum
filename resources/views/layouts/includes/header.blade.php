<header class="top-nav">
    <div class="top-bar-main">
        <div class="container-fluid">
            <div class="logo">
                Forum
            </div>

            @auth
                <div class="dropdown nav-user">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        Hello, {{ Auth::user()->name }}.
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            Settings
                        </a>

                        <div class="dropdown-divider"></div>

                        <a href="{{ route('logout') }}" class="dropdown-item">
                            Logout
                        </a>
                    </div>
                </div>
            @endauth

            <div class="clearfix"></div>
        </div>
    </div>

    <div class="navbar-custom">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link nav-link-custom" href="{{ route('home') }}">Home</a>
            </li>

            @auth
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ route('manager.dashboard') }}">Dashboard</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ route('manager.categories.index') }}">Categories</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="">Topics</a>
                </li>
            @endauth
        </ul>
    </div>
</header>
