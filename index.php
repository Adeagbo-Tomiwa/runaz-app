<!doctype html>
<html lang="en">

<!-- HEAD MOUNT -->
<?php include './partials/head.php'; ?>

<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">

<!-- HEADER?NAV MOUNT -->
  <?php include "./partials/header.php"; ?>

  <!-- Hero -->
  <section class="relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid lg:grid-cols-2 gap-10">
      <div>
        <span class="inline-flex items-center gap-2 rounded-full bg-runaz-yellow/20 text-runaz-ink px-3 py-1 text-xs font-semibold">
          <span class="w-1.5 h-1.5 rounded-full bg-runaz-yellow"></span> Connecting you with trusted services
        </span>
        <h1 class="mt-4 text-4xl sm:text-5xl font-extrabold leading-tight">
          Hire trusted <span class="text-runaz-blue">service providers</span> near you.
        </h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
          From plumbers to tutors to makeup artists—book vetted pros in minutes and pay with confidence.
        </p>

        <!-- Search -->
        <form action="/search" method="GET" class="mt-6 bg-white dark:bg-gray-800 rounded-2xl p-2 shadow-soft grid sm:grid-cols-[1fr_auto] gap-2">
          <div class="flex items-center gap-3 px-3">
            <i data-feather="search" class="text-gray-400"></i>
            <input name="q" class="w-full outline-none py-3 bg-transparent" placeholder="What service do you need? e.g. Barber in Ikorodu">
          </div>
          <button class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-runaz-blue text-white font-semibold hover:opacity-95">
            Find Providers
          </button>
        </form>

        <!-- Trust badges -->
        <div class="mt-6 flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
          <div class="inline-flex items-center gap-2"><i data-feather="check-circle" class="text-green-600"></i> Verified IDs</div>
          <div class="inline-flex items-center gap-2"><i data-feather="shield" class="text-runaz-blue"></i> Secure escrow</div>
          <div class="inline-flex items-center gap-2"><i data-feather="star" class="text-yellow-500"></i> Ratings & reviews</div>
        </div>
      </div>

      <div class="relative">
        <div class="rounded-3xl border bg-white dark:bg-gray-800 p-4 shadow-soft">
          <img class="rounded-2xl" src="./assets/runaz-bg-image.jpg" alt="Runaz preview">
        </div>
      </div>
    </div>
  </section>

  <!-- Categories -->
  <section id="categories" class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-end justify-between">
        <h2 class="text-2xl font-bold">Popular Categories</h2>
        <a href="./categories" class="text-runaz-blue font-semibold text-sm">See all →</a>
      </div>

      <div class="mt-6 grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        <a href="./categories/house-repairs" class="group rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5 hover:shadow-soft transition">
          <div class="w-10 h-10 rounded-xl bg-runaz-blue/10 grid place-items-center"><i data-feather="tool" class="text-runaz-blue"></i></div>
          <h3 class="mt-3 font-semibold">House Repairs</h3>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Plumbers, electricians, technicians</p>
          <div class="mt-4 text-sm text-runaz-blue font-semibold opacity-0 group-hover:opacity-100">Explore →</div>
        </a>

        <a href="./categories/beauty" class="group rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5 hover:shadow-soft transition">
          <div class="w-10 h-10 rounded-xl bg-runaz-blue/10 grid place-items-center"><i data-feather="scissors" class="text-runaz-blue"></i></div>
          <h3 class="mt-3 font-semibold">Beauty & Grooming</h3>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Makeup, hair, nails</p>
          <div class="mt-4 text-sm text-runaz-blue font-semibold opacity-0 group-hover:opacity-100">Explore →</div>
        </a>

        <a href="./categories/learning" class="group rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5 hover:shadow-soft transition">
          <div class="w-10 h-10 rounded-xl bg-runaz-blue/10 grid place-items-center"><i data-feather="book-open" class="text-runaz-blue"></i></div>
          <h3 class="mt-3 font-semibold">Learning</h3>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Home lessons, music, tech</p>
          <div class="mt-4 text-sm text-runaz-blue font-semibold opacity-0 group-hover:opacity-100">Explore →</div>
        </a>

        <a href="./categories/events" class="group rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5 hover:shadow-soft transition">
          <div class="w-10 h-10 rounded-xl bg-runaz-blue/10 grid place-items-center"><i data-feather="camera" class="text-runaz-blue"></i></div>
          <h3 class="mt-3 font-semibold">Events</h3>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Photography, DJ, decorations</p>
          <div class="mt-4 text-sm text-runaz-blue font-semibold opacity-0 group-hover:opacity-100">Explore →</div>
        </a>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="rounded-3xl bg-runaz-blue text-white p-10 flex flex-col lg:flex-row gap-6 items-center justify-between">
        <div>
          <h3 class="text-2xl font-bold">Ready to get help today?</h3>
          <p class="text-white/80 mt-1">Post a request and let verified pros reach out.</p>
        </div>
        <div class="flex gap-3">
          <a href="./register/" class="px-5 py-3 rounded-xl bg-white text-runaz-blue font-semibold">Create Account</a>
          <a href="./post/" class="px-5 py-3 rounded-xl bg-runaz-yellow text-runaz-ink font-semibold">Post a Request</a>
        </div>
      </div>
    </div>
  </section>

   <!-- Footer -->
<?php include "./partials/footer.php"; ?>

<?php include "./partials/script.php"; ?>
</body>
</html>
