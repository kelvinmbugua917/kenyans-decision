<!-- Login View -->

<div class="max-w-md mx-auto my-12 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
    <div class="text-center mb-6">
        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center p-2.5 mx-auto mb-3">
            <svg class="w-full h-full text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <h1 class="text-xl font-black text-slate-900">Sign In to Kenyans Decision</h1>
        <p class="text-xs text-slate-500 mt-1">Manage public opinion polls and participate in civic discussions.</p>
    </div>

    <div id="auth-alert" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold"></div>

    <form id="login-form" class="space-y-4">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
            <input type="email" name="email" required placeholder="your.email@example.co.ke" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
            <input type="password" name="password" required placeholder="••••••••" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500">
        </div>

        <button type="submit" id="login-btn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-3 rounded-xl shadow-xs transition-colors">
            Sign In
        </button>
    </form>

    <div class="mt-6 text-center text-xs text-slate-500">
        Don't have an account yet? <a href="/register" class="font-bold text-emerald-600 hover:underline">Register here</a>
    </div>

    <div class="mt-4 p-3 bg-slate-50 rounded-xl border border-slate-200 text-[11px] text-slate-500 text-center">
        <strong>Demo Admin Credentials:</strong><br>
        <code>admin@kenyansdecision.co.ke</code> / <code>AdminPassword2027!</code>
    </div>
</div>

<script>
var loginForm = document.getElementById('login-form');
if (loginForm) {
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        var alertBox = document.getElementById('auth-alert');
        var btn = document.getElementById('login-btn');

        btn.disabled = true;
        btn.innerText = 'Signing In...';

        var formData = new FormData(e.target);

        try {
            var res = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    email: formData.get('email'),
                    password: formData.get('password')
                })
            });

            var data = await res.json();
            if (res.ok && data.success) {
                window.location.href = data.user.role === 'admin' ? '/admin' : '/';
            } else {
                alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200';
                alertBox.innerText = data.error || 'Authentication failed.';
                alertBox.classList.remove('hidden');
                btn.disabled = false;
                btn.innerText = 'Sign In';
            }
        } catch (err) {
            alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200';
            alertBox.innerText = 'Network connection error.';
            alertBox.classList.remove('hidden');
            btn.disabled = false;
            btn.innerText = 'Sign In';
        }
    });
}
</script>
