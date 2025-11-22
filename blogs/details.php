<?php
// blogs/post.php - Dynamic Blog Post Detail Page

require_once '../api/config/database.php';

// Get post slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('Location: ./');
    exit();
}

// Fetch post details
$stmt = $conn->prepare("
    SELECT 
        bp.id,
        bp.title,
        bp.slug,
        bp.excerpt,
        bp.content,
        bp.featured_image,
        bp.author_id,
        bp.author_name,
        bp.published_at,
        bp.read_time,
        bp.views,
        bp.likes,
        GROUP_CONCAT(bc.name) as categories,
        GROUP_CONCAT(bt.name) as tags
    FROM blog_posts bp
    LEFT JOIN blog_post_categories bpc ON bp.id = bpc.post_id
    LEFT JOIN blog_categories bc ON bpc.category_id = bc.id
    LEFT JOIN blog_post_tags bpt ON bp.id = bpt.post_id
    LEFT JOIN blog_tags bt ON bpt.tag_id = bt.id
    WHERE bp.slug = ? AND bp.status = 'published' AND bp.published_at <= NOW()
    GROUP BY bp.id
    LIMIT 1
");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('HTTP/1.0 404 Not Found');
    echo "Post not found";
    exit();
}

$post = $result->fetch_assoc();
$stmt->close();

// Increment view count
$updateStmt = $conn->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?");
$updateStmt->bind_param("i", $post['id']);
$updateStmt->execute();
$updateStmt->close();

// Fetch related posts (same category, different post)
$relatedStmt = $conn->prepare("
    SELECT DISTINCT
        bp.id,
        bp.title,
        bp.slug,
        bp.excerpt,
        bp.featured_image,
        bp.published_at,
        bp.read_time
    FROM blog_posts bp
    JOIN blog_post_categories bpc ON bp.id = bpc.post_id
    JOIN blog_categories bc ON bpc.category_id = bc.id
    WHERE bc.id IN (
        SELECT DISTINCT bpc2.category_id
        FROM blog_post_categories bpc2
        WHERE bpc2.post_id = ?
    )
    AND bp.id != ?
    AND bp.status = 'published'
    AND bp.published_at <= NOW()
    ORDER BY bp.published_at DESC
    LIMIT 4
");
$relatedStmt->bind_param("ii", $post['id'], $post['id']);
$relatedStmt->execute();
$relatedResult = $relatedStmt->get_result();

$relatedPosts = [];
while ($row = $relatedResult->fetch_assoc()) {
    $relatedPosts[] = $row;
}
$relatedStmt->close();

// Helper functions
function formatDate($date) {
    return date('F d, Y', strtotime($date));
}

function timeAgo($date) {
    $timestamp = strtotime($date);
    $diff = time() - $timestamp;
    
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    
    return date('M d, Y', $timestamp);
}

$pageTitle = htmlspecialchars($post['title']) . " — Runaz Blog";
$pageDescription = htmlspecialchars($post['excerpt']);

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

    <!-- Hero / Post Header -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-8">
      <!-- Category -->
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-runaz-blue/10 dark:bg-runaz-blue/20 text-runaz-blue font-semibold text-sm mb-4">
        <i data-feather="tag" class="w-4 h-4"></i>
        <?php 
        if (!empty($post['categories'])) {
          echo htmlspecialchars(explode(',', $post['categories'])[0]);
        } else {
          echo 'Blog';
        }
        ?>
      </div>

      <!-- Title -->
      <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight">
        <?php echo htmlspecialchars($post['title']); ?>
      </h1>

      <!-- Excerpt -->
      <p class="text-lg text-gray-600 dark:text-gray-300 mt-4 max-w-3xl">
        <?php echo htmlspecialchars($post['excerpt']); ?>
      </p>

      <!-- Meta -->
      <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mt-6">
        <!-- Author -->
        <div class="flex items-center gap-2">
          <i data-feather="user" class="w-4 h-4"></i>
          <span class="font-medium text-gray-700 dark:text-gray-300">
            <?php echo htmlspecialchars($post['author_name'] ?? 'Runaz Team'); ?>
          </span>
        </div>

        <!-- Date -->
        <div class="flex items-center gap-2">
          <i data-feather="calendar" class="w-4 h-4"></i>
          <span><?php echo formatDate($post['published_at']); ?></span>
        </div>

        <!-- Read time -->
        <?php if (!empty($post['read_time'])): ?>
          <div class="flex items-center gap-2">
            <i data-feather="clock" class="w-4 h-4"></i>
            <span><?php echo intval($post['read_time']); ?> min read</span>
          </div>
        <?php endif; ?>

        <!-- Views -->
        <div class="flex items-center gap-2">
          <i data-feather="eye" class="w-4 h-4"></i>
          <span><?php echo number_format($post['views']); ?> views</span>
        </div>
      </div>
    </section>

    <!-- Featured Image -->
    <?php if (!empty($post['featured_image'])): ?>
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <img 
          src="<?php echo htmlspecialchars($post['featured_image']); ?>" 
          alt="<?php echo htmlspecialchars($post['title']); ?>" 
          class="rounded-2xl w-full object-cover shadow-lg"
          loading="lazy"
        />
      </div>
    <?php endif; ?>

    <!-- Main Content -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 grid lg:grid-cols-[2.5fr_1fr] gap-8">
      <!-- Article -->
      <article class="prose prose-lg dark:prose-invert max-w-none">
        <!-- Content -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border dark:border-gray-700 p-8 text-gray-700 dark:text-gray-300">
          <?php echo $post['content']; ?>
        </div>

        <!-- Tags -->
        <?php if (!empty($post['tags'])): ?>
          <div class="mt-8 flex flex-wrap items-center gap-3">
            <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
              <i data-feather="tag" class="w-4 h-4"></i>
              Tags:
            </span>
            <div class="flex flex-wrap gap-2">
              <?php 
              $tags = array_filter(array_map('trim', explode(',', $post['tags'])));
              foreach ($tags as $tag): ?>
                <a 
                  href="?tag=<?php echo urlencode($tag); ?>"
                  class="px-4 py-2 rounded-full border-2 border-runaz-blue/30 text-runaz-blue dark:text-runaz-blue hover:bg-runaz-blue hover:text-white hover:border-runaz-blue transition-all">
                  <?php echo htmlspecialchars($tag); ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- Share buttons -->
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
          <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <i data-feather="share-2" class="w-5 h-5"></i>
            Share This Article
          </h3>
          <div class="flex flex-wrap gap-3">
            <a 
              href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://runaz.app/blogs/' . $post['slug']); ?>&text=<?php echo urlencode($post['title']); ?>"
              target="_blank"
              rel="noopener"
              class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white transition-colors">
              <i data-feather="twitter" class="w-4 h-4"></i>
              Twitter
            </a>
            
            <a 
              href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://runaz.app/blogs/' . $post['slug']); ?>"
              target="_blank"
              rel="noopener"
              class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition-colors">
              <i data-feather="facebook" class="w-4 h-4"></i>
              Facebook
            </a>

            <a 
              href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode('https://runaz.app/blogs/' . $post['slug']); ?>"
              target="_blank"
              rel="noopener"
              class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-700 hover:bg-blue-800 text-white transition-colors">
              <i data-feather="linkedin" class="w-4 h-4"></i>
              LinkedIn
            </a>

            <button 
              onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied!'); return false;"
              class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 transition-colors">
              <i data-feather="link" class="w-4 h-4"></i>
              Copy Link
            </button>
          </div>
        </div>

        <!-- Author bio -->
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700 bg-gradient-to-r from-runaz-blue/5 to-runaz-yellow/5 dark:from-runaz-blue/10 dark:to-runaz-yellow/10 rounded-xl p-6">
          <h4 class="font-semibold text-gray-900 dark:text-white mb-2">About the Author</h4>
          <p class="text-sm text-gray-600 dark:text-gray-300">
            <?php 
            if ($post['author_name'] === 'Runaz Team') {
              echo 'The Runaz team is dedicated to providing insights, guides, and updates to help our community of Requesters and Runners thrive on our platform.';
            } else {
              echo 'Guest contributor to the Runaz blog, sharing expertise and insights with our community.';
            }
            ?>
          </p>
        </div>
      </article>

      <!-- Sidebar -->
      <aside class="space-y-6">
        <!-- Related Posts -->
        <?php if (!empty($relatedPosts)): ?>
          <div class="rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-6 h-fit">
            <h3 class="font-bold mb-4 text-lg text-runaz-blue flex items-center gap-2">
              <i data-feather="arrow-right" class="w-5 h-5"></i>
              Related Posts
            </h3>
            <ul class="space-y-4">
              <?php foreach ($relatedPosts as $related): ?>
                <li>
                  <a 
                    href="?slug=<?php echo urlencode($related['slug']); ?>"
                    class="text-sm font-medium text-gray-900 dark:text-white hover:text-runaz-blue transition-colors line-clamp-2">
                    <?php echo htmlspecialchars($related['title']); ?>
                  </a>
                  <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                    <i data-feather="calendar" class="w-3 h-3"></i>
                    <?php echo timeAgo($related['published_at']); ?>
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
            Join 5,000+ Runaz members getting weekly insights, tips, and opportunities.
          </p>
          <form action="../api/subscribe.php" method="post" class="space-y-3">
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

        <!-- Quick links -->
        <div class="rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
          <h3 class="font-bold mb-4 text-runaz-blue flex items-center gap-2">
            <i data-feather="link" class="w-5 h-5"></i>
            Quick Links
          </h3>
          <ul class="space-y-2 text-sm">
            <li>
              <a href="./" class="text-gray-600 dark:text-gray-300 hover:text-runaz-blue transition-colors flex items-center gap-2">
                <i data-feather="arrow-left" class="w-4 h-4"></i>
                Back to Blog
              </a>
            </li>
            <li>
              <a href="../" class="text-gray-600 dark:text-gray-300 hover:text-runaz-blue transition-colors flex items-center gap-2">
                <i data-feather="home" class="w-4 h-4"></i>
                Home
              </a>
            </li>
            <li>
              <a href="../../categories/" class="text-gray-600 dark:text-gray-300 hover:text-runaz-blue transition-colors flex items-center gap-2">
                <i data-feather="grid" class="w-4 h-4"></i>
                Browse Services
              </a>
            </li>
          </ul>
        </div>
      </aside>
    </section>

    <!-- More Posts Section -->
    <?php if (!empty($relatedPosts)): ?>
      <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t dark:border-gray-800">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
          More from the Blog
        </h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <?php foreach (array_slice($relatedPosts, 0, 3) as $featured): ?>
            <a href="?slug=<?php echo urlencode($featured['slug']); ?>" class="group">
              <div class="rounded-xl overflow-hidden border dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-lg transition-all">
                <?php if (!empty($featured['featured_image'])): ?>
                  <img 
                    src="<?php echo htmlspecialchars($featured['featured_image']); ?>"
                    alt="<?php echo htmlspecialchars($featured['title']); ?>"
                    class="w-full h-40 object-cover group-hover:scale-105 transition-transform"
                    loading="lazy"
                  />
                <?php else: ?>
                  <div class="w-full h-40 bg-gradient-to-br from-runaz-blue/20 to-runaz-yellow/20 flex items-center justify-center">
                    <i data-feather="image" class="w-8 h-8 text-gray-400"></i>
                  </div>
                <?php endif; ?>
                <div class="p-4">
                  <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-runaz-blue transition-colors line-clamp-2">
                    <?php echo htmlspecialchars($featured['title']); ?>
                  </h3>
                  <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                    <i data-feather="calendar" class="w-3 h-3"></i>
                    <?php echo timeAgo($featured['published_at']); ?>
                  </p>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

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

      /* Prose styling for blog content */
      .prose {
        --tw-prose-body: 1.125rem;
        --tw-prose-headings: 1.25em;
        --tw-prose-lead: 1.25em;
        --tw-prose-links: #3b82f6;
        --tw-prose-bold: #111827;
        --tw-prose-counters: #6b7280;
        --tw-prose-bullets: #d1d5db;
        --tw-prose-hr: #e5e7eb;
        --tw-prose-quotes: #111827;
        --tw-prose-quote-borders: #3b82f6;
        --tw-prose-captions: #6b7280;
        --tw-prose-code: #111827;
        --tw-prose-pre-bg: #1f2937;
        --tw-prose-pre-code: #f3f4f6;
        --tw-prose-pre-border: ;
        --tw-prose-th-borders: #d1d5db;
        --tw-prose-td-borders: #e5e7eb;
      }

      .prose h2 {
        @apply text-2xl font-bold mt-8 mb-4;
      }

      .prose h3 {
        @apply text-xl font-semibold mt-6 mb-3;
      }

      .prose p {
        @apply mb-4 leading-relaxed;
      }

      .prose ul {
        @apply list-disc list-inside space-y-2 mb-4;
      }

      .prose ol {
        @apply list-decimal list-inside space-y-2 mb-4;
      }

      .prose a {
        @apply text-runaz-blue hover:underline;
      }

      .prose strong {
        @apply font-bold text-gray-900 dark:text-white;
      }

      .dark .prose {
        --tw-prose-body: #d1d5db;
        --tw-prose-headings: #f3f4f6;
        --tw-prose-bold: #f3f4f6;
        --tw-prose-quotes: #f3f4f6;
      }
    </style>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
          feather.replace();
        }
      });
    </script>
  </body>
</html>