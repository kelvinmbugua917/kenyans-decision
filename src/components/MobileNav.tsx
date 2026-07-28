import React from 'react';
import { Vote, BarChart2, MessageSquare, ListFilter, Shield, User as UserIcon } from 'lucide-react';
import { User } from '../types';

interface MobileNavProps {
  currentTab: string;
  setCurrentTab: (tab: string) => void;
  currentUser: User | null;
  onOpenAuth: () => void;
}

export const MobileNav: React.FC<MobileNavProps> = ({
  currentTab,
  setCurrentTab,
  currentUser,
  onOpenAuth
}) => {
  return (
    <div className="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-200/80 px-2 py-1 shadow-lg">
      <nav className="flex items-center justify-around">
        <button
          onClick={() => setCurrentTab('home')}
          className={`flex flex-col items-center py-1.5 px-3 rounded-lg text-xs font-semibold transition-colors ${
            currentTab === 'home' ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-900'
          }`}
        >
          <Vote className="w-5 h-5 mb-0.5" />
          <span>Vote</span>
        </button>

        <button
          onClick={() => setCurrentTab('results')}
          className={`flex flex-col items-center py-1.5 px-3 rounded-lg text-xs font-semibold transition-colors ${
            currentTab === 'results' ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-900'
          }`}
        >
          <BarChart2 className="w-5 h-5 mb-0.5" />
          <span>Results</span>
        </button>

        <button
          onClick={() => setCurrentTab('discuss')}
          className={`flex flex-col items-center py-1.5 px-3 rounded-lg text-xs font-semibold transition-colors ${
            currentTab === 'discuss' ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-900'
          }`}
        >
          <MessageSquare className="w-5 h-5 mb-0.5" />
          <span>Discuss</span>
        </button>

        <button
          onClick={() => setCurrentTab('polls')}
          className={`flex flex-col items-center py-1.5 px-3 rounded-lg text-xs font-semibold transition-colors ${
            currentTab === 'polls' ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-900'
          }`}
        >
          <ListFilter className="w-5 h-5 mb-0.5" />
          <span>Polls</span>
        </button>

        {currentUser?.role === 'admin' ? (
          <button
            onClick={() => setCurrentTab('admin')}
            className={`flex flex-col items-center py-1.5 px-3 rounded-lg text-xs font-semibold transition-colors ${
              currentTab === 'admin' ? 'text-amber-700 font-bold' : 'text-slate-500 hover:text-slate-900'
            }`}
          >
            <Shield className="w-5 h-5 mb-0.5" />
            <span>Admin</span>
          </button>
        ) : currentUser ? (
          <button
            onClick={() => setCurrentTab('about')}
            className={`flex flex-col items-center py-1.5 px-3 rounded-lg text-xs font-semibold transition-colors ${
              currentTab === 'about' ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-900'
            }`}
          >
            <UserIcon className="w-5 h-5 mb-0.5" />
            <span>Profile</span>
          </button>
        ) : (
          <button
            onClick={onOpenAuth}
            className="flex flex-col items-center py-1.5 px-3 rounded-lg text-xs font-semibold text-slate-500 hover:text-slate-900"
          >
            <UserIcon className="w-5 h-5 mb-0.5" />
            <span>Sign In</span>
          </button>
        )}
      </nav>
    </div>
  );
};
