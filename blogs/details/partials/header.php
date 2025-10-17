<!-- Runaz Header -->
<header class="border-b dark:border-gray-800 bg-white/90 dark:bg-gray-900/80 backdrop-blur sticky top-0 z-40">
  <div class="max-w-7xl mx-auto h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
    <!-- Brand -->
    <a href="./../../" class="flex items-center gap-2 min-w-0">
      <img src="./../../assets/runaz-logo.png" alt="Runaz Logo" class="h-9 w-9 object-contain" />
      <span class="font-extrabold text-lg sm:text-xl tracking-tight truncate">Runaz</span>
    </a>

    <!-- Desktop Navigation -->
    <nav class="hidden md:flex items-center gap-6 lg:gap-8 text-sm font-medium">
      <a href="../../how-it-works/" class="hover:text-runaz-blue whitespace-nowrap">How it works</a>
      <a href="../../categories/" class="hover:text-runaz-blue whitespace-nowrap">Categories</a>
      <a href="../../about/" class="hover:text-runaz-blue whitespace-nowrap">Why Runaz</a>
      <a href="../../faq/" class="hover:text-runaz-blue whitespace-nowrap">FAQ</a>
    </nav>

    <!-- Actions -->
    <div class="hidden md:flex items-center gap-2 sm:gap-3">
      <!-- Theme Toggle -->
      <button id="themeToggle" class="p-2 rounded-lg border dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800" aria-label="Toggle theme">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M12 4V2M12 22v-2M4.93 4.93 3.51 3.51M20.49 20.49 19.07 19.07M4 12H2M22 12h-2M4.93 19.07 3.51 20.49M20.49 3.51 19.07 4.93"/>
          <circle cx="12" cy="12" r="4"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
      </button>

      <!-- Auth Buttons -->
      <a href="../../login/" class="text-sm font-semibold hover:text-runaz-blue whitespace-nowrap">Log in</a>
      <a href="../../register/" class="inline-flex items-center px-3 sm:px-4 py-2 rounded-xl bg-runaz-blue text-white hover:opacity-95 shadow-soft whitespace-nowrap">Get Started</a>
    </div>

    <!-- Mobile Controls -->
    <div class="md:hidden flex items-center gap-2">
      <!-- Theme Toggle Mobile -->
      <button id="themeToggleMobile" class="p-2 rounded-lg border dark:border-gray-700" aria-label="Toggle theme">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M12 4V2M12 22v-2M4.93 4.93 3.51 3.51M20.49 20.49 19.07 19.07M4 12H2M22 12h-2M4.93 19.07 3.51 20.49M20.49 3.51 19.07 4.93"/>
          <circle cx="12" cy="12" r="4"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
      </button>

      <!-- Mobile Menu Toggle -->
      <button id="navBtn" class="p-2 rounded-lg border dark:border-gray-700" aria-expanded="false" aria-controls="mobileMenu" aria-label="Open menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="md:hidden hidden border-t bg-white dark:bg-gray-900 dark:border-gray-700">
    <div class="px-4 py-3 space-y-2 text-sm">
      <a href="../../how-it-works/" class="block py-2">How it works</a>
      <a href="../../categories/" class="block py-2">Categories</a>
      <a href="../../about/" class="block py-2">Why Runaz</a>
      <a href="../../faq/" class="block py-2">FAQ</a>
      <div class="pt-2 flex gap-3">
        <a href="../../login/" class="flex-1 text-center py-2 rounded-lg border dark:border-gray-700">Log in</a>
        <a href="../../register/" class="flex-1 text-center py-2 rounded-lg bg-runaz-blue text-white">Get Started</a>
      </div>
    </div>
  </div>
</header>