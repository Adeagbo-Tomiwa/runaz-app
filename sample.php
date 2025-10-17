<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Runaz — Example</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode:'class', theme:{ extend:{ colors:{ runaz:{ blue:'#003d87', yellow:'#FFC52E', ink:'#111827' } }, boxShadow:{ soft:'0 12px 30px rgba(16,24,40,.08)' } } } }
  </script>
  <style>body{font-family:Inter,system-ui}</style>
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-50 text-runaz-ink dark:bg-gray-900 dark:text-gray-100">
  <!-- HEADER Mount -->
  <?php include "./partials/header.php"; ?> 

  <!-- Your page content -->
  <main class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold">Hello Runaz</h1>
  </main>

<!-- FOOTER Mount -->
  <?php include "./partials/footer.php"; ?>

  <!-- SCRIPT Mount -->
  <?php include "./partials/script.php"; ?>
</body>
</html>