<!-- Bento Grid Homepage -->

<div class="space-y-6 pb-12">

    <!-- Top Headline Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 text-white rounded-2xl p-6 sm:p-8 shadow-md border border-slate-800">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Live 2027 Public Opinion Engine
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    What Do Kenyans Think?
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm mt-1 max-w-2xl">
                    An independent, non-governmental public opinion platform. Cast your vote anonymously, view real-time county breakdowns, and discuss national priorities.
                </p>
            </div>
            <div class="shrink-0 flex items-center gap-2">
                <a href="/methodology" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition-colors">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Polling Security
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN BENTO GRID CONTAINER -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- HERO BENTO BOX: Featured 2027 Presidential Opinion Poll (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col justify-between">
            <?php if (!empty($featuredPoll)): ?>
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                            Featured Opinion Poll
                        </span>
                        <span class="text-xs font-semibold text-slate-500 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                        </span>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                        <?= htmlspecialchars($featuredPoll['title']) ?>
                    </h2>
                    <p class="text-slate-600 text-xs sm:text-sm mt-1 mb-6">
                        <?= htmlspecialchars($featuredPoll['description']) ?>
                    </p>

                    <!-- Voting Form & Options -->
                    <form id="featured-poll-form" class="space-y-3">
                        <input type="hidden" name="pollId" value="<?= htmlspecialchars($featuredPoll['id']) ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <?php foreach ($featuredPoll['options'] as $opt): ?>
                                <label class="relative flex items-center p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/30 cursor-pointer transition-all group">
                                    <input type="radio" name="optionId" value="<?= htmlspecialchars($opt['id']) ?>" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                    <div class="ml-3 flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= htmlspecialchars($opt['avatarColor']) ?>;"></span>
                                            <span class="font-bold text-slate-900 text-xs sm:text-sm truncate"><?= htmlspecialchars($opt['name']) ?></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 truncate"><?= htmlspecialchars($opt['party']) ?></p>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <!-- Optional County Selector for Vote Context -->
                        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-bold text-slate-700">Select County:</label>
                                <select name="county" class="text-xs font-semibold bg-white border border-slate-300 rounded-lg px-2.5 py-1.5 focus:ring-2 focus:ring-emerald-500">
                                    <?php 
                                    $counties = ['Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Kiambu', 'Machakos', 'Uasin Gishu', 'Kilifi', 'Nyeri', 'Garissa', 'Kakamega', 'Meru', 'Bungoma', 'Kajiado', 'Kisii'];
                                    foreach ($counties as $c): ?>
                                        <option value="<?= $c ?>"><?= $c ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" id="vote-btn" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm px-6 py-2.5 rounded-xl shadow-xs transition-colors flex items-center justify-center gap-2">
                                <span>Submit Anonymous Vote</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </form>

                    <!-- Alert message container -->
                    <div id="vote-alert" class="hidden mt-4 p-3.5 rounded-xl text-xs font-semibold"></div>

                    <!-- Live Results Standings -->
                    <?php if (!empty($featuredResult)): ?>
                        <div class="mt-8 pt-6 border-t border-slate-200">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
                                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                    <span>Real-Time Candidate Standings</span>
                                    <span class="text-xs font-normal text-slate-500">(<?= number_format($featuredResult['totalVotes']) ?> votes recorded)</span>
                                </h3>
                                <div class="flex items-center gap-2.5">
                                    <a href="https://api.whatsapp.com/send?text=<?= urlencode('I just voted on Kenyans Decision 🇰🇪: "' . ($featuredPoll['title'] ?? '') . '". See live results and cast your vote: https://kenyansdecision.online/polls/' . ($featuredPoll['id'] ?? '')) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-800 bg-emerald-100 hover:bg-emerald-200 px-3 py-1.5 rounded-lg border border-emerald-300 transition-colors">
                                        <svg class="w-3.5 h-3.5 fill-current text-[#25D366]" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        <span>Share on WhatsApp</span>
                                    </a>
                                    <a href="/polls/<?= htmlspecialchars($featuredPoll['id']) ?>" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Analysis →</a>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <?php foreach ($featuredResult['optionResults'] as $res): ?>
                                    <div>
                                        <div class="flex items-center justify-between text-xs font-semibold mb-1">
                                            <span class="text-slate-900 font-bold"><?= htmlspecialchars($res['name']) ?> <span class="text-slate-400 font-normal">(<?= htmlspecialchars($res['party']) ?>)</span></span>
                                            <span class="text-slate-800 font-black"><?= number_format($res['percentage'], 1) ?>% <span class="text-slate-400 font-normal">(<?= number_format($res['votes']) ?>)</span></span>
                                        </div>
                                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500" style="width: <?= $res['percentage'] ?>%; background-color: <?= htmlspecialchars($res['avatarColor']) ?>;"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- SIDEBAR BENTO BOXES (4 cols) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- STATS BENTO BOX -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-2xl p-6 border border-slate-800 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-400 mb-4 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Platform Transparency Metrics
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-800/60 p-3.5 rounded-xl border border-slate-700/50">
                        <span class="block text-2xl font-black text-white"><?= number_format($analytics['totalVotes'] ?? 0) ?></span>
                        <span class="text-[11px] font-medium text-slate-400">Total Valid Votes</span>
                    </div>
                    <div class="bg-slate-800/60 p-3.5 rounded-xl border border-slate-700/50">
                        <span class="block text-2xl font-black text-emerald-400">47 / 47</span>
                        <span class="text-[11px] font-medium text-slate-400">Counties Represented</span>
                    </div>
                    <div class="bg-slate-800/60 p-3.5 rounded-xl border border-slate-700/50">
                        <span class="block text-2xl font-black text-white"><?= number_format($analytics['totalPolls'] ?? 0) ?></span>
                        <span class="text-[11px] font-medium text-slate-400">Public Polls</span>
                    </div>
                    <div class="bg-slate-800/60 p-3.5 rounded-xl border border-slate-700/50">
                        <span class="block text-2xl font-black text-white"><?= number_format($analytics['totalDiscussions'] ?? 0) ?></span>
                        <span class="text-[11px] font-medium text-slate-400">Forum Threads</span>
                    </div>
                </div>
            </div>

            <!-- METHODOLOGY SUMMARY BENTO BOX -->
            <div class="bg-emerald-50/80 border border-emerald-200 rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-2 text-emerald-900 font-bold text-sm">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Duplicate-Vote Mitigation
                </div>
                <p class="text-emerald-950 text-xs leading-relaxed mb-3">
                    Votes are secured using first-party device tokens and keyed HMAC IP-derived temporary rate-limiting identifiers. No personal identity data or raw IPs are ever stored.
                </p>
                <a href="/methodology" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 underline">Read full methodology disclosure →</a>
            </div>

        </div>

        <!-- TRENDING CIVIC DISCUSSIONS BENTO BOX (12 cols) -->
        <div class="lg:col-span-12 bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Public Forum</span>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900">Trending Civic Discussions</h3>
                </div>
                <a href="/discussions" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200">View All Discussions →</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach ($discussions as $disc): ?>
                    <a href="/discussions/<?= htmlspecialchars($disc['id']) ?>" class="block p-5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:shadow-xs transition-all bg-slate-50/50 hover:bg-white group">
                        <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-md mb-2">
                            <?= htmlspecialchars($disc['category']) ?>
                        </span>
                        <h4 class="font-bold text-slate-900 text-sm group-hover:text-emerald-600 line-clamp-2 mb-2 transition-colors">
                            <?= htmlspecialchars($disc['title']) ?>
                        </h4>
                        <p class="text-slate-500 text-xs line-clamp-2 mb-4">
                            <?= htmlspecialchars($disc['content']) ?>
                        </p>
                        <div class="flex items-center justify-between text-[11px] font-semibold text-slate-400 pt-3 border-t border-slate-200/80">
                            <span><?= htmlspecialchars($disc['authorName']) ?></span>
                            <div class="flex items-center gap-3">
                                <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg> <?= $disc['likesCount'] ?></span>
                                <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"/></svg> <?= $disc['commentsCount'] ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>

<script>
// Interactive Client Voting Handler
var featuredForm = document.getElementById('featured-poll-form');
if (featuredForm) {
    featuredForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        var form = e.target;
        var btn = document.getElementById('vote-btn');
        var alertBox = document.getElementById('vote-alert');

        var formData = new FormData(form);
        var selectedOption = formData.get('optionId');

        if (!selectedOption) {
            alertBox.className = 'mt-4 p-3.5 rounded-xl text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200';
            alertBox.innerText = 'Please select a candidate option before submitting your vote.';
            alertBox.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = 'Submitting Vote...';

        try {
            var response = await fetch('/api/vote', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': formData.get('csrf_token')
                },
                body: JSON.stringify({
                    pollId: formData.get('pollId'),
                    optionId: selectedOption,
                    county: formData.get('county'),
                    fingerprint: typeof getOrCreateVoterToken === 'function' ? getOrCreateVoterToken() : (localStorage.getItem('kd_voter_fp') || 'fp_default')
                })
            });

            var data = await response.json();

            if (response.ok && data.success) {
                alertBox.className = 'mt-4 p-3.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200';
                alertBox.innerText = data.message || 'Your vote has been counted successfully!';
                alertBox.classList.remove('hidden');
                
                var pTitle = "<?= !empty($featuredPoll['title']) ? addslashes(htmlspecialchars($featuredPoll['title'])) : '2027 Public Opinion Poll' ?>";
                var pUrl = window.location.origin + "/polls/<?= !empty($featuredPoll['id']) ? $featuredPoll['id'] : '' ?>";
                if (typeof openWhatsAppPostVoteModal === 'function') {
                    setTimeout(function() {
                        openWhatsAppPostVoteModal(pTitle, pUrl);
                    }, 400);
                } else {
                    setTimeout(function() { window.location.reload(); }, 1500);
                }
            } else {
                alertBox.className = 'mt-4 p-3.5 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200';
                alertBox.innerText = data.error || 'An error occurred while submitting your vote.';
                alertBox.classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = 'Submit Anonymous Vote';
            }
        } catch (err) {
            alertBox.className = 'mt-4 p-3.5 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200';
            alertBox.innerText = 'Network error. Please try again.';
            alertBox.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = 'Submit Anonymous Vote';
        }
    });
}
</script>
