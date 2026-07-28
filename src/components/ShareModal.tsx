import React, { useState } from 'react';
import { X, Check, Copy, Share2 } from 'lucide-react';
import { api } from '../lib/api';

interface ShareModalProps {
  isOpen: boolean;
  onClose: () => void;
  pollTitle: string;
  leaderText?: string;
}

export const ShareModal: React.FC<ShareModalProps> = ({
  isOpen,
  onClose,
  pollTitle,
  leaderText
}) => {
  const [copied, setCopied] = useState(false);

  if (!isOpen) return null;

  const currentUrl = window.location.href;
  const shareMessage = `🇰🇪 I just voted in the Kenyans Decision public opinion poll: "${pollTitle}". ${
    leaderText ? `Current standings: ${leaderText}.` : ''
  } See what Kenyans are choosing and add your voice!`;

  const handleCopy = () => {
    navigator.clipboard.writeText(`${shareMessage}\n\n${currentUrl}`);
    setCopied(true);
    api.trackShare().catch(() => {});
    setTimeout(() => setCopied(false), 2500);
  };

  const shareWhatsApp = () => {
    api.trackShare().catch(() => {});
    const url = `https://api.whatsapp.com/send?text=${encodeURIComponent(`${shareMessage}\n${currentUrl}`)}`;
    window.open(url, '_blank');
  };

  const shareTwitter = () => {
    api.trackShare().catch(() => {});
    const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareMessage)}&url=${encodeURIComponent(currentUrl)}&hashtags=KenyansDecision,Kenya2027`;
    window.open(url, '_blank');
  };

  const shareFacebook = () => {
    api.trackShare().catch(() => {});
    const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentUrl)}`;
    window.open(url, '_blank');
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-200">
      <div className="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 p-6 relative">
        <button
          onClick={onClose}
          className="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer"
        >
          <X className="w-5 h-5" />
        </button>

        <div className="flex items-center gap-3 mb-4">
          <div className="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center">
            <Share2 className="w-5 h-5" />
          </div>
          <div>
            <h3 className="text-lg font-bold text-slate-900">Share Poll Results</h3>
            <p className="text-xs text-slate-500 font-medium">Invite fellow Kenyans to share their opinion</p>
          </div>
        </div>

        <div className="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 mb-5 leading-relaxed font-mono">
          "{shareMessage}"
        </div>

        <div className="grid grid-cols-2 gap-2.5 mb-5">
          <button
            onClick={shareWhatsApp}
            className="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all shadow-xs cursor-pointer"
          >
            <span>💬</span> WhatsApp
          </button>

          <button
            onClick={shareTwitter}
            className="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-all shadow-xs cursor-pointer"
          >
            <span>𝕏</span> X / Twitter
          </button>

          <button
            onClick={shareFacebook}
            className="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-all shadow-xs cursor-pointer"
          >
            <span>📘</span> Facebook
          </button>

          <button
            onClick={handleCopy}
            className="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold text-xs transition-all border border-slate-200 cursor-pointer"
          >
            {copied ? <Check className="w-4 h-4 text-emerald-600" /> : <Copy className="w-4 h-4" />}
            {copied ? 'Copied!' : 'Copy Link'}
          </button>
        </div>

        <p className="text-[11px] text-center text-slate-400 font-medium">
          Kenyans Decision • Neutral Public Opinion Platform
        </p>
      </div>
    </div>
  );
};
