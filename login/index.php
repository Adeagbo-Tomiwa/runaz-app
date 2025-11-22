<!doctype html>
<html lang="en">
  <!-- HEAD MOUNT -->
<?php 
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard/');
    exit();
}

include "./partials/head.php"; 
?>

<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">
<script>(function(){ const m=localStorage.getItem('runaz-theme'); if(m==='dark') document.documentElement.classList.add('dark'); })();</script>

<div class="min-h-screen grid lg:grid-cols-2">
  <section class="hidden lg:flex bg-runaz-blue text-white p-10 items-end" style="background-image: radial-gradient(600px 300px at 20% 20%, rgba(255,197,46,.25), transparent);">
    <div>
      <a href="../" class="flex items-center">
        <img src="../assets/runaz-logo.png" class="h-10 w-10 invert-0" alt="Runaz">
        <span class="font-extrabold text-2xl">Runaz</span>
      </a>
      <h1 class="mt-16 text-4xl font-extrabold leading-tight">Welcome back 👋</h1>
      <p class="mt-2 text-white/80">Sign in to manage requests, jobs, and wallet.</p>
      <div class="mt-10 text-sm text-white/80">Don't have an account? <a href="../register/" class="underline text-white">Create one</a></div>
      <!-- Breadcrumb Navigation -->
      <?php include "./partials/breadcrumb.php";  ?>
    </div>
  </section>

  <section class="flex items-center justify-center p-8">
    <form id="loginForm" class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 p-6 shadow-soft">
      <div class="flex items-center justify-between">
        <a href="../" class="flex items-center">
          <img src="../assets/runaz-logo.png" class="h-10 w-10" alt="Runaz">
          <span class="font-extrabold text-lg">Runaz</span>
        </a>
        <button id="themeToggle" type="button" class="p-2 rounded-lg border dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700" title="Toggle dark mode">🌓</button>
      </div>
      <h2 class="mt-4 text-xl font-semibold">Sign in</h2>

      <!-- Error/Success Messages -->
      <div id="messageBox" class="hidden mt-4 p-3 rounded-xl text-sm"></div>

      <div class="mt-4">
        <label class="block text-sm font-medium mb-1">Email or Phone</label>
        <input 
          type="text" 
          id="login" 
          name="login"
          class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5" 
          placeholder="you@example.com or 0801 234 5678" 
          required
          autocomplete="username">
      </div>
      
      <div class="mt-4">
        <label class="block text-sm font-medium mb-1">Password</label>
        <div class="relative">
          <input 
            type="password" 
            id="password" 
            name="password"
            class="w-full rounded-xl border dark:bg-gray-900 dark:border-gray-700 px-3 py-2.5 pr-10" 
            required
            autocomplete="current-password">
          <button 
            type="button" 
            id="togglePassword"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
            title="Show/hide password">
            👁️
          </button>
        </div>
      </div>

      <div class="mt-3 flex items-center justify-between text-sm">
        <label class="flex items-center gap-2">
          <input type="checkbox" id="remember" name="remember" class="accent-runaz-blue"> 
          Remember me
        </label>
        <a href="../forgot-password/" class="text-runaz-blue hover:underline">Forgot password?</a>
      </div>

      <button 
        type="submit" 
        id="submitBtn"
        class="mt-6 w-full px-5 py-2.5 rounded-xl bg-runaz-blue text-white font-semibold hover:bg-blue-600 transition-colors">
        Sign in
      </button>

      <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
        Don't have an account? <a href="../register/" class="text-runaz-blue underline">Create one</a>
      </p>
    </form>
  </section>
</div>

<?php include "./partials/script.php"; ?>

<script>
// Login form handling
(function() {
  const form = document.getElementById('loginForm');
  const submitBtn = document.getElementById('submitBtn');
  const messageBox = document.getElementById('messageBox');
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');

  // Toggle password visibility
  togglePassword?.addEventListener('click', () => {
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
  });

  // Show message function
  function showMessage(message, type = 'error') {
    messageBox.className = 'mt-4 p-3 rounded-xl text-sm';
    
    if (type === 'success') {
      messageBox.classList.add('bg-green-50', 'dark:bg-green-900/20', 'text-green-700', 'dark:text-green-400', 'border', 'border-green-200', 'dark:border-green-800');
    } else {
      messageBox.classList.add('bg-red-50', 'dark:bg-red-900/20', 'text-red-700', 'dark:text-red-400', 'border', 'border-red-200', 'dark:border-red-800');
    }
    
    messageBox.textContent = message;
    messageBox.classList.remove('hidden');

    // Auto-hide error messages after 5 seconds
    if (type === 'error') {
      setTimeout(() => {
        messageBox.classList.add('hidden');
      }, 5000);
    }
  }

  // Form submission
  form?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const login = document.getElementById('login').value.trim();
    const password = document.getElementById('password').value;
    const remember = document.getElementById('remember').checked;

    // Basic validation
    if (!login || !password) {
      showMessage('Please fill in all fields');
      return;
    }

    // Show loading state
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Signing in...';
    messageBox.classList.add('hidden');

    try {
      const response = await fetch('../api/login.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          login: login,
          password: password,
          remember: remember ? '1' : '0'
        })
      });

      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        throw new Error('Server returned invalid response');
      }

      const data = await response.json();

      if (data.success) {
        showMessage(data.message || 'Login successful! Redirecting...', 'success');
        
        // Redirect after a short delay
        setTimeout(() => {
          window.location.href = data.redirect || '../dashboard/';
        }, 1000);
      } else {
        showMessage(data.message || 'Login failed. Please try again.');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      }

    } catch (error) {
      console.error('Login error:', error);
      showMessage('Network error. Please check your connection and try again.');
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
    }
  });

  // Check for URL parameters (success/error messages)
  const urlParams = new URLSearchParams(window.location.search);
  const successMsg = urlParams.get('success');
  const errorMsg = urlParams.get('error');

  if (successMsg) {
    showMessage(decodeURIComponent(successMsg), 'success');
    // Clean URL
    window.history.replaceState({}, document.title, window.location.pathname);
  } else if (errorMsg) {
    showMessage(decodeURIComponent(errorMsg), 'error');
    // Clean URL
    window.history.replaceState({}, document.title, window.location.pathname);
  }
})();
</script>

</body>
</html>