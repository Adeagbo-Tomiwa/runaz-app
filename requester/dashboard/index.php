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

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="card"><div class="muted">Active Jobs</div><div class="kpi">4</div></div>
          <div class="card"><div class="muted">Offers Sent</div><div class="kpi">9</div></div>
          <div class="card"><div class="muted">Completed</div><div class="kpi">27</div></div>
          <div class="card"><div class="muted">Rating</div><div class="kpi">4.7★</div></div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex flex-col md:flex-row gap-3">
          <a href="categories.html" class="btn-primary">
            <i data-feather="compass" class="mr-2"></i> Browse Requests
          </a>

          <div class="flex-1 relative">
            <i data-feather="search" class="w-4 h-4 text-gray-400 absolute left-3 top-3"></i>
            <input class="input w-full pl-9" placeholder="Search jobs or messages…">
          </div>

          <select class="input">
            <option>Nearby</option>
            <option>Newest</option>
            <option>Budget high</option>
            <option>Budget low</option>
          </select>
        </div>

        <!-- Lists -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
          <div class="panel">
            <div class="panel-head">
              <h3 class="font-semibold">Active Jobs</h3>
              <a class="link" href="#">View all →</a>
            </div>

            <div class="list">
              <div class="row">
                <div>
                  <div class="row-title">Install water heater</div>
                  <div class="row-sub">Plumbing · Due today · Bayeku</div>
                </div>
                <div class="row-meta btns">
                  <button class="btn">Message</button>
                  <button class="btn-yellow">Mark Done</button>
                </div>
              </div>

              <div class="row">
                <div>
                  <div class="row-title">2-bedroom wiring fix</div>
                  <div class="row-sub">Electrical · Tomorrow · Ibeshe</div>
                </div>
                <div class="row-meta btns">
                  <button class="btn">Message</button>
                  <button class="btn-yellow">Mark Done</button>
                </div>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-head">
              <h3 class="font-semibold">Offers Sent</h3>
              <a class="link" href="#">Track →</a>
            </div>

            <div class="list">
              <div class="row">
                <div>
                  <div class="row-title">Fix leaking kitchen sink</div>
                  <div class="row-sub">Your offer: ₦ 7,000 · 1.9 km</div>
                </div>
                <div class="row-meta text-sm">Pending</div>
              </div>

              <div class="row">
                <div>
                  <div class="row-title">Home cleaning (2-bed)</div>
                  <div class="row-sub">Your offer: ₦ 12,500 · 3.4 km</div>
                </div>
                <div class="row-meta text-sm text-emerald-600">Accepted</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Profile completeness -->
        <div class="mt-6 rounded-3xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
          <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
              <div class="text-lg font-bold">Boost your ranking</div>
              <div class="text-sm text-gray-600 dark:text-gray-300">
                Complete KYC, add portfolio photos, and keep a 4.5★+ rating to appear higher.
              </div>
            </div>
            <a href="#" class="px-4 py-2 rounded-xl bg-runaz-blue text-white font-semibold">Improve Profile</a>
          </div>

          <div class="mt-4 h-2 rounded-full bg-gray-200 dark:bg-gray-700">
            <div class="h-2 w-2/3 rounded-full bg-runaz-blue"></div>
          </div>
          <div class="mt-2 text-xs text-gray-500">Profile completeness: 67%</div>
        </div>

      </div>
  </main>
</div>

<!-- Footer -->
<?php include "./includes/footer.php"; ?>
</body>
</html>
