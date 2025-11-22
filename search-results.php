<!doctype html>
<html lang="en">

<!-- HEAD -->
<?php include './partials/head.php'; ?>

<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">

<!-- HEADER -->
<?php include "./partials/header.php"; ?>

<!-- SEARCH BAR (Persistent at Top) -->
<section class="bg-white dark:bg-gray-800 sticky top-0 z-40 shadow-sm border-b border-gray-100 dark:border-gray-700">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
    <form action="/search" method="GET" class="flex-1 bg-gray-50 dark:bg-gray-900 rounded-xl flex items-center p-2">
      <i data-feather="search" class="text-gray-400 ml-2"></i>
      <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Search service or provider..."
        class="bg-transparent w-full px-3 py-2 outline-none text-sm">
    </form>
    <div class="flex items-center gap-2">
      <button class="px-4 py-2 rounded-xl bg-runaz-blue text-white text-sm font-medium hover:opacity-90">Search</button>
    </div>
  </div>
</section>

<!-- RESULTS GRID -->
<section class="py-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-bold">Results for “<span class="text-runaz-blue"><?= htmlspecialchars($_GET['q'] ?? 'All Services') ?></span>”</h2>
      <select class="rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm px-3 py-2">
        <option>Sort by relevance</option>
        <option>Highest rating</option>
        <option>Lowest price</option>
        <option>Nearest first</option>
      </select>
    </div>

    <!-- Provider Cards -->
    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <!-- Single Provider -->
      <a href="./provider-profile.php?id=1" class="group rounded-2xl border bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm hover:shadow-md transition overflow-hidden">
        <div class="relative">
          <img src="./assets/providers/plumber1.jpg" alt="Provider" class="h-40 w-full object-cover">
          <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full">Verified</span>
        </div>
        <div class="p-4">
          <h3 class="font-semibold group-hover:text-runaz-blue transition">John Ade - Plumber</h3>
          <p class="text-sm text-gray-500 mt-1">Ikeja, Lagos</p>
          <div class="flex items-center gap-2 mt-2">
            <i data-feather="star" class="w-4 text-yellow-400"></i>
            <span class="text-sm font-medium">4.8 (32 reviews)</span>
          </div>
          <div class="mt-4 flex items-center justify-between">
            <span class="text-runaz-blue font-semibold">₦5,000/hr</span>
            <button class="px-3 py-2 bg-runaz-blue text-white text-xs rounded-lg hover:opacity-90">Book Now</button>
          </div>
        </div>
      </a>

      <!-- Repeat similar cards dynamically from backend -->
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-12">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="rounded-3xl bg-runaz-blue text-white p-8 flex flex-col sm:flex-row justify-between items-center gap-4">
      <h3 class="text-xl font-semibold">Didn’t find what you’re looking for?</h3>
      <a href="./post.php" class="px-5 py-3 bg-runaz-yellow text-runaz-ink font-semibold rounded-xl">Post a Request</a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<?php include "./partials/footer.php"; ?>
<?php include "./partials/script.php"; ?>

<script>feather.replace();</script>
</body>
</html>
