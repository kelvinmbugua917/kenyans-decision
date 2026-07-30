<!-- Single Poll View & County Results Analysis -->

<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Poll Header -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 mb-3">
            <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                <?= htmlspecialchars($poll['category']) ?>
            </span>
            <div class="flex items-center gap-3 text-xs">
                <a href="https://api.whatsapp.com/send?text=<?= urlencode('I just voted on Kenyans Decision 🇰🇪: "' . ($poll['title'] ?? '') . '". See live results and vote: ' . (($_SERVER['HTTPS'] ?? 'off') === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/polls/' . ($poll['id'] ?? '')) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 font-bold text-emerald-800 bg-emerald-100 hover:bg-emerald-200 px-3 py-1 rounded-lg border border-emerald-300 transition-colors">
                    <svg class="w-3.5 h-3.5 fill-current text-[#25D366]" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Share on WhatsApp</span>
                </a>
                <span class="text-slate-500">
                    Total Valid Votes: <strong><?= number_format($results['totalVotes']) ?></strong>
                </span>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            <?= htmlspecialchars($poll['title']) ?>
        </h1>
        <p class="text-slate-600 text-sm mt-2">
            <?= htmlspecialchars($poll['description']) ?>
        </p>

        <!-- Voting Form -->
        <form id="poll-show-form" class="mt-6 space-y-3">
            <input type="hidden" name="pollId" value="<?= htmlspecialchars($poll['id']) ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div class="space-y-2">
                <?php foreach ($poll['options'] as $opt): 
                    $isSelected = ($selectedOptionId ?? '') === $opt['id'];
                ?>
                    <label class="flex items-center p-3.5 rounded-xl border <?= $isSelected ? 'border-emerald-600 bg-emerald-50/50' : 'border-slate-200 hover:border-emerald-400' ?> cursor-pointer transition-all">
                        <input type="radio" name="optionId" value="<?= htmlspecialchars($opt['id']) ?>" <?= $isSelected ? 'checked' : '' ?> class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                        <div class="ml-3 flex-1 min-w-0 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($opt['name']) ?></span>
                                <p class="text-xs text-slate-500"><?= htmlspecialchars($opt['party']) ?></p>
                            </div>
                            <span class="w-3 h-3 rounded-full" style="background-color: <?= htmlspecialchars($opt['avatarColor']) ?>;"></span>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-700">County:</label>
                    <select name="county" class="text-xs font-semibold bg-white border border-slate-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-emerald-500">
                        <?php 
                        $counties = ['Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Kiambu', 'Machakos', 'Uasin Gishu', 'Kilifi', 'Nyeri', 'Garissa', 'Kakamega', 'Meru', 'Bungoma', 'Kajiado', 'Kisii'];
                        foreach ($counties as $c): ?>
                            <option value="<?= $c ?>"><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" id="poll-show-btn" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-xs transition-colors">
                    <?= !empty($hasVoted) ? 'Change Vote Choice' : 'Submit Anonymous Vote' ?>
                </button>
            </div>
        </form>

        <div id="show-alert" class="hidden mt-4 p-3.5 rounded-xl text-xs font-semibold"></div>
    </div>

    <!-- Live Aggregated Results & County Filter Analysis -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <h2 class="text-lg font-bold text-slate-900">National & County Poll Standings</h2>
                <p class="text-xs text-slate-500">Filter standings by specific Kenyan county or view national totals.</p>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-xs font-bold text-slate-700">Filter Breakdown:</label>
                <select id="county-filter" class="text-xs font-bold bg-slate-100 border border-slate-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-emerald-500">
                    <option value="ALL">All Counties (National Total)</option>
                    <?php foreach (array_keys($results['countyBreakdown'] ?? []) as $cName): ?>
                        <option value="<?= htmlspecialchars($cName) ?>"><?= htmlspecialchars($cName) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Sample Size Warning Container for County Breakdown -->
        <div id="sample-warning" class="hidden bg-amber-50 border border-amber-200 text-amber-900 p-3.5 rounded-xl text-xs flex items-center gap-2 font-medium">
            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span><strong>Low Sample Size Warning:</strong> This county currently has fewer than 10 verified votes recorded. Percentages may fluctuate significantly.</span>
        </div>

        <!-- Dynamic Results List -->
        <div id="results-container" class="space-y-4">
            <?php foreach ($results['optionResults'] as $res): ?>
                <div class="option-row" data-id="<?= htmlspecialchars($res['optionId']) ?>">
                    <div class="flex items-center justify-between text-xs font-bold mb-1">
                        <span class="text-slate-900"><?= htmlspecialchars($res['name']) ?> <span class="text-slate-400 font-normal">(<?= htmlspecialchars($res['party']) ?>)</span></span>
                        <span class="text-slate-900 option-pct-label"><?= number_format($res['percentage'], 1) ?>% <span class="text-slate-400 font-normal option-votes-label">(<?= number_format($res['votes']) ?> votes)</span></span>
                    </div>
                    <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                        <div class="option-bar h-full rounded-full transition-all duration-500" style="width: <?= $res['percentage'] ?>%; background-color: <?= htmlspecialchars($res['avatarColor']) ?>;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
var rawResults = <?= json_encode($results) ?>;

var countyFilter = document.getElementById('county-filter');
if (countyFilter) {
    countyFilter.addEventListener('change', function(e) {
        var county = e.target.value;
        var warning = document.getElementById('sample-warning');
        var container = document.getElementById('results-container');

        var totalCountyVotes = 0;
        var countsMap = {};

        if (county === 'ALL') {
            if (warning) warning.classList.add('hidden');
            window.location.reload();
            return;
        }

        var cData = (rawResults.countyBreakdown && rawResults.countyBreakdown[county]) || {};
        for (var optId in cData) {
            countsMap[optId] = cData[optId];
            totalCountyVotes += cData[optId];
        }

        if (totalCountyVotes < 10) {
            if (warning) warning.classList.remove('hidden');
        } else {
            if (warning) warning.classList.add('hidden');
        }

        if (rawResults.optionResults && container) {
            rawResults.optionResults.forEach(function(opt) {
                var row = container.querySelector('[data-id="' + opt.optionId + '"]');
                if (!row) return;

                var votes = countsMap[opt.optionId] || 0;
                var pct = totalCountyVotes > 0 ? ((votes / totalCountyVotes) * 100).toFixed(1) : '0.0';

                var pctLabel = row.querySelector('.option-pct-label');
                if (pctLabel) {
                    pctLabel.innerHTML = pct + '% <span class="text-slate-400 font-normal">(' + votes + ' votes)</span>';
                }
                var bar = row.querySelector('.option-bar');
                if (bar) {
                    bar.style.width = pct + '%';
                }
            });
        }
    });
}

var pollShowForm = document.getElementById('poll-show-form');
if (pollShowForm) {
    pollShowForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        var form = e.target;
        var btn = document.getElementById('poll-show-btn');
        var alertBox = document.getElementById('show-alert');

        var formData = new FormData(form);
        var selectedOption = formData.get('optionId');

        if (!selectedOption) {
            alertBox.className = 'mt-4 p-3.5 rounded-xl text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200';
            alertBox.innerText = 'Please select an option before submitting.';
            alertBox.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = 'Submitting...';

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
                alertBox.innerText = data.message || 'Vote recorded!';
                alertBox.classList.remove('hidden');
                
                var pTitle = "<?= !empty($poll['title']) ? addslashes(htmlspecialchars($poll['title'])) : 'Public Opinion Poll' ?>";
                var pUrl = window.location.href;
                if (typeof openWhatsAppPostVoteModal === 'function') {
                    setTimeout(function() {
                        openWhatsAppPostVoteModal(pTitle, pUrl);
                    }, 400);
                } else {
                    setTimeout(function() { window.location.reload(); }, 1200);
                }
            } else {
                alertBox.className = 'mt-4 p-3.5 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200';
                alertBox.innerText = data.error || 'Failed to submit vote.';
                alertBox.classList.remove('hidden');
                btn.disabled = false;
            }
        } catch (err) {
            alertBox.className = 'mt-4 p-3.5 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200';
            alertBox.innerText = 'Network error.';
            alertBox.classList.remove('hidden');
            btn.disabled = false;
        }
    });
}
</script>
