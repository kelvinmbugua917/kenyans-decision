<!-- Polling Methodology Disclosure -->

<div class="max-w-3xl mx-auto space-y-6 py-4">

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-4">
        <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
            Methodology & Anti-Abuse Transparency
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Polling Methodology & Security Framework
        </h1>

        <p class="text-slate-600 text-sm leading-relaxed">
            Understanding how <strong>Kenyans Decision</strong> mitigates duplicate voting while safeguarding complete voter anonymity.
        </p>

        <hr class="border-slate-100">

        <div class="space-y-6 text-xs sm:text-sm text-slate-700 leading-relaxed">
            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">1. Anonymous Polling with Duplicate-Vote Mitigation</h3>
                <p>
                    Because Kenyans Decision operates as an un-authenticated, open civic platform, we implement multi-layer <strong>duplicate-vote mitigation</strong> rather than claiming guaranteed one-vote-per-person verification.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">2. Keyed HMAC IP-Derived Rate Limiting</h3>
                <p>
                    Instead of storing raw IP addresses, our server computes a <code>hash_hmac('sha256', $ip, $secretKey)</code> digest. This digest allows server-side rate-limiting logic to detect sudden vote bursts without compromising user privacy or risking IP exposure.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">3. Risk Scoring Engine</h3>
                <p>
                    Votes are evaluated and classified into four risk statuses:
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1 font-semibold text-slate-800">
                    <li><strong class="text-emerald-600">Trusted:</strong> Verified user session or valid first-party device token.</li>
                    <li><strong class="text-slate-700">Normal:</strong> Standard anonymous web vote.</li>
                    <li><strong class="text-amber-600">Suspicious:</strong> Rapid repeated votes detected from the same HMAC IP digest within a 60-second window. Flagged for audit.</li>
                    <li><strong class="text-rose-600">Blocked:</strong> Excessive automated bot bursts. Automatically excluded from public totals.</li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">4. 47-County Sample Thresholds</h3>
                <p>
                    When viewing county-filtered breakdown standings, any county with fewer than 10 recorded votes displays a <strong>Low Sample Size Warning</strong> banner to inform readers of potential sampling volatility.
                </p>
            </div>
        </div>

        <div class="pt-4 text-center border-t border-slate-100">
            <a href="/" class="text-xs font-bold text-emerald-600 hover:underline">← Back to Dashboard</a>
        </div>
    </div>

</div>
