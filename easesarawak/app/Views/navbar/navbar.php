<?php
// Helper to check if current URL matches a link (for active menu highlight)
function isActive($path)
{
    $current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // Remove trailing slash for comparison
    $current = rtrim($current, '/');
    $path    = rtrim($path, '/');

    // Special case for home page
    if ($path === '' || $path === '/') {
        return $current === '' || $current === '/';
    }

    return $current === $path;
}
?>
<!-- Navbar -->
<nav class="navbar">
    <div class="logo">
        <a href="<?= base_url('/'); ?>">
            <img src="assets/images/Ease_PNG_File-01.png" alt="EASE SARAWAK Logo">
        </a>
    </div>

    <!-- Desktop Menu (hidden on mobile) -->
    <div class="menu desktop-menu">
        <div class="dropdown">
            <a>Menu ▾</a>
            <div class="dropdown-content">
                <a href="<?= base_url('/#services') ?>">Our Services</a>

                <a href="<?= base_url('/#how') ?>">How it works</a>

                <a href="<?= base_url('/#why-choose-ease') ?>">Why us</a>

                <a href="<?= base_url('/about') ?>"
                    class="<?= isActive('/about') ? 'active' : '' ?>">About Us</a>

                <a href="<?= base_url('/#contact') ?>">Contact Us</a>
            </div>
        </div>

        <a href="<?= base_url('/booking') ?>"
            class="btn <?= isActive('/booking') ? 'active' : '' ?>">Book Now</a>
    </div>

    <!-- Hamburger Icon (visible only on mobile) -->
    <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
</nav>

<!-- Mobile Menu Overlay (hidden by default) -->
<div class="mobile-menu-overlay" id="mobileMenu">
<div class="mobile-menu">
    <a href="<?= base_url('/#services') ?>">Our Services</a>
    <a href="<?= base_url('/#how') ?>">How it works</a>
    <a href="<?= base_url('/#why-choose-ease') ?>">Why us</a>
    <a href="<?= base_url('/about') ?>" class="<?= isActive('/about') ? 'active' : '' ?>">About Us</a>
    <a href="<?= base_url('/#contact') ?>">Contact Us</a>
    <a href="<?= base_url('/booking') ?>" class="<?= isActive('/booking') ? 'active' : '' ?>">Book Now</a>
</div>
</div>
<script>
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    });

    // Close menu when clicking a link
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');
        });
    });

    // Optional: close when clicking outside
    mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');
        }
    });
</script>