<head>
  <!-- Basic Meta -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Runaz — Local Service Marketplace</title>
  <meta name="description" content="Runaz helps you discover, hire, and connect with trusted local service providers near you — fast, secure, and reliable." />
  <meta name="keywords" content="Runaz, local services, hire professionals, home repair, cleaning, freelancers, Nigeria, service marketplace" />
  <meta name="author" content="Runaz Team" />

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="./assets/runaz-logo.png" />
  <link rel="apple-touch-icon" href="./assets/runaz-logo.png" />
  <link rel="manifest" href="/manifest.json" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:title" content="Runaz — Local Service Marketplace" />
  <meta property="og:description" content="Discover and hire trusted service providers near you on Runaz." />
  <meta property="og:image" content="https://yourdomain.com/assets/runaz-banner.png" />
  <meta property="og:url" content="https://yourdomain.com/" />
  <meta property="og:site_name" content="Runaz" />

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Runaz — Local Service Marketplace" />
  <meta name="twitter:description" content="Discover, hire, and connect with trusted service providers near you." />
  <meta name="twitter:image" content="https://yourdomain.com/assets/runaz-banner.png" />

  <!-- Theme & App Colors -->
  <meta name="theme-color" content="#003d87" />
  <meta name="apple-mobile-web-app-status-bar" content="#003d87" />
  <meta name="msapplication-navbutton-color" content="#003d87" />

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            runaz: {
              blue: "#003d87",
              yellow: "#FFC52E",
              ink: "#111827",
            },
          },
          boxShadow: {
            soft: "0 12px 30px rgba(16,24,40,.08)",
          },
        },
      },
    };
  </script>

  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- PWA Support -->
  <script>
    if ("serviceWorker" in navigator) {
      window.addEventListener("load", () => {
        navigator.serviceWorker.register("/service-worker.js").then(
          (reg) => console.log("✅ Service worker registered:", reg.scope),
          (err) => console.log("❌ Service worker registration failed:", err)
        );
      });
    }
  </script>

  <!-- Custom Styles -->
  <style>
    body {
      font-family: "Inter", system-ui, sans-serif;
    }
  </style>
</head>
