<?php
// blogs/index.php - Dynamic Blog Listing Page

require_once '../api/config/database.php';

// Get pagination and filter parameters
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$itemsPerPage = 6;
$offset = ($page - 1) * $itemsPerPage;

// Build query with filters
$where = "WHERE bp.status = 'published' AND bp.published_at <= NOW()";
$params = [];
$types = '';

if (!empty($search)) {
    $where .= " AND (bp.title LIKE ? OR bp.excerpt LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ss';
}

if (!empty($category)) {
    $where .= " AND bc.slug = ?";
    $params[] = $category;
    $types .= 's';
}

// Get total count for pagination
$countQuery = "
    SELECT COUNT(DISTINCT bp.id) as total
    FROM blog_posts bp
    LEFT JOIN blog_post_categories bpc ON bp.id = bpc.post_id
    LEFT JOIN blog_categories bc ON bpc.category_id = bc.id
    $where
";

$countStmt = $conn->prepare($countQuery);
if (!empty($params)) {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$countResult = $countStmt->get_result()->fetch_assoc();
$totalPosts = $countResult['total'] ?? 0;
$totalPages = ceil($totalPosts / $itemsPerPage);
$countStmt->close();

// Fetch blog posts
$query = "
    SELECT DISTINCT
        bp.id,
        bp.title,
        bp.slug,
        bp.excerpt,
        bp.featured_image,
        bp.author_name,
        bp.published_at,
        bp.read_time,
        bp.views,
        GROUP_CONCAT(bc.name) as categories
    FROM blog_posts bp
    LEFT JOIN blog_post_categories bpc ON bp.id = bpc.post_id
    LEFT JOIN blog_categories bc ON bpc.category_id = bc.id
    $where
    GROUP BY bp.id
    ORDER BY bp.published_at DESC
    LIMIT ? OFFSET ?
";

$params[] = $itemsPerPage;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($query);
if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
$stmt->close();

// Fetch all categories for sidebar
$categoriesQuery = "
    SELECT id, name, slug, post_count
    FROM blog_categories
    ORDER BY post_count DESC
";
$categoriesResult = $conn->query($categoriesQuery);
$categories = [];
while ($row = $categoriesResult->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch trending posts (most viewed)
$trendingQuery = "
    SELECT id, title, slug, views
    FROM blog_posts
    WHERE status = 'published' AND published_at <= NOW()
    ORDER BY views DESC
    LIMIT 5
";
$trendingResult = $conn->query($trendingQuery);
$trendingPosts = [];
while ($row = $trendingResult->fetch_assoc()) {
    $trendingPosts[] = $row;
}

// Helper functions
function timeAgo($date) {
    $timestamp = strtotime($date);
    $diff = time() - $timestamp;
    
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    if ($diff < 2592000) return floor($diff / 604800) . 'w ago';
    
    return date('M d, Y', $timestamp);
}

$pageTitle = "Blog & Updates — Runaz";
$pageDescription = "Stay updated with the latest Runaz stories, guides, service tips, and platform news from our team and community.";

$conn->close();
?>
<!doctype html>
<html lang="en">
  <!-- HEAD -->
  <?php include './partials/head.php'; ?>

  <body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">

    <!-- Header -->
    <?php include "./partials/header.php"; ?>

    <!-- Breadcrumb Navigation -->
    <?php include "./partials/breadcrumb.php"; ?>

    <!-- Hero -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8 text-center">
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-runaz-blue/10 dark:bg-runaz-blue/20 text-runaz-blue font-semibold text-sm mb-4">
        <i data-feather="book-open" class="w-4 h-4"></i>
        Blog & Updates
      </div>

      <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
        Runaz Blog & Community Stories
      </h1>
      
      <p class="mt-3 text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
        Insights, platform updates, and community stories from Runaz Requesters and Runners. 
        Learn how to work safer, earn smarter, and stay informed.
      </p>
      
      <!-- Search Form -->
      <div class="mt-6 max-w-lg mx-auto">
        <form action="./" method="get" class="flex gap-2">
          <div class="relative flex-1">
            <i data-feather="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input 
              type="text" 
              name="q" 
              placeholder="Search articles or topics..." 
              value="<?php echo htmlspecialchars($search); ?>"
              class="w-full rounded-xl pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-runaz-blue text-sm"
            />
          </div>
          <button 
            type="submit" 
            class="px-6 py-3 rounded-xl bg-runaz-blue hover:bg-blue-600 text-white font-semibold transition-colors whitespace-nowrap">
            Search
          </button>
        </form>
      </div>
    </section>

    <!-- Main content -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 grid lg:grid-cols-[2fr_1fr] gap-8">

      <!-- Blog posts -->
      <div>
        <?php if (empty($posts)): ?>
          <!-- Empty state -->
          <div class="rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-900 mb-4">
              <i data-feather="search" class="w-8 h-8 text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No posts found</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">
              <?php 
              if (!empty($search)) {
                echo "No results for \"" . htmlspecialchars($search) . "\". Try a different search.";
              } elseif (!empty($category)) {
                echo "No posts in this category. Try browsing all posts.";
              } else {
                echo "Check back soon for new articles and updates!";
              }
              ?>
            </p>
            <?php if (!empty($search) || !empty($category)): ?>
              <a href="./" class="mt-4 inline-block px-4 py-2 rounded-lg bg-runaz-blue text-white font-semibold text-sm hover:bg-blue-600 transition-colors">
                View All Posts
              </a>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <!-- Blog posts grid -->
          <div class="space-y-6">
            <?php foreach ($posts as $post): ?>
              <article class="rounded-2xl overflow-hidden border dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-lg hover:border-runaz-blue/50 dark:hover:border-runaz-blue/50 transition-all duration-300">
                <!-- Featured Image -->
                <a href="details.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="block relative overflow-hidden">
                  <?php if (!empty($post['featured_image'])): ?>
                    <img 
                      src="<?php echo htmlspecialchars($post['featured_image']); ?>" 
                      alt="<?php echo htmlspecialchars($post['title']); ?>"
                      class="w-full h-60 object-cover hover:scale-105 transition-transform duration-300"
                      loading="lazy"
                    />
                  <?php else: ?>
                    <div class="w-full h-60 bg-gradient-to-br from-runaz-blue/20 to-runaz-yellow/20 flex items-center justify-center">
                      <i data-feather="image" class="w-12 h-12 text-gray-400"></i>
                    </div>
                  <?php endif; ?>
                  
                  <!-- Read time badge -->
                  <?php if (!empty($post['read_time'])): ?>
                    <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-xs font-semibold text-gray-700 dark:text-gray-300">
                      <i data-feather="clock" class="w-3 h-3 inline -mt-0.5"></i>
                      <?php echo intval($post['read_time']); ?> min read
                    </div>
                  <?php endif; ?>
                </a>

                <div class="p-6">
                  <!-- Category badges -->
                  <?php if (!empty($post['categories'])): ?>
                    <div class="flex flex-wrap gap-2 mb-3">
                      <?php 
                      $cats = explode(',', $post['categories']);
                      foreach (array_slice($cats, 0, 2) as $cat): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-runaz-blue/10 text-runaz-blue dark:bg-runaz-blue/20">
                          <?php echo htmlspecialchars(trim($cat)); ?>
                        </span>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>

                  <!-- Title -->
                  <h2 class="text-xl font-bold text-gray-900 dark:text-white hover:text-runaz-blue transition-colors line-clamp-2">
                    <a href="details.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">
                      <?php echo htmlspecialchars($post['title']); ?>
                    </a>
                  </h2>

                  <!-- Excerpt -->
                  <p class="text-gray-600 dark:text-gray-300 mt-3 text-sm line-clamp-3">
                    <?php echo htmlspecialchars($post['excerpt']); ?>
                  </p>

                  <!-- Meta -->
                  <div class="mt-4 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-3">
                      <?php if (!empty($post['author_name'])): ?>
                        <span class="flex items-center gap-1">
                          <i data-feather="user" class="w-3 h-3"></i>
                          <?php echo htmlspecialchars($post['author_name']); ?>
                        </span>
                      <?php endif; ?>
                      
                      <span class="flex items-center gap-1">
                        <i data-feather="calendar" class="w-3 h-3"></i>
                        <?php echo timeAgo($post['published_at']); ?>
                      </span>

                      <?php if (!empty($post['views'])): ?>
                        <span class="flex items-center gap-1">
                          <i data-feather="eye" class="w-3 h-3"></i>
                          <?php echo number_format($post['views']); ?>
                        </span>
                      <?php endif; ?>
                    </div>

                    <a 
                      href="details.php?slug=.<?php echo htmlspecialchars($post['slug']); ?>" 
                      class="text-runaz-blue hover:underline font-medium flex items-center gap-1">
                      Read
                      <i data-feather="arrow-right" class="w-3 h-3"></i>
                    </a>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          </div>

          <!-- Pagination -->
          <?php if ($totalPages > 1): ?>
            <div class="mt-8 flex justify-center items-center gap-2 text-sm">
              <?php if ($page > 1): ?>
                <a 
                  href="?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&q=' . urlencode($search) : ''; ?><?php echo !empty($category) ? '&category=' . urlencode($category) : ''; ?>"
                  class="px-4 py-2 rounded-lg border dark:border-gray-700 hover:bg-runaz-blue hover:text-white hover:border-runaz-blue transition-all">
                  ← Prev
                </a>
              <?php endif; ?>

              <!-- Page numbers -->
              <?php 
              $startPage = max(1, $page - 2);
              $endPage = min($totalPages, $page + 2);
              
              if ($startPage > 1): ?>
                <a href="?page=1<?php echo !empty($search) ? '&q=' . urlencode($search) : ''; ?><?php echo !empty($category) ? '&category=' . urlencode($category) : ''; ?>" class="px-3 py-2 rounded-lg border dark:border-gray-700 hover:bg-runaz-blue hover:text-white">1</a>
                <?php if ($startPage > 2): ?>
                  <span class="px-2">...</span>
                <?php endif; ?>
              <?php endif; ?>

              <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <a 
                  href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&q=' . urlencode($search) : ''; ?><?php echo !empty($category) ? '&category=' . urlencode($category) : ''; ?>"
                  class="px-3 py-2 rounded-lg border <?php echo $i === $page ? 'bg-runaz-blue text-white border-runaz-blue' : 'dark:border-gray-700 hover:bg-runaz-blue hover:text-white'; ?>">
                  <?php echo $i; ?>
                </a>
              <?php endfor; ?>

              <?php if ($endPage < $totalPages): ?>
                <?php if ($endPage < $totalPages - 1): ?>
                  <span class="px-2">...</span>
                <?php endif; ?>
                <a href="?page=<?php echo $totalPages; ?><?php echo !empty($search) ? '&q=' . urlencode($search) : ''; ?><?php echo !empty($category) ? '&category=' . urlencode($category) : ''; ?>" class="px-3 py-2 rounded-lg border dark:border-gray-700 hover:bg-runaz-blue hover:text-white"><?php echo $totalPages; ?></a>
              <?php endif; ?>

              <?php if ($page < $totalPages): ?>
                <a 
                  href="?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&q=' . urlencode($search) : ''; ?><?php echo !empty($category) ? '&category=' . urlencode($category) : ''; ?>"
                  class="px-4 py-2 rounded-lg border dark:border-gray-700 hover:bg-runaz-blue hover:text-white hover:border-runaz-blue transition-all">
                  Next →
                </a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <aside class="space-y-6">
        <!-- Categories -->
        <div class="rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-6 h-fit sticky top-20">
          <h3 class="font-bold mb-4 text-lg text-runaz-blue flex items-center gap-2">
            <i data-feather="tag" class="w-5 h-5"></i>
            Categories
          </h3>
          <ul class="space-y-2">
            <li>
              <a 
                href="./"
                class="block px-3 py-2 rounded-lg text-sm transition-colors <?php echo empty($category) ? 'bg-runaz-blue/10 text-runaz-blue font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900'; ?>">
                All Posts
              </a>
            </li>
            <?php foreach ($categories as $cat): ?>
              <li>
                <a 
                  href="?category=<?php echo urlencode($cat['slug']); ?>"
                  class="block px-3 py-2 rounded-lg text-sm transition-colors <?php echo $category === $cat['slug'] ? 'bg-runaz-blue/10 text-runaz-blue font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900'; ?>">
                  <?php echo htmlspecialchars($cat['name']); ?>
                  <span class="text-xs text-gray-500 ml-2">(<?php echo $cat['post_count']; ?>)</span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Trending -->
        <?php if (!empty($trendingPosts)): ?>
          <div class="rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
            <h3 class="font-bold mb-4 text-lg text-runaz-blue flex items-center gap-2">
              <i data-feather="trending-up" class="w-5 h-5"></i>
              Trending
            </h3>
            <ul class="space-y-3">
              <?php foreach (array_slice($trendingPosts, 0, 5) as $trend): ?>
                <li>
                  <a 
                    href=".<?php echo htmlspecialchars($trend['slug']); ?>"
                    class="text-sm text-gray-600 dark:text-gray-300 hover:text-runaz-blue transition-colors line-clamp-2">
                    <?php echo htmlspecialchars($trend['title']); ?>
                  </a>
                  <div class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                    <i data-feather="eye" class="w-3 h-3"></i>
                    <?php echo number_format($trend['views']); ?> views
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <!-- Newsletter CTA -->
        <div class="rounded-2xl border dark:border-gray-700 bg-gradient-to-br from-runaz-blue/10 to-runaz-yellow/10 dark:from-runaz-blue/20 dark:to-runaz-yellow/20 p-6">
          <h3 class="font-bold mb-2 text-lg text-runaz-blue flex items-center gap-2">
            <i data-feather="mail" class="w-5 h-5"></i>
            Subscribe
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
            Get new articles and tips delivered to your inbox every week.
          </p>
          <form action="../api/subscribe.php" method="post" class="space-y-2">
            <input 
              type="email" 
              name="email" 
              placeholder="Your email" 
              required
              class="w-full border dark:border-gray-700 rounded-lg px-3 py-2 text-sm dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-runaz-blue"
            />
            <button 
              type="submit"
              class="w-full bg-runaz-blue hover:bg-blue-600 text-white py-2 rounded-lg font-semibold text-sm transition-colors">
              Subscribe
            </button>
          </form>
        </div>
      </aside>
    </section>

    <!-- Footer -->
    <?php include "./partials/footer.php"; ?>
    <?php include "./partials/script.php"; ?>

    <style>
      .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }

      .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
    </style>

    <script>
      // Initialize Feather icons
      document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
          feather.replace();
        }
      });
    </script>
  </body>
</html>