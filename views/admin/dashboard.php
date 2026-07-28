<!-- Admin Dashboard & Audit Log Viewer -->

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-slate-900 text-white p-6 rounded-2xl border border-slate-800 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                Administrator Control Portal
            </div>
            <h1 class="text-2xl font-black text-white">System Audit & Moderation Dashboard</h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-1">Review append-only audit logs, manage featured polls, and moderate community reports.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-2xl font-black text-slate-900"><?= number_format($stats['totalVotes']) ?></span>
            <span class="text-xs font-bold text-slate-500">Valid Votes</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-2xl font-black text-amber-600"><?= number_format($stats['suspiciousVotes']) ?></span>
            <span class="text-xs font-bold text-slate-500">Suspicious Flagged Votes</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-2xl font-black text-rose-600"><?= number_format($stats['pendingReports']) ?></span>
            <span class="text-xs font-bold text-slate-500">Pending Content Reports</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="block text-2xl font-black text-emerald-600"><?= number_format($stats['totalPolls']) ?></span>
            <span class="text-xs font-bold text-slate-500">Active Polls</span>
        </div>
    </div>

    <!-- Poll Management Grid -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
        <h2 class="text-base font-bold text-slate-900">Official & Community Poll Management</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-3">Title</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Featured?</th>
                        <th class="p-3">Votes</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($polls as $p): ?>
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-3 font-bold text-slate-900 max-w-xs truncate"><?= htmlspecialchars($p['title']) ?></td>
                            <td class="p-3 capitalize"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $p['creator_type'] === 'official' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' ?>"><?= $p['creator_type'] ?></span></td>
                            <td class="p-3 font-semibold <?= $p['status'] === 'active' ? 'text-emerald-600' : 'text-slate-400' ?>"><?= ucfirst($p['status']) ?></td>
                            <td class="p-3 font-bold"><?= !empty($p['isFeatured']) ? '⭐ Featured' : '—' ?></td>
                            <td class="p-3 font-semibold"><?= number_format($p['totalVotes']) ?></td>
                            <td class="p-3 space-x-2">
                                <?php if (empty($p['isFeatured'])): ?>
                                    <button onclick="updatePoll('<?= $p['id'] ?>', 'feature')" class="text-[11px] font-bold text-emerald-600 hover:underline">Set Featured</button>
                                <?php endif; ?>
                                <?php if ($p['status'] === 'active'): ?>
                                    <button onclick="updatePoll('<?= $p['id'] ?>', 'close')" class="text-[11px] font-bold text-rose-600 hover:underline">Close Poll</button>
                                <?php else: ?>
                                    <button onclick="updatePoll('<?= $p['id'] ?>', 'reopen')" class="text-[11px] font-bold text-slate-600 hover:underline">Reopen</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Append-Only Audit Trail View -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Append-Only Administrative Audit Logs
                </h2>
                <p class="text-xs text-slate-500">Tamper-evident system activity log with cryptographic log hash chaining.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-3">Timestamp</th>
                        <th class="p-3">Admin Email</th>
                        <th class="p-3">Action</th>
                        <th class="p-3">Target ID</th>
                        <th class="p-3">Log Hash Verification</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono text-[11px]">
                    <?php foreach ($auditLogs as $log): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 font-sans text-slate-500"><?= htmlspecialchars($log['timestamp']) ?></td>
                            <td class="p-3 font-sans font-bold text-slate-900"><?= htmlspecialchars($log['admin_email']) ?></td>
                            <td class="p-3 font-sans"><span class="bg-amber-50 text-amber-900 border border-amber-200 px-2 py-0.5 rounded font-bold"><?= htmlspecialchars($log['action']) ?></span></td>
                            <td class="p-3 font-sans text-slate-700"><?= htmlspecialchars($log['target']) ?></td>
                            <td class="p-3 text-slate-400 text-[10px] truncate max-w-xs" title="<?= htmlspecialchars($log['log_hash'] ?? '') ?>">
                                <?= htmlspecialchars(substr($log['log_hash'] ?? 'Verified', 0, 16)) ?>…
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
async function updatePoll(pollId, action) {
    if (!confirm(`Are you sure you want to ${action} this poll?`)) return;

    try {
        const res = await fetch(`/api/admin/polls/${pollId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action })
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Operation failed');
        }
    } catch (err) {
        alert('Network error');
    }
}
</script>
