<!doctype html>
<html lang="en">
  <!-- HEAD -->
  <?php 
    $title = "Blog & Updates — Runaz";
    $description = "Stay updated with the latest Runaz stories, guides, service tips, and platform news from our team and community.";
    include './partials/head.php'; 
  ?>

  <body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">

    <!-- Header -->
    <?php include "./partials/header.php"; ?>

        <!-- Breadcrumb Navigation -->
    <?php include "./partials/breadcrumb.php";  ?>

    <!-- Hero -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8 text-center">
      <h1 class="text-3xl sm:text-5xl font-extrabold">Runaz Blog & Updates</h1>
      <p class="mt-3 text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
        Insights, platform updates, and community stories from Runaz Requesters and Runners.  
        Learn how to work safer, earn smarter, and stay informed.
      </p>
      <div class="mt-6">
        <form action="#" method="get" class="max-w-lg mx-auto flex">
          <input type="text" name="q" placeholder="Search articles or topics..." 
            class="w-full rounded-l-xl border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm focus:outline-none dark:bg-gray-800">
          <button type="submit" class="bg-runaz-blue text-white px-5 rounded-r-xl font-semibold">
            <i data-feather="search" class="w-4 h-4 inline"></i>
          </button>
        </form>
      </div>
    </section>

    <!-- Main content -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 grid lg:grid-cols-[2fr_1fr] gap-8">

      <!-- Blog posts -->
      <div class="space-y-6">
        <?php 
        // Example placeholder data - replace with DB fetch later
        // $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
        // $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $posts = [
          ["title" => "How Escrow Protects Your Payments", "author" => "Runaz Team", "date" => "Oct 10, 2025", "category" => "Safety", "excerpt" => "Learn how Runaz escrow ensures fairness and protects both Requesters and Runners in every transaction.", "image" => "../assets/runaz-bg-image.jpg"],
          ["title" => "Top 5 Ways Runners Can Earn More", "author" => "Admin", "date" => "Oct 6, 2025", "category" => "Tips", "excerpt" => "Boost your income by improving your Runaz profile, communication, and response rate.", "image" => "../assets/runaz-bg-image.jpg"],
          ["title" => "How to Write the Perfect Request", "author" => "Guest Author", "date" => "Sept 28, 2025", "category" => "Guides", "excerpt" => "Learn how to describe your project clearly, set expectations, and attract verified Runners faster.", "image" => "../assets/runaz-bg-image.jpg"],
        ];

        foreach ($posts as $post): ?>
        <article class="rounded-2xl overflow-hidden border dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-md transition">
          <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-60 object-cover">
          <div class="p-5">
            <div class="text-sm text-runaz-blue font-semibold uppercase"><?= $post['category'] ?></div>
            <h2 class="text-xl font-bold mt-1"><?= htmlspecialchars($post['title']) ?></h2>
            <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm"><?= htmlspecialchars($post['excerpt']) ?></p>
            <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
              <span><i data-feather="user"></i> <?= htmlspecialchars($post['author']) ?></span>
              <span><i data-feather="calendar"></i> <?= htmlspecialchars($post['date']) ?></span>
            </div>
            <a href="post.php?title=<?= urlencode($post['title']) ?>" class="mt-4 inline-block text-runaz-blue font-semibold text-sm">Read More →</a>
          </div>
        </article>
        <?php endforeach; ?>

        <!-- Pagination -->
        <div class="flex justify-center items-center mt-8 gap-2 text-sm">
          <a href="#" class="px-3 py-2 border rounded-lg dark:border-gray-700 hover:bg-runaz-blue hover:text-white">Prev</a>
          <a href="#" class="px-3 py-2 border rounded-lg bg-runaz-blue text-white">1</a>
          <a href="#" class="px-3 py-2 border rounded-lg dark:border-gray-700 hover:bg-runaz-blue hover:text-white">2</a>
          <a href="#" class="px-3 py-2 border rounded-lg dark:border-gray-700 hover:bg-runaz-blue hover:text-white">Next</a>
        </div>
      </div>

      <!-- Sidebar -->
      <aside class="space-y-6">
        <!-- Categories -->
        <div class="rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
          <h3 class="font-bold mb-3 text-runaz-blue">Categories</h3>
          <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
            <li><a href="#" class="hover:text-runaz-blue">Announcements</a></li>
            <li><a href="#" class="hover:text-runaz-blue">Safety & Escrow</a></li>
            <li><a href="#" class="hover:text-runaz-blue">Runner Tips</a></li>
            <li><a href="#" class="hover:text-runaz-blue">Platform Updates</a></li>
            <li><a href="#" class="hover:text-runaz-blue">Community Stories</a></li>
          </ul>
        </div>

        <!-- Trending -->
        <div class="rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
          <h3 class="font-bold mb-3 text-runaz-blue">Trending Posts</h3>
          <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
            <li><a href="#" class="hover:text-runaz-blue">Best practices for handling disputes</a></li>
            <li><a href="#" class="hover:text-runaz-blue">5 verified badges to look for</a></li>
            <li><a href="#" class="hover:text-runaz-blue">New escrow payout timeline update</a></li>
          </ul>
        </div>

        <!-- Newsletter -->
        <div class="rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
          <h3 class="font-bold mb-2 text-runaz-blue">Subscribe to updates</h3>
          <p class="text-sm text-gray-600 dark:text-gray-300">Get Runaz news, tips, and features delivered to your inbox.</p>
          <form action="#" method="post" class="mt-3 space-y-2">
            <input type="email" name="email" placeholder="Your email" 
              class="w-full border rounded-xl px-3 py-2 text-sm dark:bg-gray-800 dark:border-gray-700">
            <button class="w-full bg-runaz-blue text-white py-2 rounded-xl font-semibold text-sm hover:bg-runaz-blue/90">Subscribe</button>
          </form>
        </div>
      </aside>
    </section>

    <!-- Footer -->
    <?php include "./partials/footer.php"; ?>
    <?php include "./partials/script.php"; ?>

  </body>
</html>
