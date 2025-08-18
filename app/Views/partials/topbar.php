<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?= base_url('assets/images/logo-sm.svg') ?>" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url('assets/images/logo-sm.svg') ?>" alt="" height="24"> <span class="logo-txt">CV Java</span>
                    </span>
                </a>

                <a href="index" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="<?= base_url('assets/images/logo-sm.svg') ?>" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url('assets/images/logo-sm.svg') ?>" alt="" height="24"> <span class="logo-txt">CV Java</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="d-flex">
            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                    $session = \Config\Services::session();
                    $lang = $session->get('lang');
                    switch ($lang) {
                        case 'id':
                            echo '<img src="' . base_url('assets/images/flags/id.jpg') . '" alt="Header Language" height="16">';
                            break;
                        case 'en':
                            echo '<img src="' . base_url('assets/images/flags/us.jpg') . '" alt="Header Language" height="16">';
                            break;
                        default:
                            echo '<img src="' . base_url('assets/images/flags/id.jpg') . '" alt="Header Language" height="16">';
                    }
                    ?>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a href="<?= base_url('lang/id'); ?>" class="dropdown-item notify-item language" data-lang="id">
                        <img src="<?= base_url('assets/images/flags/id.jpg') ?>" alt="user-image" class="me-1" height="12"> <span class="align-middle">Indonesia</span>
                    </a>
                    <!-- item-->
                    <a href="<?= base_url('lang/en'); ?>" class="dropdown-item notify-item language" data-lang="en">
                        <img src="<?= base_url('assets/images/flags/us.jpg') ?>" alt="user-image" class="me-1" height="12"> <span class="align-middle">English</span>
                    </a>                    
                </div>
            </div>

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="<?= base_url('assets/images/users/avatar-1.jpg') ?>"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">Shawn L.</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="apps-contacts-profile"><i class="mdi mdi mdi-face-man font-size-16 align-middle me-1"></i> <?= lang('Files.Profile') ?></a>
                    <a class="dropdown-item" href="auth-lock-screen"><i class="mdi mdi-lock font-size-16 align-middle me-1"></i> <?= lang('Files.Lock_screen') ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="auth-logout"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i><?= lang('Files.Logout') ?></a>
                </div>
            </div>

        </div>
    </div>
</header>