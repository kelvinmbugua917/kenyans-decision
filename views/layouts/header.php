<?php
    $pageTitle = htmlspecialchars($title ?? 'Kenyans Decision - Public Opinion Polling & Civic Forum');
    $pageDesc = htmlspecialchars($metaDescription ?? 'Independent, non-governmental public opinion polling and civic discussion platform for Kenyans. Participate anonymously in real-time national and 47-county polls.');
    $pageKeywords = htmlspecialchars($metaKeywords ?? 'Kenya polls, Kenyan election 2027, public opinion Kenya, Kenya politics, county voting breakdown, Nairobi polls, Kisumu opinion, civic forum Kenya');
    
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'kenyansdecision.online';
    $currentUri = $_SERVER['REQUEST_URI'] ?? '/';
    $canonicalUrl = $scheme . '://' . $host . strtok($currentUri, '?');
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    
    <!-- Core SEO & Search Engine Meta Tags -->
    <meta name="description" content="<?= $pageDesc ?>">
    <meta name="keywords" content="<?= $pageKeywords ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">
    
    <!-- OpenGraph Meta Tags for WhatsApp, X, Facebook, LinkedIn -->
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $pageTitle ?>">
    <meta property="og:description" content="<?= $pageDesc ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
    <meta property="og:site_name" content="Kenyans Decision">
    <meta property="og:image" content="<?= $scheme ?>://<?= $host ?>/og-image.png">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $pageTitle ?>">
    <meta name="twitter:description" content="<?= $pageDesc ?>">
    <meta name="twitter:image" content="<?= $scheme ?>://<?= $host ?>/og-image.png">
    
    <!-- Schema.org Structured Data (JSON-LD) for Google Rich Results & AdSense Trust -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "@id": "<?= $scheme ?>://<?= $host ?>/#organization",
                "name": "Kenyans Decision",
                "url": "<?= $scheme ?>://<?= $host ?>",
                "logo": {
                    "@type": "ImageObject",
                    "url": "<?= $scheme ?>://<?= $host ?>/icon.png"
                },
                "sameAs": [
                    "https://twitter.com/KenyansDecision"
                ],
                "contactPoint": {
                    "@type": "ContactPoint",
                    "email": "contact@kenyansdecision.online",
                    "contactType": "customer service",
                    "areaServed": "KE",
                    "availableLanguage": ["en", "sw"]
                }
            },
            {
                "@type": "WebSite",
                "@id": "<?= $scheme ?>://<?= $host ?>/#website",
                "url": "<?= $scheme ?>://<?= $host ?>",
                "name": "Kenyans Decision",
                "description": "Independent, non-governmental public opinion polling and civic discussion platform for Kenyans across 47 counties.",
                "publisher": {
                    "@id": "<?= $scheme ?>://<?= $host ?>/#organization"
                },
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "<?= $scheme ?>://<?= $host ?>/polls?q={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            }
        ]
    }
    </script>
    
    <!-- Favicon & Touch Icons -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='25' fill='%23047857'/><path d='M30 50L45 65L75 35' stroke='white' stroke-width='12' stroke-linecap='round' stroke-linejoin='round'/></svg>">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/favicon.png">
    
    <!-- Tailwind CSS CDN (v3 for cross-browser & Windows 8 compatibility) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        function getOrCreateVoterToken() {
            var fp = localStorage.getItem('kd_voter_fp');
            if (!fp) {
                fp = 'fp_' + Date.now().toString(36) + '_' + Math.random().toString(36).substring(2, 11);
                localStorage.setItem('kd_voter_fp', fp);
            }
            if (document.cookie.indexOf('kd_voter_token=') === -1) {
                document.cookie = "kd_voter_token=" + fp + "; path=/; max-age=31536000; SameSite=Lax";
            }
            return fp;
        }
        getOrCreateVoterToken();
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .shadow-xs {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        button, input, select, textarea {
            font-family: inherit;
        }
        .bg-emerald-600 {
            background-color: #059669;
        }
        .hover\:bg-emerald-700:hover {
            background-color: #047857;
        }
        .text-white {
            color: #ffffff;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-slate-50 text-slate-900">

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-2">
            <a href="/" class="flex items-center gap-1.5 sm:gap-2.5 group shrink-0 min-w-0">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-br from-emerald-600 via-emerald-700 to-slate-900 text-white flex items-center justify-center p-1.5 sm:p-2 shadow-sm group-hover:scale-105 transition-transform shrink-0">
                    <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div class="truncate">
                    <span class="font-extrabold text-slate-900 tracking-tight text-base sm:text-lg">Kenyans<span class="text-emerald-600">Decision</span></span>
                    <span class="hidden md:inline-block text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-2 py-0.5 ml-1.5 uppercase tracking-wider">Independent</span>
                </div>
            </a>

            <nav class="hidden lg:flex items-center gap-1 text-sm font-semibold text-slate-600">
                <a href="/" class="px-3 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">Dashboard</a>
                <a href="/polls" class="px-3 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">All Polls</a>
                <a href="/discussions" class="px-3 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">Civic Forum</a>
                <a href="/about" class="px-3 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">About Us</a>
                <a href="/methodology" class="px-3 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">Methodology</a>
                <a href="/faq" class="px-3 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">FAQ</a>
                <a href="/contact" class="px-3 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">Contact</a>
            </nav>

            <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                <?php if (!empty($_SESSION['user'])): ?>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="hidden sm:inline-block text-xs font-semibold text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-full truncate max-w-[100px]">
                            <?= htmlspecialchars($_SESSION['user']['display_name']) ?>
                        </span>
                        <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
                            <a href="/admin" class="text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-300 px-2 py-1 rounded-full hover:bg-amber-200 transition-colors shrink-0">
                                Admin
                            </a>
                        <?php endif; ?>
                        <form action="/api/logout" method="POST" class="inline">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <button type="submit" class="text-xs font-medium text-slate-500 hover:text-slate-900 transition-colors px-1.5 py-1">
                                Sign Out
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <a href="/login" class="text-xs sm:text-sm font-semibold text-slate-700 hover:text-slate-900 px-2 sm:px-3 py-1.5 rounded-lg hover:bg-slate-100 transition-colors shrink-0">
                        Sign In
                    </a>
                    <a href="/register" class="text-xs sm:text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 sm:px-3.5 py-1.5 rounded-lg shadow-xs transition-colors shrink-0 whitespace-nowrap">
                        Create Poll
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8">
