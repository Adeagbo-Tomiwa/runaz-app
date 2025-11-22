<button 
  class="book-now-btn inline-flex items-center justify-center px-5 py-2 mt-3 rounded-xl bg-runaz-blue text-white font-semibold hover:opacity-90 transition"
  data-provider="Jane Doe - Makeup Artist">
  Book Now
</button>

<!-- Booking Confirmation Modal -->
<div id="bookingModal"
  class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
  <div
    class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md p-6 relative">
    
    <!-- Close Button -->
    <button id="closeBookingModal"
      class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
      <i data-feather="x"></i>
    </button>

    <!-- Modal Header -->
    <div class="text-center mb-4">
      <h2 class="text-2xl font-bold text-runaz-blue">Confirm Your Booking</h2>
      <p class="text-sm text-gray-500 mt-1">Please review the details before proceeding</p>
    </div>

    <!-- Provider Info -->
    <div class="border rounded-xl p-4 bg-gray-50 dark:bg-gray-800">
      <div class="flex items-center gap-3">
        <img src="./assets/provider-avatar.jpg" alt="Provider" class="w-12 h-12 rounded-full object-cover">
        <div>
          <h3 id="modalProviderName" class="font-semibold text-gray-900 dark:text-white">John Doe</h3>
          <p class="text-sm text-gray-600 dark:text-gray-400">Plumber · 4.8 ⭐ (120 reviews)</p>
        </div>
      </div>
    </div>

    <!-- Booking Details -->
    <form class="mt-6 space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1">Select Date</label>
        <input type="date" class="w-full border rounded-xl px-3 py-2 dark:bg-gray-800 dark:border-gray-700" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Select Time</label>
        <input type="time" class="w-full border rounded-xl px-3 py-2 dark:bg-gray-800 dark:border-gray-700" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Address / Location</label>
        <input type="text" placeholder="e.g. 15 Ikorodu Rd, Lagos"
          class="w-full border rounded-xl px-3 py-2 dark:bg-gray-800 dark:border-gray-700" required>
      </div>

      <!-- Confirm Button -->
      <button type="submit"
        class="w-full bg-runaz-blue hover:opacity-90 text-white font-semibold py-3 rounded-xl shadow-soft transition">
        Confirm Booking
      </button>
    </form>
  </div>
</div>

<script>
  // Modal logic
  const modal = document.getElementById('bookingModal');
  const closeModal = document.getElementById('closeBookingModal');

  // Attach to all "Book Now" buttons
  document.querySelectorAll('.book-now-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const providerName = btn.getAttribute('data-provider') || 'Service Provider';
      document.getElementById('modalProviderName').textContent = providerName;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    });
  });

  // Close modal
  closeModal.addEventListener('click', () => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  });

  // Close when clicking outside modal
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  });
</script>
