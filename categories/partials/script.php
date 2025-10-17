  <script>
    feather.replace();

    // Mobile menu toggle
    document.getElementById('navBtn')?.addEventListener('click', () =>
      document.getElementById('mobileMenu').classList.toggle('hidden')
    );

    // Theme toggle Desktop
    document.getElementById('themeToggle')?.addEventListener('click', () => {
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('runaz-theme', isDark ? 'dark' : 'light');
    });

    // Theme toggle Mobile
    document.getElementById('themeToggleMobile')?.addEventListener('click', () => {
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('runaz-theme', isDark ? 'dark' : 'light');
    });
  </script>
 <script>RunazHeader.init({ active:'', logoSrc:'/assets/runaz-logo.png', homeUrl:'/' });</script>