<?php
// Fetch categories from database
require_once '../api/config/database.php';

$categoriesQuery = "
    SELECT 
        sc.id,
        sc.category_name,
        sc.category_slug,
        sc.description,
        sc.icon,
        sc.display_order,
        sc.created_at,
        COUNT(DISTINCT usc.user_id) as provider_count
    FROM service_categories sc
    LEFT JOIN user_service_categories usc ON sc.id = usc.category_id
    WHERE sc.is_active = 1
    GROUP BY sc.id
    ORDER BY sc.display_order ASC, sc.category_name ASC
";

$categoriesResult = $conn->query($categoriesQuery);
$categories = [];
if ($categoriesResult) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Icon mapping
$categoryIcons = [
    'repairs' => 'tool',
    'plumbing' => 'droplet',
    'electrical' => 'zap',
    'beauty-grooming' => 'scissors',
    'tutoring' => 'book-open',
    'cleaning' => 'sparkles',
    'carpentry' => 'box',
    'painting' => 'edit-3',
    'catering' => 'coffee',
    'photography' => 'camera',
    'ac-cooling' => 'wind',
    'moving-logistics' => 'truck',
    'it-tech-support' => 'cpu',
    'security' => 'shield',
    'laundry' => 'package'
];

$conn->close();
?>
<!doctype html>
<html lang="en">
  <!-- HEADER MOUNT -->
<?php include './partials/head.php'; ?>

<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">
  <!-- Header mount -->
  <?php include "./partials/header.php"; ?>

  <!-- Breadcrumb Navigation -->
  <?php include "./partials/breadcrumb.php";  ?>

  <!-- Hero / Controls -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6">
    <h1 class="text-3xl font-extrabold">Browse Categories</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-300">
      Discover <?php echo count($categories); ?> service categories on Runaz. Search, filter, and find the right providers for your needs.
    </p>

    <div class="mt-6 flex flex-col md:flex-row gap-3">
      <!-- Search -->
      <div class="relative flex-1">
        <i data-feather="search" class="w-4 h-4 text-gray-400 absolute left-3 top-3"></i>
        <input 
          id="catSearch" 
          class="w-full pl-9 pr-3 py-2.5 rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-900 dark:placeholder-gray-400"
          placeholder="Search categories e.g. plumbing, makeup, AC repair…"
          type="search"/>
      </div>

      <!-- Sort -->
      <div class="w-full md:w-48">
        <select id="catSort" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-900">
          <option value="popular">Sort: Most Popular</option>
          <option value="alpha">Sort: A → Z</option>
          <option value="providers">Sort: Most Providers</option>
          <option value="newest">Sort: Newest First</option>
        </select>
      </div>
    </div>

    <!-- Filter chips (mobile horizontal scroll) -->
    <div class="mt-4 flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
      <button data-filter="all" class="filter-chip active whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-all">
        All Categories
      </button>
      <button data-filter="home" class="filter-chip whitespace-nowrap px-4 py-2 rounded-full text-sm transition-all">
        Home Services
      </button>
      <button data-filter="beauty" class="filter-chip whitespace-nowrap px-4 py-2 rounded-full text-sm transition-all">
        Beauty & Personal
      </button>
      <button data-filter="events" class="filter-chip whitespace-nowrap px-4 py-2 rounded-full text-sm transition-all">
        Events & Entertainment
      </button>
      <button data-filter="learning" class="filter-chip whitespace-nowrap px-4 py-2 rounded-full text-sm transition-all">
        Learning & Skills
      </button>
    </div>
  </section>

  <!-- Grid -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <!-- Results count -->
    <div class="mb-4 flex items-center justify-between">
      <p id="resultCount" class="text-sm text-gray-600 dark:text-gray-400">
        Showing <span class="font-semibold" id="countNum"><?php echo count($categories); ?></span> categories
      </p>
      <button id="clearFilters" class="hidden text-sm text-runaz-blue hover:underline font-medium">
        Clear filters
      </button>
    </div>

    <div id="catGrid" class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
      <!-- Categories will be inserted here by JavaScript -->
    </div>

    <!-- Loading state -->
    <div id="loadingState" class="hidden mt-10 text-center">
      <div class="inline-block w-8 h-8 border-4 border-runaz-blue border-t-transparent rounded-full animate-spin"></div>
      <p class="mt-3 text-gray-500 dark:text-gray-400">Loading categories...</p>
    </div>

    <!-- Empty state -->
    <div id="emptyState" class="hidden mt-10 rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 px-6 py-10 text-center">
      <div class="mx-auto w-12 h-12 rounded-full bg-runaz-blue/10 grid place-items-center">
        <i data-feather="search" class="text-runaz-blue"></i>
      </div>
      <h3 class="mt-4 text-lg font-semibold">No categories found</h3>
      <p class="mt-1 text-gray-600 dark:text-gray-400 text-sm">
        Try a different keyword or filter.
      </p>
      <button onclick="document.getElementById('clearFilters').click()" class="mt-4 inline-flex items-center justify-center px-4 py-2 rounded-xl bg-runaz-blue text-white font-semibold hover:bg-blue-600 transition-colors">
        Clear filters
      </button>
    </div>

    <!-- CTA -->
    <div id="request-category" class="mt-12 rounded-3xl bg-gradient-to-br from-runaz-blue to-blue-600 text-white p-8 flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between shadow-xl">
      <div>
        <h3 class="text-2xl font-bold">Don't see your category?</h3>
        <p class="text-white/90 mt-2">
          Tell us what you need—we'll add it and notify you when providers are available.
        </p>
      </div>
      <a href="../contact/" class="px-5 py-3 rounded-xl bg-white hover:bg-gray-50 text-runaz-blue font-semibold transition-colors whitespace-nowrap">
        Request Category
      </a>
    </div>
  </section>

  <!-- Newsletter -->
  <?php include "../newsletter.php"; ?>
  
  <!-- Footer mount -->
  <?php include "./partials/footer.php"; ?>
  <?php include "./partials/script.php"; ?>

  <style>
    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
    
    .filter-chip {
      background: rgba(255, 255, 255, 0.6);
      border: 1px solid #e5e7eb;
    }
    
    .dark .filter-chip {
      background: rgba(17, 24, 39, 0.6);
      border-color: #374151;
    }
    
    .filter-chip.active {
      background: var(--runaz-blue, #3b82f6);
      color: white;
      border-color: var(--runaz-blue, #3b82f6);
    }
    
    .filter-chip:hover:not(.active) {
      background: rgba(255, 255, 255, 0.9);
    }
    
    .dark .filter-chip:hover:not(.active) {
      background: rgba(17, 24, 39, 0.9);
    }
  </style>

  <script>
    // Categories data from PHP
    const CATEGORIES = <?php echo json_encode($categories); ?>;
    
    // Icon mapping
    const ICON_MAP = <?php echo json_encode($categoryIcons); ?>;

    // Category type classification (simple heuristic based on name/slug)
    function getCategoryType(slug, name) {
      const lower = slug.toLowerCase();
      if (lower.includes('beauty') || lower.includes('grooming') || lower.includes('makeup') || lower.includes('hair')) return 'beauty';
      if (lower.includes('photo') || lower.includes('event') || lower.includes('cater') || lower.includes('dj')) return 'events';
      if (lower.includes('tutor') || lower.includes('learn') || lower.includes('teach') || lower.includes('lesson')) return 'learning';
      return 'home'; // default to home services
    }

    // Get icon for category
    function getIcon(slug) {
      return ICON_MAP[slug] || 'briefcase';
    }

    // State
    const grid = document.getElementById('catGrid');
    const emptyState = document.getElementById('emptyState');
    const loadingState = document.getElementById('loadingState');
    const searchEl = document.getElementById('catSearch');
    const sortEl = document.getElementById('catSort');
    const filterChips = document.querySelectorAll('.filter-chip');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const countNum = document.getElementById('countNum');

    let state = { 
      query: '', 
      sort: 'popular', 
      filter: 'all' 
    };

    // Render categories
    function render() {
      let data = CATEGORIES.map(cat => ({
        ...cat,
        type: getCategoryType(cat.category_slug, cat.category_name),
        popularity: parseInt(cat.provider_count) || 0
      }));

      // Filter by type
      if (state.filter !== 'all') {
        data = data.filter(c => c.type === state.filter);
      }

      // Search
      if (state.query) {
        const q = state.query.toLowerCase();
        data = data.filter(c =>
          c.category_name.toLowerCase().includes(q) ||
          (c.description && c.description.toLowerCase().includes(q)) ||
          c.category_slug.toLowerCase().includes(q)
        );
      }

      // Sort
      switch(state.sort) {
        case 'alpha':
          data.sort((a, b) => a.category_name.localeCompare(b.category_name));
          break;
        case 'providers':
          data.sort((a, b) => b.popularity - a.popularity);
          break;
        case 'newest':
          data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
          break;
        default: // popular
          data.sort((a, b) => (b.display_order || 0) - (a.display_order || 0));
      }

      // Update count
      countNum.textContent = data.length;

      // Show/hide clear filters button
      const hasActiveFilters = state.query || state.filter !== 'all' || state.sort !== 'popular';
      clearFiltersBtn.classList.toggle('hidden', !hasActiveFilters);

      // Render grid
      if (data.length === 0) {
        grid.classList.add('hidden');
        emptyState.classList.remove('hidden');
      } else {
        grid.classList.remove('hidden');
        emptyState.classList.add('hidden');
        
        grid.innerHTML = data.map(cat => `
          <a href="details.php?slug=<?php echo ''; ?>${cat.category_slug}" class="group rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5 hover:shadow-lg hover:border-runaz-blue/50 dark:hover:border-runaz-blue/50 transition-all duration-300">
            <!-- Icon -->
            <div class="w-12 h-12 rounded-xl bg-runaz-blue/10 dark:bg-runaz-blue/20 grid place-items-center group-hover:scale-110 transition-transform">
              <i data-feather="${getIcon(cat.category_slug)}" class="text-runaz-blue w-6 h-6"></i>
            </div>
            
            <!-- Title -->
            <h3 class="mt-3 font-semibold text-gray-900 dark:text-white group-hover:text-runaz-blue transition-colors line-clamp-1">
              ${cat.category_name}
            </h3>
            
            <!-- Description -->
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
              ${cat.description || 'Professional services available'}
            </p>
            
            <!-- Provider count -->
            <div class="mt-3 flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
              <i data-feather="users" class="w-3 h-3"></i>
              <span>${cat.popularity} provider${cat.popularity !== 1 ? 's' : ''}</span>
            </div>
            
            <!-- Explore link -->
            <div class="mt-4 flex items-center text-sm text-runaz-blue font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
              Explore
              <i data-feather="arrow-right" class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform"></i>
            </div>
          </a>
        `).join('');

        // Replace feather icons
        if (typeof feather !== 'undefined') {
          feather.replace();
        }
      }
    }

    // Event listeners
    searchEl.addEventListener('input', (e) => {
      state.query = e.target.value.trim();
      render();
    });

    sortEl.addEventListener('change', (e) => {
      state.sort = e.target.value;
      render();
    });

    filterChips.forEach(chip => {
      chip.addEventListener('click', () => {
        state.filter = chip.dataset.filter;
        
        // Update active state
        filterChips.forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        
        render();
      });
    });

    clearFiltersBtn.addEventListener('click', () => {
      state = { query: '', sort: 'popular', filter: 'all' };
      searchEl.value = '';
      sortEl.value = 'popular';
      filterChips.forEach(c => c.classList.remove('active'));
      filterChips[0].classList.add('active'); // First chip is "All"
      render();
    });

    // Initialize
    render();

    // Initialize feather icons
    if (typeof feather !== 'undefined') {
      feather.replace();
    }
  </script>
</body>
</html>