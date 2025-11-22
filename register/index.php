<!doctype html>
<html lang="en">
  <!-- HEAD MOUNT -->
<?php 
include "../api/config/database.php";
include "./partials/head.php"; 
include "../api/fetch_categories.php"; // Adjust path as needed
?>

<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">

<!-- HEADER MOUNT -->
<?php include "./partials/header.php"; ?>

<!-- Breadcrumb Navigation -->
<?php include "./partials/breadcrumb.php";  ?>

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <h1 class="text-2xl sm:text-3xl font-extrabold">Create your account</h1>
  <p class="mt-1 text-gray-600 dark:text-gray-300">Join Runaz as a <span class="font-semibold">Requester</span> or <span class="font-semibold">Runner</span>. Complete KYC to keep the marketplace safe.</p>

  <!-- Progress -->
  <ol id="steps" class="mt-6 grid grid-cols-1 sm:grid-cols-5 gap-3 text-sm">
    <li class="step active"><div class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-3"><div class="font-semibold">1. Account</div><div class="text-gray-600 dark:text-gray-400">Role & login</div></div></li>
    <li class="step"><div class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-3"><div class="font-semibold">2. Personal</div><div class="text-gray-600 dark:text-gray-400">Contact & address</div></div></li>
    <li class="step"><div class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-3"><div class="font-semibold">3. KYC</div><div class="text-gray-600 dark:text-gray-400">ID verification</div></div></li>
    <li class="step"><div class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-3"><div class="font-semibold">4. Profile</div><div class="text-gray-600 dark:text-gray-400">Role-specific</div></div></li>
    <li class="step"><div class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-3"><div class="font-semibold">5. Review</div><div class="text-gray-600 dark:text-gray-400">Confirm & submit</div></div></li>
  </ol>

  <form id="registerForm" class="mt-8 space-y-6">
    <!-- STEP 1: Role & Account -->
    <section data-step="1" class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
      <h2 class="text-lg font-semibold">Select your role</h2>
      <div class="mt-3 grid sm:grid-cols-2 gap-4">
        <label class="flex items-center gap-3 rounded-xl border dark:border-gray-700 p-4 cursor-pointer">
          <input type="radio" name="role" value="requester" class="accent-runaz-blue" required>
          <div>
            <div class="font-medium">Requester</div>
            <div class="text-sm text-gray-600 dark:text-gray-300">I need services from trusted providers.</div>
          </div>
        </label>
        <label class="flex items-center gap-3 rounded-xl border dark:border-gray-700 p-4 cursor-pointer">
          <input type="radio" name="role" value="runner" class="accent-runaz-blue" required>
          <div>
            <div class="font-medium">Runner</div>
            <div class="text-sm text-gray-600 dark:text-gray-300">I provide services and want to get jobs.</div>
          </div>
        </label>
      </div>

      <!-- Email + Phone + Passwords -->
      <div class="mt-6 grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input name="email" type="email" autocomplete="email"
                 class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5"
                 placeholder="you@example.com" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Phone</label>
          <!-- phone required for BOTH roles -->
          <input name="phone" type="tel" inputmode="tel" autocomplete="tel"
                 class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5"
                 placeholder="+234 801 234 5678" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Password</label>
          <input name="password" type="password" autocomplete="new-password"
                 class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5"
                 minlength="6" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Confirm Password</label>
          <input name="password_confirm" type="password" autocomplete="new-password"
                 class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5"
                 minlength="6" required>
        </div>
      </div>
      <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">We'll use your phone for verification and important notifications.</p>
    </section>

    <!-- STEP 2: Personal Info -->
    <section data-step="2" class="hidden rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
      <h2 class="text-lg font-semibold">Personal information</h2>
      <div class="mt-4 grid sm:grid-cols-2 gap-4">
        <div><label class="block text-sm font-medium mb-1">First name</label><input name="first_name" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" required></div>
        <div><label class="block text-sm font-medium mb-1">Last name</label><input name="last_name" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" required></div>
        <div><label class="block text-sm font-medium mb-1">Date of birth</label><input name="dob" type="date" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" required></div>
        <div>
          <label class="block text-sm font-medium mb-1">Gender</label>
          <select name="gender" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" required>
            <option value="">Select…</option>
            <option>Male</option><option>Female</option><option>Prefer not to say</option>
          </select>
        </div>

        <!-- Address -->
        <div class="sm:col-span-2"><label class="block text-sm font-medium mb-1">Address</label><input name="address" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" placeholder="House No, Street, Area" required></div>
        <div><label class="block text-sm font-medium mb-1">City/Town</label><input name="city" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" required></div>
        <div><label class="block text-sm font-medium mb-1">State</label><input name="state" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" required></div>
        <div><label class="block text-sm font-medium mb-1">LGA</label><input name="lga" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" required></div>

        <!-- Optional: Alternate phone -->
        <div><label class="block text-sm font-medium mb-1">Alternate Phone (optional)</label>
          <input name="alt_phone" type="tel" inputmode="tel" autocomplete="tel"
                 class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5"
                 placeholder="+234 80x xxx xxxx">
        </div>

        <div><label class="block text-sm font-medium mb-1">Referral (optional)</label>
          <input name="referral" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" placeholder="Code or name">
        </div>
      </div>
    </section>

    <!-- STEP 3: KYC -->
    <section data-step="3" class="hidden rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
      <h2 class="text-lg font-semibold">KYC verification</h2>
      <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">We use these details to protect buyers and providers on Runaz.</p>
      <div class="mt-4 grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">ID Type</label>
          <select name="id_type" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" required>
            <option value="">Select…</option>
            <option>NIN Slip</option>
            <option>National ID Card</option>
            <option>Driver's Licence</option>
            <option>International Passport</option>
            <option>Voter's Card</option>
          </select>
        </div>
        <div><label class="block text-sm font-medium mb-1">ID Number</label><input name="id_number" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" required></div>
        <div><label class="block text-sm font-medium mb-1">NIN (optional)</label><input name="nin" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5"></div>
        <div><label class="block text-sm font-medium mb-1">Selfie (for liveness)</label><input name="selfie" type="file" accept="image/*" class="w-full rounded-xl border dark:border-gray-700 px-3 py-2.5 bg-white dark:bg-gray-900" required></div>
        <div><label class="block text-sm font-medium mb-1">ID Front</label><input name="id_front" type="file" accept="image/*" class="w-full rounded-xl border dark:border-gray-700 px-3 py-2.5 bg-white dark:bg-gray-900" required></div>
        <div><label class="block text-sm font-medium mb-1">ID Back</label><input name="id_back" type="file" accept="image/*" class="w-full rounded-xl border dark:border-gray-700 px-3 py-2.5 bg-white dark:bg-gray-900" required></div>
      </div>
      <div id="kycPreview" class="mt-4 hidden">
        <div class="text-sm font-medium mb-2">Preview</div>
        <div class="grid sm:grid-cols-3 gap-3">
          <img id="prevSelfie" class="rounded-xl border dark:border-gray-700">
          <img id="prevFront" class="rounded-xl border dark:border-gray-700">
          <img id="prevBack" class="rounded-xl border dark:border-gray-700">
        </div>
      </div>
    </section>

    <!-- STEP 4: Role-specific Profile -->
    <section data-step="4" class="hidden rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
      <h2 id="profileTitle" class="text-lg font-semibold">Profile</h2>

      <!-- Runner profile fields -->
      <div id="runnerFields" class="mt-4 grid sm:grid-cols-2 gap-4 hidden">
        <div class="sm:col-span-2">
          <label class="block text-sm font-medium mb-1">Service Categories <span class="text-red-500">*</span></label>
          <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Select all categories that apply to your services</p>
          <div class="grid sm:grid-cols-3 gap-2 text-sm">
            <?php if (!empty($serviceCategories)): ?>
              <?php foreach ($serviceCategories as $category): ?>
                <label class="flex items-center gap-2" title="<?php echo htmlspecialchars($category['description'] ?? ''); ?>">
                  <input type="checkbox" 
                         name="categories[]" 
                         value="<?php echo htmlspecialchars($category['id']); ?>" 
                         data-category-name="<?php echo htmlspecialchars($category['category_name']); ?>"
                         class="accent-runaz-blue category-checkbox">
                  <?php echo htmlspecialchars($category['category_name']); ?>
                </label>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-gray-500 dark:text-gray-400 col-span-3">No service categories available. Please contact support.</p>
            <?php endif; ?>
          </div>
          <div id="categoryError" class="text-red-500 text-xs mt-1 hidden">Please select at least one service category</div>
        </div>
        <div class="sm:col-span-2"><label class="block text-sm font-medium mb-1">Skills / Keywords</label><input name="skills" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" placeholder="e.g. AC servicing, wiring, nail tech"></div>
        <div><label class="block text-sm font-medium mb-1">Hourly rate (₦)</label><input name="rate" type="number" min="0" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5"></div>
        <div><label class="block text-sm font-medium mb-1">Experience (years)</label><input name="experience" type="number" min="0" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5"></div>
        <div class="sm:col-span-2"><label class="block text-sm font-medium mb-1">About / Bio</label><textarea name="bio" rows="4" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" placeholder="Tell customers about your experience"></textarea></div>
        <div class="sm:col-span-2"><label class="block text-sm font-medium mb-1">Availability</label><input name="availability" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" placeholder="e.g. Mon–Sat, 9am–6pm"></div>
      </div>

      <!-- Requester preferences -->
      <div id="requesterFields" class="mt-4 grid sm:grid-cols-2 gap-4 hidden">
        <div class="sm:col-span-2"><label class="block text-sm font-medium mb-1">Default Service Address</label><input name="default_address" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" placeholder="Where work is usually done"></div>
        <div><label class="block text-sm font-medium mb-1">Prefer verified providers only?</label>
          <select name="prefer_verified" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5">
            <option value="yes">Yes</option><option value="no">No</option>
          </select>
        </div>
        <div><label class="block text-sm font-medium mb-1">Budget Preference</label>
          <select name="budget_pref" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5">
            <option>Flexible</option><option>Economy</option><option>Premium</option>
          </select>
        </div>
        <div class="sm:col-span-2"><label class="block text-sm font-medium mb-1">Notes</label><textarea name="notes" rows="4" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" placeholder="Any preferences for providers"></textarea></div>
      </div>
    </section>

    <!-- STEP 5: Review -->
    <section data-step="5" class="hidden rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
      <h2 class="text-lg font-semibold">Review & consent</h2>
      <div id="reviewBox" class="mt-4 grid sm:grid-cols-2 gap-4 text-sm"></div>
      <label class="mt-4 flex items-center gap-2 text-sm"><input type="checkbox" id="agree" class="accent-runaz-blue" required> I agree to the <a href="#" class="text-runaz-blue underline">Terms</a> and <a href="#" class="text-runaz-blue underline">Privacy Policy</a>.</label>
    </section>

    <!-- Wizard controls -->
    <div class="flex items-center justify-between gap-3">
      <button type="button" id="backBtn" class="px-4 py-2 rounded-xl border dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Back</button>
      <div class="flex items-center gap-3">
        <span id="stepHint" class="text-sm text-gray-600 dark:text-gray-300"></span>
        <button type="button" id="nextBtn" class="px-5 py-2.5 rounded-xl bg-runaz-blue text-white font-semibold">Next</button>
        <button type="submit" id="submitBtn" class="hidden px-5 py-2.5 rounded-xl bg-runaz-yellow text-runaz-ink font-semibold">Create account</button>
      </div>
    </div>
  </form>
</main>

<!-- SCRIPT  -->
<?php include "./partials/script.php"; ?>

<script>
// Add validation for service categories before moving to next step
document.addEventListener('DOMContentLoaded', function() {
  const nextBtn = document.getElementById('nextBtn');
  const originalNextHandler = nextBtn.onclick;
  
  nextBtn.addEventListener('click', function(e) {
    const currentStep = document.querySelector('section[data-step]:not(.hidden)');
    const stepNumber = currentStep?.dataset.step;
    
    // Validate categories on step 4 for runners
    if (stepNumber === '4') {
      const runnerFields = document.getElementById('runnerFields');
      if (!runnerFields.classList.contains('hidden')) {
        const checkedCategories = document.querySelectorAll('.category-checkbox:checked');
        const errorDiv = document.getElementById('categoryError');
        
        if (checkedCategories.length === 0) {
          errorDiv.classList.remove('hidden');
          e.preventDefault();
          e.stopPropagation();
          return false;
        } else {
          errorDiv.classList.add('hidden');
        }
      }
    }
  });
  
  // Clear error when checkbox is selected
  document.querySelectorAll('.category-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const checkedCategories = document.querySelectorAll('.category-checkbox:checked');
      if (checkedCategories.length > 0) {
        document.getElementById('categoryError').classList.add('hidden');
      }
    });
  });
});
</script>

</body>
</html>