<!-- Privacy & Security Policy Disclosure -->

<div class="max-w-3xl mx-auto space-y-6 py-4">

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-4">
        <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
            Privacy & Security Disclosure
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Privacy Policy & Data Protection Guarantees
        </h1>

        <p class="text-slate-600 text-sm leading-relaxed">
            <strong>Kenyans Decision</strong> is an independent, non-governmental civic platform built to prioritize citizen privacy, anonymous participation, and transparent civic dialogue.
        </p>

        <hr class="border-slate-100">

        <div class="space-y-6 text-xs sm:text-sm text-slate-700 leading-relaxed">
            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">1. Anonymous Polling Architecture</h3>
                <p>
                    Participating in public opinion polls on Kenyans Decision does <strong>NOT</strong> require account registration, mobile phone numbers, national ID numbers, or email addresses.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">2. IP Address Privacy via Keyed HMAC Digests</h3>
                <p>
                    To mitigate duplicate voting and automated bot submission without storing raw IP addresses, the server generates a <strong>Keyed HMAC SHA-256 digest</strong> using a confidential server-side secret key. Raw IP addresses are discarded instantly in memory and are never written to permanent database storage.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">3. First-Party Vote Tokens</h3>
                <p>
                    A non-tracking, local browser token is stored on your device to prevent repeated voting in the same poll session. This token is strictly first-party and is never shared with third-party advertising or telemetry networks.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">4. User Account Data</h3>
                <p>
                    Creating an account is completely optional and only necessary if you wish to launch custom community polls or publish discussion threads. Account passwords are encrypted using industry-standard <code>password_hash()</code> bcrypt algorithms.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">5. Append-Only Administrative Audit Logs</h3>
                <p>
                    All administrative actions (such as setting featured polls or reviewing content reports) are recorded in an append-only, tamper-evident audit log with SHA-256 hash chaining to ensure full institutional transparency.
                </p>
            </div>
        </div>

        <div class="pt-4 text-center border-t border-slate-100">
            <a href="/" class="text-xs font-bold text-emerald-600 hover:underline">← Back to 2027 Dashboard</a>
        </div>
    </div>

</div>
