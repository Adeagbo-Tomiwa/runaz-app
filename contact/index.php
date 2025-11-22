<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Runaz — Contact Us</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode:'class', theme:{ extend:{ colors:{ runaz:{ blue:'#003d87', yellow:'#FFC52E', ink:'#111827' } }, boxShadow:{ soft:'0 12px 30px rgba(16,24,40,.08)' } } } }
  </script>
  <style>body{font-family:Inter,system-ui}</style>
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">

  <?php include "./partials/header.php"; ?>


  <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold mb-6">Contact Us</h1>
    <p class="mb-6 text-gray-600 dark:text-gray-300">
      Have questions or need support? Get in touch with us.
    </p>

    <form class="space-y-4" id="contactForm">
      <input type="text" name="name"  placeholder="Your Name" class="w-full px-4 py-3 rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 dark:placeholder-gray-400">
      <input type="email" name="email" placeholder="Your Email" class="w-full px-4 py-3 rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 dark:placeholder-gray-400">
      <textarea rows="5" name="message" placeholder="Your Message" class="w-full px-4 py-3 rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 dark:placeholder-gray-400"></textarea>
      <button type="submit" class="px-5 py-3 rounded-xl bg-runaz-blue text-white font-semibold">Send Message</button>
    </form>
  </main>

    <!-- Newsletter -->
  <?php include "../newsletter.php"; ?>
  
  <!-- FOOTER  -->
 <?php include "./partials/footer.php"; ?>

 <!-- SCRIPT MOUNT -->
  <?php include "./partials/script.php"; ?>

  <script>
    const form = document.getElementById('contactForm');
  
    form?.addEventListener('submit', function(e) {
      e.preventDefault();
  
      const name = form.name.value.trim();
      const email = form.email.value.trim();
      const message = form.message.value.trim();
  
      if (!name || !email || !message) {
        alert("⚠️ Please fill out all fields before sending.");
        return;
      }
  
      // ✅ Fake success (for now)
      alert("✅ Thank you, " + name + "! Your message has been sent successfully. We'll get back to you shortly.");
  
      // Reset the form
      form.reset();
    });
  </script>
</body>
</html>