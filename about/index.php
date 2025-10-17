<!doctype html>
<html lang="en">
  <!-- HEAD MOOUNT -->
<?php include "./partials/head.php"; ?>

<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">

<!-- HEADER MOUNT -->
  <?php include "./partials/header.php"; ?>

          <!-- Breadcrumb Navigation -->
    <?php include "./partials/breadcrumb.php";  ?>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold mb-6">About Runaz</h1>
    <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
      Runaz is a local service marketplace connecting customers with trusted service providers (Runners).  
      Whether you need repairs, tutoring, or beauty services, we make it easy to book vetted professionals nearby.
    </p>

    <div class="mt-10 grid md:grid-cols-3 gap-6">
      <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6 shadow-soft">
        <i data-feather="shield" class="w-6 h-6 text-runaz-blue"></i>
        <h3 class="mt-3 font-semibold">Trusted</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">Runners are verified and reviewed by the community.</p>
      </div>
      <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6 shadow-soft">
        <i data-feather="zap" class="w-6 h-6 text-runaz-yellow"></i>
        <h3 class="mt-3 font-semibold">Fast</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">Post a request and get offers within minutes.</p>
      </div>
      <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6 shadow-soft">
        <i data-feather="credit-card" class="w-6 h-6 text-runaz-blue"></i>
        <h3 class="mt-3 font-semibold">Secure</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">Escrow payments mean you only pay when satisfied.</p>
      </div>
    </div>
  </main>
  
  <!-- HEADER MOUNT -->
  <?php include "./partials/footer.php"; ?>

  <!-- HEADER MOUNT -->
  <?php include "./partials/script.php"; ?>
</body>
</html>