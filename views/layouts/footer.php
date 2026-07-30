    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-xs sm:text-sm border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center p-1">
                            <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="font-extrabold text-white text-base tracking-tight">Kenyans<span class="text-emerald-400">Decision</span></span>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed mb-3">
                        An independent, non-governmental public opinion and civic discussion platform for Kenyans. Empowering citizens to express opinions anonymously, track electoral sentiment across 47 counties, and discuss national affairs.
                    </p>
                    <div class="flex items-center gap-2 text-[11px] text-slate-400 bg-slate-800/80 border border-slate-700/60 rounded-lg p-2">
                        <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>Keyed HMAC Cryptographic Vote Security</span>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">Platform & Tools</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="/" class="hover:text-white transition-colors">National Dashboard</a></li>
                        <li><a href="/polls" class="hover:text-white transition-colors">All Public Polls</a></li>
                        <li><a href="/discussions" class="hover:text-white transition-colors">Civic Forum & Debates</a></li>
                        <li><a href="/register" class="hover:text-white transition-colors">Create Community Poll</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">About & Methodology</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="/about" class="hover:text-white transition-colors">About Kenyans Decision</a></li>
                        <li><a href="/methodology" class="hover:text-white transition-colors">Polling & Sampling Methodology</a></li>
                        <li><a href="/faq" class="hover:text-white transition-colors">FAQ & Verification Center</a></li>
                        <li><a href="/contact" class="hover:text-white transition-colors">Contact Editorial Team</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">Legal & Compliance</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="/privacy" class="hover:text-white transition-colors">Privacy & Data Policy</a></li>
                        <li><a href="/terms" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="/cookies" class="hover:text-white transition-colors">Cookie Policy & Ad Disclosures</a></li>
                        <li><a href="/sitemap.xml" target="_blank" class="hover:text-white transition-colors">XML Sitemap</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <p>© <?= date('Y') ?> Kenyans Decision. Independent Public Opinion Engine. Headquartered in Nairobi, Kenya.</p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="/privacy" class="hover:text-white">Privacy</a>
                    <a href="/terms" class="hover:text-white">Terms</a>
                    <a href="/cookies" class="hover:text-white">Cookies</a>
                    <a href="/contact" class="hover:text-white">Contact</a>
                    <a href="/sitemap.xml" target="_blank" class="hover:text-white">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Navigation Bar -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2 flex items-center justify-around shadow-lg">
        <a href="/" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-600 hover:text-emerald-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
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

    <!-- Post-Vote WhatsApp Viral Sharing Modal -->
    <div id="whatsapp-share-modal" class="hidden fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 shadow-2xl space-y-6 text-center relative animate-in fade-in zoom-in duration-200">
            
            <button onclick="closeWhatsAppPostVoteModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="mx-auto w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center p-3.5 shadow-inner">
                <svg class="w-full h-full text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>

            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Thanks for voting!</h2>
                <p class="text-slate-600 text-sm mt-1.5 leading-relaxed">
                    See how other Kenyans voted across 47 counties. Help expand our national sample by sharing on WhatsApp!
                </p>
            </div>

            <div class="space-y-3 pt-2">
                <a id="wa-modal-share-btn" href="#" target="_blank" rel="noopener" class="w-full bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold text-sm sm:text-base px-6 py-3.5 rounded-2xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2.5">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Share on WhatsApp</span>
                </a>

                <button onclick="closeWhatsAppPostVoteModal()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs sm:text-sm px-6 py-3 rounded-xl transition-colors">
                    See Live Results
                </button>
            </div>

            <p class="text-[11px] text-slate-400">
                Kenyans Decision • Independent Public Opinion Engine
            </p>
        </div>
    </div>

    <script>
    function openWhatsAppPostVoteModal(pollTitle, pollUrl) {
        pollUrl = pollUrl || window.location.href;
        pollTitle = pollTitle || "Public Opinion Poll";

        var shareText = "I just voted on Kenyans Decision: \"" + pollTitle + "\".\nSee how other Kenyans voted across 47 counties and cast your voice here:\n" + pollUrl;
        var waUrl = "https://api.whatsapp.com/send?text=" + encodeURIComponent(shareText);

        var waBtn = document.getElementById('wa-modal-share-btn');
        if (waBtn) {
            waBtn.href = waUrl;
        }

        var modal = document.getElementById('whatsapp-share-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeWhatsAppPostVoteModal() {
        var modal = document.getElementById('whatsapp-share-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
        window.location.reload();
    }
    </script>

</body>
</html>
