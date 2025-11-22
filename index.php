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
 <?php include "categories.php"; ?>
 
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

  <!-- Blog Section -->
<?php
// Fetch latest blog posts from database
$blogQuery = "
    SELECT 
        id,
        title,
        slug,
        excerpt,
        featured_image,
        author_name,
        published_at,
        read_time
    FROM blog_posts 
    WHERE status = 'published'
    AND published_at <= NOW()
    ORDER BY published_at DESC
    LIMIT 3
";

$blogResult = $conn->query($blogQuery);
$blogPosts = [];
if ($blogResult) {
    while ($row = $blogResult->fetch_assoc()) {
        $blogPosts[] = $row;
    }
}

// Helper function to format date
function formatBlogDate($date) {
    $timestamp = strtotime($date);
    return date('M d, Y', $timestamp);
}

// Helper function to get time ago
function timeAgo($date) {
    $timestamp = strtotime($date);
    $diff = time() - $timestamp;
    
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    
    return date('M d, Y', $timestamp);
}
?>

<!-- Blog Section -->
<?php include "blogs.php"; ?>

<!-- Newsletter Section -->
<?php include "newsletter.php"; ?>
  
   <!-- Footer -->
<?php include "./partials/footer.php"; ?>

<?php include "./partials/script.php"; ?>
</body>
</html>
