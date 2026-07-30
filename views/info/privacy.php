<!-- Privacy Policy & Data Protection Disclosure -->

<div class="max-w-4xl mx-auto space-y-6 py-4">

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
        <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
            Privacy & Security Disclosure
        </div>

        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Privacy Policy & Data Protection Guarantees
            </h1>
            <p class="text-slate-500 text-xs mt-1">Effective Date: July 30, 2026 | Compliant with Kenya Data Protection Act 2019 & GDPR</p>
        </div>

        <p class="text-slate-600 text-sm leading-relaxed">
            <strong>Kenyans Decision</strong> ("we", "our", "us") is dedicated to protecting citizen privacy while delivering transparent, un-manipulated public opinion tracking. This Privacy Policy details our data minimization practices, cryptographic IP digests, cookie disclosures, and advertising transparency.
        </p>

        <hr class="border-slate-100">

        <div class="space-y-6 text-xs sm:text-sm text-slate-700 leading-relaxed">
            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">1. Anonymous Voting & Data Minimization</h3>
                <p>
                    Participating in public opinion polls on Kenyans Decision does <strong>NOT</strong> require account creation, national ID numbers, mobile numbers (M-Pesa), or physical location tracking. Voting is designed to be completely anonymous to protect citizens from political intimidation or surveillance.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">2. Zero Raw IP Address Storage (Keyed HMAC SHA-256)</h3>
                <p>
                    To prevent malicious repeat voting and bot manipulation without compromising user privacy, our server processes connection IP addresses using a <strong>Keyed HMAC SHA-256 digest</strong> with a confidential server secret. The raw IP address is discarded immediately in memory and is never written to disk or database storage.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">3. Cookies & First-Party Voter Tokens</h3>
                <p>
                    We store a non-tracking, random first-party cookie token (<code>kd_voter_token</code>) on your browser. This token helps prevent accidental duplicate votes within the same poll session. For full details on cookie categories, see our <a href="/cookies" class="text-emerald-600 font-bold hover:underline">Cookie Policy</a>.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">4. Third-Party Advertising & Google AdSense Disclosures</h3>
                <p>
                    We may display non-intrusive third-party advertisements, such as Google AdSense, to support platform server costs.
                </p>
                <ul class="list-disc list-inside mt-2 space-y-1 text-slate-700">
                    <li>Third-party vendors, including Google, use cookies to serve ads based on prior visits to our website or other websites on the Internet.</li>
                    <li>Google's use of advertising cookies enables it and its partners to serve ads to users based on their visit to our site and/or other sites on the Internet.</li>
                    <li>Users may opt out of personalized advertising by visiting <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener" class="text-emerald-600 font-bold hover:underline">Google Ads Settings</a>.</li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">5. User Accounts & Registered Data</h3>
                <p>
                    Registering an account is optional and only required if you wish to post custom community polls or launch discussion topics. Registered account passwords are encrypted using strong bcrypt password hashing (<code>password_hash()</code>). We never sell, rent, or lease registered user information to third parties.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-base mb-2">6. Compliance with Kenyan & International Privacy Laws</h3>
                <p>
                    Our data management practices strictly adhere to the <strong>Data Protection Act of Kenya (2019)</strong> and international General Data Protection Regulations (GDPR). Users retain full rights to request deletion of their registered user account by contacting our data protection officer at <a href="/contact" class="text-emerald-600 font-bold hover:underline">Contact Us</a>.
                </p>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-between border-t border-slate-100 text-xs font-bold text-emerald-600">
            <a href="/terms" class="hover:underline">Terms of Service →</a>
            <a href="/cookies" class="hover:underline">Cookie Policy →</a>
            <a href="/contact" class="hover:underline">Contact Data Protection Officer →</a>
        </div>
    </div>

</div>
