<section id="blogs" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
  <div class="text-center mb-12">
    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-runaz-blue/10 dark:bg-runaz-blue/20 text-runaz-blue font-semibold text-sm mb-4">
      <i data-feather="book-open" class="w-4 h-4"></i>
      From Our Blog
    </div>
    
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white">
      Latest Stories & Insights
    </h2>
    <p class="mt-3 text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
      Tips, guides, and success stories to help you get the most out of Runaz.
    </p>
  </div>

  <!-- Blog Grid -->
  <?php if (!empty($blogPosts)): ?>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($blogPosts as $post): ?>
        <article class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden hover:shadow-lg hover:border-runaz-blue/50 dark:hover:border-runaz-blue/50 transition-all duration-300">
          <!-- Featured Image -->
          <a href="./blogs/details.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="block relative overflow-hidden">
            <?php if (!empty($post['featured_image'])): ?>
              <img 
                src="<?php echo htmlspecialchars($post['featured_image']); ?>" 
                alt="<?php echo htmlspecialchars($post['title']); ?>" 
                class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
              />
            <?php else: ?>
              <div class="w-full h-52 bg-gradient-to-br from-runaz-blue/20 to-runaz-yellow/20 flex items-center justify-center">
                <i data-feather="image" class="w-12 h-12 text-gray-400"></i>
              </div>
            <?php endif; ?>
            
            <!-- Reading time badge -->
            <?php if (!empty($post['read_time'])): ?>
              <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-xs font-semibold text-gray-700 dark:text-gray-300">
                <i data-feather="clock" class="w-3 h-3 inline -mt-0.5"></i>
                <?php echo htmlspecialchars($post['read_time']); ?> min read
              </div>
            <?php endif; ?>
          </a>
          
          <div class="p-5">
            <!-- Title -->
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-runaz-blue transition-colors line-clamp-2">
              <a href="./blogs/details.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">
                <?php echo htmlspecialchars($post['title']); ?>
              </a>
            </h3>
            
            <!-- Excerpt -->
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-3">
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
              </div>
              
              <a 
                href="./blogs/details.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" 
                class="text-runaz-blue hover:underline font-medium flex items-center gap-1 group-hover:gap-2 transition-all">
                Read
                <i data-feather="arrow-right" class="w-3 h-3"></i>
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <!-- Empty state -->
    <div class="text-center py-12">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
        <i data-feather="book-open" class="w-8 h-8 text-gray-400"></i>
      </div>
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
        No blog posts yet
      </h3>
      <p class="text-gray-600 dark:text-gray-400 text-sm">
        Check back soon for updates, tips, and stories from the Runaz community.
      </p>
    </div>
  <?php endif; ?>

  <!-- View All Button -->
  <?php if (!empty($blogPosts)): ?>
    <div class="text-center mt-12">
      <a 
        href="./blogs/" 
        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-runaz-blue hover:bg-blue-600 text-white font-semibold shadow-md hover:shadow-lg transition-all">
        View All Articles
        <i data-feather="arrow-right" class="w-4 h-4"></i>
      </a>
    </div>
  <?php endif; ?>
</section>


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
// Initialize Feather icons for blog section
document.addEventListener('DOMContentLoaded', function() {
  if (typeof feather !== 'undefined') {
    feather.replace();
  }
});
</script>