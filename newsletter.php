<!-- NEWSLETTER SECTION -->
<section class="bg-gradient-to-br from-runaz-blue/5 via-blue-50/50 to-runaz-yellow/5 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-16 border-t dark:border-gray-800">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <!-- Badge -->
    <div class="mb-6 inline-flex">
      <span class="px-4 py-2 rounded-full text-sm font-semibold bg-runaz-blue text-white shadow-lg hover:shadow-xl transition-shadow">
        <i data-feather="mail" class="w-4 h-4 inline mr-1 -mt-0.5"></i>
        Stay Updated
      </span>
    </div>

    <!-- Heading -->
    <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-4 leading-tight">
      <span class="text-runaz-blue">Never Miss</span>
      <span class="text-gray-900 dark:text-white"> an </span>
      <span class="text-runaz-yellow">Update</span>
    </h2>

    <!-- Description -->
    <p class="text-gray-600 dark:text-gray-300 text-base md:text-lg mb-8 max-w-2xl mx-auto">
      Get the latest service requests, featured providers, and <strong>exclusive deals</strong> delivered straight to your inbox. Join <strong class="text-runaz-blue">5,000+ users</strong> staying ahead!
    </p>

    <!-- Message (success/error) -->
    <div id="formMsg" class="mb-4 hidden"></div>

    <!-- Form -->
    <form id="notifyForm" class="flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
      <div class="flex-1 relative">
        <i data-feather="mail" class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
        <input 
          id="emailInput"
          name="email"
          type="email" 
          placeholder="Enter your email address" 
          class="w-full pl-12 pr-5 py-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-runaz-blue focus:border-transparent text-sm shadow-sm"
          required
        />
      </div>
      <button 
        type="submit" 
        id="subscribeBtn"
        class="px-8 py-4 rounded-xl bg-runaz-yellow hover:bg-yellow-400 text-runaz-ink font-bold text-sm shadow-md hover:shadow-lg transition-all whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <i data-feather="send" class="w-4 h-4 inline mr-2 -mt-0.5"></i>
        Subscribe
      </button>
    </form>

    <!-- Trust badges -->
    <div class="mt-8 flex items-center justify-center gap-6 text-xs text-gray-500 dark:text-gray-400">
      <span class="flex items-center gap-1">
        <i data-feather="check-circle" class="w-4 h-4 text-emerald-500"></i>
        No spam, ever
      </span>
      <span class="flex items-center gap-1">
        <i data-feather="lock" class="w-4 h-4 text-emerald-500"></i>
        Secure & private
      </span>
      <span class="flex items-center gap-1">
        <i data-feather="x-circle" class="w-4 h-4 text-emerald-500"></i>
        Unsubscribe anytime
      </span>
    </div>

    <!-- Social proof (optional) -->
    <div class="mt-6 flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-400">
      <div class="flex -space-x-2">
        <img src="https://i.pravatar.cc/40?img=1" class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800" alt="User 1">
        <img src="https://i.pravatar.cc/40?img=2" class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800" alt="User 2">
        <img src="https://i.pravatar.cc/40?img=3" class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800" alt="User 3">
        <img src="https://i.pravatar.cc/40?img=4" class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800" alt="User 4">
      </div>
      <span>Join 5,000+ subscribers</span>
    </div>
  </div>
</section>

<script>
(function() {
  const form = document.getElementById('notifyForm');
  const formMsg = document.getElementById('formMsg');
  const submitBtn = document.getElementById('subscribeBtn');
  const emailInput = document.getElementById('emailInput');
  
  if (!form) return;

  // Show message function
  function showMessage(message, type = 'error') {
    formMsg.textContent = message;
    formMsg.className = 'mb-4 p-4 rounded-xl text-sm font-medium';
    
    if (type === 'success') {
      formMsg.classList.add('bg-emerald-50', 'dark:bg-emerald-900/20', 'text-emerald-700', 'dark:text-emerald-400', 'border', 'border-emerald-200', 'dark:border-emerald-800');
    } else {
      formMsg.classList.add('bg-red-50', 'dark:bg-red-900/20', 'text-red-700', 'dark:text-red-400', 'border', 'border-red-200', 'dark:border-red-800');
    }
    
    formMsg.classList.remove('hidden');

    // Auto-hide after 5 seconds
    setTimeout(() => {
      formMsg.classList.add('hidden');
    }, 5000);
  }

  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const email = emailInput.value.trim();
    
    // Basic validation
    if (!email) {
      showMessage('Please enter your email address');
      return;
    }

    // Email format validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      showMessage('Please enter a valid email address');
      return;
    }
    
    // Disable button and show loading
    submitBtn.disabled = true;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i data-feather="loader" class="w-4 h-4 inline mr-2 -mt-0.5 animate-spin"></i> Subscribing...';
    formMsg.classList.add('hidden');
    
    try {
      const formData = new FormData();
      formData.append('email', email);
      
      // Determine correct path to subscribe.php
      const currentPath = window.location.pathname;
      let subscribePath = './api/subscribe.php';
      
      // Adjust path based on current location
      if (currentPath.includes('/categories/')) {
        subscribePath = '../api/subscribe.php';
      } else if (currentPath.includes('/runner/') || currentPath.includes('/requester/')) {
        subscribePath = '../api/subscribe.php';
      }
      
      const response = await fetch(subscribePath, {
        method: 'POST',
        body: formData
      });
      
      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        throw new Error('Server returned invalid response');
      }
      
      const data = await response.json();
      
      // Show message
      if (data.success) {
        showMessage(data.message || '🎉 Successfully subscribed! Check your email for confirmation.', 'success');
        emailInput.value = ''; // Clear input on success
        
        // Optional: Track conversion
        if (typeof gtag !== 'undefined') {
          gtag('event', 'newsletter_signup', {
            'event_category': 'engagement',
            'event_label': email
          });
        }
      } else {
        showMessage(data.message || 'Subscription failed. Please try again.');
      }
      
    } catch (error) {
      console.error('Newsletter subscription error:', error);
      showMessage('Network error. Please check your connection and try again.');
    } finally {
      // Re-enable button
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
      
      // Re-initialize Feather icons
      if (typeof feather !== 'undefined') {
        feather.replace();
      }
    }
  });

  // Initialize Feather icons
  if (typeof feather !== 'undefined') {
    feather.replace();
  }
})();
</script>