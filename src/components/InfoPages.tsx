import React from 'react';
import { ShieldCheck, Info, FileText, Lock, CheckCircle2 } from 'lucide-react';

export const AboutPage: React.FC = () => {
  return (
    <div className="max-w-3xl mx-auto space-y-6">
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
        <div className="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-bold text-xl">
          🇰🇪
        </div>
        <h1 className="text-3xl font-black text-slate-900 tracking-tight">
          About Kenyans Decision
        </h1>
        <p className="text-slate-600 text-sm sm:text-base leading-relaxed font-medium">
          <strong>Kenyans Decision</strong> is an independent, non-governmental public opinion and discussion platform built for Kenyans to share their perspectives, explore what fellow citizens think, and participate in constructive civic conversations.
        </p>

        <div className="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 text-xs sm:text-sm font-medium space-y-2">
          <div className="flex items-center gap-2 font-bold text-emerald-900">
            <ShieldCheck className="w-5 h-5 text-emerald-700" />
            Official Independence Declaration
          </div>
          <p className="leading-relaxed">
            Kenyans Decision is not affiliated with, endorsed by, or funded by the Government of Kenya, the Independent Electoral and Boundaries Commission (IEBC), or any political party.
          </p>
        </div>

        <div className="space-y-4 text-xs sm:text-sm text-slate-600 pt-2">
          <h3 className="text-base font-bold text-slate-900">Core Principles</h3>
          <ul className="space-y-2">
            <li className="flex items-start gap-2">
              <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
              <span><strong>Frictionless Anonymous Voting:</strong> Anyone can express their opinion in public polls in under 10 seconds without needing an account.</span>
            </li>
            <li className="flex items-start gap-2">
              <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
              <span><strong>Strict Non-Partisanship:</strong> All political candidates and options are displayed with neutral, equal visual treatment.</span>
            </li>
            <li className="flex items-start gap-2">
              <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
              <span><strong>Anti-Abuse Transparency:</strong> Layered technical signals filter bot manipulation without burdening real users with intrusive verification.</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  );
};

export const PrivacyPage: React.FC = () => {
  return (
    <div className="max-w-3xl mx-auto space-y-6">
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-4 text-slate-600 text-xs sm:text-sm leading-relaxed">
        <div className="flex items-center gap-2 text-emerald-700 font-bold text-xs uppercase tracking-wider">
          <Lock className="w-4 h-4" />
          Data & Privacy
        </div>
        <h1 className="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
          Privacy Policy
        </h1>
        <p>
          Your privacy is paramount at Kenyans Decision. We collect only the minimum necessary technical information required to mitigate duplicate voting and prevent automated bot spam.
        </p>

        <h3 className="text-base font-bold text-slate-900 pt-2">1. Voting Privacy & Anonymity</h3>
        <p>
          When you participate in public opinion polls, your vote selection is recorded anonymously. We do not store your name, phone number, or precise location alongside your vote choice.
        </p>

        <h3 className="text-base font-bold text-slate-900 pt-2">2. Technical Anti-Abuse Signals</h3>
        <p>
          To prevent duplicate voting and automated bot manipulation:
        </p>
        <ul className="list-disc list-inside space-y-1 pl-2">
          <li>IP addresses are converted to keyed HMAC digests using server-side secrets and temporary rate-limit keys rather than storing raw IPs.</li>
          <li>First-party local browser device tokens are stored locally to prevent accidental double-voting on the same browser.</li>
          <li>Server-side request velocity limiting throttles rapid repeated requests.</li>
        </ul>

        <h3 className="text-base font-bold text-slate-900 pt-2">3. Account Data</h3>
        <p>
          If you choose to create an account to post discussions or community polls, your email address and display name are stored securely. We never sell user data or share personal information with political entities.
        </p>
      </div>
    </div>
  );
};

export const TermsPage: React.FC = () => {
  return (
    <div className="max-w-3xl mx-auto space-y-6">
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-4 text-slate-600 text-xs sm:text-sm leading-relaxed">
        <div className="flex items-center gap-2 text-emerald-700 font-bold text-xs uppercase tracking-wider">
          <FileText className="w-4 h-4" />
          Community Standards
        </div>
        <h1 className="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
          Terms of Service
        </h1>

        <h3 className="text-base font-bold text-slate-900 pt-2">1. Acceptable Use</h3>
        <p>
          Kenyans Decision provides a platform for respectful public discourse. Users agree not to post hate speech, incitement to violence, targeted harassment, explicit false claims, or automated spam.
        </p>

        <h3 className="text-base font-bold text-slate-900 pt-2">2. Anti-Manipulation Rules</h3>
        <p>
          Attempting to manipulate poll results using bot networks, proxy farms, or rapid automated scripts is strictly prohibited. Suspicious votes will be flagged, quarantined, or discarded server-side.
        </p>

        <h3 className="text-base font-bold text-slate-900 pt-2">3. Poll Interpretation</h3>
        <p>
          Results displayed on Kenyans Decision represent voluntary public participation and are not official election forecasts, scientific surveys, or official IEBC announcements.
        </p>
      </div>
    </div>
  );
};
