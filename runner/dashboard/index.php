<?php
// runner/dashboard/index.php

// Include authentication and database
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../api/config/database.php';

// Require runner role
requireRole('runner');

// Get current user
$currentUser = getCurrentUser();
$userId = $currentUser['id'];

// Fetch runner profile and stats
$stmt = $conn->prepare("
    SELECT 
        rp.*,
        u.status,
        u.email_verified,
        u.phone_verified,
        COUNT(DISTINCT usc.category_id) as category_count
    FROM runner_profiles rp
    JOIN users u ON rp.user_id = u.id
    LEFT JOIN user_service_categories usc ON u.id = usc.user_id
    WHERE rp.user_id = ?
    GROUP BY rp.id
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$runnerProfile = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get runner's service categories
$stmt = $conn->prepare("
    SELECT sc.category_name, sc.category_slug
    FROM user_service_categories usc
    JOIN service_categories sc ON usc.category_id = sc.id
    WHERE usc.user_id = ?
    ORDER BY sc.category_name
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userCategories = [];
while ($row = $result->fetch_assoc()) {
    $userCategories[] = $row;
}
$stmt->close();

// Calculate profile completeness
$completeness = 0;
$checks = [
    'has_email_verified' => $currentUser['status'] === 'active',
    'has_categories' => count($userCategories) > 0,
    'has_skills' => !empty($runnerProfile['skills']),
    'has_rate' => !empty($runnerProfile['hourly_rate']),
    'has_bio' => !empty($runnerProfile['bio']),
    'has_experience' => !empty($runnerProfile['experience_years']),
    'has_avatar' => !empty($currentUser['avatar_url'])
];
$completeness = (count(array_filter($checks)) / count($checks)) * 100;

// Stats (mock data for now - will be replaced with real job data later)
$stats = [
    'active_jobs' => $runnerProfile['total_jobs'] - $runnerProfile['completed_jobs'],
    'offers_sent' => 0, // TODO: Implement offers system
    'completed' => $runnerProfile['completed_jobs'],
    'rating' => number_format($runnerProfile['rating'], 1)
];

// Recent activity mock data (TODO: Replace with real data)
$activeJobs = [];
$offersSent = [];

$conn->close();
?>

<!-- HEAD -->
<?php include "./includes/head.php"; ?>

<!-- Header (fixed) -->
<?php include "./includes/header.php"; ?>

<div class="flex pt-16"> 
  <!-- Add top padding = header height -->

  <!-- Sidebar (desktop only) -->
  <?php include "./includes/aside.php"; ?>

 <!-- MAIN WRAPPER (pushes content away from header + sidebar) -->
<main class="lg:ml-72">

      <!-- Inside main container -->
     <div class="max-w-7xl mx-auto p-4 lg:p-8">

        <!-- Welcome Message -->
        <div class="mb-6">
          <h1 class="text-2xl md:text-3xl font-extrabold">
            Welcome back, <?php echo htmlspecialchars($currentUser['first_name'] ?? 'Runner'); ?>! 👋
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-1">
            Here's what's happening with your services today
          </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="card">
            <div class="muted">Active Jobs</div>
            <div class="kpi"><?php echo $stats['active_jobs']; ?></div>
            <div class="text-xs text-gray-500 mt-1">In progress</div>
          </div>
          
          <div class="card">
            <div class="muted">Offers Sent</div>
            <div class="kpi"><?php echo $stats['offers_sent']; ?></div>
            <div class="text-xs text-gray-500 mt-1">Awaiting response</div>
          </div>
          
          <div class="card">
            <div class="muted">Completed</div>
            <div class="kpi"><?php echo $stats['completed']; ?></div>
            <div class="text-xs text-gray-500 mt-1">Total jobs</div>
          </div>
          
          <div class="card">
            <div class="muted">Rating</div>
            <div class="kpi">
              <?php echo $stats['rating']; ?>★
            </div>
            <div class="text-xs text-gray-500 mt-1">
              <?php echo $stats['rating'] >= 4.5 ? 'Excellent!' : 'Keep improving'; ?>
            </div>
          </div>
        </div>

        <!-- Your Services -->
        <?php if (!empty($userCategories)): ?>
        <div class="mt-6 panel">
          <div class="panel-head">
            <h3 class="font-semibold">Your Services</h3>
            <a class="link" href="../profile/edit/">Edit →</a>
          </div>
          <div class="p-4">
            <div class="flex flex-wrap gap-2">
              <?php foreach ($userCategories as $category): ?>
                <span class="px-3 py-1 rounded-full text-sm bg-runaz-blue/10 text-runaz-blue dark:bg-runaz-blue/20 font-medium">
                  <?php echo htmlspecialchars($category['category_name']); ?>
                </span>
              <?php endforeach; ?>
            </div>
            
            <?php if ($runnerProfile['hourly_rate']): ?>
              <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                <strong>Your Rate:</strong> ₦<?php echo number_format($runnerProfile['hourly_rate'], 2); ?>/hr
                <?php if ($runnerProfile['experience_years']): ?>
                  · <strong>Experience:</strong> <?php echo $runnerProfile['experience_years']; ?> years
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="mt-6 flex flex-col md:flex-row gap-3">
          <a href="../browse/" class="btn-primary">
            <i data-feather="compass" class="mr-2"></i> Browse Requests
          </a>

          <div class="flex-1 relative">
            <i data-feather="search" class="w-4 h-4 text-gray-400 absolute left-3 top-3"></i>
            <input 
              id="searchInput"
              class="input w-full pl-9" 
              placeholder="Search jobs or messages…"
              type="search">
          </div>

          <select id="sortFilter" class="input">
            <option value="nearby">Nearby</option>
            <option value="newest">Newest</option>
            <option value="budget_high">Budget (High)</option>
            <option value="budget_low">Budget (Low)</option>
          </select>
        </div>

        <!-- Lists -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
          <!-- Active Jobs -->
          <div class="panel">
            <div class="panel-head">
              <h3 class="font-semibold">Active Jobs</h3>
              <a class="link" href="../jobs/">View all →</a>
            </div>

            <div class="list">
              <?php if (!empty($activeJobs)): ?>
                <?php foreach ($activeJobs as $job): ?>
                  <div class="row">
                    <div>
                      <div class="row-title"><?php echo htmlspecialchars($job['title']); ?></div>
                      <div class="row-sub">
                        <?php echo htmlspecialchars($job['category']); ?> · 
                        <?php echo htmlspecialchars($job['due_date']); ?> · 
                        <?php echo htmlspecialchars($job['location']); ?>
                      </div>
                    </div>
                    <div class="row-meta btns">
                      <button class="btn" onclick="openMessages(<?php echo $job['id']; ?>)">Message</button>
                      <button class="btn-yellow" onclick="markDone(<?php echo $job['id']; ?>)">Mark Done</button>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                  <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                  <p class="font-medium">No active jobs yet</p>
                  <p class="text-sm mt-1">Browse requests to find work opportunities</p>
                  <a href="../browse/" class="btn-primary mt-4 inline-flex items-center">
                    <i data-feather="search" class="mr-2"></i> Browse Requests
                  </a>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Offers Sent -->
          <div class="panel">
            <div class="panel-head">
              <h3 class="font-semibold">Offers Sent</h3>
              <a class="link" href="../offers/">Track →</a>
            </div>

            <div class="list">
              <?php if (!empty($offersSent)): ?>
                <?php foreach ($offersSent as $offer): ?>
                  <div class="row">
                    <div>
                      <div class="row-title"><?php echo htmlspecialchars($offer['title']); ?></div>
                      <div class="row-sub">
                        Your offer: ₦ <?php echo number_format($offer['amount']); ?> · 
                        <?php echo $offer['distance']; ?> km
                      </div>
                    </div>
                    <div class="row-meta text-sm">
                      <?php if ($offer['status'] === 'accepted'): ?>
                        <span class="text-emerald-600 dark:text-emerald-400">Accepted ✓</span>
                      <?php elseif ($offer['status'] === 'rejected'): ?>
                        <span class="text-red-600 dark:text-red-400">Declined</span>
                      <?php else: ?>
                        <span class="text-amber-600 dark:text-amber-400">Pending</span>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                  <i data-feather="send" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                  <p class="font-medium">No offers sent yet</p>
                  <p class="text-sm mt-1">Start bidding on service requests</p>
                  <a href="../browse/" class="btn-primary mt-4 inline-flex items-center">
                    <i data-feather="compass" class="mr-2"></i> Find Requests
                  </a>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Profile Completeness -->
        <div class="mt-6 rounded-3xl border dark:border-gray-700 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 p-6 shadow-soft">
          <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
              <div class="text-lg font-bold">
                <?php if ($completeness >= 100): ?>
                  🎉 Profile Complete!
                <?php elseif ($completeness >= 75): ?>
                  Almost there! Boost your ranking
                <?php else: ?>
                  Complete your profile to get more jobs
                <?php endif; ?>
              </div>
              <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                <?php if ($completeness >= 100): ?>
                  Your profile looks great! Keep maintaining a high rating.
                <?php else: ?>
                  Complete KYC, add portfolio photos, and keep a 4.5★+ rating to appear higher in searches.
                <?php endif; ?>
              </div>
              
              <!-- Missing items -->
              <?php if ($completeness < 100): ?>
                <div class="mt-3 space-y-1 text-xs">
                  <?php if (!$checks['has_email_verified']): ?>
                    <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400">
                      <i data-feather="alert-circle" class="w-3 h-3"></i>
                      <span>Complete email verification</span>
                    </div>
                  <?php endif; ?>
                  
                  <?php if (!$checks['has_categories']): ?>
                    <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400">
                      <i data-feather="alert-circle" class="w-3 h-3"></i>
                      <span>Add at least one service category</span>
                    </div>
                  <?php endif; ?>
                  
                  <?php if (!$checks['has_skills']): ?>
                    <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400">
                      <i data-feather="alert-circle" class="w-3 h-3"></i>
                      <span>Add your skills and keywords</span>
                    </div>
                  <?php endif; ?>
                  
                  <?php if (!$checks['has_rate']): ?>
                    <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400">
                      <i data-feather="alert-circle" class="w-3 h-3"></i>
                      <span>Set your hourly rate</span>
                    </div>
                  <?php endif; ?>
                  
                  <?php if (!$checks['has_bio']): ?>
                    <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400">
                      <i data-feather="alert-circle" class="w-3 h-3"></i>
                      <span>Write a brief bio</span>
                    </div>
                  <?php endif; ?>
                  
                  <?php if (!$checks['has_avatar']): ?>
                    <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400">
                      <i data-feather="alert-circle" class="w-3 h-3"></i>
                      <span>Upload a profile photo</span>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
            
            <a href="../profile/edit/" class="px-4 py-2 rounded-xl bg-runaz-blue hover:bg-blue-600 text-white font-semibold transition-colors whitespace-nowrap">
              <?php echo $completeness >= 100 ? 'View Profile' : 'Improve Profile'; ?>
            </a>
          </div>

          <!-- Progress bar -->
          <div class="mt-4 h-3 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
            <div 
              class="h-3 rounded-full bg-gradient-to-r from-runaz-blue to-blue-600 transition-all duration-500" 
              style="width: <?php echo $completeness; ?>%">
            </div>
          </div>
          
          <div class="mt-2 flex items-center justify-between text-xs">
            <span class="text-gray-500">Profile completeness</span>
            <span class="font-bold text-runaz-blue"><?php echo round($completeness); ?>%</span>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3">
          <a href="../profile/" class="card hover:shadow-lg transition-shadow cursor-pointer">
            <div class="text-center">
              <i data-feather="user" class="w-8 h-8 mx-auto mb-2 text-runaz-blue"></i>
              <div class="font-medium">My Profile</div>
            </div>
          </a>
          
          <a href="../wallet/" class="card hover:shadow-lg transition-shadow cursor-pointer">
            <div class="text-center">
              <i data-feather="credit-card" class="w-8 h-8 mx-auto mb-2 text-emerald-600"></i>
              <div class="font-medium">Wallet</div>
            </div>
          </a>
          
          <a href="../messages/" class="card hover:shadow-lg transition-shadow cursor-pointer">
            <div class="text-center">
              <i data-feather="message-circle" class="w-8 h-8 mx-auto mb-2 text-purple-600"></i>
              <div class="font-medium">Messages</div>
            </div>
          </a>
          
          <a href="../settings/" class="card hover:shadow-lg transition-shadow cursor-pointer">
            <div class="text-center">
              <i data-feather="settings" class="w-8 h-8 mx-auto mb-2 text-gray-600"></i>
              <div class="font-medium">Settings</div>
            </div>
          </a>
        </div>

      </div>
  </main>
</div>

<!-- Footer -->
<?php include "./includes/footer.php"; ?>

<script>
// Dashboard interactions
document.addEventListener('DOMContentLoaded', function() {
  // Search functionality
  const searchInput = document.getElementById('searchInput');
  searchInput?.addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase();
    // TODO: Implement search
    console.log('Searching for:', query);
  });

  // Sort filter
  const sortFilter = document.getElementById('sortFilter');
  sortFilter?.addEventListener('change', function(e) {
    const sortBy = e.target.value;
    // TODO: Implement sorting
    console.log('Sorting by:', sortBy);
  });

  // Initialize Feather icons
  if (typeof feather !== 'undefined') {
    feather.replace();
  }
});

// Action functions
function openMessages(jobId) {
  window.location.href = `../messages/?job=${jobId}`;
}

function markDone(jobId) {
  if (confirm('Mark this job as completed?')) {
    // TODO: Implement mark as done
    console.log('Marking job as done:', jobId);
  }
}
</script>

</body>
</html>