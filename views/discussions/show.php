<!-- Single Discussion Thread & Comments -->

<div class="space-y-6 max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between gap-2 mb-3">
            <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                <?= htmlspecialchars($discussion['category']) ?>
            </span>
            <span class="text-xs text-slate-400">
                <?= date('M j, Y - H:i', strtotime($discussion['createdAt'])) ?>
            </span>
        </div>

        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
            <?= htmlspecialchars($discussion['title']) ?>
        </h1>

        <div class="my-4 text-xs font-semibold text-slate-600 flex items-center gap-2">
            <span>By <strong><?= htmlspecialchars($discussion['authorName']) ?></strong></span>
        </div>

        <div class="prose prose-slate prose-sm text-slate-700 leading-relaxed border-t border-slate-100 pt-4">
            <?= nl2br(htmlspecialchars($discussion['content'])) ?>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
            <button id="like-btn" class="flex items-center gap-2 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 px-3.5 py-2 rounded-xl hover:bg-rose-100 transition-colors">
                <svg class="w-4 h-4 fill-rose-500" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                <span id="likes-count"><?= $discussion['likesCount'] ?></span> Likes
            </button>

            <span class="text-xs text-slate-400 font-semibold"><?= count($comments) ?> Comments</span>
        </div>
    </div>

    <!-- Comment Form -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
        <h3 class="font-bold text-slate-900 text-sm mb-3">Leave a Civic Comment</h3>

        <?php if (!empty($_SESSION['user'])): ?>
            <form id="add-comment-form" class="space-y-3">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <textarea name="content" required rows="3" placeholder="Share your insights respectfully..." class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500"></textarea>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-xs transition-colors">
                    Post Comment
                </button>
            </form>
        <?php else: ?>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-center text-xs text-slate-600">
                Please <a href="/login" class="font-bold text-emerald-600 hover:underline">sign in</a> or <a href="/register" class="font-bold text-emerald-600 hover:underline">register</a> to participate in discussions.
            </div>
        <?php endif; ?>
    </div>

    <!-- Comments List -->
    <div class="space-y-3">
        <h3 class="font-bold text-slate-900 text-sm">Discussion Thread (<?= count($comments) ?>)</h3>

        <?php foreach ($comments as $c): ?>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-2xs">
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="font-bold text-slate-900"><?= htmlspecialchars($c['authorName']) ?></span>
                    <span class="text-slate-400 text-[11px]"><?= date('M j, Y - H:i', strtotime($c['createdAt'])) ?></span>
                </div>
                <p class="text-xs text-slate-700 leading-relaxed">
                    <?= nl2br(htmlspecialchars($c['content'])) ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<script>
var likeBtn = document.getElementById('like-btn');
if (likeBtn) {
    likeBtn.addEventListener('click', async function() {
        try {
            var res = await fetch('/api/discussions/<?= htmlspecialchars($discussion['id']) ?>/like', { method: 'POST' });
            var data = await res.json();
            if (data.likesCount !== undefined) {
                var countElem = document.getElementById('likes-count');
                if (countElem) countElem.innerText = data.likesCount;
            }
            if (data.alreadyLiked) {
                likeBtn.classList.add('opacity-75', 'cursor-not-allowed', 'bg-rose-100');
                likeBtn.title = "You have already liked this topic";
                alert(data.message || 'You have already liked this topic.');
            } else if (data.success) {
                likeBtn.classList.add('bg-rose-100', 'border-rose-300');
            }
        } catch (err) {}
    });
}

var commentForm = document.getElementById('add-comment-form');
if (commentForm) {
    commentForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        var formData = new FormData(e.target);

        try {
            var res = await fetch('/api/discussions/<?= htmlspecialchars($discussion['id']) ?>/comments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': formData.get('csrf_token')
                },
                body: JSON.stringify({ content: formData.get('content') })
            });

            var data = await res.json();
            if (res.ok && data.success) {
                window.location.reload();
            } else {
                alert(data.error || 'Failed to post comment');
            }
        } catch (err) {
            alert('Network error');
        }
    });
}
</script>
