<?php
// runner-profile.php - Dynamic Runner Profile Page

require_once './api/config/database.php';

// Get runner ID from URL
$runnerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (empty($runnerId)) {
    header('Location: ./');
    exit();
}

// Fetch runner details
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
    FROM users u
    JOIN user_profiles up ON u.id = up.user_id
    JOIN runner_profiles rp ON u.id = rp.user_id
    LEFT JOIN user_kyc uk ON u.id = uk.user_id
    WHERE u.id = ? 
    AND u.role = 'runner'
    AND u.status = 'active'
    LIMIT 1
");
$stmt->bind_param("i", $runnerId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('HTTP/1.0 404 Not Found');
    echo "Runner not found";
    exit();
}

$runner = $result->fetch_assoc();
$stmt->close();

// Fetch runner's service categories
$catStmt = $conn->prepare("
    SELECT sc.category_name, sc.category_slug
    FROM user_service_categories usc
    JOIN service_categories sc ON usc.category_id = sc.id
    WHERE usc.user_id = ?
    ORDER BY sc.category_name
");
$catStmt->bind_param("i", $runnerId);
$catStmt->execute();
$catResult = $catStmt->get_result();

$categories = [];
while ($row = $catResult->fetch_assoc()) {
    $categories[] = $row;
}
$catStmt->close();

// Fetch runner reviews
$reviews = [];
if ($conn->query("SHOW TABLES LIKE 'booking_comments'")->num_rows > 0) {
    $reviewStmt = $conn->prepare("
        SELECT 
            bc.id,
            bc.author_name,
            bc.content,
            bc.rating,
            bc.created_at
        FROM booking_comments bc
        JOIN bookings b ON bc.booking_id = b.id
        WHERE b.runner_id = ?
        AND bc.rating IS NOT NULL
        ORDER BY bc.created_at DESC
        LIMIT 10
    ");
    if ($reviewStmt) {
        $reviewStmt->bind_param("i", $runnerId);
        $reviewStmt->execute();
        $reviewResult = $reviewStmt->get_result();

        while ($row = $reviewResult->fetch_assoc()) {
            $reviews[] = $row;
        }
        $reviewStmt->close();
    }
}

// Helper functions
function getAvatar($runner) {
    if ($runner['avatar_url']) {
        return $runner['avatar_url'];
    }
    return "https://ui-avatars.com/api/?name=" . urlencode($runner['first_name'] . ' ' . $runner['last_name']) . "&background=3b82f6&color=fff";
}

function getLocation($runner) {
    $parts = array_filter([$runner['lga'], $runner['city'], $runner['state']]);
    return implode(', ', $parts) ?: 'Location not specified';
}

function renderStars($rating) {
    $rating = floatval($rating);
    $full = floor($rating);
    $half = ($rating - $full) >= 0.5;
    
    $html = '';
    for ($i = 0; $i < $full; $i++) {
        $html .= '<i data-feather="star" class="w-4 h-4 text-yellow-400 fill-yellow-400 inline"></i>';
    }
    if ($half) {
        $html .= '<i data-feather="star" class="w-4 h-4 text-yellow-400 inline opacity-60"></i>';
    }
    $empty = 5 - $full - ($half ? 1 : 0);
    for ($i = 0; $i < $empty; $i++) {
        $html .= '<i data-feather="star" class="w-4 h-4 text-gray-300 dark:text-gray-600 inline"></i>';
    }
    
    return $html;
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

$pageTitle = htmlspecialchars($runner['first_name'] . ' ' . $runner['last_name']) . " - Runaz Runner Profile";
$pageDescription = htmlspecialchars($runner['bio'] ?? 'Professional service provider on Runaz');

$conn->close();
?>
<!doctype html>
<html lang="en">

<?php include './partials/head.php'; ?>

<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">

<?php include "./partials/header.php"; ?>

<!-- Breadcrumb -->
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
  <nav class="text-sm text-gray-600 dark:text-gray-400">
    <a href="./" class="hover:text-runaz-blue">Home</a>
    <span class="mx-2">/</span>
    <span class="text-gray-900 dark:text-white">Runner Profile</span>
  </nav>
</section>

<section class="py-8 md:py-12">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-8">
    <!-- Left: Profile Info -->
    <div class="lg:col-span-2">
      <!-- Hero Card -->
      <div class="rounded-3xl bg-white dark:bg-gray-800 shadow-lg overflow-hidden">
        <!-- Banner Image -->
        <div class="w-full h-64 md:h-72 bg-gradient-to-br from-runaz-blue/20 to-runaz-yellow/20 flex items-center justify-center">
          <i data-feather="image" class="w-16 h-16 text-gray-400"></i>
        </div>

        <div class="p-6 md:p-8">
          <!-- Profile Header -->
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-start sm:items-center gap-4">
              <!-- Avatar -->
              <img 
                src="<?php echo getAvatar($runner); ?>" 
                alt="<?php echo htmlspecialchars($runner['first_name']); ?>"
                class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-white dark:border-gray-700 object-cover"
              />
              
              <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                  <?php echo htmlspecialchars($runner['first_name'] . ' ' . $runner['last_name']); ?>
                </h1>
                
                <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base mt-1">
                  <i data-feather="map-pin" class="w-4 h-4 inline"></i>
                  <?php echo htmlspecialchars(getLocation($runner)); ?>
                </p>

                <!-- Rating -->
                <div class="flex items-center gap-2 mt-2">
                  <div class="flex"><?php echo renderStars($runner['rating']); ?></div>
                  <span class="font-bold text-gray-900 dark:text-white">
                    <?php echo number_format($runner['rating'], 1); ?>
                  </span>
                  <span class="text-sm text-gray-500">
                    (<?php echo intval($runner['completed_jobs']); ?> job<?php echo $runner['completed_jobs'] != 1 ? 's' : ''; ?>)
                  </span>
                </div>
              </div>
            </div>

            <!-- Verification Badge -->
            <?php if ($runner['kyc_status'] === 'verified'): ?>
              <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-sm font-semibold whitespace-nowrap">
                <i data-feather="check-circle" class="w-5 h-5"></i> KYC Verified
              </span>
            <?php endif; ?>
          </div>

          <!-- Bio -->
          <?php if (!empty($runner['bio'])): ?>
            <p class="text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
              <?php echo htmlspecialchars($runner['bio']); ?>
            </p>
          <?php endif; ?>

          <!-- Quick Stats -->
          <div class="grid grid-cols-3 gap-4 mb-6 pb-6 border-b dark:border-gray-700">
            <div class="text-center">
              <div class="text-2xl font-bold text-runaz-blue">
                <?php echo intval($runner['completed_jobs']); ?>
              </div>
              <div class="text-xs text-gray-600 dark:text-gray-400">Completed</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-runaz-blue">
                <?php echo intval($runner['experience_years']); ?>
              </div>
              <div class="text-xs text-gray-600 dark:text-gray-400">Years Exp.</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-runaz-blue">
                ₦<?php echo number_format($runner['hourly_rate']); ?>
              </div>
              <div class="text-xs text-gray-600 dark:text-gray-400">Per Hour</div>
            </div>
          </div>

          <!-- Service Categories -->
          <?php if (!empty($categories)): ?>
            <div class="mb-6">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <i data-feather="briefcase" class="w-5 h-5"></i>
                Services Offered
              </h3>
              <div class="flex flex-wrap gap-2">
                <?php foreach ($categories as $cat): ?>
                  <span class="px-4 py-2 bg-runaz-blue/10 dark:bg-runaz-blue/20 text-runaz-blue rounded-full text-sm font-medium">
                    <?php echo htmlspecialchars($cat['category_name']); ?>
                  </span>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- Skills -->
          <?php if (!empty($runner['skills'])): ?>
            <div class="mb-6">
              <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <i data-feather="star" class="w-5 h-5"></i>
                Key Skills
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                <?php echo htmlspecialchars($runner['skills']); ?>
              </p>
            </div>
          <?php endif; ?>

          <!-- Availability -->
          <?php if (!empty($runner['availability'])): ?>
            <div>
              <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <i data-feather="clock" class="w-5 h-5"></i>
                Availability
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                <?php echo htmlspecialchars($runner['availability']); ?>
              </p>
            </div>
          <?php endif; ?>

          <!-- Trust Score -->
          <div class="mt-6 pt-6 border-t dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Trust Score</h3>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
              <div 
                class="bg-gradient-to-r from-runaz-blue to-blue-600 h-3 rounded-full transition-all" 
                style="width: <?php echo min($runner['rating'] * 20, 100); ?>%;"></div>
            </div>
            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
              Based on punctuality, job completion, and customer satisfaction.
            </p>
          </div>
        </div>
      </div>

      <!-- Reviews Section -->
      <?php if (!empty($reviews)): ?>
        <div class="mt-10">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <i data-feather="message-square" class="w-6 h-6"></i>
            Customer Reviews (<?php echo count($reviews); ?>)
          </h2>
          
          <div class="space-y-4">
            <?php foreach ($reviews as $review): ?>
              <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 border dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-3">
                  <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">
                      <?php echo htmlspecialchars($review['author_name'] ?? 'Anonymous'); ?>
                    </h4>
                    <p class="text-xs text-gray-500 mt-1">
                      <?php echo timeAgo($review['created_at']); ?>
                    </p>
                  </div>
                  
                  <?php if (!empty($review['rating'])): ?>
                    <div class="flex"><?php echo renderStars($review['rating']); ?></div>
                  <?php endif; ?>
                </div>
                
                <p class="text-gray-700 dark:text-gray-300">
                  <?php echo htmlspecialchars($review['content']); ?>
                </p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Right: Booking Sidebar -->
    <div>
      <div class="rounded-3xl bg-white dark:bg-gray-800 shadow-lg p-6 sticky top-24 h-fit">
        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-6 flex items-center gap-2">
          <i data-feather="calendar" class="w-5 h-5"></i>
          Request Booking
        </h3>

        <form action="./api/booking-request.php" method="post" class="space-y-4">
          <!-- Hidden runner ID -->
          <input type="hidden" name="runner_id" value="<?php echo $runnerId; ?>">

          <!-- Service Needed -->
          <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
              Service Needed <span class="text-red-500">*</span>
            </label>
            <input 
              type="text" 
              name="service_description"
              placeholder="Describe the service you need" 
              class="w-full px-3 py-2.5 border dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-runaz-blue text-sm"
              required
            />
          </div>

          <!-- Preferred Date -->
          <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
              Preferred Date <span class="text-red-500">*</span>
            </label>
            <input 
              type="date" 
              name="preferred_date"
              class="w-full px-3 py-2.5 border dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-runaz-blue text-sm"
              min="<?php echo date('Y-m-d'); ?>"
              required
            />
          </div>

          <!-- Location -->
          <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
              Service Location <span class="text-red-500">*</span>
            </label>
            <input 
              type="text" 
              name="service_location"
              placeholder="Your address" 
              class="w-full px-3 py-2.5 border dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-runaz-blue text-sm"
              required
            />
          </div>

          <!-- Budget (optional) -->
          <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
              Budget (Optional)
            </label>
            <input 
              type="number" 
              name="budget"
              placeholder="Estimated budget in ₦" 
              class="w-full px-3 py-2.5 border dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-runaz-blue text-sm"
              min="0"
            />
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
              Additional Details (Optional)
            </label>
            <textarea 
              name="additional_details"
              placeholder="Provide more details about your request"
              rows="3"
              class="w-full px-3 py-2.5 border dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-runaz-blue text-sm resize-none"
            ></textarea>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit" 
            class="w-full py-3 rounded-xl bg-runaz-blue hover:bg-blue-600 text-white font-semibold transition-colors flex items-center justify-center gap-2">
            <i data-feather="send" class="w-4 h-4"></i>
            Send Booking Request
          </button>

          <p class="text-xs text-gray-600 dark:text-gray-400 text-center">
            The provider will review and respond within 24 hours
          </p>
        </form>

        <!-- Info Box -->
        <div class="mt-6 pt-6 border-t dark:border-gray-700">
          <div class="space-y-3 text-sm">
            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
              <i data-feather="phone" class="w-4 h-4 text-runaz-blue"></i>
              <span>Direct Contact Available</span>
            </div>
            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
              <i data-feather="shield" class="w-4 h-4 text-runaz-blue"></i>
              <span>All Payments Protected</span>
            </div>
            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
              <i data-feather="star" class="w-4 h-4 text-runaz-blue"></i>
              <span>Verified Professional</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include "./partials/footer.php"; ?>
<?php include "./partials/script.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  if (typeof feather !== 'undefined') {
    feather.replace();
  }
});
</script>

</body>
</html>