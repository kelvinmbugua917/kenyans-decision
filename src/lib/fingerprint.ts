// Browser fingerprinting & anti-abuse identifier helper
export function getBrowserFingerprint(): string {
  try {
    const STORAGE_KEY = 'kd_voter_fp_v1';
    let stored = localStorage.getItem(STORAGE_KEY);
    if (stored) return stored;

    const screenRes = `${window.screen.width}x${window.screen.height}x${window.screen.colorDepth}`;
    const tz = Intl.DateTimeFormat().resolvedOptions().timeZone || 'Africa/Nairobi';
    const lang = navigator.language || 'en-KE';
    const ua = navigator.userAgent;
    const rawSignal = `${screenRes}_${tz}_${lang}_${ua}_${Math.random().toString(36).substring(2, 8)}`;

    // Simple hash
    let hash = 0;
    for (let i = 0; i < rawSignal.length; i++) {
      const char = rawSignal.charCodeAt(i);
      hash = (hash << 5) - hash + char;
      hash |= 0;
    }
    const fp = `fp_ke_${Math.abs(hash).toString(36)}_${Date.now().toString(36)}`;
    localStorage.setItem(STORAGE_KEY, fp);
    return fp;
  } catch (err) {
    return 'fp_fallback_anon';
  }
}
