<!-- Register View -->

<div class="max-w-md mx-auto my-12 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
    <div class="text-center mb-6">
        <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3">🇰🇪</div>
        <h1 class="text-xl font-black text-slate-900">Create Account & Polls</h1>
        <p class="text-xs text-slate-500 mt-1">Register to publish custom opinion polls and post in the civic forum.</p>
    </div>

    <div id="register-alert" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold"></div>

    <form id="register-form" class="space-y-4">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Display Name</label>
            <input type="text" name="displayName" required placeholder="e.g. Wanjiku Mwangi" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
            <input type="email" name="email" required placeholder="wanjiku@example.co.ke" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Home County</label>
            <select name="county" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500">
                <?php 
                $counties = ['Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Kiambu', 'Machakos', 'Uasin Gishu', 'Kilifi', 'Nyeri', 'Garissa', 'Kakamega', 'Meru', 'Bungoma', 'Kajiado', 'Kisii'];
                foreach ($counties as $c): ?>
                    <option value="<?= $c ?>"><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
            <input type="password" name="password" required minlength="6" placeholder="At least 6 characters" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500">
        </div>

        <button type="submit" id="register-btn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-3 rounded-xl shadow-xs transition-colors">
            Register Account
        </button>
    </form>

    <div class="mt-6 text-center text-xs text-slate-500">
        Already have an account? <a href="/login" class="font-bold text-emerald-600 hover:underline">Sign in here</a>
    </div>
</div>

<script>
document.getElementById('register-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const alertBox = document.getElementById('register-alert');
    const btn = document.getElementById('register-btn');

    btn.disabled = true;
    btn.innerText = 'Creating Account...';

    const formData = new FormData(e.target);

    try {
        const res = await fetch('/api/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                displayName: formData.get('displayName'),
                email: formData.get('email'),
                county: formData.get('county'),
                password: formData.get('password')
            })
        });

        const data = await res.json();
        if (res.ok && data.success) {
            window.location.href = '/';
        } else {
            alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200';
            alertBox.innerText = data.error || 'Registration failed.';
            alertBox.classList.remove('hidden');
            btn.disabled = false;
            btn.innerText = 'Register Account';
        }
    } catch (err) {
        alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200';
        alertBox.innerText = 'Network connection error.';
        alertBox.classList.remove('hidden');
        btn.disabled = false;
        btn.innerText = 'Register Account';
    }
});
</script>
