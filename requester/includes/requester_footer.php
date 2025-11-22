<script>
  const drawer = document.getElementById('drawer');
  document.getElementById('openDrawer')?.addEventListener('click', ()=> drawer.classList.remove('hidden'));
  document.getElementById('closeDrawer')?.addEventListener('click', ()=> drawer.classList.add('hidden'));
  document.getElementById('drawerBack')?.addEventListener('click', ()=> drawer.classList.add('hidden'));

  if (window.feather) feather.replace();
</script>

<style type="text/tailwindcss">
  @layer components {
    .card{ @apply rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5 shadow-soft; }
    .muted{ @apply text-sm text-gray-600 dark:text-gray-300; }
    .kpi{ @apply mt-1 text-3xl font-extrabold; }
    .btn-primary{ @apply inline-flex items-center justify-center px-4 py-2 rounded-xl bg-runaz-blue text-white font-semibold; }
    .input{ @apply rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2.5; }
    .panel{ @apply rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5; }
    .panel-head{ @apply flex items-center justify-between; }
    .list{ @apply mt-4 space-y-3; }
    .row{ @apply flex items-start justify-between gap-3 rounded-xl border dark:border-gray-700 p-3; }
    .row-title{ @apply font-semibold; }
    .row-sub{ @apply text-sm text-gray-600 dark:text-gray-300; }
    .row-meta{ @apply text-right; }
    .btn{ @apply px-3 py-2 rounded-lg border dark:border-gray-700 text-sm; }
    .btn-yellow{ @apply px-3 py-2 rounded-lg bg-runaz-yellow text-runaz-ink text-sm font-semibold; }
    .pill{ @apply px-2 py-0.5 rounded-full text-xs bg-gray-100 dark:bg-gray-800; }
    .pill-yellow{ @apply bg-runaz-yellow text-runaz-ink; }
    .dash-link{ @apply flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800; }
    .dash-link.active{ @apply bg-gray-100 dark:bg-gray-800 font-semibold; }
  }
</style>
</body>
</html>
