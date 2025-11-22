<!doctype html>
<html lang="en">
  <!-- HEAD MOOUNT -->
<?php include "./partials/head.php"; ?>

<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">

<!-- HEADER MOUNT -->
 <?php include "./partials/header.php" ?>

         <!-- Breadcrumb Navigation -->
    <?php include "./partials/breadcrumb.php";  ?>

  <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold mb-6">Frequently Asked Questions</h1>
    
    <div class="space-y-4">
      <details class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
        <summary class="font-semibold cursor-pointer">How do I post a request?</summary>
        <p class="mt-2 text-gray-600 dark:text-gray-300 text-sm">Simply log in, click “Post a Request,” fill in the service details, and publish. Runners will send offers you can compare.</p>
      </details>

      <details class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
        <summary class="font-semibold cursor-pointer">How do payments work?</summary>
        <p class="mt-2 text-gray-600 dark:text-gray-300 text-sm">Payments go into escrow and are only released to the Runner after you confirm the job is completed satisfactorily.</p>
      </details>

      <details class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
        <summary class="font-semibold cursor-pointer">Can I be both a Runner and a Requester?</summary>
        <p class="mt-2 text-gray-600 dark:text-gray-300 text-sm">Yes. You can switch roles at any time from your dashboard.</p>
      </details>
    </div>
  </main>

    <!-- Newsletter -->
  <?php include "../newsletter.php"; ?>
  
  <!-- FOOTER MOUNT -->
 <?php include "./partials/footer.php" ?>

 <!-- SCRIPT MOUNT -->
 <?php include "./partials/script.php" ?>
</body>
</html>