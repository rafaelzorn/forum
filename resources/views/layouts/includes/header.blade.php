<header class="top-nav">
    <div class="top-bar-main">
        <div class="container-fluid">
            <div class="logo">
                Forum
            </div>

            @auth
                <div class="dropdown nav-user">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        Hello, Rafael Zorn
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            Settings
                        </a>

                        <div class="dropdown-divider"></div>

                        <a href="#" class="dropdown-item">
                            Logout
                        </a>
                    </div>
                </div>
            @endauth

            <div class="clearfix"></div>
        </div>
    </div>

    @auth
        <div class="navbar-custom">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom active" href="#">Dashboard</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="#">Categories</a>
                </li>
            </ul>
        </div>
    @endauth
</header>
