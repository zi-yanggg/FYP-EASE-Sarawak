<?php
function timeAgo($datetime)
{
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return "Just now";
    if ($diff < 3600) return floor($diff / 60) . " minutes ago";
    if ($diff < 86400) return floor($diff / 3600) . " hours ago";
    if ($diff < 604800) return floor($diff / 86400) . " days ago";

    return date('d M Y', $time);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>EASE SARAWAK | Admin Portal</title>
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport" />
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/cropped-Ease_PNG_File-09.png') ?>">

    <!-- Ensure relative asset paths resolve from the application root -->
    <base href="<?= base_url('/') ?>">

    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="<?= base_url('assets/js/admin/plugin/webfont/webfont.min.js') ?>"></script>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["assets/css/admin/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script> -->
    <script>
        (function() {
            try {
                if (localStorage.getItem('easeSidebarMinimized') === '1') {
                    document.documentElement.classList.add('ease-restore-minimized');
                }
            } catch (e) {
                // Ignore storage-access issues and fall back to default layout.
            }
        })();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('darkModeToggle');
            const darkModeIcon = document.getElementById('darkModeIcon');
            const body = document.body;

            if (!toggle) return;

            const syncModeIcon = () => {
                if (!darkModeIcon) return;
                const isDark = body.classList.contains('dark-mode');
                darkModeIcon.className = isDark ? 'bi bi-moon-stars-fill' : 'bi bi-sun-fill';
            };

            if (localStorage.getItem('darkMode') === 'enabled') {
                body.classList.add('dark-mode');
                toggle.checked = true;
            }
            syncModeIcon();

            toggle.addEventListener('change', function() {
                body.classList.toggle('dark-mode');
                if (body.classList.contains('dark-mode')) {
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    localStorage.setItem('darkMode', 'disabled');
                }
                syncModeIcon();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.querySelector('.wrapper');
            if (!wrapper) return;

            const sidebarStateKey = 'easeSidebarMinimized';
            const savedSidebarState = localStorage.getItem(sidebarStateKey);

            if (savedSidebarState === '1') {
                wrapper.classList.add('sidebar_minimize');
                wrapper.classList.remove('sidebar_minimize_hover');
            } else if (savedSidebarState === '0') {
                wrapper.classList.remove('sidebar_minimize');
                wrapper.classList.remove('sidebar_minimize_hover');
            }

            requestAnimationFrame(function() {
                document.documentElement.classList.remove('ease-restore-minimized');
            });

            const persistSidebarState = () => {
                localStorage.setItem(
                    sidebarStateKey,
                    wrapper.classList.contains('sidebar_minimize') ? '1' : '0'
                );
            };

            document.addEventListener('click', function(event) {
                if (!event.target.closest('.toggle-sidebar, .sidenav-toggler')) return;

                // Kaiadmin toggles class in its own handler, so persist after it runs.
                setTimeout(persistSidebarState, 0);
                setTimeout(persistSidebarState, 220);
            });

            const sidebarObserver = new MutationObserver(function(mutations) {
                for (const mutation of mutations) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        persistSidebarState();
                    }
                }
            });
            sidebarObserver.observe(wrapper, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/plugins.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/kaiadmin.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/header.css') ?>" />
    <style>
        html,
        body {
            margin: 0 ;
            padding: 0 ;
        }

        #orderModal .card {
            border-radius: 1rem;
        }

        #orderModal .card-header {
            border-bottom: 1px solid #e9ecef;
            font-size: 1rem;
        }

        #orderModal p {
            margin-bottom: 0.4rem;
        }

        .badge-superadmin {
            background: #900707ff;
            color: white;
            font-size: 1rem;
            padding: 6px 12px;
        }

        .badge-admin {
            background: #5B532C;
            color: white;
            font-size: 1rem;
            padding: 6px 12px;
        }

        .badge-pending {
            background-color: #f2be00;
            color: #000;
            font-size: 1rem;
            padding: 6px 12px;
            font-weight: 600;
        }

        .badge-progress {
            background-color: #5B532C;
            color: #ffffff;
            font-size: 1rem;
            padding: 6px 12px;
            font-weight: 600;
        }

        .badge-completed {
            background-color: #ABE7B2;
            color: #000;
            font-size: 1rem;
            padding: 6px 12px;
            font-weight: 600;
        }

        .btn-update {
            background-color: #f2be00;
            color: #000;
        }

        .btn-update:hover {
            background-color: #e6ac00;
            color: #000;
        }

        .btn-cancel {
            background-color: #5B532C;
            color: #fff;
        }

        .btn-cancel:hover {
            background-color: #47421f;
            color: #fff;
        }

        .status-indicator {
            position: absolute;
            top: 0;
            right: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .notif-img {
            position: relative;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-weight: bold;
            color: white;
        }

        /* Color sets */
        .bg-a {
            background-color: #5B532C ;
        }

        .bg-b {
            background-color: #47421f ;
        }

        .bg-c {
            background-color: #51cf66 ;
        }

        .bg-d {
            background-color: #845ef7 ;
        }

        .bg-e {
            background-color: #ffa94d ;
        }

        .icon-visitor {
            background-color: #f2be00 ;
            color: #fff ;
            border-radius: 10px;
        }

        .icon-admin {
            background-color: #900707ff ;
            color: #fff ;
            border-radius: 10px;
        }

        .icon-sales {
            background-color: #84994F ;
            color: #fff ;
            border-radius: 10px;
        }

        .icon-order {
            background-color: #A18D6D ;
            color: #fff ;
            border-radius: 10px;
        }

        .btn-pending {
            background-color: #A72703;
            color: #fff;
            font-size: 15px;
        }

        .btn-pending:hover {
            background-color: #921f03;
            color: #fff;
        }

        .btn-progress {
            background-color: #5B532C;
            color: #fff;
            font-size: 15px;
        }

        .btn-progress:hover {
            background-color: #47421f;
            color: #fff;
        }

        .btn-completed {
            background-color: #63A361;
            color: #fff;
            font-size: 15px;
        }

        .btn-completed:hover {
            background-color: #4d844d;
            color: #fff;
        }

        body.dark-mode {
            background-color: #18191a;
            color: #e4e6eb;
        }

        body.dark-mode .card,
        body.dark-mode .navbar,
        body.dark-mode .footer,
        body.dark-mode .sidebar,
        body.dark-mode .main-header,
        body.dark-mode .main-panel {
            background-color: #242526 ;
            color: #e4e6eb ;
        }

        body.dark-mode .table,
        body.dark-mode .table th,
        body.dark-mode .table td {
            background-color: #242526 ;
            color: #e4e6eb ;
            border-color: #3a3b3c ;
        }

        body.dark-mode .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #2d2e2f ;
            color: #e4e6eb ;
        }

        body.dark-mode .table-hover > tbody > tr:hover > * {
            background-color: #3a3b3c ;
            color: #fff ;
        }

        body.dark-mode .table-light th,
        body.dark-mode .table-light td,
        body.dark-mode thead.table-light th {
            background-color: #1e1f20 ;
            color: #e4e6eb ;
        }

        body.dark-mode,
        body.dark-mode p,
        body.dark-mode span,
        body.dark-mode label,
        body.dark-mode h1, body.dark-mode h2, body.dark-mode h3,
        body.dark-mode h4, body.dark-mode h5, body.dark-mode h6,
        body.dark-mode a:not(.btn),
        body.dark-mode .card-title,
        body.dark-mode .card-body {
            color: #e4e6eb ;
        }

        body.dark-mode .text-muted {
            color: #adb5bd ;
        }

        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.3em;
        }

        .form-switch .form-check-input:checked {
            background-color: #5B532C;
            border-color: #5B532C;
        .nav-pills.nav-secondary .nav-link.active,
        .nav-pills.nav-secondary .nav-link.active:hover,
        .nav-pills.nav-secondary .nav-link.active:focus {
            background-color: #f2be00 ;
            color: #000 ;
            border: none ;
            box-shadow: none ;
        }

        .btn-secondary {
            background-color: #f2be00 ;
            border-color: #f2be00 ;
            color: #000 ;
        }

        .btn-secondary:hover {
            background-color: #e6ac00 ;
            border-color: #e6ac00 ;
            color: #000 ;
        }

        /* ============================================================
           NAVBAR — Profile Dropdown (Reports/Revenue Theme)
           Selectors are scoped to .navbar-header / .navbar-nav so they
           outrank kaiadmin's `.navbar-header .navbar-nav .dropdown-menu`
           and `.navbar-header .dropdown-menu:after` defaults.
           ============================================================ */
        .ease-user-dropdown,
        .navbar-header .ease-user-dropdown,
        .navbar-header .navbar-nav .ease-user-dropdown,
        .main-header .navbar-header .navbar-nav .ease-user-dropdown {
            min-width: 280px ;
            width: 280px ;
            max-width: 320px ;
            padding: 0 ;
            border: none ;
            border-radius: 0 ;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.22) ;
            overflow: hidden ;
            background: #ffffff ;
            font-family: 'Oxanium', 'Public Sans', sans-serif;
        }

        /* Recolor / hide the kaiadmin pointer arrow on top of the dropdown
           (it's a CSS triangle whose visible color is border-bottom) */
        .navbar-header .ease-user-dropdown:after,
        .navbar-header .navbar-nav .ease-user-dropdown:after {
            border-bottom-color: #1A1A1A ;
        }

        .ease-user-dropdown .dropdown-user-scroll,
        .navbar-header .ease-user-dropdown .dropdown-user-scroll {
            padding: 0 ;
            margin: 0 ;
            background: #ffffff ;
            max-height: none ;
        }

        .ease-user-dropdown li,
        .navbar-header .ease-user-dropdown li {
            list-style: none ;
            margin: 0 ;
            padding: 0 ;
        }

        /* Dark gold-titled header — matches rpt-card-header */
        .ease-user-dropdown .ease-user-box,
        .navbar-header .ease-user-dropdown .ease-user-box,
        .navbar-header .ease-user-dropdown .user-box.ease-user-box {
            background: #1A1A1A ;
            color: #fff ;
            padding: 18px 18px 16px ;
            border-bottom: 2px solid #F2BE00 ;
            display: flex ;
            align-items: center ;
            gap: 14px ;
            margin: 0 ;
        }

        .ease-user-dropdown .ease-user-avatar,
        .navbar-header .ease-user-dropdown .ease-user-avatar {
            width: 56px ;
            height: 56px ;
            flex-shrink: 0;
            border-radius: 50% ;
            border: 2px solid #F2BE00 ;
            overflow: hidden;
            box-shadow: 0 0 0 3px rgba(242, 190, 0, 0.18);
            background: #1A1A1A ;
            padding: 0 ;
        }

        .ease-user-dropdown .ease-user-avatar .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .ease-user-dropdown .ease-user-text,
        .navbar-header .ease-user-dropdown .u-text.ease-user-text {
            flex: 1 1 auto ;
            min-width: 0 ;
            padding: 0 ;
        }

        .ease-user-dropdown .ease-user-name,
        .navbar-header .ease-user-dropdown .u-text h4.ease-user-name {
            font-family: 'Oxanium', sans-serif ;
            font-size: 1rem ;
            font-weight: 800 ;
            color: #F2BE00 ;
            margin: 0 0 2px ;
            line-height: 1.2 ;
            letter-spacing: -0.01em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ease-user-dropdown .ease-user-email,
        .navbar-header .ease-user-dropdown .u-text p.ease-user-email {
            font-size: 0.78rem ;
            color: #c9c9c9 ;
            margin: 0 0 8px ;
            line-height: 1.3 ;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Role badge — matches profile page badges */
        .ease-user-dropdown .ease-role-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 999px;
            font-family: 'Oxanium', sans-serif;
            font-size: 0.66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            line-height: 1.4;
        }

        .ease-user-dropdown .ease-role-super {
            background: #F2BE00;
            color: #111;
        }

        .ease-user-dropdown .ease-role-admin {
            background: rgba(242, 190, 0, 0.15);
            color: #F2BE00;
            border: 1px solid rgba(242, 190, 0, 0.35);
        }

        /* Action items below the header */
        .ease-user-dropdown .ease-user-actions,
        .navbar-header .ease-user-dropdown .ease-user-actions {
            padding: 6px 0 ;
            background: #ffffff ;
        }

        .ease-user-dropdown .ease-user-item,
        .navbar-header .ease-user-dropdown .ease-user-item,
        .navbar-header .navbar-nav .ease-user-dropdown .dropdown-item.ease-user-item {
            display: flex ;
            align-items: center ;
            gap: 12px ;
            padding: 10px 18px ;
            font-family: 'Oxanium', sans-serif ;
            font-size: 0.86rem ;
            font-weight: 600 ;
            color: #111827 ;
            background: transparent ;
            border: none ;
            transition: background 0.18s ease, color 0.18s ease, padding-left 0.18s ease ;
            white-space: nowrap ;
        }

        .ease-user-dropdown .ease-user-item i {
            width: 18px;
            font-size: 0.9rem;
            color: #6B7280;
            flex-shrink: 0;
            text-align: center;
            transition: color 0.18s ease;
        }

        .ease-user-dropdown .ease-user-item:hover,
        .ease-user-dropdown .ease-user-item:focus {
            background: rgba(242, 190, 0, 0.12) ;
            color: #111827 ;
            padding-left: 22px ;
        }

        .ease-user-dropdown .ease-user-item:hover i,
        .ease-user-dropdown .ease-user-item:focus i {
            color: #d4a700 ;
        }

        /* Logout — subtle red accent on hover */
        .ease-user-dropdown .ease-user-logout {
            color: #b91c1c ;
        }

        .ease-user-dropdown .ease-user-logout i {
            color: #b91c1c;
        }

        .ease-user-dropdown .ease-user-logout:hover,
        .ease-user-dropdown .ease-user-logout:focus {
            background: rgba(185, 28, 28, 0.08) ;
            color: #991b1b ;
        }

        .ease-user-dropdown .ease-user-logout:hover i,
        .ease-user-dropdown .ease-user-logout:focus i {
            color: #991b1b ;
        }

        /* Divider between primary actions and logout */
        .ease-user-dropdown .ease-user-divider {
            height: 1px;
            background: #F3F4F6;
            margin: 6px 14px;
        }

        /* Profile pic toggle — gold ring on hover/active */
        .ease-profile-toggle .avatar-sm .avatar-img {
            border: 2px solid transparent;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .ease-profile-toggle:hover .avatar-sm .avatar-img,
        .ease-profile-toggle[aria-expanded="true"] .avatar-sm .avatar-img {
            border-color: #F2BE00;
            box-shadow: 0 0 0 3px rgba(242, 190, 0, 0.2);
        }

        /* Dark mode — keep contrast inside dropdown body */
        body.dark-mode .ease-user-dropdown {
            background: #242526 ;
        }

        body.dark-mode .ease-user-dropdown .ease-user-actions {
            background: #242526 ;
        }

        body.dark-mode .ease-user-dropdown .ease-user-item {
            color: #e4e6eb ;
        }

        body.dark-mode .ease-user-dropdown .ease-user-item i {
            color: #adb5bd;
        }

        body.dark-mode .ease-user-dropdown .ease-user-divider {
            background: #3a3b3c;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="white">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="white">
                    <a href="<?= base_url('/admin'); ?>" class="logo">
                        <img
                            src="<?= base_url('assets/images/Ease_PNG_File-01-1.png') ?>"
                            alt="navbar brand"
                            class="navbar-brand"
                            height="65" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar" data-tooltip="Toggle sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-right"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="fas fa-ellipsis-vertical"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <?php
                        $currentPath = trim(uri_string(), '/');
                        $isSidebarActive = static function (array $routes) use ($currentPath): bool {
                            foreach ($routes as $route) {
                                $route = trim((string) $route, '/');
                                if ($route === $currentPath) {
                                    return true;
                                }
                            }
                            return false;
                        };
                    ?>
                    <ul class="nav nav-secondary">
                        <li class="nav-section">
                            <h4 class="text-section">Overview</h4>
                        </li>
                        <li class="nav-item<?= $isSidebarActive(['admin']) ? ' active' : '' ?>">
                            <a href="<?= base_url('/admin'); ?>">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-section">
                            <h4 class="text-section">Orders</h4>
                        </li>
                        <li class="nav-item<?= $isSidebarActive(['order']) ? ' active' : '' ?>">
                            <a href="<?= base_url('/order'); ?>">
                                <i class="fas fa-layer-group"></i>
                                <p>Order Management</p>
                            </a>
                        </li>
                        <li class="nav-item<?= $isSidebarActive(['admin/calendar']) ? ' active' : '' ?>">
                            <a href="<?= base_url('/admin/calendar'); ?>">
                                <i class="fas fa-calendar-alt"></i>
                                <p>Booking Calendar</p>
                            </a>
                        </li>
                        <li class="nav-section">
                            <h4 class="text-section">Users</h4>
                        </li>
                        <li class="nav-item<?= $isSidebarActive(['user']) ? ' active' : '' ?>">
                            <a href="<?= base_url('/user'); ?>">
                                <i class="fas fa-th-list"></i>
                                <p>User Management</p>
                            </a>
                        </li>
                        <?php if (session()->get('role') === '1'): ?>
                            <li class="nav-item<?= $isSidebarActive(['create_user']) ? ' active' : '' ?>">
                                <a href="<?= base_url('/create_user'); ?>">
                                    <i class="fas fa-user-plus"></i>
                                    <p>Add User</p>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-section">
                            <h4 class="text-section">Reports</h4>
                        </li>
                        <li class="nav-item<?= $isSidebarActive(['report']) ? ' active' : '' ?>">
                            <a href="<?= base_url('/report'); ?>">
                                <i class="fas fa-pen-square"></i>
                                <p>Revenue</p>
                            </a>
                        </li>
                        <li class="nav-item<?= $isSidebarActive(['transaction_history']) ? ' active' : '' ?>">
                            <a href="<?= base_url('/transaction_history'); ?>">
                                <i class="fas fa-file-invoice"></i>
                                <p>Transaction History</p>
                            </a>
                        </li>

                        <?php if (session()->get('role') === '1'): ?>
                            <li class="nav-section">
                                <h4 class="text-section">Management</h4>
                            </li>
                            <li class="nav-item<?= $isSidebarActive(['admin/service_management']) ? ' active' : '' ?>">
                                <a href="<?= base_url('/admin/service_management'); ?>">
                                    <i class="fas fa-table"></i>
                                    <p>Service Management</p>
                                </a>
                            </li>
                            <li class="nav-item<?= $isSidebarActive(['admin/promo_code']) ? ' active' : '' ?>">
                                <a href="<?= base_url('/admin/promo_code'); ?>">
                                    <i class="fas fa-tag"></i>
                                    <p>Promo Code</p>
                                </a>
                            </li>
                            <li class="nav-item<?= $isSidebarActive(['admin/contact']) ? ' active' : '' ?>">
                                <a href="<?= base_url('/admin/contact'); ?>">
                                    <i class="fas fa-envelope"></i>
                                    <p>Contact</p>
                                </a>
                            </li>

                            <li class="nav-item<?= $isSidebarActive(['admin/refund_request']) ? ' active' : '' ?>">
                                <a href="<?= base_url('/admin/refund_request'); ?>">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <p>Refund Request</p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#tables">
                                <i class="fas fa-table"></i>
                                <p>History</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="tables">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="tables/tables.html">
                                            <span class="sub-item">Basic Table</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="tables/datatables.html">
                                            <span class="sub-item">Datatables</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#maps">
                                <i class="fas fa-map-marker-alt"></i>
                                <p>Maps</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="maps">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="maps/googlemaps.html">
                                            <span class="sub-item">Google Maps</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="maps/jsvectormap.html">
                                            <span class="sub-item">Jsvectormap</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#charts">
                                <i class="far fa-chart-bar"></i>
                                <p>Charts</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="charts">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="charts/charts.html">
                                            <span class="sub-item">Chart Js</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="charts/sparkline.html">
                                            <span class="sub-item">Sparkline</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="widgets.html">
                                <i class="fas fa-desktop"></i>
                                <p>Widgets</p>
                                <span class="badge badge-success">4</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../documentation/index.html">
                                <i class="fas fa-file"></i>
                                <p>Documentation</p>
                                <span class="badge badge-secondary">1</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#submenu">
                                <i class="fas fa-bars"></i>
                                <p>Menu Levels</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="submenu">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a data-bs-toggle="collapse" href="#subnav1">
                                            <span class="sub-item">Level 1</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse" id="subnav1">
                                            <ul class="nav nav-collapse subnav">
                                                <li>
                                                    <a href="#">
                                                        <span class="sub-item">Level 2</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <span class="sub-item">Level 2</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="collapse" href="#subnav2">
                                            <span class="sub-item">Level 1</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse" id="subnav2">
                                            <ul class="nav nav-collapse subnav">
                                                <li>
                                                    <a href="#">
                                                        <span class="sub-item">Level 2</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="sub-item">Level 1</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li> -->
                    </ul>
                    <div class="sidebar-darkmode-wrap">
                        <div class="form-check form-switch sidebar-darkmode-toggle">
                            <label class="form-check-label mode-label" for="darkModeToggle">
                                <i id="darkModeIcon" class="bi bi-sun-fill"></i>
                                <span class="mode-text">Theme</span>
                            </label>
                            <input class="form-check-input" type="checkbox" id="darkModeToggle">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="white">
                        <a href="index.html" class="logo">
                            <img
                                src="<?= base_url('assets/images/Ease_PNG_File-01-1.png') ?>"
                                alt="navbar brand"
                                class="navbar-brand"
                                height="60" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar" data-tooltip="Toggle sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-right"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="fas fa-ellipsis-vertical"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>

                <!-- Centered brand â€” only shown when sidebar is minimized -->
                <a href="<?= base_url('/admin'); ?>" class="ease-minimized-brand" aria-label="EASE Sarawak Home">
                    <img
                        src="<?= base_url('assets/images/Ease_PNG_File-01-1.png') ?>"
                        alt="EASE Sarawak" />
                </a>
                <!-- Navbar Header -->
                <nav
                    class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <!-- <nav
                            class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pe-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input
                                    type="text"
                                    placeholder="Search ..."
                                    class="form-control" />
                            </div>
                        </nav> -->

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li
                                class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                                <a
                                    class="nav-link dropdown-toggle ease-topbar-trigger"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    role="button"
                                    aria-expanded="false"
                                    aria-haspopup="true"
                                    data-tooltip="Search">
                                    <i class="fa fa-search"></i>
                                    <span class="ease-topbar-tooltip-chip">Search</span>
                                </a>
                                <ul class="dropdown-menu dropdown-search animated fadeIn">
                                    <form class="navbar-left navbar-form nav-search">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                placeholder="Search ..."
                                                class="form-control" />
                                        </div>
                                    </form>
                                </ul>
                            </li>
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a
                                    class="nav-link dropdown-toggle ease-topbar-trigger"
                                    href="#"
                                    id="messageDropdown"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    data-tooltip="Messages<?= ((int)$newMessageCount > 0) ? ' (' . (int)$newMessageCount . ' new)' : '' ?>">
                                    <i class="fa fa-envelope"></i>
                                    <span class="ease-topbar-tooltip-chip">Messages<?= ((int)$newMessageCount > 0) ? ' (' . (int)$newMessageCount . ' new)' : '' ?></span>
                                    <?php if ($newMessageCount > 0): ?>
                                        <span class="notification"><?php echo $newMessageCount; ?></span>
                                    <?php endif; ?>
                                </a>
                                
                                <ul
                                    class="dropdown-menu messages-notif-box animated fadeIn"
                                    aria-labelledby="messageDropdown">
                                    <li>
                                        <div
                                            class="dropdown-title d-flex justify-content-between align-items-center">
                                            Messages
                                            <a href="#" id="markAllMessagesRead" class="small">Mark all as read</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-notif-scroll scrollbar-outer">
                                            <div class="notif-center">
                                                <?php if (!empty($headerMessages)): ?>
                                                    <?php foreach ($headerMessages as $msg): ?>
                                                        <a href="<?= base_url('admin/contact?message_id=' . $msg['msg_id']) ?>">
                                                            <div class="notif-img">
                                                                <img src="assets/img/default-user.png" alt="Img Profile" />
                                                                <?php
                                                                $messageStatus = trim((string) ($msg['status'] ?? ''));
                                                                ?>
                                                                <?php if ($messageStatus === '' || $messageStatus === 'new'): ?>
                                                                    <span class="status-indicator bg-danger"></span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="notif-content">
                                                                <span class="subject"><?= esc($msg['email']) ?></span>
                                                                <span class="block"><?= esc($msg['msg']) ?></span>
                                                                <span class="time">
                                                                    <?= timeAgo($msg['created_date']) ?>
                                                                </span>
                                                            </div>
                                                        </a>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <p class="messages-empty">No messages yet.</p>
                                                <?php endif; ?>
                                                <!-- <a href="#">
                                                    <div class="notif-img">
                                                        <img
                                                            src="assets/img/chadengle.jpg"
                                                            alt="Img Profile" />
                                                    </div>
                                                    <div class="notif-content">
                                                        <span class="subject">Chad</span> -->
                                                <!-- <span class="block"> Ok, Thanks ! </span>
                                                        <span class="time">12 minutes ago</span>
                                                    </div>
                                                </a>
                                                <a href="#">
                                                    <div class="notif-img">
                                                        <img
                                                            src="assets/img/mlane.jpg"
                                                            alt="Img Profile" />
                                                    </div>
                                                    <div class="notif-content">
                                                        <span class="subject">Jhon Doe</span>
                                                        <span class="block">
                                                            Ready for the meeting today...
                                                        </span>
                                                        <span class="time">12 minutes ago</span>
                                                    </div>
                                                </a>
                                                <a href="#">
                                                    <div class="notif-img">
                                                        <img
                                                            src="assets/img/talha.jpg"
                                                            alt="Img Profile" />
                                                    </div>
                                                    <div class="notif-content">
                                                        <span class="subject">Talha</span>
                                                        <span class="block"> Hi, Apa Kabar ? </span>
                                                        <span class="time">17 minutes ago</span>
                                                    </div>
                                                </a> -->
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="see-all" href="<?= base_url('admin/contact') ?>">See all messages<i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const markAllLink = document.getElementById('markAllMessagesRead');
                                    if (!markAllLink) {
                                        return;
                                    }

                                    markAllLink.addEventListener('click', function(event) {
                                        event.preventDefault();

                                        fetch('<?= base_url('admin/markAllMessagesRead') ?>', {
                                            method: 'POST',
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                document.querySelectorAll('.status-indicator').forEach(el => el.remove());
                                                const countBadge = document.querySelector('#messageDropdown .notification');
                                                if (countBadge) {
                                                    countBadge.remove();
                                                }
                                                const messageTrigger = document.getElementById('messageDropdown');
                                                if (messageTrigger) {
                                                    messageTrigger.setAttribute('data-tooltip', 'Messages');
                                                    const messageTooltipChip = messageTrigger.querySelector('.ease-topbar-tooltip-chip');
                                                    if (messageTooltipChip) {
                                                        messageTooltipChip.textContent = 'Messages';
                                                    }
                                                }
                                            }
                                        });
                                    });
                                });
                            </script>
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a
                                    class="nav-link dropdown-toggle ease-topbar-trigger"
                                    href="#"
                                    id="notifDropdown"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    data-tooltip="Notifications<?= ((int)$newMessageCount > 0) ? ' (' . (int)$newMessageCount . ' new)' : '' ?>">
                                    <i class="fa fa-bell"></i>
                                    <span class="ease-topbar-tooltip-chip">Notifications<?= ((int)$newMessageCount > 0) ? ' (' . (int)$newMessageCount . ' new)' : '' ?></span>
                                    <?php if ($newMessageCount > 0): ?>
                                        <span class="notification"><?= (int)$newMessageCount ?></span>
                                    <?php endif; ?>
                                </a>
                                <ul
                                    class="dropdown-menu notif-box animated fadeIn"
                                    aria-labelledby="notifDropdown">
                                    <li>
                                        <div class="dropdown-title d-flex justify-content-between align-items-center">
                                            <span>
                                                <?= (int)$newMessageCount > 0
                                                    ? 'You have ' . (int)$newMessageCount . ' new notification' . ((int)$newMessageCount === 1 ? '' : 's')
                                                    : 'Notifications' ?>
                                            </span>
                                            <a href="#" id="clearAllNotifications" class="small">Remove notifications</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="notif-scroll scrollbar-outer">
                                            <div class="notif-center">
                                                <?php if (!empty($headerMessages)): ?>
                                                    <?php foreach ($headerMessages as $msg): ?>
                                                        <?php
                                                        $notifText = strtolower(trim((string)($msg['msg'] ?? '')));
                                                        $status = strtolower(trim((string)($msg['status'] ?? '')));
                                                        $notifIcon = 'fa-bell';
                                                        $notifTone = 'notif-primary';

                                                        if ($status === 'new' || $status === '' || str_contains($notifText, 'new')) {
                                                            $notifIcon = 'fa-envelope';
                                                            $notifTone = 'notif-warning';
                                                        } elseif (str_contains($notifText, 'refund')) {
                                                            $notifIcon = 'fa-file-invoice-dollar';
                                                            $notifTone = 'notif-danger';
                                                        } elseif (str_contains($notifText, 'payment') || str_contains($notifText, 'transaction')) {
                                                            $notifIcon = 'fa-credit-card';
                                                            $notifTone = 'notif-success';
                                                        } elseif (str_contains($notifText, 'booking') || str_contains($notifText, 'order')) {
                                                            $notifIcon = 'fa-calendar-check';
                                                            $notifTone = 'notif-info';
                                                        } elseif (str_contains($notifText, 'contact') || str_contains($notifText, 'message')) {
                                                            $notifIcon = 'fa-comment-dots';
                                                            $notifTone = 'notif-primary';
                                                        }
                                                        ?>
                                                        <a href="<?= base_url('admin/contact?message_id=' . (int)$msg['msg_id']) ?>">
                                                            <div class="notif-icon <?= esc($notifTone) ?>">
                                                                <i class="fa <?= esc($notifIcon) ?>"></i>
                                                            </div>
                                                            <div class="notif-content">
                                                                <span class="block"><?= esc($msg['msg'] ?: 'You have a new notification') ?></span>
                                                                <span class="time"><?= timeAgo($msg['created_date']) ?></span>
                                                            </div>
                                                        </a>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <p class="messages-empty">No notifications yet.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const clearNotifLink = document.getElementById('clearAllNotifications');
                                    if (!clearNotifLink) return;

                                    clearNotifLink.addEventListener('click', function(event) {
                                        event.preventDefault();

                                        fetch('<?= base_url('admin/markAllMessagesRead') ?>', {
                                            method: 'POST',
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        })
                                        .then(response => response.json())
                                        .then((data) => {
                                            if (!data.success) return;

                                            const notifMenu = document.querySelector('#notifDropdown')
                                                ?.closest('.nav-item')
                                                ?.querySelector('.notif-box .notif-center');
                                            if (notifMenu) {
                                                notifMenu.innerHTML = '<p class="messages-empty">No notifications yet.</p>';
                                            }

                                            const notifCountBadge = document.querySelector('#notifDropdown .notification');
                                            if (notifCountBadge) notifCountBadge.remove();

                                            const notifTitle = document.querySelector('.notif-box .dropdown-title span');
                                            if (notifTitle) notifTitle.textContent = 'Notifications';

                                            const notifTrigger = document.getElementById('notifDropdown');
                                            if (notifTrigger) {
                                                notifTrigger.setAttribute('data-tooltip', 'Notifications');
                                                const notifTooltipChip = notifTrigger.querySelector('.ease-topbar-tooltip-chip');
                                                if (notifTooltipChip) {
                                                    notifTooltipChip.textContent = 'Notifications';
                                                }
                                            }
                                        });
                                    });
                                });
                            </script>
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a
                                    class="nav-link ease-topbar-trigger"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    aria-expanded="false"
                                    data-tooltip="Quick Actions">
                                    <i class="fas fa-layer-group"></i>
                                    <span class="ease-topbar-tooltip-chip">Quick Actions</span>
                                </a>
                                <div class="dropdown-menu quick-actions animated fadeIn">
                                    <div class="quick-actions-header">
                                        <span class="title mb-1">Quick Actions</span>
                                        <span class="subtitle op-7">Shortcuts</span>
                                    </div>
                                    <div class="quick-actions-scroll scrollbar-outer">
                                        <div class="quick-actions-items">
                                            <div class="row m-0">
                                                <a class="col-6 col-md-4 p-0" href="<?= base_url('/admin/calendar'); ?>">
                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-danger rounded-circle">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </div>
                                                        <span class="text">Calendar</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0" href="<?= base_url('/') ?>">
                                                    <div class="quick-actions-item">
                                                        <div
                                                            class="avatar-item bg-warning rounded-circle">
                                                            <i class="fas fa-map"></i>
                                                        </div>
                                                        <span class="text">Main Website</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0" href="<?= base_url('/report') ?>">
                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-info rounded-circle">
                                                            <i class="fas fa-file-excel"></i>
                                                        </div>
                                                        <span class="text">Reports</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0" href="<?= base_url('/admin/contact'); ?>">
                                                    <div class="quick-actions-item">
                                                        <div
                                                            class="avatar-item bg-success rounded-circle">
                                                            <i class="fas fa-envelope"></i>
                                                        </div>
                                                        <span class="text">Contact</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0" href="<?= base_url('/transaction_history'); ?>">
                                                    <div class="quick-actions-item">
                                                        <div
                                                            class="avatar-item bg-primary rounded-circle">
                                                            <i class="fas fa-file-invoice-dollar"></i>
                                                        </div>
                                                        <span class="text">Transaction History</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0" href="<?= base_url('/user'); ?>">
                                                    <div class="quick-actions-item">
                                                        <div
                                                            class="avatar-item bg-secondary rounded-circle">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <span class="text">User</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <?php $session = session(); ?>
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a
                                    class="dropdown-toggle profile-pic ease-profile-toggle"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    aria-expanded="false"
                                    data-tooltip="My Account">
                                    <span class="ease-topbar-tooltip-chip">My Account</span>
                                    <div class="avatar-sm">
                                        <img
                                            src="<?= esc($user['profile_picture'] ? base_url($user['profile_picture']) : base_url('assets/images/user.png')) ?>"
                                            alt="..."
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold"><?= esc($session->get('username')) ?></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user ease-user-dropdown animated fadeIn">
                                    <li>
                                        <div class="user-box ease-user-box">
                                            <div class="avatar-lg ease-user-avatar">
                                                <img
                                                    src="<?= esc($user['profile_picture'] ? base_url($user['profile_picture']) : base_url('assets/images/user.png')) ?>"
                                                    alt="image profile"
                                                    class="avatar-img rounded-circle" />
                                            </div>
                                            <div class="u-text ease-user-text">
                                                <h4 class="ease-user-name"><?= esc($session->get('username')) ?></h4>
                                                <p class="ease-user-email"><?= esc($session->get('email')) ?></p>
                                                <?php
                                                    $hdrRole = session()->get('role');
                                                    $hdrRoleLabel = $hdrRole === '1' ? 'Super Admin' : 'Admin';
                                                    $hdrBadgeClass = $hdrRole === '1' ? 'ease-role-super' : 'ease-role-admin';
                                                ?>
                                                <span class="ease-role-badge <?= $hdrBadgeClass ?>"><?= esc($hdrRoleLabel) ?></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ease-user-actions">
                                            <a class="dropdown-item ease-user-item" href="<?= base_url('/profile') ?>">
                                                <i class="fas fa-user"></i>
                                                <span>My Profile</span>
                                            </a>
                                            <a class="dropdown-item ease-user-item" href="<?= base_url('/edit_profile/' . (int) $session->get('user_id')) ?>">
                                                <i class="fas fa-user-edit"></i>
                                                <span>Edit Profile</span>
                                            </a>
                                            <a class="dropdown-item ease-user-item" href="<?= base_url('/change_password') ?>">
                                                <i class="fas fa-key"></i>
                                                <span>Change Password</span>
                                            </a>
                                            <div class="ease-user-divider"></div>
                                            <a class="dropdown-item ease-user-item ease-user-logout" href="<?= base_url('/logout') ?>">
                                                <i class="fas fa-sign-out-alt"></i>
                                                <span>Logout</span>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>
