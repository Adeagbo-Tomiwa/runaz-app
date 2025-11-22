/* /scripts/runner-header.js */
window.RunazRunnerHeader = (function () {
    function view(cfg) {
      return `
  <header class="border-b dark:border-gray-800 bg-white/90 dark:bg-gray-900/80 backdrop-blur sticky top-0 z-40">
    <div class="max-w-7xl mx-auto h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
      <!-- Left: brand + mobile menu -->
      <div class="flex items-center gap-2">
        <button id="openDrawer" class="lg:hidden p-2 rounded-lg border dark:border-gray-700" aria-label="Open sidebar">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
        </button>
        <a href="/runners/index.html" class="flex items-center gap-2 min-w-0">
          <img src="${cfg.logoSrc}" alt="${cfg.brand}" class="h-9 w-9 object-contain"/>
          <span class="font-extrabold text-lg sm:text-xl tracking-tight truncate">${cfg.brand}</span>
          <span class="hidden sm:inline text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 ml-2">Runner</span>
        </a>
      </div>
  
      <!-- Middle: location (hide on xs) -->
      <div class="hidden sm:flex items-center text-sm text-gray-600 dark:text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        ${cfg.locationText}
      </div>
  
      <!-- Right: wallet, theme, bell, avatar -->
      <div class="flex items-center gap-2 sm:gap-3">
        <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-lg border dark:border-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 15V8a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2h14a2 2 0 002-2z"/><path d="M16 13a2 2 0 100-4 2 2 0 000 4z"/></svg>
          <span class="text-sm font-semibold">${cfg.wallet}</span>
        </div>
  
        <button id="themeToggle" class="p-2 rounded-lg border dark:border-gray-700" aria-label="Toggle theme">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 4V2M12 22v-2M4.93 4.93 3.51 3.51M20.49 20.49 19.07 19.07M4 12H2M22 12h-2M4.93 19.07 3.51 20.49M20.49 3.51 19.07 4.93"/><circle cx="12" cy="12" r="4"/></svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
  
        <button class="p-2 rounded-lg border dark:border-gray-700" aria-label="Notifications">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        </button>
  
        <img class="w-9 h-9 rounded-full" src="${cfg.avatar}" alt="Profile">
      </div>
    </div>
  </header>`;
    }
  
    function init(userCfg = {}) {
      const cfg = Object.assign({
        mountId: 'app-header',
        brand: 'Runaz',
        logoSrc: '/assets/runaz-logo.png',
        locationText: 'Your location',
        wallet: '₦ 0',
        avatar: 'https://i.pravatar.cc/100?img=1'
      }, userCfg);
  
      // ensure theme on first paint
      const saved = localStorage.getItem('runaz-theme');
      if (saved === 'dark') document.documentElement.classList.add('dark');
  
      const el = document.getElementById(cfg.mountId);
      if (!el) return console.warn('RunazRunnerHeader: mount not found');
      el.innerHTML = view(cfg);
  
      // theme toggle
      const toggle = () => {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('runaz-theme', isDark ? 'dark' : 'light');
      };
      document.getElementById('themeToggle')?.addEventListener('click', toggle);
    }
  
    return { init };
  })();