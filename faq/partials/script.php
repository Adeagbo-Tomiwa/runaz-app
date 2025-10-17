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

        // FAQ accordion
    document.querySelectorAll('.faq-toggle').forEach(btn => {
      btn.addEventListener('click', () => {
        const panel = btn.parentElement.querySelector('.faq-panel');
        const isOpen = !panel.classList.contains('hidden');
        // close all others on mobile for tidiness
        document.querySelectorAll('.faq-panel').forEach(p => p.classList.add('hidden'));
        if (!isOpen) panel.classList.remove('hidden');
        if (window.feather) feather.replace();
      });
    });
  </script>
 <script src="/scripts/header.js"></script>
 <script>RunazHeader.init({ active:'', logoSrc:'/assets/runaz-logo.png', homeUrl:'/' });</script>