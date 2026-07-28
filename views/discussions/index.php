<!-- Civic Discussions Forum Listing -->

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Civic Discussions & Public Forum</h1>
            <p class="text-slate-600 text-xs sm:text-sm mt-1">Constructive debate on governance, economy, 2027 elections, and social policy.</p>
        </div>

        <?php if (!empty($_SESSION['user'])): ?>
            <button onclick="document.getElementById('new-topic-modal').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm px-4 py-2.5 rounded-xl shadow-xs transition-colors shrink-0">
                + New Discussion Topic
            </button>
        <?php else: ?>
            <a href="/login" class="bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm px-4 py-2.5 rounded-xl shadow-xs transition-colors shrink-0">
                Sign In to Post Topic
            </a>
        <?php endif; ?>
    </div>

    <!-- Category Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-semibold">
        <?php 
        $categories = ['All', '2027 Elections', 'Cost of Living', 'Healthcare', 'Governance & Corruption', 'Technology'];
        foreach ($categories as $cat): 
            $isActive = ($currentCategory ?? 'All') === $cat;
        ?>
            <a href="/discussions?category=<?= urlencode($cat) ?>" class="px-3.5 py-2 rounded-xl shrink-0 transition-colors <?= $isActive ? 'bg-slate-900 text-white font-bold' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' ?>">
                <?= $cat ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Threads Feed -->
    <div class="space-y-4">
        <?php foreach ($discussions as $d): ?>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs hover:border-emerald-500 transition-all">
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                        <?= htmlspecialchars($d['category']) ?>
                    </span>
                    <span class="text-xs text-slate-400">
                        <?= date('M j, Y', strtotime($d['createdAt'])) ?>
                    </span>
                </div>

                <h2 class="text-lg font-bold text-slate-900 mb-2">
                    <a href="/discussions/<?= htmlspecialchars($d['id']) ?>" class="hover:text-emerald-600 transition-colors">
                        <?= htmlspecialchars($d['title']) ?>
                    </a>
                </h2>

                <p class="text-slate-600 text-xs sm:text-sm line-clamp-3 mb-4">
                    <?= htmlspecialchars($d['content']) ?>
                </p>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100 text-xs font-semibold text-slate-500">
                    <span class="text-slate-700">By <strong><?= htmlspecialchars($d['authorName']) ?></strong></span>
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg> <?= $d['likesCount'] ?> Likes</span>
                        <a href="/discussions/<?= htmlspecialchars($d['id']) ?>" class="flex items-center gap-1.5 font-bold text-emerald-600 hover:text-emerald-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"/></svg>
                            <?= $d['commentsCount'] ?> Comments →
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- New Topic Modal -->
<div id="new-topic-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-900 text-lg">Start a New Discussion Thread</h3>
            <button onclick="document.getElementById('new-topic-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form id="new-topic-form" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Topic Title</label>
                <input type="text" name="title" required placeholder="e.g. Key priorities for agricultural support in North Rift" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Category</label>
                <select name="category" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500">
                    <option value="2027 Elections">2027 Elections</option>
                    <option value="Cost of Living">Cost of Living</option>
                    <option value="Healthcare">Healthcare</option>
                    <option value="Governance & Corruption">Governance & Corruption</option>
                    <option value="Technology">Technology</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Discussion Content</label>
                <textarea name="content" required rows="4" placeholder="Share your perspective and questions for fellow citizens..." class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-3 rounded-xl shadow-xs transition-colors">
                Publish Discussion Thread
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('new-topic-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);

    try {
        const res = await fetch('/api/discussions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': formData.get('csrf_token')
            },
            body: JSON.stringify({
                title: formData.get('title'),
                content: formData.get('content'),
                category: formData.get('category')
            })
        });

        const data = await res.json();
        if (res.ok && data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Failed to create topic');
        }
    } catch (err) {
        alert('Network error');
    }
});
</script>
