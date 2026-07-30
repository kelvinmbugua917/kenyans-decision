<!-- Poll Results History & Momentum Timeline View -->

<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header & Navigation -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between gap-3 mb-4">
            <a href="/polls/<?= htmlspecialchars($poll['id']) ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-emerald-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Live Poll & Voting</span>
            </a>
            <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                <?= htmlspecialchars($poll['category']) ?>
            </span>
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Results History & Momentum
        </h1>
        <p class="text-slate-600 text-sm mt-1">
            Tracking public opinion shift trajectory over time for: <strong class="text-slate-900"><?= htmlspecialchars($poll['title']) ?></strong>
        </p>

        <!-- Momentum Overview Cards -->
        <?php 
        $currentOpts = $historyData['history']['Today']['optionResults'] ?? [];
        $launchOpts = $historyData['history']['Since Launch']['optionResults'] ?? $currentOpts;
        $leader = $currentOpts[0] ?? null;

        // Calculate biggest gainer
        $biggestGainer = null;
        $maxGain = -999;
        foreach ($currentOpts as $curOpt) {
            $optId = $curOpt['optionId'];
            $launchPct = 0;
            foreach ($launchOpts as $lOpt) {
                if ($lOpt['optionId'] === $optId) {
                    $launchPct = $lOpt['percentage'];
                    break;
                }
            }
            $gain = $curOpt['percentage'] - $launchPct;
            if ($gain > $maxGain) {
                $maxGain = $gain;
                $biggestGainer = [
                    'name' => $curOpt['name'],
                    'party' => $curOpt['party'],
                    'gain' => round($gain, 1)
                ];
            }
        }
        ?>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-6 pt-6 border-t border-slate-100">
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Current Front-Runner</div>
                <div class="font-extrabold text-slate-900 text-sm truncate"><?= htmlspecialchars($leader['name'] ?? 'N/A') ?></div>
                <div class="text-xs font-semibold text-emerald-600 mt-0.5"><?= number_format($leader['percentage'] ?? 0, 1) ?>% Share</div>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Strongest Momentum</div>
                <div class="font-extrabold text-slate-900 text-sm truncate"><?= htmlspecialchars($biggestGainer['name'] ?? 'N/A') ?></div>
                <div class="text-xs font-semibold text-emerald-600 mt-0.5">+<?= number_format(max(0, $biggestGainer['gain'] ?? 0), 1) ?>% Shift Since Launch</div>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Total Verified Sample</div>
                <div class="font-extrabold text-slate-900 text-sm"><?= number_format($historyData['history']['Today']['totalVotes'] ?? 0) ?> Votes</div>
                <div class="text-xs text-slate-500 mt-0.5">Across 47 Kenyan Counties</div>
            </div>
        </div>
    </div>

    <!-- Timeline Snapshot Breakdown -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Historical Momentum Comparison</h2>
            <p class="text-xs text-slate-500 mt-0.5">Compare candidate percentages across key opinion audit checkpoints.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($historyData['history'] as $timeKey => $tfData): 
                $isToday = ($timeKey === 'Today');
            ?>
                <div class="bg-slate-50/80 rounded-2xl p-5 border <?= $isToday ? 'border-emerald-300 ring-2 ring-emerald-500/10' : 'border-slate-200' ?> space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            <span class="font-black text-slate-900 text-base"><?= htmlspecialchars($timeKey) ?></span>
                            <?php if ($isToday): ?>
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-300">Live Snapshot</span>
                            <?php endif; ?>
                        </div>
                        <span class="text-xs font-semibold text-slate-500"><?= number_format($tfData['totalVotes']) ?> votes</span>
                    </div>

                    <div class="space-y-3">
                        <?php foreach ($tfData['optionResults'] as $idx => $opt): ?>
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs font-bold">
                                    <span class="text-slate-900 truncate max-w-[180px]"><?= htmlspecialchars($opt['name']) ?></span>
                                    <span class="text-slate-900 font-black"><?= number_format($opt['percentage'], 1) ?>%</span>
                                </div>
                                <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-300" style="width: <?= $opt['percentage'] ?>%; background-color: <?= htmlspecialchars($opt['avatarColor']) ?>;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
