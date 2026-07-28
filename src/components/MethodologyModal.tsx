import React from 'react';
import { X, ShieldCheck, AlertTriangle, FileText, CheckCircle2 } from 'lucide-react';

interface MethodologyModalProps {
  isOpen: boolean;
  onClose: () => void;
}

export const MethodologyModal: React.FC<MethodologyModalProps> = ({ isOpen, onClose }) => {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-200">
      <div className="bg-white rounded-2xl max-w-xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-200 p-6 relative">
        <button
          onClick={onClose}
          className="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors"
        >
          <X className="w-5 h-5" />
        </button>

        <div className="flex items-center gap-3 mb-4">
          <div className="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center">
            <ShieldCheck className="w-6 h-6" />
          </div>
          <div>
            <h3 className="text-xl font-bold text-slate-900">Poll Methodology & Anti-Abuse</h3>
            <p className="text-xs text-slate-500 font-medium">How Kenyans Decision maintains integrity</p>
          </div>
        </div>

        <div className="space-y-4 text-sm text-slate-600">
          <div className="p-3.5 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
            <AlertTriangle className="w-5 h-5 text-amber-700 shrink-0 mt-0.5" />
            <p className="text-xs text-amber-900 font-medium leading-relaxed">
              <strong>Official Disclaimer:</strong> Kenyans Decision is an independent, non-governmental platform.
              It is not affiliated with the Independent Electoral and Boundaries Commission (IEBC), the Government of Kenya,
              or any political party.
            </p>
          </div>

          <div>
            <h4 className="font-bold text-slate-900 mb-1.5 flex items-center gap-2 text-base">
              <CheckCircle2 className="w-4 h-4 text-emerald-600" />
              1. Anonymous Polling with Duplicate-Vote Mitigation
            </h4>
            <p className="text-xs leading-relaxed text-slate-600">
              Users are not required to create an account or submit personal identity documents to participate in public opinion polls.
              This guarantees voter privacy while implementing robust technical controls to mitigate repeat voting.
            </p>
          </div>

          <div>
            <h4 className="font-bold text-slate-900 mb-1.5 flex items-center gap-2 text-base">
              <CheckCircle2 className="w-4 h-4 text-emerald-600" />
              2. Layered Technical Anti-Abuse Signals
            </h4>
            <p className="text-xs leading-relaxed text-slate-600 mb-2">
              To mitigate duplicate and automated bot submissions without burdening normal users with intrusive CAPTCHAs,
              our server processes privacy-preserving technical signals:
            </p>
            <ul className="list-disc list-inside text-xs space-y-1 text-slate-600 pl-1">
              <li>Keyed HMAC IP digests using server-side secrets for rate limiting without storing raw IPs</li>
              <li>First-party device tokens and local vote key verification</li>
              <li>Request velocity and rate throttling algorithms</li>
              <li>Append-only administrative audit logs for platform governance</li>
            </ul>
          </div>

          <div>
            <h4 className="font-bold text-slate-900 mb-1.5 flex items-center gap-2 text-base">
              <FileText className="w-4 h-4 text-emerald-600" />
              3. Interpretation of Results
            </h4>
            <p className="text-xs leading-relaxed text-slate-600">
              Online public opinion polls reflect the views of voluntary internet participants and should be interpreted as a sample of online public sentiment rather than an official scientific election prediction or representative national census.
            </p>
          </div>
        </div>

        <div className="mt-6 pt-4 border-t border-slate-100 flex justify-end">
          <button
            onClick={onClose}
            className="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition-colors cursor-pointer"
          >
            I Understand
          </button>
        </div>
      </div>
    </div>
  );
};
