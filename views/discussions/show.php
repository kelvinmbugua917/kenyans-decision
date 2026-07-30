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

        <?php 
            $discCookie = 'kd_liked_' . preg_replace('/[^a-zA-Z0-9_]/', '', $discussion['id']);
            $isLiked = !empty($_SESSION['liked_discussions'][$discussion['id']]) || isset($_COOKIE[$discCookie]);
        ?>
        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
            <button id="like-btn" data-liked="<?= $isLiked ? 'true' : 'false' ?>" class="flex items-center gap-2 text-xs font-bold transition-all px-3.5 py-2 rounded-xl border <?= $isLiked ? 'text-rose-700 bg-rose-100 border-rose-300 shadow-2xs' : 'text-slate-700 bg-slate-50 border-slate-200 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200' ?>">
                <svg class="w-4 h-4 <?= $isLiked ? 'fill-rose-600 text-rose-600' : 'fill-none stroke-current' ?>" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span id="likes-count"><?= $discussion['likesCount'] ?></span> <span id="like-label"><?= $isLiked ? 'Liked' : 'Like' ?></span>
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
            var labelElem = document.getElementById('like-label');
            var svgElem = likeBtn.querySelector('svg');
            
            if (data.liked) {
                likeBtn.setAttribute('data-liked', 'true');
                likeBtn.className = "flex items-center gap-2 text-xs font-bold transition-all px-3.5 py-2 rounded-xl border text-rose-700 bg-rose-100 border-rose-300 shadow-2xs";
                if (labelElem) labelElem.innerText = 'Liked';
                if (svgElem) svgElem.className.baseVal = "w-4 h-4 fill-rose-600 text-rose-600";
            } else {
                likeBtn.setAttribute('data-liked', 'false');
                likeBtn.className = "flex items-center gap-2 text-xs font-bold transition-all px-3.5 py-2 rounded-xl border text-slate-700 bg-slate-50 border-slate-200 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200";
                if (labelElem) labelElem.innerText = 'Like';
                if (svgElem) svgElem.className.baseVal = "w-4 h-4 fill-none stroke-current";
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
