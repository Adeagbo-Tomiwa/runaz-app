<?php require_once __DIR__ . '../../../../config/bootstrap.php'; ?>
<head>
  <!-- Basic Meta -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Service Categories - Runaz</title>
  <meta name="description" content="Runaz is a privacy-first local service marketplace that connects people who need tasks done with trusted runners nearby. Fast, safe, and reliable." />
  <meta name="keywords" content="Runaz, local marketplace, hire service, runners, errands, delivery, freelance, Nigeria, services near me" />
  <meta name="author" content="Runaz Team" />
  <meta name="theme-color" content="#003d87" />

  <!-- Canonical -->
  <link rel="canonical" href="https://runaz.app/" />

  <!-- Open Graph (Facebook, LinkedIn, WhatsApp) -->
  <meta property="og:title" content="Runaz — Local Service Marketplace" />
  <meta property="og:description" content="Hire trusted runners near you for errands, delivery, and quick services. Privacy-first and easy to use." />
  <meta property="og:image" content="https://runaz.app/assets/runaz-preview.jpg" />
  <meta property="og:url" content="https://runaz.app/" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="Runaz" />

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Runaz — Local Service Marketplace" />
  <meta name="twitter:description" content="Hire trusted runners near you for errands, delivery, and quick services. Fast, secure, and reliable." />
  <meta name="twitter:image" content="https://runaz.app/assets/runaz-preview.jpg" />
  <meta name="twitter:creator" content="@runazapp" />

  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="../../assets/runaz-logo.png">
  <link rel="icon" type="image/png" sizes="32x32" href=".././assets/runaz-logo.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../../assets/runaz-logo.png">
  <link rel="manifest" href="../../manifest.json">
  <link rel="mask-icon" href="../../assets/icons/safari-pinned-tab.svg" color="#003d87">
  <meta name="msapplication-TileColor" content="#003d87">

  <!-- Fonts & Icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-TuW2PkBfE8tG2tYiK8kXYEZ+xzLzkHoDbfI4c1EpOMuQIoYpn7HWm6XG1YpAo8l80VNzGq1tvIoP/ZdTGAWFAA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            runaz: {
              blue: '#003d87',
              yellow: '#FFC52E',
              ink: '#111827'
            }
          },
          boxShadow: {
            soft: '0 12px 30px rgba(16,24,40,.08)'
          }
        }
      }
    }
  </script>

  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- Global Styles -->
  <style>
    body { font-family: 'Inter', system-ui, sans-serif; }
    ::selection { background: #003d87; color: #fff; }
  </style>

  <!-- PWA Meta -->
  <meta name="application-name" content="Runaz" />
  <meta name="apple-mobile-web-app-title" content="Runaz" />
  <meta name="apple-mobile-web-app-status-bar-style" content="default" />
  <meta name="mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-capable" content="yes" />

  <!-- Manifest (PWA) -->
  <link rel="manifest" href="./manifest.json">
</head>
