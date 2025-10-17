<!doctype html>
<html lang="en">
  <!-- HEAD MOOUNT -->
<?php include "./partials/head.php"; ?>

<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">
<script>(function(){ const m=localStorage.getItem('runaz-theme'); if(m==='dark') document.documentElement.classList.add('dark'); })();</script>

<div class="min-h-screen grid lg:grid-cols-2">
  <section class="hidden lg:flex bg-runaz-blue text-white p-10 items-end" style="background-image: radial-gradient(600px 300px at 20% 20%, rgba(255,197,46,.25), transparent);">
    <div>
      <a href="../" class="flex items-center">
        <img src="../assets/runaz-logo.png" class="h-10 w-10 invert-0" alt="Runaz">
        <span class="font-extrabold text-2xl">Runaz</span>
      </a>
      <h1 class="mt-16 text-4xl font-extrabold leading-tight">Forgot Password</h1>
      <p class="mt-2 text-white/80">Put in your email to get the password reset link.</p>
      <!-- <div class="mt-10 text-sm text-white/80">Don’t have an account? <a href="register.html" class="underline text-white">Create one</a></div> -->
    </div>
  </section>

  <section class="flex items-center justify-center p-8">
    <form class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6 shadow-soft">
      <div class="flex items-center justify-between">
        <a href="../" class="flex items-center"><img src="../assets/runaz-logo.png" class="h-10 w-10" alt="Runaz"><span class="font-extrabold text-lg">Runaz</span></a>
        <button id="themeToggle" type="button" class="p-2 rounded-lg border dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700" title="Toggle dark mode">🌓</button>
      </div>
      <h2 class="mt-4 text-xl font-semibold">Reset Password</h2>

      <div class="mt-4">
        <label class="block text-sm font-medium mb-1">Email or Phone</label>
        <input type="text" class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" placeholder="you@example.com or 0801 234 5678" required>
      </div>
      <button type="button" onclick="alert('Demo: sign in successful! Redirecting to dashboard…')" class="mt-6 w-full px-5 py-2.5 rounded-xl bg-runaz-blue text-white font-semibold">Reset</button>

      <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">Choose another way? <a href="../others/" class="text-runaz-blue underline">Click here</a></p>
    </form>
  </section>
</div>

<?php include "./partials/script.php"; ?>
</body>
</html>