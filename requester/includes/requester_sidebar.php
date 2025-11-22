<!-- Sidebar (desktop) -->
<aside class="hidden lg:flex w-72 shrink-0 flex-col border-r dark:border-gray-800 bg-white dark:bg-gray-900">
  <div class="h-16 px-6 border-b dark:border-gray-800 flex items-center font-extrabold">Requester</div>
  <nav class="p-3 text-sm">
    <a class="dash-link active" href="index.php"><i data-feather="home"></i> Overview</a>
    <a class="dash-link" href="post.php"><i data-feather="plus-circle"></i> Post Request</a>
    <a class="dash-link" href="my_requests.php"><i data-feather="inbox"></i> My Requests</a>
    <a class="dash-link" href="offers.php"><i data-feather="tag"></i> Offers</a>
    <a class="dash-link" href="messages.php"><i data-feather="message-square"></i> Messages</a>
    <a class="dash-link" href="wallet.php"><i data-feather="credit-card"></i> Wallet</a>
    <a class="dash-link" href="settings.php"><i data-feather="settings"></i> Settings</a>
  </nav>
  <div class="mt-auto p-4 text-xs text-gray-500 dark:text-gray-400">© <?= date('Y'); ?> Runaz</div>
</aside>

<!-- Mobile Drawer -->
<div id="drawer" class="fixed inset-0 z-50 hidden">
  <div id="drawerBack" class="absolute inset-0 bg-black/40"></div>
  <aside class="absolute left-0 top-0 bottom-0 w-72 bg-white dark:bg-gray-900 border-r dark:border-gray-800 p-3">
    <div class="h-14 flex items-center justify-between">
      <div class="font-extrabold">Requester</div>
      <button id="closeDrawer" class="p-2 rounded-lg border dark:border-gray-700"><i data-feather="x"></i></button>
    </div>
    <nav class="text-sm">
      <a class="dash-link active" href="index.php"><i data-feather="home"></i> Overview</a>
      <a class="dash-link" href="post.php"><i data-feather="plus-circle"></i> Post Request</a>
      <a class="dash-link" href="my_requests.php"><i data-feather="inbox"></i> My Requests</a>
      <a class="dash-link" href="offers.php"><i data-feather="tag"></i> Offers</a>
      <a class="dash-link" href="messages.php"><i data-feather="message-square"></i> Messages</a>
      <a class="dash-link" href="wallet.php"><i data-feather="credit-card"></i> Wallet</a>
      <a class="dash-link" href="settings.php"><i data-feather="settings"></i> Settings</a>
    </nav>
  </aside>
</div>
