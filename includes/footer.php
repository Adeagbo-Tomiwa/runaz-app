  <footer class="py-10 border-t bg-white dark:bg-gray-900 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-600 dark:text-gray-400 flex flex-col md:flex-row gap-2 md:items-center md:justify-between">
      <p>© 2025 Runaz. All rights reserved.</p>
      <div class="flex gap-4">
        <a href="#" class="hover:text-runaz-blue">Terms</a>
        <a href="#" class="hover:text-runaz-blue">Privacy</a>
        <a href="#" class="hover:text-runaz-blue">Support</a>
      </div>
    </div>
  </footer>

  <script>
    feather.replace();

    // Mobile menu toggle
    document.getElementById('navBtn')?.addEventListener('click', () =>
      document.getElementById('mobileMenu').classList.toggle('hidden')
    );

    // Theme toggle
    document.getElementById('themeToggle')?.addEventListener('click', () => {
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('runaz-theme', isDark ? 'dark' : 'light');
    });
  </script>
 <script src="/scripts/header.js"></script>
 <script>RunazHeader.init({ active:'', logoSrc:'../assets/runaz-logo.png', homeUrl:'/' });</script>