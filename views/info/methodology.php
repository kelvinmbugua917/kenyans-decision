<!-- Polling Methodology & Security Disclosure -->

<div class="max-w-4xl mx-auto space-y-6 py-4">

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
        <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
            Methodology & Technical Transparency
        </div>

        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Polling Methodology & Security Framework
            </h1>
            <p class="text-slate-500 text-xs mt-1">Independent Sample Framework | 47-County Geographic Distribution</p>
        </div>

        <p class="text-slate-600 text-sm leading-relaxed">
            Understanding how <strong>Kenyans Decision</strong> mitigates duplicate voting, calculates statistical standings, and safeguards full voter anonymity.
        </p>

        <hr class="border-slate-100">

        <div class="space-y-6 text-xs sm:text-sm text-slate-700 leading-relaxed">
            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">1. Sample Collection Framework</h3>
                <p>
                    Kenyans Decision measures real-time public sentiment using opt-in, web-based digital sampling across Kenya's 47 counties. Poll questions cover candidate popularity, tax policies, constitutional reforms, cost of living, healthcare, and economic outlooks.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">2. Multi-Layer Duplicate Vote Mitigation</h3>
                <p>
                    Because Kenyans Decision allows un-authenticated citizen participation to protect anonymity, we implement multi-layer security filters to detect and prevent automated manipulation:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <strong class="text-slate-900 block mb-1">Keyed HMAC IP Hash Digest</strong>
                        <span class="text-slate-600 text-xs">Computes <code>hash_hmac('sha256', $ip, $secret)</code> to track rate limits without saving raw IP addresses.</span>
                    </div>
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <strong class="text-slate-900 block mb-1">First-Party Device Tokens</strong>
                        <span class="text-slate-600 text-xs">Non-tracking browser tokens (<code>kd_voter_token</code>) block repeat clicks within active poll sessions.</span>
                    </div>
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <strong class="text-slate-900 block mb-1">Rate-Limit Sliding Windows</strong>
                        <span class="text-slate-600 text-xs">Enforces maximum vote submissions per minute per HMAC digest pool.</span>
                    </div>
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <strong class="text-slate-900 block mb-1">Automated Risk Scoring</strong>
                        <span class="text-slate-600 text-xs">Votes are categorized as trusted, normal, suspicious, or blocked. Blocked votes are excluded from public totals.</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">3. 47-County Sample Threshold Warning</h3>
                <p>
                    When filtering results by specific counties (e.g. Kisumu, Nairobi, Kiambu, Uasin Gishu, Mombasa), our system evaluates sample volume. Any county filter with fewer than 10 total votes displays an explicit <strong>Low Sample Size Warning</strong> banner to alert readers to sampling variance.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">4. Results History & Timeline Tracking</h3>
                <p>
                    Rather than presenting static figures, our platform records time-series audit checkpoints (Today, Yesterday, Last 7 Days, Since Launch). This provides transparency into opinion trajectory shifts and momentum trends over time.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">5. Append-Only Tamper-Evident Audit Logs</h3>
                <p>
                    Administrative moderation actions (such as setting featured polls or clearing flagged spam) are automatically recorded in an append-only audit log with SHA-256 hash chaining to ensure institutional integrity.
                </p>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-between border-t border-slate-100 text-xs font-bold text-emerald-600">
            <a href="/privacy" class="hover:underline">Privacy Policy →</a>
            <a href="/faq" class="hover:underline">Trust & FAQ Center →</a>
            <a href="/contact" class="hover:underline">Methodology Inquiries →</a>
        </div>
    </div>

</div>
