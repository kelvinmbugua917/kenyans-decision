import React, { useState, useEffect } from 'react';
import { Poll, PollResult, KENYAN_COUNTIES, AGE_GROUPS } from '../types';
import { api } from '../lib/api';
import { CheckCircle2, Share2, Info, RefreshCw, AlertCircle, Sparkles, MapPin } from 'lucide-react';
import { ShareModal } from './ShareModal';
import { MethodologyModal } from './MethodologyModal';

interface FeaturedPollCardProps {
  poll: Poll;
  onVoteSuccess?: () => void;
}

export const FeaturedPollCard: React.FC<FeaturedPollCardProps> = ({ poll, onVoteSuccess }) => {
  const [selectedOptionId, setSelectedOptionId] = useState<string | null>(null);
  const [confirming, setConfirming] = useState<boolean>(false);
  const [isSubmitting, setIsSubmitting] = useState<boolean>(false);
  const [hasVoted, setHasVoted] = useState<boolean>(false);
  const [votedOptionId, setVotedOptionId] = useState<string | null>(null);
  const [results, setResults] = useState<PollResult | null>(null);
  const [loadingResults, setLoadingResults] = useState<boolean>(false);
  const [voteError, setVoteError] = useState<string | null>(null);

  // Voluntary demographic fields
  const [selectedCounty, setSelectedCounty] = useState<string>('Nairobi');
  const [selectedAge, setSelectedAge] = useState<string>('25-34');

  // Modals
  const [isShareOpen, setIsShareOpen] = useState(false);
  const [isMethodologyOpen, setIsMethodologyOpen] = useState(false);

  useEffect(() => {
    checkStatusAndLoadResults();
  }, [poll.id]);

  const checkStatusAndLoadResults = async () => {
    setLoadingResults(true);
    try {
      const [statusRes, resultsRes] = await Promise.all([
        api.getVotedStatus(poll.id),
        api.getPollResults(poll.id)
      ]);

      setResults(resultsRes);
      if (statusRes.hasVoted) {
        setHasVoted(true);
        if (statusRes.selectedOptionId) {
          setVotedOptionId(statusRes.selectedOptionId);
          setSelectedOptionId(statusRes.selectedOptionId);
        }
      }
    } catch (err) {
      console.error('Error loading poll data:', err);
    } finally {
      setLoadingResults(false);
    }
  };

  const handleCastVoteClick = () => {
    if (!selectedOptionId) return;
    setVoteError(null);
    setConfirming(true);
  };

  const handleConfirmVote = async () => {
    if (!selectedOptionId) return;
    setIsSubmitting(true);
    setVoteError(null);

    try {
      const res = await api.castVote(poll.id, selectedOptionId, selectedCounty, selectedAge);
      if (res.success) {
        setHasVoted(true);
        setVotedOptionId(selectedOptionId);
        setResults(res.pollResult);
        setConfirming(false);
        if (onVoteSuccess) onVoteSuccess();
      }
    } catch (err: any) {
      setVoteError(err.message || 'Failed to record vote. Please try again.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const selectedCandidate = poll.options.find(o => o.id === selectedOptionId);
  const leaderResult = results?.optionResults[0];
  const leaderText = leaderResult ? `${leaderResult.name} (${leaderResult.percentage}%)` : '';

  return (
    <div className="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden transition-all h-full flex flex-col justify-between p-6 sm:p-8">
      {/* Top Tag & Info Header */}
      <div>
        <div className="flex flex-wrap items-center justify-between gap-3 mb-3">
          <span className="text-[11px] uppercase font-black tracking-wider px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/80 inline-flex items-center gap-1.5">
            <span>🇰🇪</span> Featured Public Opinion Poll
          </span>

          <div className="flex items-center gap-2">
            <span className="text-xs font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">
              {poll.totalVotes.toLocaleString()} votes
            </span>
            <button
              onClick={() => setIsMethodologyOpen(true)}
              className="flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-900 transition-colors bg-slate-100 px-2.5 py-1 rounded-full hover:bg-slate-200 cursor-pointer font-semibold"
            >
              <Info className="w-3.5 h-3.5 text-emerald-600" />
              Methodology
            </button>
          </div>
        </div>

        <h2 className="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight mb-2">
          {poll.title}
        </h2>
        <p className="text-slate-500 text-sm sm:text-base leading-relaxed max-w-2xl font-normal">
          {poll.description}
        </p>

        {hasVoted && (
          <div className="mt-4 inline-flex items-center gap-2 bg-emerald-50 text-emerald-900 text-xs font-bold px-3 py-1.5 rounded-xl border border-emerald-200">
            <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0" />
            <span>You have voted in this opinion poll.</span>
            {poll.allowVoteChange && (
              <button
                onClick={() => {
                  setHasVoted(false);
                  setConfirming(false);
                }}
                className="underline hover:text-slate-900 ml-1 font-semibold cursor-pointer"
              >
                Change vote
              </button>
            )}
          </div>
        )}
      </div>

      {/* Main Options / Voting Area */}
      <div className="mt-6">
        {voteError && (
          <div className="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold rounded-2xl flex items-center gap-3">
            <AlertCircle className="w-5 h-5 text-rose-600 shrink-0" />
            <span>{voteError}</span>
          </div>
        )}

        {/* STATE 1: VOTING OPTIONS FORM */}
        {!hasVoted && !confirming && (
          <div className="space-y-3">
            <p className="text-xs font-bold uppercase text-slate-400 tracking-wider mb-2">
              Select Candidate / Option below
            </p>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
              {poll.options.map((option) => {
                const isSelected = selectedOptionId === option.id;
                return (
                  <button
                    key={option.id}
                    type="button"
                    onClick={() => setSelectedOptionId(option.id)}
                    className={`flex items-center gap-3.5 p-4 rounded-2xl border-2 text-left transition-all cursor-pointer relative ${
                      isSelected
                        ? 'border-emerald-600 bg-emerald-50/80 shadow-xs'
                        : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50 text-slate-800'
                    }`}
                  >
                    {/* Candidate Initial Avatar */}
                    <div
                      className="w-10 h-10 rounded-xl flex items-center justify-center font-black text-xs shrink-0 shadow-2xs"
                      style={{
                        backgroundColor: isSelected ? '#059669' : option.avatarColor || '#0f172a',
                        color: '#ffffff'
                      }}
                    >
                      {option.name.substring(0, 2).toUpperCase()}
                    </div>

                    <div className="flex-1 min-w-0">
                      <h3 className="font-extrabold text-sm sm:text-base leading-tight truncate text-slate-900">
                        {option.name}
                      </h3>
                      <p className={`text-xs mt-0.5 truncate ${isSelected ? 'text-emerald-800 font-bold' : 'text-slate-500 font-medium'}`}>
                        {option.party}
                      </p>
                    </div>

                    {/* Radio indicator */}
                    <div
                      className={`w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 ${
                        isSelected
                          ? 'border-emerald-600 bg-emerald-600'
                          : 'border-slate-300 bg-white'
                      }`}
                    >
                      {isSelected && <div className="w-2 h-2 rounded-full bg-white" />}
                    </div>
                  </button>
                );
              })}
            </div>

            {/* Submit Action Bar */}
            <div className="mt-6 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
              <div className="text-xs text-slate-500 flex items-center gap-1.5 font-medium">
                <Sparkles className="w-4 h-4 text-emerald-600" />
                <span>No sign-up required • Anonymous Polling with Duplicate-Vote Mitigation</span>
              </div>

              <button
                type="button"
                disabled={!selectedOptionId}
                onClick={handleCastVoteClick}
                className={`w-full sm:w-auto px-8 py-3.5 rounded-2xl font-black text-base transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer ${
                  selectedOptionId
                    ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-emerald-600/20 active:scale-98'
                    : 'bg-slate-200 text-slate-400 cursor-not-allowed'
                }`}
              >
                Cast My Vote
              </button>
            </div>
          </div>
        )}

        {/* STATE 2: CONFIRMATION STEP */}
        {!hasVoted && confirming && selectedCandidate && (
          <div className="p-6 bg-slate-50 rounded-2xl border border-slate-200 animate-in fade-in duration-200">
            <h3 className="text-lg font-bold text-slate-900 mb-1">Confirm Your Choice</h3>
            <p className="text-xs text-slate-500 mb-4">
              You are about to cast your opinion vote in the 2027 General Election poll.
            </p>

            <div className="p-4 bg-white rounded-xl border border-slate-200 mb-5 flex items-center gap-3">
              <div
                className="w-12 h-12 rounded-xl flex items-center justify-center font-extrabold text-white text-lg"
                style={{ backgroundColor: selectedCandidate.avatarColor }}
              >
                {selectedCandidate.name.substring(0, 2).toUpperCase()}
              </div>
              <div>
                <h4 className="font-extrabold text-slate-900 text-base">{selectedCandidate.name}</h4>
                <p className="text-xs text-slate-500 font-medium">{selectedCandidate.party}</p>
              </div>
            </div>

            {/* Optional County Selector */}
            <div className="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1 flex items-center gap-1">
                  <MapPin className="w-3.5 h-3.5 text-slate-500" />
                  Your County (Optional)
                </label>
                <select
                  value={selectedCounty}
                  onChange={(e) => setSelectedCounty(e.target.value)}
                  className="w-full text-xs font-medium bg-white border border-slate-300 rounded-xl p-2.5 text-slate-800 focus:outline-none focus:border-slate-900"
                >
                  {KENYAN_COUNTIES.map(c => (
                    <option key={c} value={c}>{c}</option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1">
                  Age Group (Optional)
                </label>
                <select
                  value={selectedAge}
                  onChange={(e) => setSelectedAge(e.target.value)}
                  className="w-full text-xs font-medium bg-white border border-slate-300 rounded-xl p-2.5 text-slate-800 focus:outline-none focus:border-slate-900"
                >
                  {AGE_GROUPS.map(a => (
                    <option key={a} value={a}>{a} years</option>
                  ))}
                </select>
              </div>
            </div>

            <div className="flex items-center gap-3 justify-end pt-2">
              <button
                type="button"
                onClick={() => setConfirming(false)}
                className="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-200 transition-colors cursor-pointer"
              >
                Change Choice
              </button>

              <button
                type="button"
                disabled={isSubmitting}
                onClick={handleConfirmVote}
                className="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm transition-all shadow-md flex items-center gap-2 cursor-pointer disabled:opacity-50"
              >
                {isSubmitting ? (
                  <>
                    <RefreshCw className="w-4 h-4 animate-spin" />
                    Counting Vote...
                  </>
                ) : (
                  'Confirm Vote'
                )}
              </button>
            </div>
          </div>
        )}

        {/* STATE 3: LIVE RESULTS VIEW */}
        {hasVoted && results && (
          <div className="space-y-5 animate-in fade-in duration-300">
            <div className="flex flex-wrap items-center justify-between gap-2 pb-2 border-b border-slate-100">
              <div>
                <h3 className="text-xs font-extrabold uppercase tracking-wider text-slate-400">
                  CURRENT LIVE STANDINGS
                </h3>
                <p className="text-xs text-slate-500 font-medium">
                  Based on {results.totalVotes.toLocaleString()} voluntary public responses
                </p>
              </div>

              <div className="flex items-center gap-2">
                <button
                  onClick={checkStatusAndLoadResults}
                  className="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer"
                  title="Refresh standings"
                >
                  <RefreshCw className={`w-4 h-4 ${loadingResults ? 'animate-spin' : ''}`} />
                </button>
                <button
                  onClick={() => setIsShareOpen(true)}
                  className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition-colors cursor-pointer"
                >
                  <Share2 className="w-3.5 h-3.5 text-emerald-600" />
                  Share Results
                </button>
              </div>
            </div>

            {/* Results Progress Bars */}
            <div className="space-y-4">
              {results.optionResults.map((opt, rank) => {
                const isUserChoice = opt.optionId === votedOptionId;
                return (
                  <div key={opt.optionId} className="group">
                    <div className="flex items-center justify-between text-xs sm:text-sm font-bold text-slate-900 mb-1">
                      <div className="flex items-center gap-2 min-w-0 pr-2">
                        <span className="text-xs font-extrabold text-slate-400 w-4">
                          #{rank + 1}
                        </span>
                        <span className="truncate">{opt.name}</span>
                        <span className="text-[11px] font-medium text-slate-500 truncate hidden sm:inline">
                          ({opt.party})
                        </span>
                        {isUserChoice && (
                          <span className="text-[10px] uppercase font-extrabold px-1.5 py-0.5 rounded-md bg-slate-900 text-white shrink-0">
                            Your Vote
                          </span>
                        )}
                      </div>
                      <div className="text-right shrink-0">
                        <span className="text-slate-900 font-black text-sm sm:text-base">
                          {opt.percentage}%
                        </span>
                        <span className="text-slate-400 text-xs ml-1.5 font-normal">
                          ({opt.votes.toLocaleString()})
                        </span>
                      </div>
                    </div>

                    {/* Progress Bar Container */}
                    <div className="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden p-0.5 border border-slate-200/60">
                      <div
                        className="h-full rounded-full transition-all duration-700 ease-out"
                        style={{
                          width: `${Math.max(opt.percentage, 1)}%`,
                          backgroundColor: opt.avatarColor || '#10b981'
                        }}
                      />
                    </div>
                  </div>
                );
              })}
            </div>

            {/* Bottom Disclaimer */}
            <div className="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-500 leading-relaxed">
              <p>
                <strong>Methodology Note:</strong> This online poll is an independent measure of opinion among voluntary online participants and is not an official election result.
              </p>
            </div>
          </div>
        )}
      </div>

      {/* Modals */}
      <ShareModal
        isOpen={isShareOpen}
        onClose={() => setIsShareOpen(false)}
        pollTitle={poll.title}
        leaderText={leaderText}
      />
      <MethodologyModal
        isOpen={isMethodologyOpen}
        onClose={() => setIsMethodologyOpen(false)}
      />
    </div>
  );
};
