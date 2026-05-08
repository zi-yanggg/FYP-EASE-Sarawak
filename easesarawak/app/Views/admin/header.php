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

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/plugins.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/kaiadmin.min.css') ?>" />
    <style>
        #orderModal .card {
            border-radius: 1rem;
        }

        :root {
            --ease-sidebar-icon-purple: #7c3aed;
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
            background-color: #5B532C !important;
        }

        .bg-b {
            background-color: #47421f !important;
        }

        .bg-c {
            background-color: #51cf66 !important;
        }

        .bg-d {
            background-color: #845ef7 !important;
        }

        .bg-e {
            background-color: #ffa94d !important;
        }

        .icon-visitor {
            background-color: #f2be00 !important;
            color: #fff !important;
            border-radius: 10px;
        }

        .icon-admin {
            background-color: #900707ff !important;
            color: #fff !important;
            border-radius: 10px;
        }

        .icon-sales {
            background-color: #84994F !important;
            color: #fff !important;
            border-radius: 10px;
        }

        .icon-order {
            background-color: #A18D6D !important;
            color: #fff !important;
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
            background-color: #242526 !important;
            color: #e4e6eb !important;
        }

        body.dark-mode .table,
        body.dark-mode .table th,
        body.dark-mode .table td {
            background-color: #242526 !important;
            color: #e4e6eb !important;
            border-color: #3a3b3c !important;
        }

        body.dark-mode .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #2d2e2f !important;
            color: #e4e6eb !important;
        }

        body.dark-mode .table-hover > tbody > tr:hover > * {
            background-color: #3a3b3c !important;
            color: #fff !important;
        }

        body.dark-mode .table-light th,
        body.dark-mode .table-light td,
        body.dark-mode thead.table-light th {
            background-color: #1e1f20 !important;
            color: #e4e6eb !important;
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
            color: #e4e6eb !important;
        }

        body.dark-mode .text-muted {
            color: #adb5bd !important;
        }

        /* ============================================================
           Dark-Mode Toggle (Sidebar) — Professional Gold Theme
           Always-white thumb, neutral gray track when off,
           gold track when on, gold focus ring. No blue, no black flash.
           ============================================================ */
        .sidebar-darkmode-toggle .form-check-input,
        .sidebar .sidebar-darkmode-toggle .form-check-input {
            width: 2.4em !important;
            height: 1.35em !important;
            margin: 0 !important;
            padding: 0 !important;
            background-color: #cbd5e1 !important;       /* slate gray track (light mode, off) */
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
            cursor: pointer;
            transition: background-color .25s ease, border-color .25s ease, box-shadow .25s ease;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e") !important;
        }

        .sidebar-darkmode-toggle .form-check-input:hover,
        .sidebar .sidebar-darkmode-toggle .form-check-input:hover {
            background-color: #94a3b8 !important;
            border-color: #94a3b8 !important;
        }

        .sidebar-darkmode-toggle .form-check-input:focus,
        .sidebar .sidebar-darkmode-toggle .form-check-input:focus {
            border-color: #f2be00 !important;
            box-shadow: 0 0 0 .2rem rgba(242, 190, 0, 0.28) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e") !important;
        }

        .sidebar-darkmode-toggle .form-check-input:checked,
        .sidebar .sidebar-darkmode-toggle .form-check-input:checked {
            background-color: #f2be00 !important;       /* gold track (on) */
            border-color: #f2be00 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e") !important;
        }

        .sidebar-darkmode-toggle .form-check-input:checked:hover,
        .sidebar .sidebar-darkmode-toggle .form-check-input:checked:hover {
            background-color: #d4a700 !important;
            border-color: #d4a700 !important;
        }

        .sidebar-darkmode-toggle .form-check-input:checked:focus,
        .sidebar .sidebar-darkmode-toggle .form-check-input:checked:focus {
            background-color: #f2be00 !important;
            border-color: #f2be00 !important;
            box-shadow: 0 0 0 .2rem rgba(242, 190, 0, 0.4) !important;
        }

        /* Dark mode body — maintain the same gold-on-gold paradigm */
        body.dark-mode .sidebar-darkmode-toggle .form-check-input {
            background-color: #4b5563 !important;
            border-color: #4b5563 !important;
        }

        body.dark-mode .sidebar-darkmode-toggle .form-check-input:hover {
            background-color: #6b7280 !important;
            border-color: #6b7280 !important;
        }

        body.dark-mode .sidebar-darkmode-toggle .form-check-input:checked {
            background-color: #f2be00 !important;
            border-color: #f2be00 !important;
        }

        body.dark-mode .sidebar-darkmode-toggle .form-check-input:focus {
            border-color: #f2be00 !important;
            box-shadow: 0 0 0 .2rem rgba(242, 190, 0, 0.32) !important;
        }

        .nav-pills.nav-secondary .nav-link.active,
        .nav-pills.nav-secondary .nav-link.active:hover,
        .nav-pills.nav-secondary .nav-link.active:focus {
            background-color: #f2be00 !important;
            color: #000 !important;
            border: none !important;
            box-shadow: none !important;
        }

        .btn-secondary {
            background-color: #f2be00 !important;
            border-color: #f2be00 !important;
            color: #000 !important;
        }

        .btn-secondary:hover {
            background-color: #e6ac00 !important;
            border-color: #e6ac00 !important;
            color: #000 !important;
        }

        /* Revenue-page themed dropdown menus */
        .navbar .dropdown-menu,
        .main-header .dropdown-menu {
            background: #ffffff !important;
            border: 1px solid #000 !important;
            box-shadow: 0 10px 30px rgba(47, 42, 18, 0.12) !important;
            border-radius: 0 !important;
            overflow: hidden;
        }

        .main-header .quick-actions,
        .main-header .dropdown-menu.quick-actions {
            border: 1px solid #000 !important;
            border-radius: 0 !important;
            overflow: hidden;
        }

        .main-header .quick-actions .quick-actions-header {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .main-header .quick-actions .quick-actions-header {
            background: #f2be00 !important;
            color: #1a1a1a !important;
            border-bottom: 1px solid rgba(26, 26, 26, 0.12) !important;
        }

        .main-header .quick-actions .quick-actions-item {
            color: #1a1a1a !important;
            border-radius: 10px;
            transition: background-color .18s ease, color .18s ease;
        }

        .main-header .quick-actions .quick-actions-item:hover {
            background: #fdf3c6 !important;
            color: #1a1a1a !important;
        }

        .main-header .quick-actions .quick-actions-item .avatar-item {
            background: #1a1a1a !important;
            color: #f2be00 !important;
        }

        /* Top navbar typography consistency */
        .main-header,
        .main-header .navbar,
        .main-header .navbar * ,
        .main-header .topbar-nav,
        .main-header .topbar-nav * {
            font-family: 'Oxanium', sans-serif !important;
        }

        /* Keep icon fonts intact (do not override with Oxanium) */
        .main-header i.fa,
        .main-header i.fas,
        .main-header i.far,
        .main-header i.fab,
        .main-header [class^="fa-"],
        .main-header [class*=" fa-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
        }

        .main-header [class^="gg-"],
        .main-header [class*=" gg-"] {
            font-family: initial !important;
        }

        .main-header .bi {
            font-family: "bootstrap-icons" !important;
        }

        .navbar .dropdown-menu .dropdown-title,
        .main-header .dropdown-menu .dropdown-title {
            background: #f2be00 !important;
            color: #1a1a1a !important;
            border-bottom: 1px solid rgba(26, 26, 26, 0.12) !important;
            font-weight: 700;
        }

        .navbar .dropdown-menu .dropdown-item,
        .main-header .dropdown-menu .dropdown-item,
        .navbar .dropdown-menu a,
        .main-header .dropdown-menu a {
            color: #2f2a12 !important;
        }

        .navbar .dropdown-menu .dropdown-item:hover,
        .navbar .dropdown-menu .dropdown-item:focus,
        .main-header .dropdown-menu .dropdown-item:hover,
        .main-header .dropdown-menu .dropdown-item:focus,
        .navbar .dropdown-menu a:hover,
        .main-header .dropdown-menu a:hover {
            background: #fdf3c6 !important;
            color: #1a1a1a !important;
        }

        .navbar .dropdown-menu .dropdown-divider,
        .main-header .dropdown-menu .dropdown-divider {
            border-top-color: rgba(26, 26, 26, 0.12) !important;
        }

        .navbar .dropdown-menu .see-all,
        .main-header .dropdown-menu .see-all {
            color: #1a1a1a !important;
            font-weight: 700;
        }

        .navbar .dropdown-menu .see-all:hover,
        .main-header .dropdown-menu .see-all:hover {
            background: #fdf3c6 !important;
            color: #1a1a1a !important;
        }

        #markAllMessagesRead:hover,
        #markAllMessagesRead:focus,
        #clearAllNotifications:hover,
        #clearAllNotifications:focus {
            background: transparent !important;
            color: inherit !important;
            text-decoration: none !important;
            box-shadow: none !important;
        }

        .messages-notif-box .notif-center .messages-empty {
            text-align: center;
            margin: 12px 0;
            color: #1a1a1a;
            font-weight: 600;
        }

        .notif-box .notif-center .messages-empty {
            text-align: center;
            margin: 12px 0;
            color: #1a1a1a;
            font-weight: 600;
        }

        /* Top navigation icon purple glow */
        .topbar-nav .nav-link i,
        .topbar-nav .topbar-toggler i,
        .topbar-nav .dropdown-toggle i {
            transition: color .18s ease, text-shadow .18s ease, transform .18s ease !important;
        }

        .topbar-nav .nav-link,
        .topbar-nav .topbar-toggler,
        .topbar-nav .dropdown-toggle {
            border-radius: 10px;
            transition: background-color .18s ease !important;
        }

        .topbar-nav .nav-link:hover i,
        .topbar-nav .nav-link:focus i,
        .topbar-nav .topbar-toggler:hover i,
        .topbar-nav .topbar-toggler:focus i,
        .topbar-nav .dropdown-toggle:hover i,
        .topbar-nav .dropdown-toggle:focus i {
            color: var(--ease-sidebar-icon-purple) !important;
            text-shadow: none !important;
            transform: none !important;
        }

        .topbar-nav .nav-link:hover,
        .topbar-nav .nav-link:focus,
        .topbar-nav .topbar-toggler:hover,
        .topbar-nav .topbar-toggler:focus,
        .topbar-nav .dropdown-toggle:hover,
        .topbar-nav .dropdown-toggle:focus {
            background: #ffd24d !important;
        }

        .sidebar .nav,
        .sidebar .nav p,
        .sidebar .nav .sub-item,
        .sidebar .logo-header,
        .sidebar .logo-header .logo {
            font-family: 'Oxanium', sans-serif !important;
        }

        .sidebar .nav p {
            font-size: 0.86rem !important;
            line-height: 1.2 !important;
        }

        .sidebar .nav .nav-item a i {
            font-size: 0.92rem !important;
        }

        .sidebar .nav .nav-item > a {
            border-radius: 10px;
            transition: background-color .2s ease, color .2s ease;
            min-height: 48px;
            display: flex;
            align-items: center;
        }

        .sidebar .nav .nav-item > a:hover,
        .sidebar .nav .nav-item > a:focus {
            background: #ffd24d !important;
            color: #2b2200 !important;
        }

        .sidebar .nav .nav-item.active > a,
        .sidebar .nav .nav-item > a[aria-expanded="true"] {
            background: #f2be00 !important;
            color: #1f1a00 !important;
            font-weight: 700;
        }

        .sidebar .nav .nav-item.active > a i,
        .sidebar .nav .nav-item > a:hover i,
        .sidebar .nav .nav-item > a:focus i {
            color: inherit !important;
        }

        .sidebar .nav.nav-secondary .nav-item > a:hover p,
        .sidebar .nav.nav-secondary .nav-item > a:focus p,
        .sidebar .nav.nav-secondary .nav-item > a:hover i,
        .sidebar .nav.nav-secondary .nav-item > a:focus i,
        .sidebar .nav.nav-secondary .nav-item > a:hover .caret,
        .sidebar .nav.nav-secondary .nav-item > a:focus .caret {
            color: #2b2200 !important;
        }

        .sidebar .nav.nav-secondary .nav-item > a:hover::before,
        .sidebar .nav.nav-secondary .nav-item > a:focus::before,
        .sidebar .nav.nav-secondary .nav-item.active > a::before {
            background: #ffd24d !important;
        }

        .sidebar .sidebar-content {
            min-height: calc(100vh - 96px);
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
            flex: 1 1 auto !important;
            padding-bottom: 0;
            overflow: hidden;
        }

        .sidebar .sidebar-wrapper,
        .sidebar .sidebar-wrapper.scrollbar,
        .sidebar .sidebar-wrapper.scrollbar-inner {
            overflow: hidden !important;
            height: calc(100vh - 96px) !important;
            display: flex !important;
            flex-direction: column !important;
        }

        /* The kaiadmin scrollbar plugin wraps content in `.scroll-content`;
           force it to flex-column so margin-top:auto on .sidebar-darkmode-wrap
           still works and pins the toggle to the bottom. */
        .sidebar .sidebar-wrapper > .scroll-content,
        .sidebar .scroll-content {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 auto !important;
            height: 100% !important;
            min-height: 100% !important;
            width: 100% !important;
            overflow: hidden !important;
        }

        .sidebar .scroll-element,
        .sidebar .scroll-element_outer,
        .sidebar .scroll-element_track,
        .sidebar .scroll-element_bar {
            display: none !important;
        }

        .sidebar .nav.nav-secondary {
            margin-top: 4px;
            flex: 0 0 auto;
            gap: 0;
        }

        /* Section header — sits indented from the bar's left edge so items
           below can outdent slightly and hang under it like a grouped list. */
        .sidebar .nav .nav-section {
            margin: 10px 0 4px;
            padding: 0 12px 0 28px;
            text-align: left;
            display: block;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
        }

        .sidebar .nav .nav-section:first-of-type {
            border-top: 0;
            padding-top: 0;
        }

        .sidebar .nav .nav-section .text-section {
            font-family: 'Oxanium', sans-serif !important;
            font-size: .72rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-weight: 800;
            color: #9a8a54;
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1.4;
            display: block;
        }

        /* First nav-section (right after the menu opens) — tighter top spacing */
        .sidebar .nav .nav-section:first-child {
            margin-top: 4px;
        }

        /* Nav-items: icon column outdented slightly LEFT of where the section
           text starts (28px), so the row visually 'belongs' to the section. */
        .sidebar .nav .nav-item > a {
            width: 100%;
            padding-top: 7px;
            padding-bottom: 7px;
            min-height: 38px;
            padding-left: 16px;
            padding-right: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Pin icon to a fixed slot so all rows align vertically */
        .sidebar .nav .nav-item > a > i {
            width: 20px;
            min-width: 20px;
            text-align: center;
            margin-right: 0;
        }

        .sidebar .nav .nav-item > a > p {
            margin: 0 !important;
            padding-left: 2px;
        }

        .sidebar-darkmode-wrap {
            margin-top: auto;
            border-top: 3px solid rgba(242, 190, 0, 0.45);
            box-shadow: inset 0 1px 0 rgba(0, 0, 0, 0.04);
            padding: 12px 4px 10px;
            font-family: 'Oxanium', sans-serif !important;
        }

        body.dark-mode .sidebar-darkmode-wrap {
            border-top-color: rgba(242, 190, 0, 0.55);
        }

        /* ============================================================
           Gold borders — sidebar right edge + top bar bottom edge
           Both lines run unbroken along the FULL length of their edges.
           ============================================================ */

        /* Sidebar right border — full viewport height, every state */
        .wrapper .sidebar,
        .wrapper .sidebar:hover,
        .wrapper.sidebar_minimize .sidebar,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar,
        .wrapper.sidebar_minimize .sidebar:hover,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar:hover {
            border-right: 3px solid #F2BE00 !important;
            min-height: 100vh !important;
        }

        /* Top bar bottom border only on main panel header (not sidebar area). */
        .main-panel .main-header {
            border-bottom: 3px solid #F2BE00 !important;
        }

        /* Zero out kaiadmin's `.logo-header` right border so the sidebar's
           own right border owns that edge (no double line). */
        .wrapper .sidebar .logo-header,
        .wrapper.sidebar_minimize .sidebar .logo-header,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header {
            border-right: 0 !important;
            border-top: 0 !important;
            border-left: 0 !important;
        }

        /* Bootstrap puts `border-bottom` on the inner <nav>; zero that so we
           don't end up with a 6px doubled line. */
        .main-panel .main-header .navbar-header,
        .main-panel .main-header nav.navbar-header,
        .main-panel .main-header nav.navbar.navbar-header.border-bottom {
            border-bottom: 0 !important;
        }

        /* Dark mode keeps the gold edges */
        body.dark-mode .wrapper .sidebar,
        body.dark-mode .wrapper .sidebar .logo-header,
        body.dark-mode .main-panel .main-header {
            border-color: #F2BE00 !important;
        }

        /* ============================================================
           .logo-header — remove ALL layout hover effects.
           Allowed only: icons inside change to gold + slight scale-up.
           ============================================================ */

        /* Lock geometry across every hover/minimized combination so the
           kaiadmin theme can't widen, pad, slide, or scale the container. */
        .wrapper .sidebar .logo-header,
        .wrapper .sidebar:hover .logo-header,
        .wrapper.sidebar_minimize .sidebar .logo-header,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header,
        .wrapper.sidebar_minimize .sidebar:hover .logo-header,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar:hover .logo-header {
            transform: none !important;
            transition: none !important;
            animation: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }

        .wrapper .sidebar:hover .logo-header .logo,
        .wrapper .sidebar .logo-header:hover .logo,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header .logo,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar:hover .logo-header .logo {
            transition: none !important;
            animation: none !important;
        }

        /* Remove visual effects for header control icons. */
        .sidebar .logo-header .gg-more-vertical-alt,
        .sidebar .logo-header .gg-menu-right,
        .main-header-logo .logo-header .gg-more-vertical-alt,
        .main-header-logo .logo-header .gg-menu-right,
        .sidebar .logo-header .fa-grip-lines-vertical,
        .sidebar .logo-header .fa-ellipsis-vertical,
        .main-header-logo .logo-header .fa-grip-lines-vertical,
        .main-header-logo .logo-header .fa-ellipsis-vertical {
            color: #4a4a4a !important;
            transition: none !important;
            transform: none !important;
        }

        .sidebar .logo-header .topbar-toggler:hover .gg-more-vertical-alt,
        .sidebar .logo-header .topbar-toggler:focus .gg-more-vertical-alt,
        .sidebar .logo-header .nav-toggle .btn-toggle:hover .gg-menu-right,
        .sidebar .logo-header .nav-toggle .btn-toggle:focus .gg-menu-right,
        .main-header-logo .logo-header .topbar-toggler:hover .gg-more-vertical-alt,
        .main-header-logo .logo-header .topbar-toggler:focus .gg-more-vertical-alt,
        .main-header-logo .logo-header .nav-toggle .btn-toggle:hover .gg-menu-right,
        .main-header-logo .logo-header .nav-toggle .btn-toggle:focus .gg-menu-right,
        .sidebar .logo-header .topbar-toggler:hover .fa-ellipsis-vertical,
        .sidebar .logo-header .topbar-toggler:focus .fa-ellipsis-vertical,
        .sidebar .logo-header .nav-toggle .btn-toggle:hover .fa-grip-lines-vertical,
        .sidebar .logo-header .nav-toggle .btn-toggle:focus .fa-grip-lines-vertical,
        .main-header-logo .logo-header .topbar-toggler:hover .fa-ellipsis-vertical,
        .main-header-logo .logo-header .topbar-toggler:focus .fa-ellipsis-vertical,
        .main-header-logo .logo-header .nav-toggle .btn-toggle:hover .fa-grip-lines-vertical,
        .main-header-logo .logo-header .nav-toggle .btn-toggle:focus .fa-grip-lines-vertical {
            color: #4a4a4a !important;
            transition: none !important;
            transform: none !important;
        }

        /* Requested: slight enlarge + purple hover for more icon */
        .sidebar .logo-header .topbar-toggler.more .gg-more-vertical-alt,
        .main-header-logo .logo-header .topbar-toggler.more .gg-more-vertical-alt,
        .sidebar .logo-header .topbar-toggler.more .fa-ellipsis-vertical,
        .main-header-logo .logo-header .topbar-toggler.more .fa-ellipsis-vertical {
            transition: color .16s ease, transform .16s ease !important;
        }

        .sidebar .logo-header .topbar-toggler.more:hover .gg-more-vertical-alt,
        .sidebar .logo-header .topbar-toggler.more:focus .gg-more-vertical-alt,
        .main-header-logo .logo-header .topbar-toggler.more:hover .gg-more-vertical-alt,
        .main-header-logo .logo-header .topbar-toggler.more:focus .gg-more-vertical-alt,
        .sidebar .logo-header .topbar-toggler.more:hover .fa-ellipsis-vertical,
        .sidebar .logo-header .topbar-toggler.more:focus .fa-ellipsis-vertical,
        .main-header-logo .logo-header .topbar-toggler.more:hover .fa-ellipsis-vertical,
        .main-header-logo .logo-header .topbar-toggler.more:focus .fa-ellipsis-vertical {
            color: var(--ease-sidebar-icon-purple) !important;
            transform: scale(1.08) !important;
        }

        /* Sidebar toggle icon (gg-menu-right / gg-more-vertical-alt): purple hover */
        .sidebar .logo-header .nav-toggle .toggle-sidebar:hover i.gg-menu-right,
        .sidebar .logo-header .nav-toggle .toggle-sidebar:hover i.gg-more-vertical-alt,
        .sidebar .logo-header .nav-toggle .toggle-sidebar:hover i.gg-menu-left {
            color: var(--ease-sidebar-icon-purple) !important;
            transform: scale(1.08) !important;
        }

        .sidebar .logo-header .nav-toggle .toggle-sidebar:focus i.gg-menu-right,
        .sidebar .logo-header .nav-toggle .toggle-sidebar:focus i.gg-more-vertical-alt,
        .sidebar .logo-header .nav-toggle .toggle-sidebar:focus i.gg-menu-left,
        .sidebar .logo-header .nav-toggle .toggle-sidebar:active i.gg-menu-right,
        .sidebar .logo-header .nav-toggle .toggle-sidebar:active i.gg-more-vertical-alt,
        .sidebar .logo-header .nav-toggle .toggle-sidebar:active i.gg-menu-left,
        .sidebar .logo-header .nav-toggle .toggle-sidebar.toggled i {
            color: #4a4a4a !important;
            transform: none !important;
        }

        /* Neutralize button hover scaling/color effects in header controls. */
        .sidebar .logo-header .nav-toggle .btn-toggle,
        .sidebar .logo-header .topbar-toggler {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            transition: none !important;
            border-radius: 8px;
        }

        .sidebar .logo-header .nav-toggle .toggle-sidebar,
        .sidebar .logo-header .nav-toggle .sidenav-toggler {
            width: 38px;
            height: 38px;
            min-width: 38px;
            min-height: 38px;
            padding: 0 !important;
            text-align: center;
            line-height: 38px;
        }

        .sidebar .logo-header .nav-toggle .toggle-sidebar i.gg-more-vertical-alt,
        .sidebar .logo-header .nav-toggle .toggle-sidebar i.gg-menu-right,
        .main-header-logo .logo-header .nav-toggle .toggle-sidebar i.gg-more-vertical-alt,
        .main-header-logo .logo-header .nav-toggle .toggle-sidebar i.gg-menu-right {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        /* Text popup for sidebar expand/shrink control */
        .sidebar .logo-header .nav-toggle .toggle-sidebar::after,
        .main-header-logo .logo-header .nav-toggle .toggle-sidebar::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 50%;
            top: auto;
            bottom: calc(100% + 8px);
            transform: translateX(-50%) translateY(4px);
            background: #000 !important;
            color: #f2be00 !important;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 0.76rem;
            font-weight: 600;
            line-height: 1.1;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            z-index: 10050;
            transition: opacity .14s ease, transform .14s ease, visibility .14s;
        }

        .sidebar .logo-header .nav-toggle .toggle-sidebar:hover::after,
        .sidebar .logo-header .nav-toggle .toggle-sidebar:focus::after,
        .main-header-logo .logo-header .nav-toggle .toggle-sidebar:hover::after,
        .main-header-logo .logo-header .nav-toggle .toggle-sidebar:focus::after {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .sidebar .logo-header .topbar-toggler.more,
        .main-header-logo .logo-header .topbar-toggler.more {
            width: 38px;
            height: 38px;
            min-width: 38px;
            min-height: 38px;
            padding: 0 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1 !important;
            text-align: center;
        }

        .sidebar .logo-header .nav-toggle .btn-toggle i,
        .sidebar .logo-header .topbar-toggler i {
            color: #4a4a4a !important;
            transition: none !important;
            font-size: 1.12rem;
            line-height: 1;
        }

        .sidebar .logo-header .nav-toggle .btn-toggle:hover,
        .sidebar .logo-header .nav-toggle .btn-toggle:focus,
        .sidebar .logo-header .topbar-toggler:hover,
        .sidebar .logo-header .topbar-toggler:focus {
            background: transparent !important;
            outline: none !important;
        }

        .sidebar .logo-header .nav-toggle .btn-toggle:hover i,
        .sidebar .logo-header .nav-toggle .btn-toggle:focus i,
        .sidebar .logo-header .topbar-toggler:hover i,
        .sidebar .logo-header .topbar-toggler:focus i {
            color: #4a4a4a !important;
        }

        /* Allowed effect #2 — brand image grows a touch on hover */
        .sidebar .logo-header .logo .navbar-brand {
            transition: transform .2s ease !important;
        }

        .sidebar .logo-header .logo:hover .navbar-brand,
        .sidebar .logo-header:hover .logo .navbar-brand {
            transform: scale(1.05);
        }

        body.dark-mode .sidebar .logo-header .nav-toggle .btn-toggle i,
        body.dark-mode .sidebar .logo-header .topbar-toggler i {
            color: #e4e6eb !important;
        }

        body.dark-mode .sidebar .logo-header .nav-toggle .btn-toggle:hover i,
        body.dark-mode .sidebar .logo-header .topbar-toggler:hover i {
            color: #e4e6eb !important;
        }

        /* ============================================================
           Layout fix — keep main-panel + main-header always edge-to-edge
           with the sidebar, in BOTH modes and DURING the toggle animation.

           Kaiadmin defaults:
             .sidebar         { position:fixed; left:0; width:265px }
             .main-panel      { width:calc(100% - 265px); float:right; transition:all .3s }
             .main-header     { position:fixed; width:calc(100% - 250px) } ← no `left`, off by 15px
             .sidebar_minimize .main-panel/.main-header { width:calc(100% - 75px) }

           Our overrides force the sidebar to 85px, so we explicitly pin
           `left` AND `width` on .main-header and .main-panel in both states,
           and sync the .3s transition across all three so nothing 'gaps'
           mid-toggle.
           Note: kaiadmin applies `sidebar_minimize` to `.wrapper`, NOT body.
           ============================================================ */

        /* Sync the transition timing across sidebar + panel + header */
        .wrapper .sidebar,
        .wrapper .main-panel,
        .wrapper .main-header {
            transition: width .3s ease, left .3s ease, transform .3s ease !important;
        }

        /* NORMAL (sidebar full width = 265px) */
        .wrapper:not(.sidebar_minimize) .main-header {
            left: 265px !important;
            right: 0 !important;
            width: calc(100% - 265px) !important;
        }

        .wrapper:not(.sidebar_minimize) .main-panel {
            width: calc(100% - 265px) !important;
        }

        /* MINIMIZED (sidebar = 85px) — also covers the hover-restore variant */
        .wrapper.sidebar_minimize .main-header,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .main-header {
            left: 85px !important;
            right: 0 !important;
            width: calc(100% - 85px) !important;
        }

        .wrapper.sidebar_minimize .main-panel,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .main-panel {
            width: calc(100% - 85px) !important;
        }

        /* Belt-and-braces: ensure no leftover transform / margin on the
           panel or header from kaiadmin's various transition states */
        .wrapper.sidebar_minimize .main-panel,
        .wrapper.sidebar_minimize .main-header,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .main-panel,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .main-header {
            margin-left: 0 !important;
            margin-right: 0 !important;
            transform: none !important;
        }

        /* ============================================================
           Centered brand in the top navbar — visible only when the
           sidebar is minimized; otherwise the sidebar's own brand shows.
           ============================================================ */
        .ease-minimized-brand {
            display: none;
        }
        /* `.main-header` keeps kaiadmin's `position: fixed` — that already
           creates a positioning context, so .ease-minimized-brand can use
           `position: absolute` without us touching .main-header's positioning. */

        .wrapper.sidebar_minimize .ease-minimized-brand,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .ease-minimized-brand {
            display: flex !important;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            transform: translateX(-50%);
            z-index: 5;
            text-decoration: none;
            pointer-events: auto;
            height: 100%;
        }

        .wrapper.sidebar_minimize .ease-minimized-brand img,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .ease-minimized-brand img {
            height: 50px;
            width: auto;
            display: block;
            transition: transform .2s ease;
        }

        .wrapper.sidebar_minimize .ease-minimized-brand:hover img,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .ease-minimized-brand:hover img {
            transform: scale(1.04);
        }

        /* Hide the sidebar's own brand image when minimized so it doesn't
           duplicate or fight for space with the centered navbar logo.
           Higher specificity than kaiadmin's `.sidebar_minimize_hover` rule
           which would otherwise restore opacity:1 and slide the logo back
           into view ("extending out"). */
        .wrapper.sidebar_minimize .sidebar .sidebar-logo .logo-header .logo .navbar-brand,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-logo .logo-header .logo .navbar-brand,
        .wrapper.sidebar_minimize .sidebar .logo-header .logo,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header .logo,
        .wrapper.sidebar_minimize .sidebar:hover .logo-header .logo,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar:hover .logo-header .logo {
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            transform: none !important;
            transition: none !important;
            position: absolute !important;
            display: none !important;
        }

        /* Lock the .logo-header itself to the 85px column so nothing widens
           on hover — kills kaiadmin's `width:265px;padding:25px;text-align:left`
           hover rule that produces the "extend out" effect. */
        .wrapper.sidebar_minimize .sidebar .logo-header,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header,
        .wrapper.sidebar_minimize .sidebar:hover .logo-header,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar:hover .logo-header {
            width: 85px !important;
            min-width: 85px !important;
            max-width: 85px !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            text-align: center !important;
            transform: none !important;
            transition: none !important;
            overflow: hidden !important;
        }

        /* Also kill the logo image / brand image animations directly */
        .wrapper.sidebar_minimize .sidebar .logo-header .logo .navbar-brand,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header .logo .navbar-brand,
        .wrapper.sidebar_minimize .sidebar:hover .logo-header .logo .navbar-brand,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar:hover .logo-header .logo .navbar-brand,
        .wrapper.sidebar_minimize .sidebar .logo-header .logo img,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header .logo img {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            transform: none !important;
            transition: none !important;
        }

        .sidebar-darkmode-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin: 0;
            width: 100%;
        }

        .sidebar-darkmode-toggle .mode-label {
            font-size: .84rem;
            font-weight: 600;
            color: #111111;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-darkmode-toggle .mode-label i {
            font-size: 1rem;
            color: #111111 !important;
        }

        .sidebar-darkmode-toggle .mode-label i.bi-sun-fill {
            color: #f2be00 !important;
        }

        body.dark-mode .sidebar-darkmode-toggle .mode-label {
            color: #f2be00 !important;
        }

        body.dark-mode .sidebar-darkmode-toggle .mode-label i {
            color: #f2be00 !important;
        }

        .sidebar-darkmode-toggle .mode-text {
            display: inline;
        }

        .sidebar_minimize.sidebar_minimize_hover .sidebar {
            width: 85px !important;
        }

        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-wrapper {
            width: 85px !important;
        }

        .sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header {
            width: 85px !important;
        }

        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-wrapper .nav-item a p,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-wrapper .nav-item a span,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-wrapper .nav-item a .caret,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-wrapper .nav-section .text-section {
            visibility: hidden !important;
            opacity: 0 !important;
        }

        .sidebar_minimize .sidebar,
        .sidebar_minimize .sidebar:hover,
        .sidebar_minimize.sidebar_minimize_hover .sidebar,
        .sidebar_minimize.sidebar_minimize_hover .sidebar:hover {
            width: 85px !important;
            max-width: 85px !important;
            box-shadow: none !important;
            transform: none !important;
        }

        .sidebar_minimize .sidebar .sidebar-wrapper,
        .sidebar_minimize .sidebar:hover .sidebar-wrapper,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-wrapper,
        .sidebar_minimize.sidebar_minimize_hover .sidebar:hover .sidebar-wrapper,
        .sidebar_minimize .sidebar .logo-header,
        .sidebar_minimize .sidebar:hover .logo-header,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header,
        .sidebar_minimize.sidebar_minimize_hover .sidebar:hover .logo-header {
            width: 85px !important;
            min-width: 85px !important;
            max-width: 85px !important;
            box-shadow: none !important;
            transform: none !important;
        }

        .wrapper.sidebar_minimize .sidebar .logo-header,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header,
        .wrapper.sidebar_minimize .sidebar:hover .logo-header,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar:hover .logo-header {
            padding-left: 0 !important;
            padding-right: 0 !important;
            text-align: center !important;
        }

        .wrapper.sidebar_minimize .sidebar .logo-header .nav-toggle,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header .nav-toggle,
        .wrapper.sidebar_minimize .sidebar:hover .logo-header .nav-toggle,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar:hover .logo-header .nav-toggle {
            left: 50% !important;
            right: 0 !important;
            transform: translateX(-50%) !important;
        }

        .wrapper.sidebar_minimize .sidebar .logo-header .topbar-toggler.more,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header .topbar-toggler.more,
        .wrapper.sidebar_minimize .sidebar:hover .logo-header .topbar-toggler.more,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar:hover .logo-header .topbar-toggler.more {
            position: absolute;
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
            margin-left: 0 !important;
        }

        .wrapper.sidebar_minimize .sidebar .logo-header .topbar-toggler.more:hover,
        .wrapper.sidebar_minimize .sidebar .logo-header .topbar-toggler.more:focus,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header .topbar-toggler.more:hover,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar .logo-header .topbar-toggler.more:focus,
        .wrapper.sidebar_minimize .sidebar:hover .logo-header .topbar-toggler.more:hover,
        .wrapper.sidebar_minimize .sidebar:hover .logo-header .topbar-toggler.more:focus,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar:hover .logo-header .topbar-toggler.more:hover,
        .wrapper.sidebar_minimize.sidebar_minimize_hover .sidebar:hover .logo-header .topbar-toggler.more:focus {
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
        }

        /* ============================================================
           Minimized Sidebar — No layout shift on hover, only a tooltip
           ============================================================ */

        /* Allow tooltip to render outside the sidebar bounds */
        .sidebar_minimize .sidebar,
        .sidebar_minimize .sidebar:hover,
        .sidebar_minimize.sidebar_minimize_hover .sidebar,
        .sidebar_minimize.sidebar_minimize_hover .sidebar:hover,
        .sidebar_minimize .sidebar .sidebar-wrapper,
        .sidebar_minimize .sidebar .sidebar-content,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-wrapper,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-content {
            overflow: visible !important;
        }

        /* Center icons in the 85px column; no padding-shift on hover */
        .sidebar_minimize .sidebar .nav .nav-item,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .nav .nav-item {
            position: relative !important;
        }

        .sidebar_minimize .sidebar .nav .nav-item > a,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .nav .nav-item > a {
            padding-left: 0 !important;
            padding-right: 0 !important;
            justify-content: center !important;
            gap: 0 !important;
            width: 85px !important;
            transition: none !important;
        }

        .sidebar_minimize .sidebar .nav .nav-item > a > i,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .nav .nav-item > a > i {
            width: 22px !important;
            min-width: 22px !important;
            margin: 0 auto !important;
            text-align: center !important;
        }

        /* Suppress hover/focus visual changes that move or recolor things;
           keep the only hover affordance as the tooltip itself. */
        .sidebar_minimize .sidebar .sidebar-wrapper .nav-item > a:hover,
        .sidebar_minimize .sidebar .sidebar-wrapper .nav-item > a:focus,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-wrapper .nav-item > a:hover,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-wrapper .nav-item > a:focus {
            background: transparent !important;
            color: inherit !important;
            box-shadow: none !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            transform: none !important;
        }

        .sidebar_minimize .sidebar .sidebar-wrapper .nav-item > a:hover > i,
        .sidebar_minimize .sidebar .sidebar-wrapper .nav-item > a:focus > i {
            color: inherit !important;
            transform: none !important;
        }

        .sidebar_minimize .sidebar .sidebar-wrapper .nav-item > a:hover::before,
        .sidebar_minimize .sidebar .sidebar-wrapper .nav-item > a:focus::before,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-wrapper .nav-item > a:hover::before,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .sidebar-wrapper .nav-item > a:focus::before {
            display: none !important;
            background: transparent !important;
        }

        /* Tooltip — repurpose the existing <p> label as a floating chip on hover.
           In minimized mode the <p> is hidden by default; on hover of its <li>,
           it floats out to the right of the icon as a small dark gold tooltip. */
        .sidebar_minimize .sidebar .nav .nav-item > a > p,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .nav .nav-item > a > p {
            visibility: hidden !important;
            opacity: 0 !important;
            position: absolute !important;
            left: 88px !important;
            top: 50% !important;
            transform: translateY(-50%) translateX(-4px) !important;
            background: #000 !important;
            color: #fff !important;
            padding: 7px 12px !important;
            border-radius: 4px !important;
            border-left: 0 !important;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.28) !important;
            white-space: nowrap !important;
            z-index: 9999 !important;
            font-family: 'Oxanium', sans-serif !important;
            font-size: .82rem !important;
            font-weight: 700 !important;
            line-height: 1 !important;
            pointer-events: none !important;
            transition: opacity .14s ease, transform .14s ease, visibility .14s !important;
            display: block !important;
        }

        .sidebar_minimize .sidebar .nav .nav-item:hover > a > p,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .nav .nav-item:hover > a > p {
            visibility: visible !important;
            opacity: 1 !important;
            transform: translateY(-50%) translateX(0) !important;
        }

        /* Tooltip arrow */
        .sidebar_minimize .sidebar .nav .nav-item > a > p::before,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .nav .nav-item > a > p::before {
            content: "";
            position: absolute;
            left: -7px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-top: 6px solid transparent;
            border-bottom: 6px solid transparent;
            border-right: 7px solid #000;
        }

        /* Keep all bootstrap hover tooltips black-only theme */
        .tooltip .tooltip-inner {
            background: #000 !important;
            color: #fff !important;
            border: none !important;
        }

        .bs-tooltip-auto[data-popper-placement^="top"] .tooltip-arrow::before,
        .bs-tooltip-top .tooltip-arrow::before,
        .bs-tooltip-auto[data-popper-placement^="right"] .tooltip-arrow::before,
        .bs-tooltip-end .tooltip-arrow::before,
        .bs-tooltip-auto[data-popper-placement^="bottom"] .tooltip-arrow::before,
        .bs-tooltip-bottom .tooltip-arrow::before,
        .bs-tooltip-auto[data-popper-placement^="left"] .tooltip-arrow::before,
        .bs-tooltip-start .tooltip-arrow::before {
            border-top-color: #000 !important;
            border-right-color: #000 !important;
            border-bottom-color: #000 !important;
            border-left-color: #000 !important;
        }

        /* Hide section labels entirely while minimized — there's no room for them
           and they would otherwise inherit the tooltip styling above. */
        .sidebar_minimize .sidebar .nav .nav-section,
        .sidebar_minimize.sidebar_minimize_hover .sidebar .nav .nav-section {
            display: none !important;
        }

        .sidebar_minimize .sidebar-darkmode-wrap,
        .sidebar_minimize.sidebar_minimize_hover .sidebar-darkmode-wrap {
            padding: 8px 0 8px !important;
        }

        .sidebar_minimize .sidebar-darkmode-toggle,
        .sidebar_minimize.sidebar_minimize_hover .sidebar-darkmode-toggle {
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 6px;
        }

        .sidebar_minimize .sidebar-darkmode-toggle .mode-label,
        .sidebar_minimize.sidebar_minimize_hover .sidebar-darkmode-toggle .mode-label {
            justify-content: center;
            margin: 0;
            gap: 0;
        }

        .sidebar_minimize .sidebar-darkmode-toggle .mode-text,
        .sidebar_minimize.sidebar_minimize_hover .sidebar-darkmode-toggle .mode-text {
            display: none;
        }

        .sidebar_minimize .sidebar-darkmode-toggle .form-check-input,
        .sidebar_minimize.sidebar_minimize_hover .sidebar-darkmode-toggle .form-check-input {
            margin: 0;
            transform: scale(0.85);
            transform-origin: center;
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
                            <i class="fas fa-grip-lines-vertical"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="fas fa-grip-lines-vertical"></i>
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
                    <ul class="nav nav-secondary">
                        <li class="nav-section">
                            <h4 class="text-section">Overview</h4>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/admin'); ?>">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-section">
                            <h4 class="text-section">Orders</h4>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/order'); ?>">
                                <i class="fas fa-layer-group"></i>
                                <p>Order Management</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/admin/calendar'); ?>">
                                <i class="fas fa-calendar-alt"></i>
                                <p>Booking Calendar</p>
                            </a>
                        </li>
                        <li class="nav-section">
                            <h4 class="text-section">Users</h4>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/user'); ?>">
                                <i class="fas fa-th-list"></i>
                                <p>User Management</p>
                            </a>
                        </li>
                        <?php if (session()->get('role') === '1'): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('/create_user'); ?>">
                                    <i class="fas fa-user-plus"></i>
                                    <p>Add User</p>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-section">
                            <h4 class="text-section">Reports</h4>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/report'); ?>">
                                <i class="fas fa-pen-square"></i>
                                <p>Revenue</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/transaction_history'); ?>">
                                <i class="fas fa-file-invoice"></i>
                                <p>Transaction History</p>
                            </a>
                        </li>

                        <?php if (session()->get('role') === '1'): ?>
                            <li class="nav-section">
                                <h4 class="text-section">Management</h4>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/admin/service_management'); ?>">
                                    <i class="fas fa-table"></i>
                                    <p>Service Management</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/admin/promo_code'); ?>">
                                    <i class="fas fa-tag"></i>
                                    <p>Promo Code</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('/admin/contact'); ?>">
                                    <i class="fas fa-envelope"></i>
                                    <p>Contact</p>
                                </a>
                            </li>

                            <li class="nav-item">
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
                                <i class="fas fa-grip-lines-vertical"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="fas fa-grip-lines-vertical"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="fas fa-ellipsis-vertical"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>

                <!-- Centered brand — only shown when sidebar is minimized -->
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
                                    class="nav-link dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    role="button"
                                    aria-expanded="false"
                                    aria-haspopup="true">
                                    <i class="fa fa-search"></i>
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
                                    class="nav-link dropdown-toggle"
                                    href="#"
                                    id="messageDropdown"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa fa-envelope"></i>
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
                                            }
                                        });
                                    });
                                });
                            </script>
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a
                                    class="nav-link dropdown-toggle"
                                    href="#"
                                    id="notifDropdown"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa fa-bell"></i>
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
                                    <li>
                                        <a class="see-all" href="<?= base_url('admin/contact') ?>">See all notifications<i class="fa fa-angle-right"></i>
                                        </a>
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
                                        });
                                    });
                                });
                            </script>
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a
                                    class="nav-link"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    aria-expanded="false">
                                    <i class="fas fa-layer-group"></i>
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

                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a
                                    class="dropdown-toggle profile-pic"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img
                                            src="<?= esc($user['profile_picture'] ? base_url($user['profile_picture']) : base_url('assets/images/user.png')) ?>"
                                            alt="..."
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <?php $session = session(); ?>
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold"><?= esc($session->get('username')) ?></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img
                                                        src="<?= esc($user['profile_picture'] ? base_url($user['profile_picture']) : base_url('assets/images/user.png')) ?>"
                                                        alt="image profile"
                                                        class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4><?= esc($session->get('username')) ?></h4>
                                                    <p class="text-muted"><?= esc($session->get('email')) ?></p>
                                                    <a
                                                        href="<?= base_url('/profile') ?>"
                                                        class="btn btn-xs btn-sm"
                                                        style="background: #84994F; color: white;">View Profile</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?= base_url('/profile') ?>">My Profile</a>
                                            <!-- <a class="dropdown-item" href="#">Inbox</a> -->
                                            <!-- <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Account Setting</a> -->
                                            <!-- <div class="dropdown-divider"></div> -->
                                            <a class="dropdown-item" href="<?= base_url('/logout') ?>">Logout</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>