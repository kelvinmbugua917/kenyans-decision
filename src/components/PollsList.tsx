import React, { useState, useEffect } from 'react';
import { Poll, User, PollCategory } from '../types';
import { api } from '../lib/api';
import { ListFilter, Plus, CheckCircle2, Info, Users, Sparkles, X } from 'lucide-react';
import { FeaturedPollCard } from './FeaturedPollCard';

interface PollsListProps {
  currentUser: User | null;
  onOpenAuth: () => void;
}

const CATEGORIES: PollCategory[] = [
  '2027 Elections',
  'Cost of Living',
  'Jobs & Economy',
  'Education',
  'Healthcare',
  'Housing',
  'Technology',
  'General Kenya'
];

export const PollsList: React.FC<PollsListProps> = ({ currentUser, onOpenAuth }) => {
  const [polls, setPolls] = useState<Poll[]>([]);
  const [activeTab, setActiveTab] = useState<'all' | 'official' | 'community'>('all');
  const [loading, setLoading] = useState<boolean>(true);

  // New Poll Modal
  const [isNewPollOpen, setIsNewPollOpen] = useState(false);
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [category, setCategory] = useState<PollCategory>('General Kenya');
  const [options, setOptions] = useState<string[]>(['Option 1', 'Option 2']);
  const [allowVoteChange, setAllowVoteChange] = useState(true);

  useEffect(() => {
    loadPolls();
  }, [activeTab]);

  const loadPolls = async () => {
    setLoading(true);
    try {
      const data = await api.getPolls(undefined, activeTab);
      setPolls(data);
    } catch (err) {
      console.error('Failed to load polls:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleAddOption = () => {
    if (options.length < 6) {
      setOptions([...options, `Option ${options.length + 1}`]);
    }
  };

  const handleOptionChange = (index: number, value: string) => {
    const updated = [...options];
    updated[index] = value;
    setOptions(updated);
  };

  const handleCreatePoll = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!currentUser) {
      onOpenAuth();
      return;
    }

    const filteredOptions = options.map(o => o.trim()).filter(o => o.length > 0);
    if (!title.trim() || filteredOptions.length < 2) {
      alert('Please provide a title and at least 2 valid options.');
      return;
    }

    try {
      const formattedOptions = filteredOptions.map((optName, idx) => ({
        id: `opt_c_${idx}_${Date.now()}`,
        name: optName,
        party: 'Community Choice',
        avatarColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899'][idx % 5]
      }));

      const created = await api.createPoll({
        title,
        description,
        category,
        options: formattedOptions,
        allowVoteChange
      });

      setPolls([created, ...polls]);
      setIsNewPollOpen(false);
      setTitle('');
      setDescription('');
      setOptions(['Option 1', 'Option 2']);
    } catch (err: any) {
      alert(err.message || 'Failed to create poll.');
    }
  };

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      {/* Header */}
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <div className="flex items-center gap-2 mb-1">
            <span className="text-xs uppercase font-extrabold tracking-wider px-2 py-0.5 rounded-full bg-slate-100 text-slate-800 border border-slate-200">
              📋 Public & Community Opinion Polls
            </span>
          </div>
          <h1 className="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Explore All Polls
          </h1>
          <p className="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
            Participate in official platform polls or user-created community polls.
          </p>
        </div>

        <button
          onClick={() => {
            if (!currentUser) onOpenAuth();
            else setIsNewPollOpen(true);
          }}
          className="px-5 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer shrink-0"
        >
          <Plus className="w-4 h-4" />
          Create Community Poll
        </button>
      </div>

      {/* Tabs */}
      <div className="flex items-center gap-2 border-b border-slate-200 pb-3">
        <button
          onClick={() => setActiveTab('all')}
          className={`px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer ${
            activeTab === 'all'
              ? 'bg-slate-900 text-white shadow-xs'
              : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
          }`}
        >
          All Polls
        </button>
        <button
          onClick={() => setActiveTab('official')}
          className={`px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer ${
            activeTab === 'official'
              ? 'bg-slate-900 text-white shadow-xs'
              : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
          }`}
        >
          Featured / Official
        </button>
        <button
          onClick={() => setActiveTab('community')}
          className={`px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer ${
            activeTab === 'community'
              ? 'bg-slate-900 text-white shadow-xs'
              : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
          }`}
        >
          Community Created
        </button>
      </div>

      {/* Polls Feed */}
      {loading ? (
        <div className="p-12 text-center text-slate-400 font-medium bg-white rounded-3xl border border-slate-200">
          Loading polls...
        </div>
      ) : polls.length === 0 ? (
        <div className="bg-white rounded-3xl p-12 text-center space-y-3 border border-slate-200">
          <Users className="w-10 h-10 text-slate-300 mx-auto" />
          <h3 className="font-bold text-slate-800 text-base">No community polls yet</h3>
          <p className="text-xs text-slate-500">Create the first community poll to ask what Kenyans think!</p>
        </div>
      ) : (
        <div className="space-y-6">
          {polls.map((p) => (
            <FeaturedPollCard key={p.id} poll={p} onVoteSuccess={loadPolls} />
          ))}
        </div>
      )}

      {/* CREATE COMMUNITY POLL MODAL */}
      {isNewPollOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-200">
          <div className="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 relative max-h-[90vh] overflow-y-auto">
            <button
              onClick={() => setIsNewPollOpen(false)}
              className="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer"
            >
              <X className="w-5 h-5" />
            </button>

            <h2 className="text-xl font-bold text-slate-900 mb-1">Create Community Poll</h2>
            <p className="text-xs text-slate-500 mb-4">Ask fellow Kenyans for their opinion on any topic.</p>

            <form onSubmit={handleCreatePoll} className="space-y-4">
              <div>
                <label className="block text-xs font-bold uppercase text-slate-500 mb-1">Category</label>
                <select
                  value={category}
                  onChange={(e) => setCategory(e.target.value as PollCategory)}
                  className="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs font-bold text-slate-800"
                >
                  {CATEGORIES.map((c) => (
                    <option key={c} value={c}>{c}</option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold uppercase text-slate-500 mb-1">Poll Question</label>
                <input
                  type="text"
                  required
                  placeholder="e.g. What is the most effective approach to reducing food prices?"
                  value={title}
                  onChange={(e) => setTitle(e.target.value)}
                  className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-sm font-bold text-slate-900 focus:outline-none"
                />
              </div>

              <div>
                <label className="block text-xs font-bold uppercase text-slate-500 mb-1">Description (Optional)</label>
                <input
                  type="text"
                  placeholder="Provide context for voters..."
                  value={description}
                  onChange={(e) => setDescription(e.target.value)}
                  className="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs font-medium text-slate-800"
                />
              </div>

              <div>
                <label className="block text-xs font-bold uppercase text-slate-500 mb-1">Poll Options</label>
                <div className="space-y-2">
                  {options.map((opt, idx) => (
                    <input
                      key={idx}
                      type="text"
                      required
                      placeholder={`Option ${idx + 1}`}
                      value={opt}
                      onChange={(e) => handleOptionChange(idx, e.target.value)}
                      className="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs font-bold text-slate-900"
                    />
                  ))}
                </div>
                {options.length < 6 && (
                  <button
                    type="button"
                    onClick={handleAddOption}
                    className="mt-2 text-xs font-bold text-emerald-700 hover:underline cursor-pointer"
                  >
                    + Add another option
                  </button>
                )}
              </div>

              <div className="flex items-center gap-2 pt-1">
                <input
                  type="checkbox"
                  id="voteChange"
                  checked={allowVoteChange}
                  onChange={(e) => setAllowVoteChange(e.target.checked)}
                  className="rounded text-emerald-600 focus:ring-emerald-500"
                />
                <label htmlFor="voteChange" className="text-xs font-medium text-slate-700">
                  Allow voters to change their choice later
                </label>
              </div>

              <div className="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setIsNewPollOpen(false)}
                  className="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 cursor-pointer"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-6 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors cursor-pointer"
                >
                  Publish Community Poll
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
