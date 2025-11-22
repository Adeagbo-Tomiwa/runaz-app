<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- ===== MOBILE OVERLAY (for closing drawer) ===== -->
<div id="drawerOverlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>


<!-- ======================================================
                     DESKTOP SIDEBAR
====================================================== -->
<aside class="hidden lg:flex w-72 shrink-0 flex-col border-r dark:border-gray-800 
       bg-white dark:bg-gray-900 fixed top-16 bottom-0 left-0 overflow-y-auto">

  <div class="h-16 px-6 border-b dark:border-gray-800 flex items-center font-extrabold dark:text-white">
    Runner
  </div>

  <nav class="p-3 text-sm space-y-1">

    <a href="dashboard.php"
       class="dash-link <?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
       <i data-feather="home" class="w-5 h-5"></i> Overview
    </a>

    <a href="jobs.php"
       class="dash-link <?= $currentPage == 'jobs.php' ? 'active' : '' ?>">
       <i data-feather="briefcase" class="w-5 h-5"></i> My Jobs
    </a>

    <a href="offers.php"
       class="dash-link <?= $currentPage == 'offers.php' ? 'active' : '' ?>">
       <i data-feather="tag" class="w-5 h-5"></i> Offers Sent
    </a>

    <a href="browse.php"
       class="dash-link <?= $currentPage == 'browse.php' ? 'active' : '' ?>">
       <i data-feather="compass" class="w-5 h-5"></i> Browse Requests
    </a>

    <a href="messages.php"
       class="dash-link <?= $currentPage == 'messages.php' ? 'active' : '' ?>">
       <i data-feather="message-square" class="w-5 h-5"></i> Messages
    </a>

    <a href="wallet.php"
       class="dash-link <?= $currentPage == 'wallet.php' ? 'active' : '' ?>">
       <i data-feather="credit-card" class="w-5 h-5"></i> Wallet
    </a>

    <a href="settings.php"
       class="dash-link <?= $currentPage == 'settings.php' ? 'active' : '' ?>">
       <i data-feather="settings" class="w-5 h-5"></i> Settings
    </a>

     <a href="../../api/logout.php"
      class="dash-link text-red-500">
      <i data-feather="log-out" class="w-5 h-5"></i> Log Out
    </a>

  </nav>

  <div class="mt-auto p-4 text-xs text-gray-500 dark:text-gray-400">© 2025 Runaz</div>
</aside>



<!-- ======================================================
                      MOBILE DRAWER
====================================================== -->
<aside id="drawerMenu"
       class="fixed top-0 left-0 w-72 h-full bg-white dark:bg-gray-900 shadow-xl
       -translate-x-full transition-transform duration-300 z-40 lg:hidden">

  <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center">
    <h2 class="text-lg font-semibold dark:text-white">Menu</h2>
    <button id="drawerClose" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800">
      <i data-feather="x" class="w-6 h-6"></i>
    </button>
  </div>

  <nav class="p-4 space-y-3">

    <a href="dashboard.php" class="drawer-link flex items-center gap-2 <?= $currentPage=='dashboard.php'?'active':'' ?>">
      <i data-feather="home" class="w-5 h-5"></i> Overview
    </a>

    <a href="jobs.php" class="drawer-link flex items-center gap-2 <?= $currentPage=='jobs.php'?'active':'' ?>">
      <i data-feather="briefcase" class="w-5 h-5"></i> My Jobs
    </a>

    <a href="offers.php" class="drawer-link flex items-center gap-2 <?= $currentPage=='offers.php'?'active':'' ?>">
      <i data-feather="tag" class="w-5 h-5"></i> Offers Sent
    </a>

    <a href="browse.php" class="drawer-link flex items-center gap-2 <?= $currentPage=='browse.php'?'active':'' ?>">
      <i data-feather="compass" class="w-5 h-5"></i> Browse Requests
    </a>

    <a href="messages.php" class="drawer-link flex items-center gap-2 <?= $currentPage=='messages.php'?'active':'' ?>">
      <i data-feather="message-square" class="w-5 h-5"></i> Messages
    </a>

    <a href="wallet.php" class="drawer-link flex items-center gap-2 <?= $currentPage=='wallet.php'?'active':'' ?>">
      <i data-feather="credit-card" class="w-5 h-5"></i> Wallet
    </a>

    <a href="settings.php" class="drawer-link flex items-center gap-2 <?= $currentPage=='settings.php'?'active':'' ?>">
      <i data-feather="settings" class="w-5 h-5"></i> Settings
    </a>

    <a href="../logout.php" class="text-red-500 flex items-center gap-2">
      <i data-feather="log-out" class="w-5 h-5"></i> Log Out
    </a>

  </nav>
</aside>


<!-- ======================================================
                     JS FOR DRAWER + THEME
====================================================== -->
<script>
document.addEventListener("DOMContentLoaded", () => {

  const drawerToggle = document.getElementById("drawerToggle");
  const drawerMenu   = document.getElementById("drawerMenu");
  const drawerOverlay = document.getElementById("drawerOverlay");
  const drawerClose  = document.getElementById("drawerClose");

  /* -------- OPEN DRAWER -------- */
  drawerToggle?.addEventListener("click", () => {
    drawerMenu.classList.remove("-translate-x-full");
    drawerOverlay.classList.remove("hidden");
  });

  /* -------- CLOSE DRAWER -------- */
  drawerClose?.addEventListener("click", closeDrawer);
  drawerOverlay?.addEventListener("click", closeDrawer);

  function closeDrawer() {
    drawerMenu.classList.add("-translate-x-full");
    drawerOverlay.classList.add("hidden");
  }

  /* -------- THEME TOGGLE -------- */
  const themeToggle = document.getElementById("themeToggle");
  const themeIcon = document.getElementById("themeIcon");

  let isDark = localStorage.getItem("runner-theme") === "dark";
  if (isDark) document.documentElement.classList.add("dark");

  function updateIcon() {
    themeIcon.setAttribute("data-feather", isDark ? "moon" : "sun");
    feather.replace();
  }

  updateIcon();

  themeToggle?.addEventListener("click", () => {
    document.documentElement.classList.toggle("dark");
    isDark = document.documentElement.classList.contains("dark");
    localStorage.setItem("runner-theme", isDark ? "dark" : "light");
    updateIcon();
  });

  feather.replace();
});
</script>


<!-- ======================================================
                   FIXED ALIGNMENT STYLES
====================================================== -->
<style>
.dash-link, .drawer-link {
  @apply flex items-center gap-3 px-4 py-2 rounded-md 
         text-gray-700 dark:text-gray-200 
         hover:bg-gray-100 dark:hover:bg-gray-800;
}

.dash-link.active,
.drawer-link.active {
  @apply bg-indigo-600 text-white;
}

.drawer-link i, .dash-link i {
  @apply w-5 h-5;
}
</style>
