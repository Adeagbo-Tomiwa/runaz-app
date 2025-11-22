<?php
// OPTIONAL: Secure runner pages
// require_once __DIR__ . '/auth_runner.php';

// Detect current page name (e.g., dashboard.php)
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- ======= HEADER (TOP NAV) ======= -->
<header class="w-full bg-white dark:bg-gray-900 shadow-sm fixed top-0 left-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">

      <!-- Left: Drawer Toggle + Logo -->
      <div class="flex items-center gap-3">
        <!-- Mobile Drawer Toggle -->
        <button id="drawerToggle" class="p-2 rounded-md lg:hidden hover:bg-gray-100 dark:hover:bg-gray-800">
          <i data-feather="menu" class="w-6 h-6"></i>
        </button>

        <!-- Logo -->
        <a href="dashboard.php" class="flex items-center gap-2">
          <img src="./assets/runaz-logo.png" class="w-8" alt="Runner">
          <span class="text-xl font-bold dark:text-white">Runaz</span>
        </a>
      </div>

      <!-- Right Icons -->
      <div class="flex items-center gap-4">

        <!-- Theme Toggle -->
        <button id="themeToggle" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800">
          <i data-feather="sun" id="themeIcon" class="w-6 h-6"></i>
        </button>

        <!-- Notifications -->
        <button id="notifBtn" class="relative p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800">
          <i data-feather="bell" class="w-6 h-6"></i>
          <span class="absolute top-1 right-1 w-2 h-2 bg-red-600 rounded-full"></span>
        </button>

        <!-- Profile -->
        <a href="profile.php" class="flex items-center gap-2">
          <img src="./assets/runaz-logo.png" class="w-8 h-8 rounded-full border" alt="">
        </a>
      </div>
    </div>
  </div>
</header>

