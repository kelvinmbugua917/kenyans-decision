<!-- Polls Listing View -->

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-black text-slate-900">All Public Opinion Polls</h1>
            <p class="text-slate-600 text-xs sm:text-sm mt-1">Participate in public opinion polls or filter by national priority topics.</p>
        </div>
        <a href="/register" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm px-4 py-2.5 rounded-xl shadow-xs transition-colors shrink-0">
            + Create New Poll
        </a>
    </div>

    <!-- Poll Category Filter Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none text-xs font-semibold">
        <?php 
        $categories = ['All', 'Elections & Politics', 'Cost of Living', 'Jobs & Economy', 'Healthcare', 'Technology', 'Governance & Corruption'];
        foreach ($categories as $cat): 
            $isActive = ($currentCategory ?? 'All') === $cat;
        ?>
            <a href="/polls?category=<?= urlencode($cat) ?>" class="px-3.5 py-2 rounded-xl shrink-0 transition-colors <?= $isActive ? 'bg-slate-900 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' ?>">
                <?= $cat ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Poll Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($polls as $p): ?>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col justify-between hover:border-emerald-500/80 transition-all">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            <?= htmlspecialchars($p['category']) ?>
                        </span>
                        <span class="text-xs font-semibold text-slate-500">
                            <?= number_format($p['totalVotes']) ?> votes
                        </span>
                    </div>

                    <h2 class="text-lg font-bold text-slate-900 mb-2">
                        <a href="/polls/<?= htmlspecialchars($p['id']) ?>" class="hover:text-emerald-600 transition-colors">
                            <?= htmlspecialchars($p['title']) ?>
                        </a>
                    </h2>

                    <p class="text-slate-600 text-xs sm:text-sm line-clamp-2 mb-4">
                        <?= htmlspecialchars($p['description']) ?>
                    </p>

                    <!-- Option list previews -->
                    <div class="space-y-1.5 mb-6">
                        <?php foreach (array_slice($p['options'], 0, 4) as $opt): ?>
                            <div class="flex items-center justify-between text-xs bg-slate-50 p-2 rounded-lg border border-slate-100">
                                <span class="font-semibold text-slate-800"><?= htmlspecialchars($opt['name']) ?></span>
                                <span class="text-slate-400 font-medium text-[11px]"><?= htmlspecialchars($opt['party']) ?></span>
                            </div>
                        <?php endforeach; ?>
                        <?php if (count($p['options']) > 4): ?>
                            <p class="text-[11px] text-slate-400 text-right italic">+ <?= count($p['options']) - 4 ?> more options</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500">By <?= htmlspecialchars($p['creator_name']) ?></span>
                    <a href="/polls/<?= htmlspecialchars($p['id']) ?>" class="font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                        <span>Cast Vote / View Breakdown</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
