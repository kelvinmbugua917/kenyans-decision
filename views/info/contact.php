<!-- Contact Us & Editorial Office Page -->

<div class="max-w-4xl mx-auto space-y-8 py-4">

    <!-- Header Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-3">
        <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
            Get In Touch
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Contact Editorial & Policy Team
        </h1>

        <p class="text-slate-600 text-sm leading-relaxed max-w-2xl">
            Have questions about our polling methodology, want to suggest a national opinion poll topic, or need to reach our press office? Send us a message below.
        </p>
    </div>

    <?php if (!empty($success)): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl p-6 text-sm font-semibold flex items-center gap-3">
            <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="font-bold text-base">Message Received!</p>
                <p class="text-xs text-emerald-700 mt-0.5">Thank you for reaching out to Kenyans Decision. Our team will review your inquiry and respond shortly.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl p-4 text-xs font-semibold">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Contact Info Cards -->
        <div class="space-y-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">General & Media Inquiries</h4>
                <p class="text-slate-600 text-xs">contact@kenyansdecision.online</p>
                <p class="text-slate-500 text-[11px]">Press releases, methodology inquiries, policy questions.</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                </div>
                <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Location Reference</h4>
                <p class="text-slate-600 text-xs font-semibold">Nairobi, Kenya</p>
                <p class="text-slate-500 text-[11px]">Serving citizens across all 47 counties of Kenya.</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Response Window</h4>
                <p class="text-slate-600 text-xs">Monday – Friday, 8am – 5pm EAT</p>
                <p class="text-slate-500 text-[11px]">Inquiries reviewed within 24-48 hours.</p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="md:col-span-2 bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Send Us a Direct Message</h3>

            <form action="/contact" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Wanjiku Odhiambo" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required placeholder="name@example.co.ke" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Inquiry Subject</label>
                    <select name="subject" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 font-semibold text-slate-700">
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Poll Topic Suggestion">Suggest a New Poll Topic</option>
                        <option value="Methodology Question">Polling Methodology Question</option>
                        <option value="Media & Press">Media & Press Relations</option>
                        <option value="Content Report">Content Moderation & Policy Report</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Your Message <span class="text-rose-500">*</span></label>
                    <textarea name="message" required rows="5" placeholder="Write your message or topic suggestion in detail..." class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-3 rounded-xl shadow-xs transition-colors">
                    Send Message
                </button>
            </form>
        </div>

    </div>

</div>
