import React, { useState, useEffect } from 'react';
import { DiscussionPost, DiscussionComment, User, PollCategory } from '../types';
import { api } from '../lib/api';
import { MessageSquare, Heart, Plus, MessageCircle, AlertTriangle, Send, User as UserIcon, X } from 'lucide-react';

interface DiscussionFeedProps {
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

export const DiscussionFeed: React.FC<DiscussionFeedProps> = ({ currentUser, onOpenAuth }) => {
  const [discussions, setDiscussions] = useState<DiscussionPost[]>([]);
  const [selectedCategory, setSelectedCategory] = useState<string>('All');
  const [loading, setLoading] = useState<boolean>(true);

  // Modal State
  const [isNewPostOpen, setIsNewPostOpen] = useState(false);
  const [newTitle, setNewTitle] = useState('');
  const [newContent, setNewContent] = useState('');
  const [newCategory, setNewCategory] = useState<PollCategory>('2027 Elections');

  // Active Discussion Comments Modal
  const [activeDiscussion, setActiveDiscussion] = useState<DiscussionPost | null>(null);
  const [comments, setComments] = useState<DiscussionComment[]>([]);
  const [newCommentText, setNewCommentText] = useState('');
  const [loadingComments, setLoadingComments] = useState(false);

  // Report Modal
  const [reportingTarget, setReportingTarget] = useState<{ id: string; type: 'post' | 'comment' } | null>(null);
  const [reportReason, setReportReason] = useState('Hate Speech / Harassment');
  const [reportSubmitted, setReportSubmitted] = useState(false);

  useEffect(() => {
    loadDiscussions();
  }, [selectedCategory]);

  const loadDiscussions = async () => {
    setLoading(true);
    try {
      const data = await api.getDiscussions(selectedCategory);
      setDiscussions(data);
    } catch (err) {
      console.error('Failed to load discussions:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleCreatePost = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!currentUser) {
      onOpenAuth();
      return;
    }
    if (!newTitle.trim() || !newContent.trim()) return;

    try {
      const created = await api.createDiscussion(newTitle, newContent, newCategory);
      setDiscussions([created, ...discussions]);
      setNewTitle('');
      setNewContent('');
      setIsNewPostOpen(false);
    } catch (err: any) {
      alert(err.message || 'Failed to publish discussion');
    }
  };

  const handleLike = async (id: string, e: React.MouseEvent) => {
    e.stopPropagation();
    try {
      const res = await api.likeDiscussion(id);
      setDiscussions(discussions.map(d => d.id === id ? { ...d, likesCount: res.likesCount, isLiked: true } : d));
    } catch (err) {
      console.error(err);
    }
  };

  const handleOpenComments = async (post: DiscussionPost) => {
    setActiveDiscussion(post);
    setLoadingComments(true);
    try {
      const cmnts = await api.getComments(post.id);
      setComments(cmnts);
    } catch (err) {
      console.error(err);
    } finally {
      setLoadingComments(false);
    }
  };

  const handleAddComment = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!currentUser) {
      onOpenAuth();
      return;
    }
    if (!activeDiscussion || !newCommentText.trim()) return;

    try {
      const cmnt = await api.addComment(activeDiscussion.id, newCommentText);
      setComments([...comments, cmnt]);
      setNewCommentText('');
      setDiscussions(discussions.map(d => d.id === activeDiscussion.id ? { ...d, commentsCount: d.commentsCount + 1 } : d));
    } catch (err: any) {
      alert(err.message || 'Failed to submit comment');
    }
  };

  const handleSendReport = async () => {
    if (!reportingTarget) return;
    try {
      await api.submitReport(reportingTarget.type, reportingTarget.id, reportReason);
      setReportSubmitted(true);
      setTimeout(() => {
        setReportSubmitted(false);
        setReportingTarget(null);
      }, 2000);
    } catch (err) {
      alert('Failed to submit report');
    }
  };

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      {/* Header Banner */}
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <div className="flex items-center gap-2 mb-1">
            <span className="text-xs uppercase font-extrabold tracking-wider px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
              💬 Community Discussions
            </span>
          </div>
          <h1 className="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Kenyans Are Talking
          </h1>
          <p className="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
            Join public conversations, share perspectives, and debate issues affecting Kenya.
          </p>
        </div>

        <button
          onClick={() => {
            if (!currentUser) onOpenAuth();
            else setIsNewPostOpen(true);
          }}
          className="px-5 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer shrink-0"
        >
          <Plus className="w-4 h-4" />
          Start Discussion
        </button>
      </div>

      {/* Categories Bar */}
      <div className="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
        <button
          onClick={() => setSelectedCategory('All')}
          className={`px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer ${
            selectedCategory === 'All'
              ? 'bg-slate-900 text-white shadow-xs'
              : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
          }`}
        >
          All Topics
        </button>
        {CATEGORIES.map((cat) => (
          <button
            key={cat}
            onClick={() => setSelectedCategory(cat)}
            className={`px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer ${
              selectedCategory === cat
                ? 'bg-slate-900 text-white shadow-xs'
                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
            }`}
          >
            {cat}
          </button>
        ))}
      </div>

      {/* Discussion Posts Feed */}
      {loading ? (
        <div className="bg-white rounded-3xl p-12 text-center text-slate-400 text-sm font-medium border border-slate-200">
          Loading discussion feed...
        </div>
      ) : discussions.length === 0 ? (
        <div className="bg-white rounded-3xl p-12 text-center space-y-3 border border-slate-200">
          <MessageSquare className="w-10 h-10 text-slate-300 mx-auto" />
          <h3 className="font-bold text-slate-800 text-base">No discussions yet in this category</h3>
          <p className="text-xs text-slate-500">Be the first to share your thoughts on issues affecting Kenyans.</p>
          <button
            onClick={() => {
              if (!currentUser) onOpenAuth();
              else setIsNewPostOpen(true);
            }}
            className="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors inline-block cursor-pointer"
          >
            Create Discussion
          </button>
        </div>
      ) : (
        <div className="space-y-4">
          {discussions.map((post) => (
            <div
              key={post.id}
              onClick={() => handleOpenComments(post)}
              className="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-2xs hover:border-slate-300 transition-all cursor-pointer group"
            >
              <div className="flex items-center justify-between gap-2 mb-2">
                <span className="text-[11px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                  {post.category}
                </span>
                <span className="text-xs font-medium text-slate-400">
                  {new Date(post.createdAt).toLocaleDateString()}
                </span>
              </div>

              <h2 className="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors mb-2">
                {post.title}
              </h2>
              <p className="text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-3 mb-4">
                {post.content}
              </p>

              <div className="flex items-center justify-between pt-3 border-t border-slate-100 text-xs font-semibold text-slate-500">
                <div className="flex items-center gap-1.5 text-slate-700">
                  <UserIcon className="w-3.5 h-3.5 text-slate-400" />
                  <span>{post.authorName}</span>
                </div>

                <div className="flex items-center gap-4">
                  <button
                    onClick={(e) => handleLike(post.id, e)}
                    className={`flex items-center gap-1.5 px-2.5 py-1 rounded-lg transition-colors cursor-pointer ${
                      post.isLiked ? 'text-rose-600 bg-rose-50' : 'hover:text-rose-600 hover:bg-slate-100'
                    }`}
                  >
                    <Heart className={`w-4 h-4 ${post.isLiked ? 'fill-rose-600 text-rose-600' : ''}`} />
                    <span>{post.likesCount}</span>
                  </button>

                  <div className="flex items-center gap-1.5 text-slate-500">
                    <MessageCircle className="w-4 h-4" />
                    <span>{post.commentsCount} comments</span>
                  </div>

                  <button
                    onClick={(e) => {
                      e.stopPropagation();
                      setReportingTarget({ id: post.id, type: 'post' });
                    }}
                    className="p-1 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                    title="Report content"
                  >
                    <AlertTriangle className="w-3.5 h-3.5" />
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* CREATE NEW POST MODAL */}
      {isNewPostOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-200">
          <div className="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 relative">
            <button
              onClick={() => setIsNewPostOpen(false)}
              className="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer"
            >
              <X className="w-5 h-5" />
            </button>

            <h2 className="text-xl font-bold text-slate-900 mb-4">Start a Discussion</h2>

            <form onSubmit={handleCreatePost} className="space-y-4">
              <div>
                <label className="block text-xs font-bold uppercase text-slate-500 mb-1">Category</label>
                <select
                  value={newCategory}
                  onChange={(e) => setNewCategory(e.target.value as PollCategory)}
                  className="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs font-bold text-slate-800 focus:outline-none"
                >
                  {CATEGORIES.map((c) => (
                    <option key={c} value={c}>{c}</option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold uppercase text-slate-500 mb-1">Title / Question</label>
                <input
                  type="text"
                  required
                  placeholder="e.g. How can local governance improve healthcare access?"
                  value={newTitle}
                  onChange={(e) => setNewTitle(e.target.value)}
                  className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-sm font-bold text-slate-900 focus:outline-none"
                />
              </div>

              <div>
                <label className="block text-xs font-bold uppercase text-slate-500 mb-1">Details & Perspective</label>
                <textarea
                  required
                  rows={4}
                  placeholder="Provide context and explain your perspective..."
                  value={newContent}
                  onChange={(e) => setNewContent(e.target.value)}
                  className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-xs sm:text-sm font-medium text-slate-900 focus:outline-none"
                />
              </div>

              <div className="flex justify-end gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setIsNewPostOpen(false)}
                  className="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 cursor-pointer"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-6 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors cursor-pointer"
                >
                  Publish Post
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* COMMENTS MODAL */}
      {activeDiscussion && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-200">
          <div className="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden relative">
            <div className="p-6 border-b border-slate-100 pr-12">
              <button
                onClick={() => setActiveDiscussion(null)}
                className="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer"
              >
                <X className="w-5 h-5" />
              </button>

              <span className="text-[10px] font-extrabold uppercase text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                {activeDiscussion.category}
              </span>
              <h2 className="text-lg font-bold text-slate-900 mt-1">{activeDiscussion.title}</h2>
              <p className="text-xs text-slate-500 font-medium mt-0.5">By {activeDiscussion.authorName}</p>
            </div>

            {/* Comments List */}
            <div className="flex-1 overflow-y-auto p-6 space-y-4">
              <div className="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs sm:text-sm text-slate-700 leading-relaxed">
                {activeDiscussion.content}
              </div>

              <h3 className="text-xs font-extrabold uppercase tracking-wider text-slate-400 pt-2">
                Comments ({comments.length})
              </h3>

              {loadingComments ? (
                <p className="text-xs text-slate-400">Loading comments...</p>
              ) : comments.length === 0 ? (
                <p className="text-xs text-slate-400 italic">No comments yet. Join the conversation!</p>
              ) : (
                comments.map((c) => (
                  <div key={c.id} className="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 text-xs text-slate-800">
                    <div className="flex items-center justify-between font-bold text-slate-900 mb-1">
                      <span>{c.authorName}</span>
                      <span className="text-[10px] text-slate-400 font-normal">{new Date(c.createdAt).toLocaleDateString()}</span>
                    </div>
                    <p className="leading-relaxed font-medium">{c.content}</p>
                  </div>
                ))
              )}
            </div>

            {/* Add Comment Input */}
            <form onSubmit={handleAddComment} className="p-4 border-t border-slate-100 bg-slate-50/50 flex gap-2">
              <input
                type="text"
                placeholder={currentUser ? "Write a polite comment..." : "Sign in to join the conversation"}
                value={newCommentText}
                onChange={(e) => setNewCommentText(e.target.value)}
                onClick={() => {
                  if (!currentUser) onOpenAuth();
                }}
                className="flex-1 bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-900 focus:outline-none"
              />
              <button
                type="submit"
                className="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition-colors flex items-center gap-1.5 cursor-pointer shrink-0"
              >
                <Send className="w-3.5 h-3.5" />
                Comment
              </button>
            </form>
          </div>
        </div>
      )}

      {/* REPORT CONTENT MODAL */}
      {reportingTarget && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in">
          <div className="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-200">
            <h3 className="text-lg font-bold text-slate-900 mb-2">Report Content</h3>
            <p className="text-xs text-slate-500 mb-4">Select reason for reporting to platform moderators:</p>

            {reportSubmitted ? (
              <div className="p-4 bg-emerald-50 text-emerald-800 rounded-2xl text-xs font-bold text-center">
                ✅ Report submitted. Thank you for keeping Kenyans Decision safe.
              </div>
            ) : (
              <div className="space-y-3">
                <select
                  value={reportReason}
                  onChange={(e) => setReportReason(e.target.value)}
                  className="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs font-bold text-slate-800"
                >
                  <option value="Hate Speech / Harassment">Hate Speech / Harassment</option>
                  <option value="Misinformation / Manipulation">Misinformation / Manipulation</option>
                  <option value="Spam / Advertisements">Spam / Advertisements</option>
                  <option value="Offensive Language">Offensive Language</option>
                </select>

                <div className="flex justify-end gap-2 pt-2">
                  <button
                    onClick={() => setReportingTarget(null)}
                    className="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 cursor-pointer"
                  >
                    Cancel
                  </button>
                  <button
                    onClick={handleSendReport}
                    className="px-4 py-2 rounded-xl bg-rose-600 text-white text-xs font-bold hover:bg-rose-700 transition-colors cursor-pointer"
                  >
                    Submit Report
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
};
