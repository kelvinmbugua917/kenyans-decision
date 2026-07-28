    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-xs sm:text-sm border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xl">🇰🇪</span>
                        <span class="font-extrabold text-white text-base tracking-tight">Kenyans<span class="text-emerald-400">Decision</span></span>
                    </div>
                    <p class="text-slate-400 text-xs sm:text-sm max-w-md leading-relaxed">
                        An independent, non-governmental public opinion and civic discussion platform for Kenyans. Empowering citizens to express opinions anonymously, track electoral sentiment, and discuss national affairs.
                    </p>
                    <div class="mt-4 flex items-center gap-2 text-xs text-slate-400 bg-slate-800/80 border border-slate-700/60 rounded-lg p-2.5 max-w-md">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>Protected by <strong>Anonymous Polling with Duplicate-Vote Mitigation</strong>. No personal identity data stored.</span>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">Platform Navigation</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="/" class="hover:text-white transition-colors">2027 Opinion Polls</a></li>
                        <li><a href="/polls" class="hover:text-white transition-colors">Public Issues</a></li>
                        <li><a href="/discussions" class="hover:text-white transition-colors">Civic Forum</a></li>
                        <li><a href="/register" class="hover:text-white transition-colors">Create Custom Poll</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">Trust & Methodology</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="/methodology" class="hover:text-white transition-colors">Polling Methodology</a></li>
                        <li><a href="/privacy" class="hover:text-white transition-colors">Privacy & Security Disclosures</a></li>
                        <li><a href="/admin" class="hover:text-white transition-colors">Append-Only Audit Logs</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <p>© <?= date('Y') ?> Kenyans Decision. Independent Public Opinion Platform. Non-governmental & Non-partisan.</p>
                <div class="flex items-center gap-4">
                    <a href="/privacy" class="hover:text-white">Privacy</a>
                    <a href="/methodology" class="hover:text-white">Methodology</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Navigation Bar -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2 flex items-center justify-around shadow-lg">
        <a href="/" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-600 hover:text-emerald-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            2027 Dashboard
        </a>
        <a href="/polls" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-600 hover:text-emerald-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            All Polls
        </a>
        <a href="/discussions" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-600 hover:text-emerald-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
            Civic Forum
        </a>
    </div>

</body>
</html>
