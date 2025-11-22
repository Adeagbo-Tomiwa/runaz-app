<?php
// ./search/search-query.php
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<!-- Header Include -->
<?php include('./partials/head.php'); ?>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

<!-- Header Include -->
<?php include('./partials/header.php'); ?>

<!-- Breadcrumb -->
<nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 text-sm">
  <ol class="flex items-center space-x-2 text-gray-500 dark:text-gray-400">
    <li><a href="../" class="hover:text-runaz-blue">Home</a></li>
    <li>/</li>
    <li class="text-gray-700 dark:text-gray-300 font-medium">Search</li>
  </ol>
</nav>

<!-- Search Results Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <h1 class="text-2xl sm:text-3xl font-bold mb-4">Search Results</h1>

  <?php if ($query): ?>
    <p class="text-gray-600 dark:text-gray-400 mb-8">
      Showing results for <span class="font-semibold text-runaz-blue">"<?= htmlspecialchars($query) ?>"</span>
    </p>

    <!-- Mock Results (Replace later with DB results) -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php
      // Example mock results (in real app, fetch from database)
      $results = [
        ['name'=>'John Doe — Electrician','location'=>'Ikeja, Lagos','rating'=>4.8,'image'=>'../assets/pro1.jpg'],
        ['name'=>'Mary Gold — Makeup Artist','location'=>'Lekki, Lagos','rating'=>4.9,'image'=>'../assets/pro2.jpg'],
        ['name'=>'Tunde Smith — Plumber','location'=>'Ikorodu, Lagos','rating'=>4.7,'image'=>'../assets/pro3.jpg'],
      ];
      foreach ($results as $r): ?>
      <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition">
        <img src="<?= $r['image'] ?>" alt="<?= $r['name'] ?>" class="w-full h-48 object-cover rounded-t-2xl">
        <div class="p-5">
          <h3 class="text-lg font-semibold mb-1"><?= htmlspecialchars($r['name']) ?></h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
            <i class="fa-solid fa-location-dot text-runaz-blue"></i> <?= htmlspecialchars($r['location']) ?>
          </p>
          <div class="flex items-center gap-1 text-yellow-500 text-sm mb-3">
            <?php
            $fullStars = floor($r['rating']);
            $halfStar = ($r['rating'] - $fullStars) >= 0.5;
            for ($i=0; $i<$fullStars; $i++) echo '<i class="fa-solid fa-star"></i>';
            if ($halfStar) echo '<i class="fa-solid fa-star-half-stroke"></i>';
            ?>
            <span class="ml-1 text-gray-600 dark:text-gray-400"><?= $r['rating'] ?></span>
          </div>
          <a href="#" class="inline-flex items-center justify-center w-full py-2 rounded-xl bg-runaz-blue text-white font-semibold hover:opacity-95">
            View Profile
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  <?php else: ?>
    <div class="text-center py-16">
      <i class="fa-solid fa-search text-4xl text-gray-400 mb-4"></i>
      <p class="text-gray-600 dark:text-gray-400">Please enter a search query.</p>
    </div>
  <?php endif; ?>
</section>

<!-- Newsletter Section -->
<?php include "../newsletter.php"; ?>

<!-- Footer Include -->
<?php include('./partials/footer.php'); ?>

   <!-- Header -->
 <?php include "./partials/script.php"; ?>

<script src="https://unpkg.com/feather-icons"></script>
<script>feather.replace();</script>
</body>
</html>
