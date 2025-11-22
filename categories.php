<?php
// Fetch categories from database
require_once './api/config/database.php';

$categoriesQuery = "
    SELECT id, category_name, category_slug, description, icon
    FROM service_categories 
    WHERE is_active = 1 
    ORDER BY display_order ASC, category_name ASC
    LIMIT 12
";

$categoriesResult = $conn->query($categoriesQuery);
$categories = [];
if ($categoriesResult) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Icon mapping for categories
$categoryIcons = [
    'repairs' => 'tool',
    'plumbing' => 'droplet',
    'electrical' => 'zap',
    'beauty-grooming' => 'scissors',
    'tutoring' => 'book-open',
    'cleaning' => 'wind',
    'carpentry' => 'box',
    'painting' => 'edit-3',
    'catering' => 'coffee',
    'photography' => 'camera',
    'ac-cooling' => 'thermometer',
    'moving-logistics' => 'truck',
    'it-tech-support' => 'monitor',
    'security' => 'shield',
    'laundry' => 'package'
];

// Get icon for category
function getCategoryIcon($slug, $iconMapping) {
    return $iconMapping[$slug] ?? 'briefcase'; // default icon
}
?>

<!-- Categories -->
<section id="categories" class="py-12 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-end justify-between mb-6">
        <div>
          <h2 class="text-2xl md:text-3xl font-bold">Popular Categories</h2>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Find the right service provider for your needs
          </p>
        </div>
        <a href="./categories/" class="text-runaz-blue hover:text-blue-700 font-semibold text-sm transition-colors hidden sm:flex items-center gap-1">
          See all
          <i data-feather="arrow-right" class="w-4 h-4"></i>
        </a>
      </div>

      <!-- Horizontal Scrolling Carousel -->
      <div class="relative">
        <!-- Scroll buttons for desktop -->
        <button 
          id="scrollLeft" 
          class="hidden lg:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 w-10 h-10 items-center justify-center rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all border dark:border-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
          aria-label="Scroll left">
          <i data-feather="chevron-left" class="w-5 h-5"></i>
        </button>
        
        <button 
          id="scrollRight" 
          class="hidden lg:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 w-10 h-10 items-center justify-center rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all border dark:border-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
          aria-label="Scroll right">
          <i data-feather="chevron-right" class="w-5 h-5"></i>
        </button>

        <!-- Scrollable container -->
        <div 
          id="categoriesContainer" 
          class="flex gap-4 overflow-x-auto scroll-smooth pb-4 scrollbar-hide snap-x snap-mandatory"
          style="-webkit-overflow-scrolling: touch;">
          
          <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
              <a 
                href="./categories/details?slug=<?php echo htmlspecialchars($category['category_slug']); ?>" 
                class="group flex-shrink-0 w-64 snap-start rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5 hover:shadow-soft hover:border-runaz-blue/50 transition-all duration-300">
                
                <!-- Icon -->
                <div class="w-12 h-12 rounded-xl bg-runaz-blue/10 dark:bg-runaz-blue/20 grid place-items-center group-hover:scale-110 transition-transform">
                  <i data-feather="<?php echo getCategoryIcon($category['category_slug'], $categoryIcons); ?>" class="text-runaz-blue w-6 h-6"></i>
                </div>
                
                <!-- Title -->
                <h3 class="mt-3 font-semibold text-gray-900 dark:text-white group-hover:text-runaz-blue transition-colors">
                  <?php echo htmlspecialchars($category['category_name']); ?>
                </h3>
                
                <!-- Description -->
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                  <?php echo htmlspecialchars($category['description'] ?? 'Professional services'); ?>
                </p>
                
                <!-- Explore link -->
                <div class="mt-4 flex items-center text-sm text-runaz-blue font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                  Explore
                  <i data-feather="arrow-right" class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform"></i>
                </div>
              </a>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- Fallback when no categories -->
            <div class="w-full text-center py-12">
              <i data-feather="inbox" class="w-12 h-12 mx-auto text-gray-400 mb-3"></i>
              <p class="text-gray-500 dark:text-gray-400">No categories available at the moment</p>
            </div>
          <?php endif; ?>
        </div>

        <!-- Mobile "See all" button -->
        <div class="mt-4 sm:hidden text-center">
          <a href="./categories/" class="inline-flex items-center gap-1 text-runaz-blue hover:text-blue-700 font-semibold text-sm">
            See all categories
            <i data-feather="arrow-right" class="w-4 h-4"></i>
          </a>
        </div>
      </div>

      <!-- Scroll indicators (dots) -->
      <div id="scrollIndicators" class="flex justify-center gap-2 mt-4 lg:hidden">
        <!-- Generated by JavaScript -->
      </div>
    </div>
</section>

<style>
/* Hide scrollbar but keep functionality */
.scrollbar-hide {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;  /* Chrome, Safari and Opera */
}

/* Smooth scroll behavior */
#categoriesContainer {
  scroll-behavior: smooth;
}

/* Line clamp for descriptions */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('categoriesContainer');
  const scrollLeftBtn = document.getElementById('scrollLeft');
  const scrollRightBtn = document.getElementById('scrollRight');
  const indicators = document.getElementById('scrollIndicators');
  
  if (!container) return;

  // Scroll amount (width of card + gap)
  const scrollAmount = 272; // 256px card + 16px gap

  // Scroll left
  scrollLeftBtn?.addEventListener('click', () => {
    container.scrollBy({
      left: -scrollAmount,
      behavior: 'smooth'
    });
  });

  // Scroll right
  scrollRightBtn?.addEventListener('click', () => {
    container.scrollBy({
      left: scrollAmount,
      behavior: 'smooth'
    });
  });

  // Update button states based on scroll position
  function updateScrollButtons() {
    if (!scrollLeftBtn || !scrollRightBtn) return;
    
    const isAtStart = container.scrollLeft <= 0;
    const isAtEnd = container.scrollLeft >= (container.scrollWidth - container.clientWidth - 10);
    
    scrollLeftBtn.disabled = isAtStart;
    scrollRightBtn.disabled = isAtEnd;
  }

  // Create scroll indicators for mobile
  function createIndicators() {
    if (!indicators || window.innerWidth >= 1024) return;
    
    const cardWidth = 272;
    const visibleWidth = container.clientWidth;
    const totalWidth = container.scrollWidth;
    const numDots = Math.ceil(totalWidth / visibleWidth);
    
    indicators.innerHTML = '';
    
    for (let i = 0; i < numDots; i++) {
      const dot = document.createElement('button');
      dot.className = 'w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600 transition-all';
      dot.setAttribute('aria-label', `Go to page ${i + 1}`);
      dot.addEventListener('click', () => {
        container.scrollTo({
          left: i * visibleWidth,
          behavior: 'smooth'
        });
      });
      indicators.appendChild(dot);
    }
    
    updateIndicators();
  }

  // Update active indicator
  function updateIndicators() {
    if (!indicators || window.innerWidth >= 1024) return;
    
    const dots = indicators.children;
    const visibleWidth = container.clientWidth;
    const currentPage = Math.round(container.scrollLeft / visibleWidth);
    
    Array.from(dots).forEach((dot, index) => {
      if (index === currentPage) {
        dot.classList.remove('bg-gray-300', 'dark:bg-gray-600');
        dot.classList.add('bg-runaz-blue', 'w-6');
      } else {
        dot.classList.add('bg-gray-300', 'dark:bg-gray-600');
        dot.classList.remove('bg-runaz-blue', 'w-6');
      }
    });
  }

  // Listen to scroll events
  container.addEventListener('scroll', () => {
    updateScrollButtons();
    updateIndicators();
  });

  // Initialize
  updateScrollButtons();
  createIndicators();

  // Re-create indicators on resize
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      createIndicators();
      updateScrollButtons();
    }, 250);
  });

  // Keyboard navigation
  container.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
      e.preventDefault();
      scrollLeftBtn?.click();
    } else if (e.key === 'ArrowRight') {
      e.preventDefault();
      scrollRightBtn?.click();
    }
  });

  // Touch swipe support
  let touchStartX = 0;
  let touchEndX = 0;

  container.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
  }, { passive: true });

  container.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
  }, { passive: true });

  function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    
    if (Math.abs(diff) > swipeThreshold) {
      if (diff > 0) {
        // Swiped left
        scrollRightBtn?.click();
      } else {
        // Swiped right
        scrollLeftBtn?.click();
      }
    }
  }

  // Initialize Feather icons
  if (typeof feather !== 'undefined') {
    feather.replace();
  }
});
</script>