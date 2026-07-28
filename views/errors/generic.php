<!-- Error View -->

<div class="max-w-md mx-auto my-12 text-center bg-white p-8 rounded-2xl border border-slate-200 shadow-xs">
    <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-xl font-bold mx-auto mb-3">
        <?= htmlspecialchars((string)($code ?? '404')) ?>
    </div>
    <h1 class="text-xl font-black text-slate-900 mb-2">Notice</h1>
    <p class="text-xs text-slate-600 mb-6"><?= htmlspecialchars($message ?? 'The requested page or resource could not be found.') ?></p>
    <a href="/" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-xs transition-colors">
        Return to Home Dashboard
    </a>
</div>
