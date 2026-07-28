/**
 * @license
 * SPDX-License-Identifier: Apache-2.0
 */

import React, { useState, useEffect } from 'react';
import { Poll, PollResult, User, DiscussionPost, PlatformAnalytics } from './types';
import { api, setAuthToken } from './lib/api';
import { Navbar } from './components/Navbar';
import { MobileNav } from './components/MobileNav';
import { FeaturedPollCard } from './components/FeaturedPollCard';
import { PollResultsView } from './components/PollResultsView';
import { DiscussionFeed } from './components/DiscussionFeed';
import { PollsList } from './components/PollsList';
import { AdminPanel } from './components/AdminPanel';
import { AuthModal } from './components/AuthModal';
import { MethodologyModal } from './components/MethodologyModal';
import { AboutPage, PrivacyPage, TermsPage } from './components/InfoPages';
import { ShieldCheck, BarChart2, MessageSquare, ListFilter, Sparkles, Heart, ArrowRight, Shield } from 'lucide-react';

export default function App() {
  const [currentTab, setCurrentTab] = useState<string>('home');
  const [currentUser, setCurrentUser] = useState<User | null>(null);
  const [polls, setPolls] = useState<Poll[]>([]);
  const [analytics, setAnalytics] = useState<PlatformAnalytics | null>(null);
  const [discussions, setDiscussions] = useState<DiscussionPost[]>([]);
  const [featuredResult, setFeaturedResult] = useState<PollResult | null>(null);
  const [loading, setLoading] = useState<boolean>(true);

  // Modals
  const [isAuthOpen, setIsAuthOpen] = useState<boolean>(false);
  const [isMethodologyOpen, setIsMethodologyOpen] = useState<boolean>(false);

  useEffect(() => {
    initApp();
  }, []);

  const initApp = async () => {
    setLoading(true);
    try {
      // Check auth session if token exists
      try {
        const user = await api.getCurrentUser();
        setCurrentUser(user);
      } catch {
        // Token invalid or absent
      }

      const [pollsData, statsData, discData] = await Promise.all([
        api.getPolls(),
        api.getAnalytics(),
        api.getDiscussions()
      ]);

      setPolls(pollsData);
      setAnalytics(statsData);
      setDiscussions(discData);

      const featured = pollsData.find(p => p.isFeatured) || pollsData[0];
      if (featured) {
        try {
          const fRes = await api.getPollResults(featured.id);
          setFeaturedResult(fRes);
        } catch (e) {
          console.error(e);
        }
      }
    } catch (err) {
      console.error('Initialization error:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleLogout = () => {
    setAuthToken(null);
    setCurrentUser(null);
    if (currentTab === 'admin') {
      setCurrentTab('home');
    }
  };

  const featuredPoll = polls.find(p => p.isFeatured) || polls[0];

  return (
    <div className="min-h-screen bg-slate-50 text-slate-900 font-sans flex flex-col selection:bg-emerald-200 selection:text-emerald-900">
      {/* Top Header */}
      <Navbar
        currentTab={currentTab}
        setCurrentTab={setCurrentTab}
        currentUser={currentUser}
        onOpenAuth={() => setIsAuthOpen(true)}
        onLogout={handleLogout}
      />

      {/* Main Content Area */}
      <main className="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 py-6 sm:py-8 pb-24 md:pb-12">
        {loading ? (
          <div className="py-20 text-center space-y-3">
            <div className="w-10 h-10 border-4 border-slate-900 border-t-transparent rounded-full animate-spin mx-auto" />
            <p className="text-xs font-bold text-slate-500 uppercase tracking-wider">
              Loading Kenyans Decision...
            </p>
          </div>
        ) : (
          <>
            {/* TAB: HOME / VOTE (BENTO GRID LAYOUT) */}
            {currentTab === 'home' && (
              <div className="space-y-6 animate-in fade-in duration-200">
                {/* Hero Title */}
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-2xs">
                  <div>
                    <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs font-extrabold uppercase tracking-wider mb-2">
                      <span>🇰🇪</span>
                      <span>Public Opinion Dashboard</span>
                    </div>
                    <h1 className="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                      Kenyans Decision 2027
                    </h1>
                    <p className="text-slate-500 text-xs sm:text-sm font-medium mt-1 max-w-xl">
                      An independent, non-partisan public opinion platform. Share your perspective anonymously and explore what fellow citizens think.
                    </p>
                  </div>

                  <div className="flex items-center gap-2 shrink-0">
                    <button
                      onClick={() => setCurrentTab('results')}
                      className="px-4 py-2.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-extrabold transition-colors border border-emerald-200 cursor-pointer flex items-center gap-1.5"
                    >
                      <BarChart2 className="w-4 h-4 text-emerald-600" />
                      Live Standings
                    </button>
                    <button
                      onClick={() => setCurrentTab('discuss')}
                      className="px-4 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold transition-colors cursor-pointer flex items-center gap-1.5"
                    >
                      <MessageSquare className="w-4 h-4" />
                      Join Discussion
                    </button>
                  </div>
                </div>

                {/* BENTO GRID MAIN CONTAINER */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                  {/* BENTO TILE 1: FEATURED POLL CARD (Col Span 8) */}
                  <div className="lg:col-span-8">
                    {featuredPoll && (
                      <FeaturedPollCard
                        poll={featuredPoll}
                        onVoteSuccess={initApp}
                      />
                    )}
                  </div>

                  {/* BENTO TILE 2: LIVE TRACKING STATS (Col Span 4 - Dark Theme) */}
                  <div className="lg:col-span-4 bg-slate-900 text-white rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-md flex flex-col justify-between">
                    <div>
                      <div className="flex items-center justify-between border-b border-slate-800 pb-4">
                        <h3 className="font-extrabold text-xs text-slate-400 uppercase tracking-wider">
                          Live Tracking
                        </h3>
                        <span className="flex items-center gap-1.5 text-xs font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/20">
                          <span className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
                          Live
                        </span>
                      </div>

                      <div className="my-6">
                        <div className="text-4xl sm:text-5xl font-black tracking-tight text-white">
                          {(featuredResult?.totalVotes || analytics?.totalVotes || 12543).toLocaleString()}
                        </div>
                        <div className="text-xs text-slate-400 font-medium mt-1">
                          Total Voluntary Votes Recorded
                        </div>
                      </div>

                      {/* Standings Progress Bars */}
                      <div className="space-y-4">
                        <div className="text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                          Current Leading Preferences
                        </div>
                        {featuredResult?.optionResults?.slice(0, 3).map((opt) => (
                          <div key={opt.optionId} className="space-y-1.5">
                            <div className="flex justify-between text-xs font-bold text-slate-300">
                              <span className="truncate pr-2">{opt.name}</span>
                              <span className="text-emerald-400 font-black shrink-0">{opt.percentage}%</span>
                            </div>
                            <div className="h-2 bg-slate-800 rounded-full overflow-hidden p-0.5">
                              <div
                                className="h-full bg-emerald-500 rounded-full transition-all duration-700"
                                style={{ width: `${Math.max(opt.percentage, 3)}%` }}
                              />
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>

                    <button
                      onClick={() => setCurrentTab('results')}
                      className="mt-8 w-full bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs sm:text-sm py-3 px-4 rounded-2xl border border-slate-700 transition-all cursor-pointer flex items-center justify-center gap-2 group"
                    >
                      <BarChart2 className="w-4 h-4 text-emerald-400" />
                      <span>View Full County Results</span>
                      <ArrowRight className="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition-transform" />
                    </button>
                  </div>

                  {/* BENTO TILE 3: TRENDING DISCUSSIONS (Col Span 4) */}
                  <div className="lg:col-span-4 bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-2xs flex flex-col justify-between">
                    <div>
                      <div className="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h3 className="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                          <MessageSquare className="w-4 h-4 text-blue-600" />
                          Trending Discussions
                        </h3>
                        <span className="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Public Forum</span>
                      </div>

                      <div className="space-y-3">
                        {discussions.slice(0, 3).map((disc) => (
                          <div
                            key={disc.id}
                            onClick={() => setCurrentTab('discuss')}
                            className="p-3.5 bg-slate-50/80 hover:bg-slate-100 rounded-2xl border border-slate-100 transition-all cursor-pointer group"
                          >
                            <span className="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                              {disc.category}
                            </span>
                            <h4 className="text-xs font-bold text-slate-900 group-hover:text-emerald-700 transition-colors mt-1.5 line-clamp-2">
                              {disc.title}
                            </h4>
                            <div className="flex items-center gap-3 mt-2 text-[11px] font-medium text-slate-400">
                              <span>{disc.commentsCount} comments</span>
                              <span>•</span>
                              <span>{disc.likesCount} likes</span>
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>

                    <button
                      onClick={() => setCurrentTab('discuss')}
                      className="mt-6 w-full bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold text-xs py-3 rounded-2xl transition-colors cursor-pointer text-center"
                    >
                      View All Conversations
                    </button>
                  </div>

                  {/* BENTO TILE 4: METHODOLOGY & DISCLAIMER (Col Span 8) */}
                  <div className="lg:col-span-8 bg-rose-50/90 rounded-3xl p-6 sm:p-8 border border-rose-200/80 shadow-2xs flex flex-col sm:flex-row items-start gap-4 justify-between">
                    <div className="flex items-start gap-3.5">
                      <div className="text-2xl shrink-0 p-2.5 bg-white rounded-2xl border border-rose-200 shadow-2xs">
                        ⚠️
                      </div>
                      <div>
                        <h4 className="font-extrabold text-rose-900 text-xs sm:text-sm uppercase tracking-wider">
                          Poll Methodology & Disclaimer
                        </h4>
                        <p className="text-rose-950 text-xs sm:text-sm leading-relaxed mt-1.5 font-medium">
                          This is an independent online public opinion poll and is not affiliated with IEBC or any political party. Results are based on voluntary participation and may not represent the views of the entire Kenyan electorate. This poll is not an official election result or prediction. Our system uses multiple technical signals to ensure one-person-one-vote integrity.
                        </p>
                        <div className="flex flex-wrap items-center gap-4 mt-3">
                          <button
                            onClick={() => setIsMethodologyOpen(true)}
                            className="text-xs font-extrabold text-rose-900 underline hover:text-rose-950 cursor-pointer"
                          >
                            Read Full Methodology
                          </button>
                          <button
                            onClick={() => setCurrentTab('privacy')}
                            className="text-xs font-extrabold text-rose-900 underline hover:text-rose-950 cursor-pointer"
                          >
                            Privacy Practices
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* BENTO TILE 5: FEATURE HUBS (Col Span 12) */}
                  <div className="lg:col-span-12 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div
                      onClick={() => setCurrentTab('results')}
                      className="p-6 bg-white rounded-3xl border border-slate-200/90 shadow-2xs hover:border-emerald-500/50 hover:shadow-md transition-all cursor-pointer group"
                    >
                      <div className="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center mb-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <BarChart2 className="w-5 h-5" />
                      </div>
                      <h3 className="font-extrabold text-slate-900 text-base mb-1">Live Results & Maps</h3>
                      <p className="text-xs text-slate-500 font-medium leading-relaxed">
                        View national percentages, candidate rankings, and county breakdowns as votes are cast.
                      </p>
                    </div>

                    <div
                      onClick={() => setCurrentTab('polls')}
                      className="p-6 bg-white rounded-3xl border border-slate-200/90 shadow-2xs hover:border-amber-500/50 hover:shadow-md transition-all cursor-pointer group"
                    >
                      <div className="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center mb-3 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                        <ListFilter className="w-5 h-5" />
                      </div>
                      <h3 className="font-extrabold text-slate-900 text-base mb-1">Community Polls</h3>
                      <p className="text-xs text-slate-500 font-medium leading-relaxed">
                        Explore user-created opinion polls or launch your own public question for free.
                      </p>
                    </div>

                    <div
                      onClick={() => setCurrentTab('discuss')}
                      className="p-6 bg-white rounded-3xl border border-slate-200/90 shadow-2xs hover:border-blue-500/50 hover:shadow-md transition-all cursor-pointer group"
                    >
                      <div className="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <MessageSquare className="w-5 h-5" />
                      </div>
                      <h3 className="font-extrabold text-slate-900 text-base mb-1">Kenyans Are Talking</h3>
                      <p className="text-xs text-slate-500 font-medium leading-relaxed">
                        Discuss cost of living, healthcare, jobs, and the 2027 General Election with fellow citizens.
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            )}

            {/* TAB: RESULTS */}
            {currentTab === 'results' && (
              <PollResultsView polls={polls} />
            )}

            {/* TAB: DISCUSS */}
            {currentTab === 'discuss' && (
              <DiscussionFeed
                currentUser={currentUser}
                onOpenAuth={() => setIsAuthOpen(true)}
              />
            )}

            {/* TAB: POLLS */}
            {currentTab === 'polls' && (
              <PollsList
                currentUser={currentUser}
                onOpenAuth={() => setIsAuthOpen(true)}
              />
            )}

            {/* TAB: ABOUT */}
            {currentTab === 'about' && <AboutPage />}

            {/* TAB: PRIVACY */}
            {currentTab === 'privacy' && <PrivacyPage />}

            {/* TAB: TERMS */}
            {currentTab === 'terms' && <TermsPage />}

            {/* TAB: ADMIN */}
            {currentTab === 'admin' && (
              <AdminPanel currentUser={currentUser} />
            )}
          </>
        )}
      </main>

      {/* Footer */}
      <footer className="bg-white border-t border-slate-200/80 py-8 text-slate-500 text-xs">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
          <div>
            <div className="flex items-center justify-center md:justify-start gap-2 font-black text-slate-900 text-sm">
              <span className="w-5 h-5 bg-emerald-600 text-white rounded-md flex items-center justify-center text-xs">🇰🇪</span> Kenyans Decision
            </div>
            <p className="text-slate-500 text-[11px] mt-1 font-medium">
              Independent • Non-Governmental • Public Opinion Platform
            </p>
          </div>

          <div className="flex flex-wrap items-center justify-center gap-4 text-xs font-semibold text-slate-600">
            <button onClick={() => setCurrentTab('about')} className="hover:text-slate-900 transition-colors cursor-pointer">
              About
            </button>
            <button onClick={() => setCurrentTab('privacy')} className="hover:text-slate-900 transition-colors cursor-pointer">
              Privacy Policy
            </button>
            <button onClick={() => setCurrentTab('terms')} className="hover:text-slate-900 transition-colors cursor-pointer">
              Terms of Service
            </button>
            {currentUser?.role === 'admin' && (
              <button onClick={() => setCurrentTab('admin')} className="text-amber-700 font-bold hover:underline cursor-pointer">
                Admin Panel
              </button>
            )}
          </div>
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 mt-6 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-center text-[11px] text-slate-400">
          <span>Disclaimer: Not affiliated with IEBC or the Government of Kenya. Results reflect voluntary public responses.</span>
          <div className="flex gap-2">
            <span className="font-bold text-slate-600">Share:</span>
            <button onClick={() => api.trackShare()} className="hover:text-slate-800 font-bold cursor-pointer">WhatsApp</button>
            <button onClick={() => api.trackShare()} className="hover:text-slate-800 font-bold cursor-pointer">X (Twitter)</button>
            <button onClick={() => api.trackShare()} className="hover:text-slate-800 font-bold cursor-pointer">Facebook</button>
          </div>
        </div>
      </footer>

      {/* Mobile Sticky Navigation */}
      <MobileNav
        currentTab={currentTab}
        setCurrentTab={setCurrentTab}
        currentUser={currentUser}
        onOpenAuth={() => setIsAuthOpen(true)}
      />

      {/* Auth Modal */}
      <AuthModal
        isOpen={isAuthOpen}
        onClose={() => setIsAuthOpen(false)}
        onSuccess={(user) => {
          setCurrentUser(user);
          initApp();
        }}
      />

      {/* Methodology Modal */}
      <MethodologyModal
        isOpen={isMethodologyOpen}
        onClose={() => setIsMethodologyOpen(false)}
      />
    </div>
  );
}

