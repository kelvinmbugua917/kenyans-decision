<!-- FAQ & Trust Center Page - Kenyans Decision -->

<div class="max-w-4xl mx-auto space-y-8 py-4">

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-3">
        <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
            Trust & Transparency
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Frequently Asked Questions (FAQ)
        </h1>

        <p class="text-slate-600 text-sm leading-relaxed max-w-2xl">
            Everything you need to know about voting anonymity, data verification, anti-bot safeguards, and our independent non-partisan mandate.
        </p>
    </div>

    <!-- FAQ Accordion / Grid -->
    <div class="space-y-4">
        
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-2">
            <h3 class="font-extrabold text-slate-900 text-base">Is my vote really anonymous?</h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                Yes, 100%. Voting on Kenyans Decision requires no registration, phone number, national ID, or email address. When you cast a vote, our server computes a <strong>Keyed HMAC SHA-256 digest</strong> of your connection to prevent rapid duplicate votes. Your raw IP address is discarded immediately from server memory and is never written to a database.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-2">
            <h3 class="font-extrabold text-slate-900 text-base">How do you prevent people from voting 100 times?</h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                We use a combination of session cookies, first-party voter tokens, keyed HMAC IP digests, rate-limit windows, and automated risk scoring. Any votes originating from rapid automated bot scripts are assigned a <code>risk_score = 'blocked'</code> status and automatically excluded from public standings.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-2">
            <h3 class="font-extrabold text-slate-900 text-base">Is Kenyans Decision affiliated with IEBC or any political party?</h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                No. Kenyans Decision is a completely independent, self-funded civic technology platform. We have no affiliation with the Independent Electoral and Boundaries Commission (IEBC), any government ministry, or any political party in Kenya.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-2">
            <h3 class="font-extrabold text-slate-900 text-base">Can I create my own community poll or discussion topic?</h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                Yes! Any registered user can launch custom polls on national, regional, or local Kenyan issues or open civic discussion topics in our Civic Forum. Creating an account takes less than a minute and requires only a display name and password.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-2">
            <h3 class="font-extrabold text-slate-900 text-base">How does County-by-County breakdown work?</h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                Voters select their home county when casting a vote or setting up their profile. You can filter poll results by any of Kenya's 47 counties (e.g., Nairobi, Kisumu, Nakuru, Mombasa, Eldoret/Uasin Gishu, Kiambu, Nyeri, Garissa) to see how regional opinion differs across the country.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-2">
            <h3 class="font-extrabold text-slate-900 text-base">What is the "Results History & Momentum" feature?</h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                The Results History page allows you to track how public opinion shifts over time (e.g. Today vs. Yesterday vs. Last 7 Days vs. Since Launch). It provides clear trajectory data rather than just a single static snapshot.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-2">
            <h3 class="font-extrabold text-slate-900 text-base">How can media or researchers cite your data?</h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                Journalists, news outlets, and researchers are welcome to cite Kenyans Decision opinion statistics provided they attribute <strong>Kenyans Decision (kenyansdecision.online)</strong> and note that the statistics represent independent online web sample opinion.
            </p>
        </div>

    </div>

    <!-- Contact Banner -->
    <div class="bg-slate-900 text-slate-200 rounded-2xl p-6 text-center space-y-3">
        <h3 class="font-bold text-white text-base">Still have questions?</h3>
        <p class="text-xs text-slate-400 max-w-md mx-auto">Our editorial team is here to answer your questions regarding methodology, privacy, or policy disclosures.</p>
        <a href="/contact" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition-colors">
            Contact Us Today
        </a>
    </div>

</div>
