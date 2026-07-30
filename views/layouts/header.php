<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Kenyans Decision - Public Opinion & Polling') ?></title>
    
    <!-- Meta & OpenGraph for WhatsApp/X/Social sharing -->
    <meta name="description" content="Independent, non-governmental public opinion polling and civic discussion platform for Kenyans.">
    <meta property="og:title" content="<?= htmlspecialchars($title ?? 'Kenyans Decision - Public Opinion Platform') ?>">
    <meta property="og:description" content="What Do Kenyans Think? Participate anonymously in public opinion polls and view real-time county breakdowns.">
    <meta property="og:type" content="website">
    
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
        /* Fallbacks for older browsers on Windows 8 / legacy engines */
        .shadow-xs {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        button, input, select, textarea {
            font-family: inherit;
        }
        /* Ensure primary emerald buttons stand out explicitly on old display drivers */
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-600 via-emerald-700 to-slate-900 text-white flex items-center justify-center p-2 shadow-sm group-hover:scale-105 transition-transform">
                    <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <span class="font-extrabold text-slate-900 tracking-tight text-lg">Kenyans<span class="text-emerald-600">Decision</span></span>
                    <span class="hidden sm:inline-block text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-2 py-0.5 ml-1.5 uppercase tracking-wider">Independent</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-slate-600">
                <a href="/" class="px-3.5 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">Dashboard</a>
                <a href="/polls" class="px-3.5 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">All Polls</a>
                <a href="/discussions" class="px-3.5 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">Civic Forum</a>
                <a href="/methodology" class="px-3.5 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">Methodology</a>
            </nav>

            <div class="flex items-center gap-3">
                <?php if (!empty($_SESSION['user'])): ?>
                    <div class="flex items-center gap-2">
                        <span class="hidden sm:inline-block text-xs font-semibold text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-full">
                            <?= htmlspecialchars($_SESSION['user']['display_name']) ?>
                        </span>
                        <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
                            <a href="/admin" class="text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-300 px-2.5 py-1 rounded-full hover:bg-amber-200 transition-colors">
                                Admin Portal
                            </a>
                        <?php endif; ?>
                        <form action="/api/logout" method="POST" class="inline">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <button type="submit" class="text-xs font-medium text-slate-500 hover:text-slate-900 transition-colors px-2 py-1">
                                Sign Out
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <a href="/login" class="text-xs sm:text-sm font-semibold text-slate-700 hover:text-slate-900 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        Sign In
                    </a>
                    <a href="/register" class="text-xs sm:text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-1.5 rounded-lg shadow-xs transition-colors">
                        Create Poll
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
