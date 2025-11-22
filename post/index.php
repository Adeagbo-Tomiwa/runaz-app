<!doctype html>
<html lang="en">

<!-- HEAD MOUNT -->
<?php include "./partials/head.php"; ?>

<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">
  <!-- Header -->
  <?php include "./partials/header.php"; ?>

  <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-extrabold">Post a Request</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-300">Describe the job and get offers from vetted Runners.</p>

    <!-- Progress Bar -->
    <div class="mt-8 flex items-center justify-between">
      <div class="relative w-full">
        <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
        <div id="progress" class="absolute top-1/2 left-0 h-1 bg-runaz-blue rounded-full transition-all duration-500" style="width:25%"></div>
      </div>
      <div class="flex justify-between w-full text-xs mt-3 text-gray-500 dark:text-gray-400">
        <span>Details</span>
        <span>Budget</span>
        <span>Location</span>
        <span>Confirm</span>
      </div>
    </div>

    <form id="multiStepForm" class="mt-8 space-y-6">
      <!-- STEP 1: Job Details -->
      <section class="step" data-step="1">
        <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
          <h2 class="text-lg font-semibold">Job details</h2>
          <div class="mt-4 grid gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Title</label>
              <input name="title" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-900" placeholder="e.g. Fix leaking kitchen sink" required>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Category</label>
              <select name="category" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-900" required>
                <option value="">Select…</option>
                <option>Plumbing</option>
                <option>Electrical</option>
                <option>Cleaning</option>
                <option>Beauty & Grooming</option>
                <option>Photography</option>
                <option>Catering</option>
                <option>Home Lessons</option>
                <option>Tech Help</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Description</label>
              <textarea id="desc" name="description" rows="5" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-900" placeholder="Explain the task..." required></textarea>
              <div class="mt-1 text-xs text-gray-500"><span id="descCount">0</span>/600 characters</div>
            </div>
          </div>
        </div>
      </section>

      <!-- STEP 2: Budget -->
      <section class="step hidden" data-step="2">
        <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
          <h2 class="text-lg font-semibold">Budget & Timing</h2>
          <div class="mt-4 grid gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Budget Type</label>
              <div class="grid sm:grid-cols-3 gap-2">
                <label class="flex items-center gap-2 rounded-xl border dark:border-gray-700 px-3 py-2"><input type="radio" name="budget_type" value="fixed" class="accent-runaz-blue" checked> Fixed</label>
                <label class="flex items-center gap-2 rounded-xl border dark:border-gray-700 px-3 py-2"><input type="radio" name="budget_type" value="hourly" class="accent-runaz-blue"> Hourly</label>
                <label class="flex items-center gap-2 rounded-xl border dark:border-gray-700 px-3 py-2"><input type="radio" name="budget_type" value="range" class="accent-runaz-blue"> Range</label>
              </div>
            </div>

            <div id="budgetInputs" class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Amount (₦)</label>
                <input name="amount" type="number" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-900" placeholder="e.g. 12000">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Urgency</label>
                <select name="urgency" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-900">
                  <option>Flexible</option>
                  <option>Today</option>
                  <option>Within 3 days</option>
                  <option>This week</option>
                </select>
              </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
              <div><label class="block text-sm font-medium mb-1">Preferred date</label><input name="date" type="date" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-900"></div>
              <div><label class="block text-sm font-medium mb-1">Preferred time</label><input name="time" type="time" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-900"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- STEP 3: Location -->
      <section class="step hidden" data-step="3">
        <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
          <h2 class="text-lg font-semibold">Location & Preferences</h2>
          <div class="mt-4 grid gap-4">
            <div><label class="block text-sm font-medium mb-1">Address</label><input name="address" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-900" required></div>
            <div class="grid sm:grid-cols-3 gap-4">
              <div><label class="block text-sm font-medium mb-1">City</label><input name="city" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-900"></div>
              <div><label class="block text-sm font-medium mb-1">State</label><input name="state" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-900"></div>
              <div><label class="block text-sm font-medium mb-1">LGA</label><input name="lga" class="w-full px-3 py-2.5 rounded-xl border dark:border-gray-700 dark:bg-gray-900"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- STEP 4: Confirm -->
      <section class="step hidden" data-step="4">
        <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6 text-center">
          <h2 class="text-lg font-semibold">Review & Publish</h2>
          <p class="mt-2 text-gray-600 dark:text-gray-300">Check your details before posting your request.</p>
          <div id="reviewSummary" class="mt-4 text-left text-sm grid gap-2"></div>
        </div>
      </section>

      <!-- Navigation Buttons -->
      <div class="flex items-center justify-between mt-8">
        <button type="button" id="prevBtn" class="px-5 py-2.5 rounded-xl border dark:border-gray-700 hidden">Back</button>
        <button type="button" id="nextBtn" class="px-5 py-2.5 rounded-xl bg-runaz-blue text-white font-semibold">Next</button>
      </div>
    </form>
  </main>

  <!-- Newsletter -->
  <?php include "../newsletter.php"; ?>

  <!-- FOOTER -->
  <?php include "./partials/footer.php"; ?>

  <!-- Scripts -->
  <?php include "./partials/script.php"; ?>

  <script>
    const steps = document.querySelectorAll(".step");
    const progress = document.getElementById("progress");
    const nextBtn = document.getElementById("nextBtn");
    const prevBtn = document.getElementById("prevBtn");
    let currentStep = 1;

    function showStep(step) {
      steps.forEach((s, i) => {
        s.classList.toggle("hidden", i + 1 !== step);
      });
      prevBtn.classList.toggle("hidden", step === 1);
      nextBtn.textContent = step === steps.length ? "Publish" : "Next";
      progress.style.width = `${(step / steps.length) * 100}%`;
    }

    nextBtn.addEventListener("click", () => {
      if (currentStep < steps.length) currentStep++;
      else alert("✅ Request published successfully!");
      showStep(currentStep);
    });

    prevBtn.addEventListener("click", () => {
      if (currentStep > 1) currentStep--;
      showStep(currentStep);
    });

    showStep(currentStep);
  </script>
</body>
</html>
