<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 text-slate-900 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Kenyans Decision 🇰🇪') ?></title>
    
    <!-- Meta & OpenGraph for WhatsApp/X/Social sharing -->
    <meta name="description" content="Independent, non-governmental public opinion polling and civic discussion platform for Kenyans.">
    <meta property="og:title" content="<?= htmlspecialchars($title ?? 'Kenyans Decision 🇰🇪') ?>">
    <meta property="og:description" content="What Do Kenyans Think? Participate anonymously in public opinion polls and view real-time county breakdowns.">
    <meta property="og:type" content="website">
    
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-slate-50 text-slate-900">

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-600 via-emerald-700 to-black text-white font-bold flex items-center justify-center text-lg shadow-sm group-hover:scale-105 transition-transform">
                    🇰🇪
                </div>
                <div>
                    <span class="font-extrabold text-slate-900 tracking-tight text-lg">Kenyans<span class="text-emerald-600">Decision</span></span>
                    <span class="hidden sm:inline-block text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-2 py-0.5 ml-1.5 uppercase tracking-wider">Independent</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-slate-600">
                <a href="/" class="px-3.5 py-2 rounded-lg hover:text-slate-900 hover:bg-slate-100 transition-colors">2027 Dashboard</a>
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
