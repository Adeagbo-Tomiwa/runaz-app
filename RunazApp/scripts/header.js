window.RunazHeader = (function () {
  const menuSvg = `
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>`;
  const closeSvg = `
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>`;

  const html = (cfg) => `
  <header class="border-b dark:border-gray-800 bg-white/90 dark:bg-gray-900/80 backdrop-blur sticky top-0 z-40">
    <div class="max-w-7xl mx-auto h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
      <!-- Brand -->
      <a href="${cfg.homeUrl}" class="flex items-center gap-2 min-w-0">
        <img src="${cfg.logoSrc}" alt="${cfg.brand}" class="h-9 w-9 object-contain"/>
        <span class="font-extrabold text-lg sm:text-xl tracking-tight truncate">${cfg.brand}</span>
      </a>

      <!-- Desktop nav -->
      <nav class="hidden md:flex items-center gap-6 lg:gap-8 text-sm font-medium">
        ${cfg.links.map(l => `
          <a class="hover:text-runaz-blue whitespace-nowrap ${l.key === cfg.active ? 'text-runaz-blue font-semibold' : ''}" href="${l.href}">${l.label}</a>
        `).join('')}
      </nav>

      <!-- Actions -->
      <div class="hidden md:flex items-center gap-2 sm:gap-3">
        <button id="themeToggle" class="p-2 rounded-lg border dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800" aria-label="Toggle theme">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 4V2M12 22v-2M4.93 4.93 3.51 3.51M20.49 20.49 19.07 19.07M4 12H2M22 12h-2M4.93 19.07 3.51 20.49M20.49 3.51 19.07 4.93"/><circle cx="12" cy="12" r="4"/></svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
        ${cfg.showAuth ? `
          <a href="${cfg.loginUrl}" class="text-sm font-semibold hover:text-runaz-blue whitespace-nowrap">Log in</a>
          <a href="${cfg.registerUrl}" class="inline-flex items-center px-3 sm:px-4 py-2 rounded-xl bg-runaz-blue text-white hover:opacity-95 shadow-soft whitespace-nowrap">Get Started</a>
        ` : ''}
      </div>

      <!-- Mobile controls -->
      <div class="md:hidden flex items-center gap-2">
        <button id="themeToggleMobile" class="p-2 rounded-lg border dark:border-gray-700" aria-label="Toggle theme">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 4V2M12 22v-2M4.93 4.93 3.51 3.51M20.49 20.49 19.07 19.07M4 12H2M22 12h-2M4.93 19.07 3.51 20.49M20.49 3.51 19.07 4.93"/><circle cx="12" cy="12" r="4"/></svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
        <button id="navBtn" class="p-2 rounded-lg border dark:border-gray-700" aria-expanded="false" aria-controls="mobileMenu" aria-label="Open menu">
          ${menuSvg}
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobileMenu" class="md:hidden hidden border-t bg-white dark:bg-gray-900 dark:border-gray-700">
      <div class="px-4 py-3 space-y-2 text-sm">
        ${cfg.links.map(l => `
          <a class="block py-2 ${l.key === cfg.active ? 'text-runaz-blue font-semibold' : ''}" href="${l.href}">${l.label}</a>
        `).join('')}
        ${cfg.showAuth ? `
        <div class="pt-2 flex gap-3">
          <a href="${cfg.loginUrl}" class="flex-1 text-center py-2 rounded-lg border dark:border-gray-700">Log in</a>
          <a href="${cfg.registerUrl}" class="flex-1 text-center py-2 rounded-lg bg-runaz-blue text-white">Get Started</a>
        </div>` : ''}
      </div>
    </div>
  </header>`;

  function init(userCfg = {}) {
    const cfg = Object.assign({
      brand: 'Runaz',
      logoSrc: '/assets/runaz-logo.png',
      homeUrl: 'index.html',
      active: '',
      showAuth: true,
      loginUrl: 'login.html',
      registerUrl: 'register.html',
      links: [
        { key: 'how',        label: 'How it works', href: 'how-it-works.html' },
        { key: 'categories', label: 'Categories',   href: 'categories.html' },
        { key: 'trust',      label: 'Why Runaz',    href: 'about.html' },
        { key: 'faq',        label: 'FAQ',          href: 'faq.html' }
      ]
    }, userCfg);

    // Respect saved theme before mount
    const saved = localStorage.getItem('runaz-theme');
    if (saved === 'dark') document.documentElement.classList.add('dark');

    const mount = document.getElementById('site-header');
    if (!mount) return console.warn('RunazHeader: #site-header not found.');
    mount.innerHTML = html(cfg);

    // Wire icons (for any remaining <i data-feather> in page)
    if (window.feather) window.feather.replace();

    // Theme toggles
    const toggleTheme = () => {
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('runaz-theme', isDark ? 'dark' : 'light');
    };
    document.getElementById('themeToggle')?.addEventListener('click', toggleTheme);
    document.getElementById('themeToggleMobile')?.addEventListener('click', toggleTheme);

    // Mobile menu
    const navBtn = document.getElementById('navBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    const setMenuState = (open) => {
      mobileMenu.classList.toggle('hidden', !open);
      mobileMenu.classList.toggle('block', open); // ensure it actually displays
      navBtn.setAttribute('aria-expanded', String(open));
      navBtn.innerHTML = open ? closeSvg : menuSvg; // swap icon without depending on feather
    };

    const openMenu = (e) => {
      e?.stopPropagation(); // prevent immediate close via document click
      const isOpen = navBtn.getAttribute('aria-expanded') === 'true';
      setMenuState(!isOpen);
    };

    navBtn?.addEventListener('click', openMenu);

    // Close on route click (mobile)
    mobileMenu?.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => setMenuState(false));
    });

    // Close on escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobileMenu && !mobileMenu.classList.contains('hidden')) {
        setMenuState(false);
      }
    });

    // Click outside to close
    document.addEventListener('click', (e) => {
      if (!mobileMenu || mobileMenu.classList.contains('hidden')) return;
      const header = mobileMenu.closest('header');
      if (header && !header.contains(e.target)) setMenuState(false);
    });
  }

  return { init };
})();