<header class="top-nav">
    <div class="top-bar-main">
        <div class="container-fluid">
            <div class="logo">
                Forum
            </div>

            @guest
                <div class="dropdown nav-user">
                    <a class="nav-link" href="{{ route('login') }}">
                        Login
                    </a>
                </div>
            @endguest

            @auth
                <div class="dropdown nav-user">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        @lang('main.hello'), {{ Auth::user()->name }}.
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <i class="fa fa-cog"></i>
                            @lang('main.settings')
                        </a>

                        <div class="dropdown-divider"></div>

                        <a href="{{ route('logout') }}" class="dropdown-item">
                            <i class="fa fa-sign-out"></i>
                            @lang('main.logout')
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
                <a class="nav-link nav-link-custom" href="{{ route('topics.index') }}">@lang('main.home')</a>
            </li>

            @auth
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ route('manager.dashboard') }}">@lang('main.dashboard')</a>
                </li>

                @can('admin')
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('manager.categories.index') }}">@lang('main.categories')</a>
                    </li>
                @endcan

                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ route('manager.topics.index') }}">@lang('main.topics')</a>
                </li>

                @can('admin')
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('manager.users.index') }}">@lang('main.users')</a>
                    </li>
                @endcan
            @endauth
        </ul>
    </div>
</header>
