<div class="content">
    <div class="navbar-light navbar-glass navbar-top navbar-expand" style="background-color:#ffff">
        <div class="d-flex align-items-center ps-3 pe-3" style="justify-content: space-between;">
            @if (Route::currentRouteName() == 'dashboard')
                <a class="" href="{{ route('dashboard') }}">
                    <img class="img-responsive" src="/assets/img/icons/home.png" alt="" width="40" />
                </a>
            @else
                {{-- <a class="" href="#">
                    <img class="img-responsive" src="/assets/img/icons/back.png" alt="" width="40" />
                </a> --}}
                <?php echo add_back_button($get_route); ?>
            @endif

            <a class="" href="{{ route('work_type') }}">
                <img class="img-responsive" src="/assets/img/icons/spot-illustrations/logo.png" alt=""
                    width="100" />
            </a>

            <!-- <a class="" href="{{ route('logout') }}">
                {{-- <span class="h5 pe-3 text-dark"> {{ Auth::user()->name }} </span> --}}
                <span class="pe-2 text-500 h4">{{ Auth::user()->name }} <br><small style="font-size: 14px;">{{Auth::user()->role->name}}</small></span>
                <img class="img-responsive" src="/assets/img/icons/profile.png" alt="" width="40" />
            </a> -->
            <div class="dropdown">
                <a class="d-flex align-items-center dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="pe-2 text-500 h4 mb-0">
                        {{ Auth::user()->name }}
                        <br>
                        <small style="font-size: 14px;">{{ Auth::user()->role->name }}</small>
                    </span>
                    <img class="img-responsive" src="/assets/img/icons/profile.png" alt="" width="40" />
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
