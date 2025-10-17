<!doctype html>
<html lang="en">
  <!-- HEAD MOUNT -->
<?php $title = "FAQ — Runaz"; include './partials/head.php'; ?>

<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">
  <!-- Header -->
   <?php include "./partials/header.php"; ?>

           <!-- Breadcrumb Navigation -->
    <?php include "./partials/breadcrumb.php";  ?>

  <!-- Hero -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-8">
    <div class="grid lg:grid-cols-[1fr_380px] gap-8 items-center">
      <div>
        <h1 class="text-3xl sm:text-4xl font-extrabold">How Runaz works</h1>
        <p class="mt-3 text-lg text-gray-600 dark:text-gray-300">
          Runaz connects people who need services with trusted local professionals (Runners).
          Post a request, compare offers, pay safely with escrow, and rate your experience.
        </p>

        <div class="mt-6 flex flex-wrap gap-3">
          <a href="register.html" class="px-5 py-3 rounded-xl bg-runaz-blue text-white font-semibold">Create Account</a>
          <a href="post.html" class="px-5 py-3 rounded-xl bg-runaz-yellow text-runaz-ink font-semibold">Post a Request</a>
        </div>

        <div class="mt-6 flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-300">
          <span class="inline-flex items-center gap-2"><i data-feather="shield" class="text-runaz-blue"></i> KYC-verified providers</span>
          <span class="inline-flex items-center gap-2"><i data-feather="briefcase" class="text-runaz-blue"></i> Secure escrow payments</span>
          <span class="inline-flex items-center gap-2"><i data-feather="star" class="text-runaz-blue"></i> Ratings & reviews</span>
        </div>
      </div>

      <!-- Quick cards -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-1 gap-4">
        <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5">
          <div class="w-10 h-10 rounded-xl bg-runaz-blue/10 grid place-items-center">
            <i data-feather="user-plus" class="text-runaz-blue"></i>
          </div>
          <h3 class="mt-3 font-semibold">For Requesters</h3>
          <p class="text-sm text-gray-600 dark:text-gray-300">Describe your job and receive offers from nearby Runners.</p>
          <a href="#requester" class="mt-3 inline-block text-runaz-blue text-sm font-semibold">See steps →</a>
        </div>
        <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-5">
          <div class="w-10 h-10 rounded-xl bg-runaz-blue/10 grid place-items-center">
            <i data-feather="zap" class="text-runaz-blue"></i>
          </div>
          <h3 class="mt-3 font-semibold">For Runners</h3>
          <p class="text-sm text-gray-600 dark:text-gray-300">Verify your identity, set your profile, and win gigs.</p>
          <a href="#runner" class="mt-3 inline-block text-runaz-blue text-sm font-semibold">See steps →</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Steps: Requester / Runner -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 grid lg:grid-cols-2 gap-6" id="requester">
    <!-- Requester flow -->
    <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-extrabold">Requester — Book a pro in 4 steps</h2>
        <a href="post.html" class="text-sm text-runaz-blue font-semibold">Post a Request</a>
      </div>
      <ol class="mt-4 space-y-4">
        <li class="flex gap-4">
          <span class="w-8 h-8 shrink-0 grid place-items-center rounded-full bg-runaz-blue text-white font-bold">1</span>
          <div>
            <div class="font-semibold">Describe your job</div>
            <p class="text-sm text-gray-600 dark:text-gray-300">Pick a category, add details, photos, budget, and your preferred timing.</p>
          </div>
        </li>
        <li class="flex gap-4">
          <span class="w-8 h-8 shrink-0 grid place-items-center rounded-full bg-runaz-blue text-white font-bold">2</span>
          <div>
            <div class="font-semibold">Compare offers</div>
            <p class="text-sm text-gray-600 dark:text-gray-300">Chat with Runners, review ratings, pricing, and KYC badges.</p>
          </div>
        </li>
        <li class="flex gap-4">
          <span class="w-8 h-8 shrink-0 grid place-items-center rounded-full bg-runaz-blue text-white font-bold">3</span>
          <div>
            <div class="font-semibold">Secure payment (escrow)</div>
            <p class="text-sm text-gray-600 dark:text-gray-300">Fund the job into escrow. Money is released only when you confirm completion.</p>
          </div>
        </li>
        <li class="flex gap-4">
          <span class="w-8 h-8 shrink-0 grid place-items-center rounded-full bg-runaz-blue text-white font-bold">4</span>
          <div>
            <div class="font-semibold">Rate & review</div>
            <p class="text-sm text-gray-600 dark:text-gray-300">Close the job, leave a rating, and help others hire better.</p>
          </div>
        </li>
      </ol>

      <div class="mt-5 grid sm:grid-cols-3 gap-3 text-sm">
        <div class="rounded-xl border dark:border-gray-700 p-3">
          <div class="text-xs text-gray-500">Average response time</div>
          <div class="font-bold mt-1">~15 min</div>
        </div>
        <div class="rounded-xl border dark:border-gray-700 p-3">
          <div class="text-xs text-gray-500">Payment safety</div>
          <div class="font-bold mt-1">Escrow</div>
        </div>
        <div class="rounded-xl border dark:border-gray-700 p-3">
          <div class="text-xs text-gray-500">Verified providers</div>
          <div class="font-bold mt-1">KYC badge</div>
        </div>
      </div>
    </div>

    <!-- Runner flow -->
    <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6" id="runner">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-extrabold">Runner — Get hired in 4 steps</h2>
        <a href="register.html" class="text-sm text-runaz-blue font-semibold">Become a Runner</a>
      </div>
      <ol class="mt-4 space-y-4">
        <li class="flex gap-4">
          <span class="w-8 h-8 shrink-0 grid place-items-center rounded-full bg-runaz-blue text-white font-bold">1</span>
          <div>
            <div class="font-semibold">Create & verify your account</div>
            <p class="text-sm text-gray-600 dark:text-gray-300">Complete KYC (ID + selfie) to unlock bidding and payouts.</p>
          </div>
        </li>
        <li class="flex gap-4">
          <span class="w-8 h-8 shrink-0 grid place-items-center rounded-full bg-runaz-blue text-white font-bold">2</span>
          <div>
            <div class="font-semibold">Build a compelling profile</div>
            <p class="text-sm text-gray-600 dark:text-gray-300">Set your skills, service areas, hourly rates, and availability.</p>
          </div>
        </li>
        <li class="flex gap-4">
          <span class="w-8 h-8 shrink-0 grid place-items-center rounded-full bg-runaz-blue text-white font-bold">3</span>
          <div>
            <div class="font-semibold">Send offers & deliver</div>
            <p class="text-sm text-gray-600 dark:text-gray-300">Message the client, agree on scope, and do the job as scheduled.</p>
          </div>
        </li>
        <li class="flex gap-4">
          <span class="w-8 h-8 shrink-0 grid place-items-center rounded-full bg-runaz-blue text-white font-bold">4</span>
          <div>
            <div class="font-semibold">Get paid fast</div>
            <p class="text-sm text-gray-600 dark:text-gray-300">Escrow is released on client approval. Funds land in your Runaz wallet.</p>
          </div>
        </li>
      </ol>

      <div class="mt-5 grid sm:grid-cols-3 gap-3 text-sm">
        <div class="rounded-xl border dark:border-gray-700 p-3">
          <div class="text-xs text-gray-500">Payouts</div>
          <div class="font-bold mt-1">Wallet → Bank</div>
        </div>
        <div class="rounded-xl border dark:border-gray-700 p-3">
          <div class="text-xs text-gray-500">Boost visibility</div>
          <div class="font-bold mt-1">Great ratings</div>
        </div>
        <div class="rounded-xl border dark:border-gray-700 p-3">
          <div class="text-xs text-gray-500">Disputes</div>
          <div class="font-bold mt-1">Support-assisted</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Escrow & Safety -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-4">
    <div class="grid lg:grid-cols-2 gap-6">
      <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-runaz-blue/10 grid place-items-center">
            <i data-feather="lock" class="text-runaz-blue"></i>
          </div>
          <h3 class="text-xl font-extrabold">How escrow works</h3>
        </div>
        <ul class="mt-4 space-y-3 text-sm">
          <li class="flex gap-3"><i data-feather="check-circle" class="text-emerald-600"></i><span>Client funds the job into a secure escrow account.</span></li>
          <li class="flex gap-3"><i data-feather="check-circle" class="text-emerald-600"></i><span>Runner sees that funds are secured before starting.</span></li>
          <li class="flex gap-3"><i data-feather="check-circle" class="text-emerald-600"></i><span>On completion, client approves and escrow releases to the Runner’s wallet.</span></li>
          <li class="flex gap-3"><i data-feather="alert-circle" class="text-yellow-600"></i><span>In a dispute, Runaz Support can review chats, attachments, and milestones.</span></li>
        </ul>
      </div>

      <div class="rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-runaz-blue/10 grid place-items-center">
            <i data-feather="shield" class="text-runaz-blue"></i>
          </div>
          <h3 class="text-xl font-extrabold">Trust & safety</h3>
        </div>
        <ul class="mt-4 space-y-3 text-sm">
          <li class="flex gap-3"><i data-feather="id-card" class="text-runaz-blue"></i><span><strong>KYC verification</strong> (ID + selfie) for Runners.</span></li>
          <li class="flex gap-3"><i data-feather="message-square" class="text-runaz-blue"></i><span>Keep conversations and agreements in <strong>Runaz chat</strong>.</span></li>
          <li class="flex gap-3"><i data-feather="camera" class="text-runaz-blue"></i><span>Attach <strong>photos/videos</strong> before & after for clarity.</span></li>
          <li class="flex gap-3"><i data-feather="star" class="text-runaz-blue"></i><span>Use <strong>ratings & reviews</strong> to choose with confidence.</span></li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Fees summary -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="rounded-3xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6">
      <h3 class="text-xl font-extrabold">Pricing & fees</h3>
      <div class="mt-4 grid md:grid-cols-3 gap-4 text-sm">
        <div class="rounded-xl border dark:border-gray-700 p-4">
          <div class="font-semibold">Free to post</div>
          <p class="text-gray-600 dark:text-gray-300 mt-1">Requesters can post jobs at no charge.</p>
        </div>
        <div class="rounded-xl border dark:border-gray-700 p-4">
          <div class="font-semibold">Service fee</div>
          <p class="text-gray-600 dark:text-gray-300 mt-1">A small fee applies when escrow is funded.</p>
        </div>
        <div class="rounded-xl border dark:border-gray-700 p-4">
          <div class="font-semibold">Runner commission</div>
          <p class="text-gray-600 dark:text-gray-300 mt-1">A platform commission is deducted upon payout.</p>
        </div>
      </div>
      <p class="text-xs text-gray-500 mt-3">Exact fees vary by category and payment method. You’ll always see fees before confirming.</p>
    </div>
  </section>

  <!-- FAQ -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12" id="faq">
    <h3 class="text-2xl font-extrabold">Frequently asked questions</h3>
    <div class="mt-6 space-y-3">
      <!-- Item -->
      <div class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800">
        <button class="faq-toggle w-full flex items-center justify-between px-4 py-3">
          <span class="font-semibold text-left">Do I have to pay before work starts?</span>
          <i data-feather="chevron-down"></i>
        </button>
        <div class="faq-panel px-4 pb-4 hidden text-sm text-gray-600 dark:text-gray-300">
          For fixed or hourly jobs, clients fund escrow before work begins. Funds are only released when you confirm completion.
        </div>
      </div>
      <div class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800">
        <button class="faq-toggle w-full flex items-center justify-between px-4 py-3">
          <span class="font-semibold text-left">How does KYC protect me?</span>
          <i data-feather="chevron-down"></i>
        </button>
        <div class="faq-panel px-4 pb-4 hidden text-sm text-gray-600 dark:text-gray-300">
          KYC helps verify identity, reduce fraud, and keep both sides accountable. Look for the KYC badge on Runner profiles.
        </div>
      </div>
      <div class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800">
        <button class="faq-toggle w-full flex items-center justify-between px-4 py-3">
          <span class="font-semibold text-left">What if there’s a dispute?</span>
          <i data-feather="chevron-down"></i>
        </button>
        <div class="faq-panel px-4 pb-4 hidden text-sm text-gray-600 dark:text-gray-300">
          Contact Support in the job room. We’ll review chat history, attachments, and agreed scope to help resolve fairly.
        </div>
      </div>
      <div class="rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800">
        <button class="faq-toggle w-full flex items-center justify-between px-4 py-3">
          <span class="font-semibold text-left">Can I hire the same Runner again?</span>
          <i data-feather="chevron-down"></i>
        </button>
        <div class="faq-panel px-4 pb-4 hidden text-sm text-gray-600 dark:text-gray-300">
          Yes. Open their profile and tap <em>Hire again</em> or send a direct request from your Messages.
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="rounded-3xl bg-runaz-blue text-white p-8 flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between">
      <div>
        <h3 class="text-2xl font-bold">Ready to get started?</h3>
        <p class="text-white/80 mt-1">Join Runaz today — hire trusted providers or start earning as a Runner.</p>
      </div>
      <div class="flex gap-3">
        <a href="register.html" class="px-5 py-3 rounded-xl bg-white text-runaz-blue font-semibold">Create Account</a>
        <a href="post.html" class="px-5 py-3 rounded-xl bg-runaz-yellow text-runaz-ink font-semibold">Post a Request</a>
      </div>
    </div>
  </section>

  <!-- Footer -->
<?php include "./partials/footer.php"; ?>
<?php include "./partials/script.php"; ?>
</body>
</html>