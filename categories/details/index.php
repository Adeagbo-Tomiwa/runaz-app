<?php
// Get category slug from URL
$categorySlug = isset($_GET['slug']) ? $_GET['slug'] : (basename(dirname($_SERVER['PHP_SELF'])));

// Fetch category details and runners
require_once '../../api/config/database.php';

// Get category info
$stmt = $conn->prepare("
    SELECT 
        sc.id,
        sc.category_name,
        sc.category_slug,
        sc.description,
        sc.icon,
        COUNT(DISTINCT usc.user_id) as total_providers
    FROM service_categories sc
    LEFT JOIN user_service_categories usc ON sc.id = usc.category_id
    WHERE sc.category_slug = ? AND sc.is_active = 1
    GROUP BY sc.id
    LIMIT 1
");
$stmt->bind_param("s", $categorySlug);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();
$stmt->close();

// If category not found, redirect to categories page
if (!$category) {
    header('Location: ../');
    exit();
}

$categoryId = $category['id'];

// Fetch runners in this category with their profiles
$stmt = $conn->prepare("
    SELECT 
        u.id,
        u.email,
        u.phone,
        u.status,
        up.first_name,
        up.last_name,
        up.city,
        up.state,
        up.lga,
        up.avatar_url,
        rp.skills,
        rp.hourly_rate,
        rp.experience_years,
        rp.bio,
        rp.availability,
        rp.rating,
        rp.total_jobs,
        rp.completed_jobs,
        uk.verification_status as kyc_status
    FROM user_service_categories usc
    JOIN users u ON usc.user_id = u.id
    JOIN user_profiles up ON u.id = up.user_id
    JOIN runner_profiles rp ON u.id = rp.user_id
    LEFT JOIN user_kyc uk ON u.id = uk.user_id
    WHERE usc.category_id = ? 
    AND u.status = 'active'
    AND u.role = 'runner'
    ORDER BY rp.rating DESC, rp.completed_jobs DESC
");
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$result = $stmt->get_result();

$runners = [];
while ($row = $result->fetch_assoc()) {
    $runners[] = $row;
}
$stmt->close();

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

$categoryIcon = $categoryIcons[$category['category_slug']] ?? 'briefcase';

$conn->close();
?>
<!doctype html>
<html lang="en">
  <!-- HEAD MOUNT -->
<?php include './partials/head.php'; ?>
<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">

  <!-- Header -->
 <?php include "./partials/header.php" ?>

  <!-- Breadcrumb Navigation -->
  <?php include "./partials/breadcrumb.php";  ?>
    
  <!-- Hero -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6">
    <nav class="text-sm text-gray-600 dark:text-gray-400">
      <a href="../" class="hover:text-runaz-blue transition-colors">Categories</a> 
      <span class="mx-2">/</span> 
      <span class="text-gray-900 dark:text-white font-medium"><?php echo htmlspecialchars($category['category_name']); ?></span>
    </nav>
    
    <div class="mt-4 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
      <div>
        <h1 class="text-3xl md:text-4xl font-extrabold flex items-center gap-3">
          <span class="inline-grid place-items-center w-12 h-12 rounded-xl bg-runaz-blue/10 dark:bg-runaz-blue/20">
            <i data-feather="<?php echo $categoryIcon; ?>" class="text-runaz-blue w-6 h-6"></i>
          </span>
          <?php echo htmlspecialchars($category['category_name']); ?>
        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-300">
          <?php echo htmlspecialchars($category['description'] ?: 'Find trusted professionals for your needs'); ?>
        </p>
        <div class="mt-3 flex items-center gap-4 text-sm">
          <span class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
            <i data-feather="users" class="w-4 h-4"></i>
            <strong class="text-gray-900 dark:text-white"><?php echo $category['total_providers']; ?></strong> 
            provider<?php echo $category['total_providers'] != 1 ? 's' : ''; ?> available
          </span>
        </div>
      </div>
      <a href="../../post/?category=<?php echo urlencode($category['category_slug']); ?>" 
         class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-runaz-blue hover:bg-blue-600 text-white font-semibold transition-colors whitespace-nowrap">
        <i data-feather="plus" class="mr-2 w-5 h-5"></i> Post a Request
      </a>
    </div>
  </section>

  <!-- Content -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 grid lg:grid-cols-[280px_1fr] gap-6">
    <!-- Filters (desktop) -->
    <aside class="hidden lg:block rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5 h-fit sticky top-20">
      <h3 class="font-semibold text-lg flex items-center gap-2">
        <i data-feather="sliders" class="w-5 h-5"></i>
        Filters
      </h3>
      
      <div class="mt-5 space-y-5 text-sm">
        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Search</label>
          <div class="relative">
            <i data-feather="search" class="w-4 h-4 text-gray-400 absolute left-3 top-2.5"></i>
            <input 
              id="q" 
              class="w-full pl-9 pr-3 py-2 rounded-lg border dark:border-gray-700 bg-white dark:bg-gray-900 dark:placeholder-gray-400 text-sm" 
              placeholder="Search by name, skills..."
              type="search">
          </div>
        </div>

        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Minimum Rating</label>
          <select id="minRating" class="w-full px-3 py-2 rounded-lg border dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
            <option value="0">Any rating</option>
            <option value="3">3★ and above</option>
            <option value="4">4★ and above</option>
            <option value="4.5">4.5★ and above</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Experience</label>
          <select id="minExperience" class="w-full px-3 py-2 rounded-lg border dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
            <option value="0">Any experience</option>
            <option value="1">1+ years</option>
            <option value="3">3+ years</option>
            <option value="5">5+ years</option>
          </select>
        </div>

        <div class="flex items-center gap-2">
          <input id="kycOnly" type="checkbox" class="accent-runaz-blue w-4 h-4">
          <label for="kycOnly" class="text-sm">KYC verified only</label>
        </div>

        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
            Max Hourly Rate: <span id="maxPriceOut" class="text-runaz-blue font-semibold">₦25,000</span>
          </label>
          <input 
            id="maxPrice" 
            type="range" 
            min="1000" 
            max="50000" 
            step="1000" 
            value="50000" 
            class="w-full accent-runaz-blue">
          <div class="flex justify-between text-xs text-gray-500 mt-1">
            <span>₦1k</span>
            <span>₦50k</span>
          </div>
        </div>

        <button id="clearFilters" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-900 text-sm font-medium transition-colors">
          Clear all filters
        </button>
      </div>
    </aside>

    <!-- Results -->
    <div>
      <!-- Mobile filter bar -->
      <div class="lg:hidden rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-4 mb-4">
        <div class="grid grid-cols-2 gap-3">
          <button id="openFilters" class="px-4 py-2 rounded-lg border dark:border-gray-700 bg-white dark:bg-gray-900 text-sm font-medium flex items-center justify-center gap-2 hover:bg-gray-50 dark:hover:bg-gray-950 transition-colors">
            <i data-feather="sliders" class="w-4 h-4"></i> Filters
          </button>
          <select id="sortMobile" class="px-4 py-2 rounded-lg border dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
            <option value="rating">Sort: Rating</option>
            <option value="price_low">Price (Low-High)</option>
            <option value="price_high">Price (High-Low)</option>
            <option value="experience">Experience</option>
          </select>
        </div>
      </div>

      <!-- Desktop sort -->
      <div class="hidden lg:flex items-center justify-between mb-4 pb-4 border-b dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400">
          Showing <span id="count" class="font-semibold text-gray-900 dark:text-white">0</span> 
          <?php echo strtolower($category['category_name']); ?> provider<?php echo '<span id="countPlural">s</span>'; ?>
        </div>
        <div class="flex items-center gap-2">
          <label class="text-sm text-gray-600 dark:text-gray-400">Sort by:</label>
          <select id="sortDesktop" class="px-3 py-2 rounded-lg border dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
            <option value="rating">Top Rated</option>
            <option value="price_low">Price (Low-High)</option>
            <option value="price_high">Price (High-Low)</option>
            <option value="experience">Most Experienced</option>
          </select>
        </div>
      </div>

      <!-- Cards -->
      <div id="list" class="grid gap-4">
        <!-- Loaded by JavaScript -->
      </div>

      <!-- Loading state -->
      <div id="loading" class="hidden text-center py-12">
        <div class="inline-block w-10 h-10 border-4 border-runaz-blue border-t-transparent rounded-full animate-spin"></div>
        <p class="mt-3 text-gray-500 dark:text-gray-400">Loading providers...</p>
      </div>

      <!-- Empty state -->
      <div id="empty" class="hidden mt-8 rounded-2xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-10 text-center">
        <div class="mx-auto w-14 h-14 rounded-full bg-runaz-blue/10 grid place-items-center">
          <i data-feather="users" class="text-runaz-blue w-7 h-7"></i>
        </div>
        <h3 class="mt-4 text-lg font-semibold">No providers found</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          Try adjusting your filters or check back later.
        </p>
        <button onclick="document.getElementById('clearFilters').click()" class="mt-4 px-4 py-2 rounded-xl bg-runaz-blue text-white font-semibold hover:bg-blue-600 transition-colors">
          Clear filters
        </button>
      </div>

      <!-- CTA -->
      <div class="mt-8 rounded-3xl bg-gradient-to-br from-runaz-blue to-blue-600 text-white p-8 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between shadow-xl">
        <div>
          <h3 class="text-2xl font-bold">Need something specific?</h3>
          <p class="text-white/90 mt-2">
            Post a request and let verified <?php echo strtolower($category['category_name']); ?> providers reach out with custom offers.
          </p>
        </div>
        <a href="../../post/?category=<?php echo urlencode($category['category_slug']); ?>" 
           class="px-5 py-3 rounded-xl bg-white hover:bg-gray-50 text-runaz-blue font-semibold transition-colors whitespace-nowrap">
          Post a Request
        </a>
      </div>
    </div>
  </section>

  <!-- Newsletter -->
  <?php include "../../newsletter.php"; ?>

  <!-- Footer -->
  <?php include "./partials/footer.php"; ?>

  <!-- SCRIPT -->
  <?php include "./partials/script.php" ?>

  <script>
    // Runners data from PHP
    const RUNNERS = <?php echo json_encode($runners); ?>;

    // Elements
    const q = document.getElementById('q');
    const minRating = document.getElementById('minRating');
    const minExperience = document.getElementById('minExperience');
    const kycOnly = document.getElementById('kycOnly');
    const maxPrice = document.getElementById('maxPrice');
    const maxPriceOut = document.getElementById('maxPriceOut');
    const sortMobile = document.getElementById('sortMobile');
    const sortDesktop = document.getElementById('sortDesktop');
    const list = document.getElementById('list');
    const empty = document.getElementById('empty');
    const loading = document.getElementById('loading');
    const count = document.getElementById('count');
    const countPlural = document.getElementById('countPlural');
    const openFilters = document.getElementById('openFilters');
    const clearFilters = document.getElementById('clearFilters');

    let state = { 
      q: '', 
      minRating: 0, 
      minExperience: 0,
      kyc: false, 
      maxPrice: 50000, 
      sort: 'rating' 
    };

    // Star rating HTML generator
    function stars(rating) {
      const full = Math.floor(rating);
      const hasHalf = rating - full >= 0.5;
      let html = '';
      
      for(let i = 0; i < full; i++) {
        html += '<i data-feather="star" class="w-4 h-4 text-yellow-500 fill-yellow-500 inline"></i>';
      }
      if(hasHalf) {
        html += '<i data-feather="star" class="w-4 h-4 text-yellow-500 inline opacity-60"></i>';
      }
      const empty = 5 - full - (hasHalf ? 1 : 0);
      for(let i = 0; i < empty; i++) {
        html += '<i data-feather="star" class="w-4 h-4 text-gray-300 dark:text-gray-600 inline"></i>';
      }
      
      return html;
    }

    // Get avatar URL
    function getAvatar(runner) {
      if (runner.avatar_url) return runner.avatar_url;
      return `https://ui-avatars.com/api/?name=${encodeURIComponent(runner.first_name + ' ' + runner.last_name)}&background=3b82f6&color=fff`;
    }

    // Format location
    function getLocation(runner) {
      const parts = [runner.lga, runner.city, runner.state].filter(Boolean);
      return parts.join(', ') || 'Location not specified';
    }

    // Render function
    function render() {
      let data = RUNNERS.map(r => ({
        ...r,
        fullName: `${r.first_name} ${r.last_name}`.trim(),
        rating: parseFloat(r.rating) || 0,
        hourly_rate: parseFloat(r.hourly_rate) || 0,
        experience_years: parseInt(r.experience_years) || 0,
        isKycVerified: r.kyc_status === 'verified'
      }));

      // Filter by search query
      if (state.q) {
        const query = state.q.toLowerCase();
        data = data.filter(r => 
          r.fullName.toLowerCase().includes(query) ||
          (r.skills && r.skills.toLowerCase().includes(query)) ||
          (r.bio && r.bio.toLowerCase().includes(query)) ||
          getLocation(r).toLowerCase().includes(query)
        );
      }

      // Filter by minimum rating
      if (state.minRating > 0) {
        data = data.filter(r => r.rating >= state.minRating);
      }

      // Filter by experience
      if (state.minExperience > 0) {
        data = data.filter(r => r.experience_years >= state.minExperience);
      }

      // Filter by KYC
      if (state.kyc) {
        data = data.filter(r => r.isKycVerified);
      }

      // Filter by max price
      data = data.filter(r => r.hourly_rate <= state.maxPrice);

      // Sort
      switch(state.sort) {
        case 'price_low':
          data.sort((a, b) => a.hourly_rate - b.hourly_rate);
          break;
        case 'price_high':
          data.sort((a, b) => b.hourly_rate - a.hourly_rate);
          break;
        case 'experience':
          data.sort((a, b) => b.experience_years - a.experience_years);
          break;
        default: // rating
          data.sort((a, b) => b.rating - a.rating);
      }

      // Update count
      count.textContent = data.length;
      if (countPlural) {
        countPlural.textContent = data.length === 1 ? '' : 's';
      }

      // Show/hide states
      if (data.length === 0) {
        list.classList.add('hidden');
        empty.classList.remove('hidden');
        loading.classList.add('hidden');
        return;
      }

      list.classList.remove('hidden');
      empty.classList.add('hidden');
      loading.classList.add('hidden');

      // Render cards
      list.innerHTML = data.map(r => `
        <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5 hover:shadow-lg hover:border-runaz-blue/30 transition-all duration-300">
          <div class="flex items-start gap-4">
            <!-- Avatar -->
            <img 
              class="w-16 h-16 rounded-full object-cover border-2 border-gray-100 dark:border-gray-700" 
              src="${getAvatar(r)}" 
              alt="${r.fullName}"
              onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(r.fullName)}&background=3b82f6&color=fff'">
            
            <div class="flex-1 min-w-0">
              <!-- Name and badges -->
              <div class="flex items-start justify-between gap-2 flex-wrap">
                <div>
                  <h3 class="font-semibold text-lg text-gray-900 dark:text-white">
                    ${r.fullName}
                  </h3>
                  <div class="flex items-center gap-2 mt-1 flex-wrap">
                    ${r.isKycVerified ? '<span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-medium">KYC Verified</span>' : ''}
                    ${r.status === 'active' ? '<span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-medium">Active</span>' : ''}
                  </div>
                </div>
                
                <!-- Price -->
                <div class="text-right">
                  <div class="text-xl font-extrabold text-gray-900 dark:text-white">
                    ₦${r.hourly_rate ? r.hourly_rate.toLocaleString() : 'N/A'}
                    ${r.hourly_rate ? '<span class="text-sm font-medium text-gray-500">/hr</span>' : ''}
                  </div>
                </div>
              </div>

              <!-- Rating and location -->
              <div class="mt-2 flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300 flex-wrap">
                <span class="inline-flex items-center gap-1">
                  ${stars(r.rating)}
                  <span class="ml-1 font-medium">${r.rating.toFixed(1)}</span>
                  ${r.completed_jobs > 0 ? `<span class="text-gray-400">(${r.completed_jobs} job${r.completed_jobs !== 1 ? 's' : ''})</span>` : ''}
                </span>
                <span class="text-gray-400">•</span>
                <span class="inline-flex items-center gap-1">
                  <i data-feather="map-pin" class="w-3 h-3"></i>
                  ${getLocation(r)}
                </span>
                ${r.experience_years > 0 ? `
                  <span class="text-gray-400">•</span>
                  <span>${r.experience_years} yr${r.experience_years !== 1 ? 's' : ''} exp</span>
                ` : ''}
              </div>

              <!-- Skills -->
              ${r.skills ? `
                <p class="mt-3 text-sm text-gray-700 dark:text-gray-300">
                  <strong class="text-gray-900 dark:text-white">Skills:</strong> ${r.skills}
                </p>
              ` : ''}

              <!-- Bio -->
              ${r.bio ? `
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                  ${r.bio}
                </p>
              ` : ''}

              <!-- Actions -->
              <div class="mt-4 flex items-center gap-3 flex-wrap">
                <a href="../../messages/?runner=${r.id}" class="px-4 py-2 rounded-lg border dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 font-medium transition-colors">
                  Message
                </a>
                <a href="../../offer/?runner=${r.id}&category=<?php echo $categoryId; ?>" class="px-4 py-2 rounded-lg bg-runaz-yellow hover:bg-yellow-400 text-runaz-ink font-semibold transition-colors">
                  Make Offer
                </a>
                <a href="../../runner/profile/?id=${r.id}" class="ml-auto text-sm text-runaz-blue hover:underline font-medium">
                  View Profile →
                </a>
              </div>
            </div>
          </div>
        </div>
      `).join('');

      // Replace feather icons
      if (typeof feather !== 'undefined') {
        feather.replace();
      }
    }

    // Event listeners
    q?.addEventListener('input', (e) => {
      state.q = e.target.value.trim();
      render();
    });

    minRating?.addEventListener('change', (e) => {
      state.minRating = parseFloat(e.target.value);
      render();
    });

    minExperience?.addEventListener('change', (e) => {
      state.minExperience = parseInt(e.target.value);
      render();
    });

    kycOnly?.addEventListener('change', (e) => {
      state.kyc = e.target.checked;
      render();
    });

    maxPrice?.addEventListener('input', (e) => {
      state.maxPrice = parseInt(e.target.value, 10);
      maxPriceOut.textContent = '₦' + state.maxPrice.toLocaleString();
      render();
    });

    sortMobile?.addEventListener('change', (e) => {
      state.sort = e.target.value;
      if (sortDesktop) sortDesktop.value = state.sort;
      render();
    });

    sortDesktop?.addEventListener('change', (e) => {
      state.sort = e.target.value;
      if (sortMobile) sortMobile.value = state.sort;
      render();
    });

    clearFilters?.addEventListener('click', () => {
      state = { q: '', minRating: 0, minExperience: 0, kyc: false, maxPrice: 50000, sort: 'rating' };
      if (q) q.value = '';
      if (minRating) minRating.value = '0';
      if (minExperience) minExperience.value = '0';
      if (kycOnly) kycOnly.checked = false;
      if (maxPrice) {
        maxPrice.value = '50000';
        maxPriceOut.textContent = '₦50,000';
      }
      if (sortMobile) sortMobile.value = 'rating';
      if (sortDesktop) sortDesktop.value = 'rating';
      render();
    });

    // Mobile filter modal (simplified)
    openFilters?.addEventListener('click', () => {
      alert('Mobile filters modal - implement as needed');
    });

    // Initialize
    render();

    // Initialize feather icons
    if (typeof feather !== 'undefined') {
      feather.replace();
    }
  </script>

  <style>
    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
  </style>
</body>
</html>