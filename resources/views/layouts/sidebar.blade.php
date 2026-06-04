<div class="shadow-sm">
    <header class="container d-flex justify-content-between align-items-center">
        <div class="logo fw-bold fs-4 text-primary">
            <img src="/assets/atom/Images/logo.png" class="logo" alt="Atom Food Safety" srcset="">
        </div>
        <div class="dropdown">
            <div class="profile-trigger d-flex gap-3 align-items-center" data-bs-toggle="dropdown"
                aria-expanded="false">
                <div class="text-end d-none d-sm-block">
                    <h4 class="profile-name fs-5 fw-bold">Jack Will</h4>
                    <p class="profile-role fs-6 text-color">Food Safety Manager</p>
                </div>
                <div class="profile-icon-wrapper">
                    <i class="bi bi-person-circle fs-1"></i>
                </div>
            </div>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-person-fill"></i> My Profile
                    </a>
                </li>

                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-shield-lock"></i> Change Password
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="{{route('logout')}}">
                        <i class="bi bi-box-arrow-left"></i> Log Out
                    </a>
                </li>
            </ul>
        </div>
    </header>
</div>
