<!doctype html>
<html lang="en">
  <!-- HEAD -->
  <?php 
    // Example dynamic values (you can replace these with DB fetch later)
    // $slug = $_GET['slug'] ?? '';
    // $stmt = $pdo->prepare("SELECT * FROM posts WHERE slug = ?");
    // $stmt->execute([$slug]);
    // $post = $stmt->fetch();

    $post = [
      "title" => "How Escrow Protects Your Payments",
      "author" => "Runaz Team",
      "date" => "October 10, 2025",
      "category" => "Safety",
      "image" => "../../assets/runaz-bg-image.jpg",
      "excerpt" => "Learn how Runaz escrow ensures fairness and protects both Requesters and Runners in every transaction.",
      "content" => "
        <p>At Runaz, your trust and safety are our top priorities. One of the ways we ensure secure transactions is through our <strong>Escrow Payment System</strong>.</p>

        <p>When you hire a Runner, your payment is securely held in escrow until the job is completed. This protects both sides — ensuring Requesters only release funds when satisfied, and Runners are guaranteed payment once they deliver quality service.</p>

        <h2 class='text-xl font-semibold mt-6 mb-2'>How It Works</h2>
        <ul class='list-disc list-inside space-y-1'>
          <li>The Requester funds the task into escrow before work starts.</li>
          <li>The Runner completes the job and marks it as delivered.</li>
          <li>The Requester reviews and releases payment through Runaz.</li>
          <li>If disputes arise, Runaz Support steps in to mediate fairly.</li>
        </ul>

        <p class='mt-4'>This transparent process helps build confidence and fosters long-term working relationships between users. With Runaz Escrow, you never have to worry about fraud or unpaid tasks again.</p>
      "
    ];

    $title = $post['title'] . " — Runaz Blog";
    $description = $post['excerpt'];
    include './partials/head.php'; 
  ?>

  <body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">

    <!-- Header -->
    <?php include "./partials/header.php"; ?>
    <!-- Breadcrumb Navigation -->
    <?php include "./partials/breadcrumb.php";  ?>

    <!-- Hero / Post Header -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6 text-center">
      <div class="text-runaz-blue text-sm font-semibold uppercase"><?= htmlspecialchars($post['category']) ?></div>
      <h1 class="text-3xl sm:text-5xl font-extrabold mt-2"><?= htmlspecialchars($post['title']) ?></h1>
      <div class="flex justify-center items-center gap-3 text-sm text-gray-500 mt-3">
        <div class="flex justify-center items-center">
            <span class="flex justify-center items-center"><i data-feather="user"></i> <?= htmlspecialchars($post['author']) ?></span>
        </div>
        <div class="flex justify-center items-center">
            <span class="flex justify-center items-center"><i data-feather="calendar"></i> <?= htmlspecialchars($post['date']) ?></span>
        </div>
        
      </div>
    </section>

    <!-- Featured Image -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
      <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="rounded-2xl w-full object-cover shadow-soft">
    </div>

    <!-- Main Content -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 grid lg:grid-cols-[2fr_1fr] gap-8">
      <!-- Article -->
      <article class="bg-white dark:bg-gray-800 rounded-2xl border dark:border-gray-700 p-6 leading-relaxed text-gray-700 dark:text-gray-300 prose prose-gray dark:prose-invert max-w-none">
        <?= $post['content'] ?>

        <div class="mt-8 flex flex-wrap items-center gap-3 text-sm">
          <span class="font-semibold text-runaz-blue">Tags:</span>
          <a href="#" class="px-3 py-1 border border-runaz-blue/30 rounded-full text-runaz-blue hover:bg-runaz-blue hover:text-white transition">Safety</a>
          <a href="#" class="px-3 py-1 border border-runaz-blue/30 rounded-full text-runaz-blue hover:bg-runaz-blue hover:text-white transition">Escrow</a>
          <a href="#" class="px-3 py-1 border border-runaz-blue/30 rounded-full text-runaz-blue hover:bg-runaz-blue hover:text-white transition">Payments</a>
        </div>
      </article>

      <!-- Sidebar -->
      <aside class="space-y-6">
        <!-- Related Posts -->
        <div class="rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
          <h3 class="font-bold mb-3 text-runaz-blue">Related Posts</h3>
          <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
            <li><a href="#" class="hover:text-runaz-blue">How to avoid fake service requests</a></li>
            <li><a href="#" class="hover:text-runaz-blue">Understanding Runaz verification badges</a></li>
            <li><a href="#" class="hover:text-runaz-blue">What to do when disputes occur</a></li>
          </ul>
        </div>

        <!-- Subscribe -->
        <div class="rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
          <h3 class="font-bold mb-2 text-runaz-blue">Subscribe for updates</h3>
          <p class="text-sm text-gray-600 dark:text-gray-300">Join 5,000+ Runaz members getting weekly insights and opportunities.</p>
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
