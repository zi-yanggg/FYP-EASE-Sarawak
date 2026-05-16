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
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/navigation.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/components.css') ?>" />
</head>

<body>
    <div class="wrapper ease-dir">
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
                                if ($route === $currentPath) return true;
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

            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="white">
                        <a href="<?= base_url('/admin'); ?>" class="logo">
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
        <div class="main-panel">
