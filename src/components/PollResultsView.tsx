import React, { useState, useEffect } from 'react';
import { Poll, PollResult, KENYAN_COUNTIES, AGE_GROUPS } from '../types';
import { api } from '../lib/api';
import { BarChart2, Filter, Share2, Info, AlertTriangle, RefreshCw, Layers } from 'lucide-react';
import { ShareModal } from './ShareModal';

interface PollResultsViewProps {
  polls: Poll[];
  selectedPollId?: string;
}

export const PollResultsView: React.FC<PollResultsViewProps> = ({ polls, selectedPollId }) => {
  const [activePollId, setActivePollId] = useState<string>(selectedPollId || polls[0]?.id || 'kenya-2027-presidential-opinion-poll');
  const [results, setResults] = useState<PollResult | null>(null);
  const [loading, setLoading] = useState<boolean>(true);
  const [selectedCounty, setSelectedCounty] = useState<string>('All');
  const [selectedAge, setSelectedAge] = useState<string>('All');
  const [isShareOpen, setIsShareOpen] = useState(false);

  const activePoll = polls.find(p => p.id === activePollId) || polls[0];

  useEffect(() => {
    if (activePollId) {
      loadResults(activePollId);
    }
  }, [activePollId]);

  const loadResults = async (id: string) => {
    setLoading(true);
    try {
      const data = await api.getPollResults(id);
      setResults(data);
    } catch (err) {
      console.error('Error loading results:', err);
    } finally {
      setLoading(false);
    }
  };

  // Compute county-specific or age-specific counts if selected
  let displayedResults = results?.optionResults || [];
  let displayedTotalVotes = results?.totalVotes || 0;
  let countySampleWarning = false;

  if (results && selectedCounty !== 'All') {
    const countyData = results.countyBreakdown?.[selectedCounty] || {};
    let countyTotal = 0;
    Object.values(countyData).forEach((cnt: any) => { countyTotal += Number(cnt) || 0; });

    if (countyTotal < 10) {
      countySampleWarning = true;
    } else {
      displayedTotalVotes = countyTotal;
      displayedResults = activePoll.options.map(opt => {
        const count = countyData[opt.id] || 0;
        const pct = countyTotal > 0 ? parseFloat(((count / countyTotal) * 100).toFixed(1)) : 0;
        return {
          optionId: opt.id,
          name: opt.name,
          party: opt.party,
          avatarColor: opt.avatarColor,
          votes: count,
          percentage: pct
        };
      }).sort((a, b) => b.votes - a.votes);
    }
  }

  const leaderResult = displayedResults[0];

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      {/* Header */}
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div className="flex items-center gap-2 mb-1">
            <span className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
            <span className="text-xs font-extrabold uppercase tracking-wider text-slate-500">
              Live Public Opinion Breakdown
            </span>
          </div>
          <h1 className="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            National Poll Results
          </h1>
          <p className="text-xs sm:text-sm text-slate-500 font-medium mt-1">
            Voluntary, aggregated public opinions across Kenya.
          </p>
        </div>

        {/* Poll Selector Dropdown */}
        <div className="w-full md:w-auto">
          <label className="block text-[11px] font-extrabold uppercase text-slate-400 mb-1">
            Select Opinion Poll
          </label>
          <select
            value={activePollId}
            onChange={(e) => {
              setActivePollId(e.target.value);
              setSelectedCounty('All');
              setSelectedAge('All');
            }}
            className="w-full md:w-72 bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 font-bold text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
          >
            {polls.map((p) => (
              <option key={p.id} value={p.id}>
                {p.title} ({p.totalVotes.toLocaleString()} votes)
              </option>
            ))}
          </select>
        </div>
      </div>

      {/* Main Results Container */}
      {activePoll && (
        <div className="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-6">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
              <h2 className="text-xl font-extrabold text-slate-900">
                {activePoll.title}
              </h2>
              <p className="text-xs text-slate-500 font-medium mt-0.5">
                {displayedTotalVotes.toLocaleString()} responses recorded • Updated just now
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={() => loadResults(activePoll.id)}
                className="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer"
                title="Refresh"
              >
                <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
              </button>
              <button
                onClick={() => setIsShareOpen(true)}
                className="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs cursor-pointer"
              >
                <Share2 className="w-3.5 h-3.5" />
                Share
              </button>
            </div>
          </div>

          {/* Filtering Bar */}
          <div className="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex flex-wrap items-center justify-between gap-3">
            <div className="flex items-center gap-2 text-xs font-bold text-slate-700">
              <Filter className="w-4 h-4 text-emerald-600" />
              <span>Demographic Filters:</span>
            </div>

            <div className="flex flex-wrap items-center gap-3 w-full sm:w-auto">
              {/* County Filter */}
              <div className="flex items-center gap-1.5">
                <span className="text-xs font-medium text-slate-500">County:</span>
                <select
                  value={selectedCounty}
                  onChange={(e) => setSelectedCounty(e.target.value)}
                  className="bg-white border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs font-bold text-slate-800 focus:outline-none"
                >
                  <option value="All">Kenya (National)</option>
                  {KENYAN_COUNTIES.map(c => (
                    <option key={c} value={c}>{c}</option>
                  ))}
                </select>
              </div>

              {/* Age Group Filter */}
              <div className="flex items-center gap-1.5">
                <span className="text-xs font-medium text-slate-500">Age:</span>
                <select
                  value={selectedAge}
                  onChange={(e) => setSelectedAge(e.target.value)}
                  className="bg-white border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs font-bold text-slate-800 focus:outline-none"
                >
                  <option value="All">All Age Groups</option>
                  {AGE_GROUPS.map(a => (
                    <option key={a} value={a}>{a} years</option>
                  ))}
                </select>
              </div>
            </div>
          </div>

          {/* Warning for insufficient county sample size */}
          {countySampleWarning && (
            <div className="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-amber-900 text-xs font-semibold flex items-center gap-2.5">
              <AlertTriangle className="w-5 h-5 text-amber-600 shrink-0" />
              <span>
                Not enough responses to show reliable county-level results for <strong>{selectedCounty}</strong>. Showing national aggregation instead.
              </span>
            </div>
          )}

          {/* Results List */}
          {loading ? (
            <div className="py-12 text-center text-slate-400 text-sm font-medium">
              Loading live poll results...
            </div>
          ) : (
            <div className="space-y-4">
              {displayedResults.map((opt, rank) => (
                <div key={opt.optionId} className="space-y-1">
                  <div className="flex items-center justify-between text-xs sm:text-sm font-bold text-slate-900">
                    <div className="flex items-center gap-2.5 min-w-0 pr-2">
                      <span className="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-extrabold text-slate-500 shrink-0">
                        #{rank + 1}
                      </span>
                      <span className="truncate">{opt.name}</span>
                      <span className="text-xs font-normal text-slate-500 truncate hidden sm:inline">
                        • {opt.party}
                      </span>
                    </div>

                    <div className="text-right shrink-0">
                      <span className="text-slate-900 font-extrabold text-base">
                        {opt.percentage}%
                      </span>
                      <span className="text-slate-400 text-xs ml-1 font-normal">
                        ({opt.votes.toLocaleString()} votes)
                      </span>
                    </div>
                  </div>

                  <div className="w-full bg-slate-100 rounded-full h-3 overflow-hidden p-0.5 border border-slate-200/60">
                    <div
                      className="h-full rounded-full transition-all duration-700 ease-out"
                      style={{
                        width: `${Math.max(opt.percentage, 1)}%`,
                        backgroundColor: opt.avatarColor || '#10b981'
                      }}
                    />
                  </div>
                </div>
              ))}
            </div>
          )}

          {/* Methodology Footer Note */}
          <div className="pt-4 border-t border-slate-100 text-xs text-slate-400 flex items-center justify-between">
            <span className="flex items-center gap-1">
              <Info className="w-3.5 h-3.5" />
              Voluntary public opinion sample • Non-governmental
            </span>
            <span>Kenyans Decision Platform</span>
          </div>
        </div>
      )}

      {/* Share Modal */}
      {activePoll && (
        <ShareModal
          isOpen={isShareOpen}
          onClose={() => setIsShareOpen(false)}
          pollTitle={activePoll.title}
          leaderText={leaderResult ? `${leaderResult.name} (${leaderResult.percentage}%)` : ''}
        />
      )}
    </div>
  );
};
