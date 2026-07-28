import React, { useState, useEffect } from 'react';
import { PlatformAnalytics, AdminAuditLog, ContentReport, User } from '../types';
import { api } from '../lib/api';
import { Shield, Eye, AlertOctagon, FileText, CheckCircle2, Trash2, Ban, Lock, RefreshCw } from 'lucide-react';

interface AdminPanelProps {
  currentUser: User | null;
}

export const AdminPanel: React.FC<AdminPanelProps> = ({ currentUser }) => {
  const [analytics, setAnalytics] = useState<PlatformAnalytics | null>(null);
  const [auditLogs, setAuditLogs] = useState<AdminAuditLog[]>([]);
  const [reports, setReports] = useState<ContentReport[]>([]);
  const [recentVotes, setRecentVotes] = useState<any[]>([]);
  const [activeTab, setActiveTab] = useState<'analytics' | 'votes' | 'reports' | 'audit'>('analytics');
  const [loading, setLoading] = useState<boolean>(true);

  useEffect(() => {
    if (currentUser?.role === 'admin') {
      loadAdminData();
    }
  }, [currentUser]);

  const loadAdminData = async () => {
    setLoading(true);
    try {
      const [statsRes, logsRes, reportsRes, votesRes] = await Promise.all([
        api.getAnalytics(),
        api.getAdminAuditLogs(),
        api.getAdminReports(),
        api.getAdminVotes()
      ]);

      setAnalytics(statsRes);
      setAuditLogs(logsRes);
      setReports(reportsRes);
      setRecentVotes(votesRes);
    } catch (err) {
      console.error('Failed to load admin data:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleUpdateReport = async (id: string, status: 'pending' | 'reviewed' | 'dismissed') => {
    try {
      await api.updateReportStatus(id, status);
      setReports(reports.map(r => r.id === id ? { ...r, status } : r));
    } catch (err: any) {
      alert(err.message || 'Failed to update report status');
    }
  };

  const handleDeletePost = async (postId: string) => {
    if (!confirm('Are you sure you want to delete this discussion post?')) return;
    try {
      await api.deleteDiscussion(postId);
      alert('Post deleted.');
      loadAdminData();
    } catch (err: any) {
      alert(err.message || 'Failed to delete post');
    }
  };

  if (!currentUser || currentUser.role !== 'admin') {
    return (
      <div className="max-w-md mx-auto my-12 p-8 bg-white rounded-3xl border border-slate-200 text-center space-y-4">
        <Lock className="w-12 h-12 text-rose-500 mx-auto" />
        <h2 className="text-xl font-bold text-slate-900">Admin Access Required</h2>
        <p className="text-xs text-slate-500 leading-relaxed">
          You must be signed in with administrative credentials to access the Kenyans Decision audit and moderation panel.
        </p>
      </div>
    );
  }

  return (
    <div className="max-w-5xl mx-auto space-y-6">
      {/* Admin Banner */}
      <div className="bg-slate-900 text-white p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <div className="flex items-center gap-2 mb-1">
            <Shield className="w-4 h-4 text-amber-400" />
            <span className="text-xs font-bold uppercase tracking-wider text-amber-400">
              Admin & Anti-Abuse Dashboard
            </span>
          </div>
          <h1 className="text-2xl font-black text-white">Platform Governance</h1>
          <p className="text-xs text-slate-400 mt-0.5">
            Logged in as {currentUser.email} • Audit logging active
          </p>
        </div>

        <button
          onClick={loadAdminData}
          className="p-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl transition-colors cursor-pointer self-start sm:self-auto"
          title="Refresh statistics"
        >
          <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
        </button>
      </div>

      {/* Admin Tabs */}
      <div className="flex items-center gap-2 border-b border-slate-200 pb-3 overflow-x-auto">
        <button
          onClick={() => setActiveTab('analytics')}
          className={`px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer ${
            activeTab === 'analytics'
              ? 'bg-slate-900 text-white shadow-xs'
              : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
          }`}
        >
          Overview & Metrics
        </button>
        <button
          onClick={() => setActiveTab('votes')}
          className={`px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer ${
            activeTab === 'votes'
              ? 'bg-slate-900 text-white shadow-xs'
              : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
          }`}
        >
          Anti-Abuse Vote Log ({recentVotes.length})
        </button>
        <button
          onClick={() => setActiveTab('reports')}
          className={`px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer ${
            activeTab === 'reports'
              ? 'bg-slate-900 text-white shadow-xs'
              : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
          }`}
        >
          Moderation Queue ({reports.filter(r => r.status === 'pending').length})
        </button>
        <button
          onClick={() => setActiveTab('audit')}
          className={`px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer ${
            activeTab === 'audit'
              ? 'bg-slate-900 text-white shadow-xs'
              : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
          }`}
        >
          Audit Logs ({auditLogs.length})
        </button>
      </div>

      {/* TAB 1: ANALYTICS METRICS */}
      {activeTab === 'analytics' && analytics && (
        <div className="space-y-6">
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div className="p-5 bg-white rounded-2xl border border-slate-200 shadow-2xs">
              <span className="text-[11px] font-bold uppercase text-slate-400">Total Visitors</span>
              <p className="text-2xl font-black text-slate-900 mt-1">{analytics.totalVisitors.toLocaleString()}</p>
              <span className="text-[10px] text-emerald-600 font-bold">+{analytics.visitorsToday} today</span>
            </div>

            <div className="p-5 bg-white rounded-2xl border border-slate-200 shadow-2xs">
              <span className="text-[11px] font-bold uppercase text-slate-400">Total Votes</span>
              <p className="text-2xl font-black text-slate-900 mt-1">{analytics.totalVotes.toLocaleString()}</p>
              <span className="text-[10px] text-emerald-600 font-bold">+{analytics.votesToday} today</span>
            </div>

            <div className="p-5 bg-white rounded-2xl border border-slate-200 shadow-2xs">
              <span className="text-[11px] font-bold uppercase text-slate-400">Social Shares</span>
              <p className="text-2xl font-black text-slate-900 mt-1">{analytics.sharesTotal.toLocaleString()}</p>
              <span className="text-[10px] text-slate-500 font-medium">WhatsApp / X / FB</span>
            </div>

            <div className="p-5 bg-white rounded-2xl border border-slate-200 shadow-2xs">
              <span className="text-[11px] font-bold uppercase text-slate-400">Suspicious Votes</span>
              <p className="text-2xl font-black text-amber-600 mt-1">{analytics.suspiciousVotesCount}</p>
              <span className="text-[10px] text-slate-500 font-medium">Filtered & Flagged</span>
            </div>
          </div>

          <div className="p-6 bg-white rounded-3xl border border-slate-200 space-y-3">
            <h3 className="font-extrabold text-slate-900 text-base">Platform Integrity Rules</h3>
            <p className="text-xs text-slate-600 leading-relaxed">
              Every administrative change, status toggle, or moderation action is permanently logged to an unalterable audit log containing timestamp, admin email, action, and targeted entity.
              Public vote counts are calculated strictly server-side through fingerprint verification.
            </p>
          </div>
        </div>
      )}

      {/* TAB 2: VOTES ANTI-ABUSE LOG */}
      {activeTab === 'votes' && (
        <div className="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
          <h3 className="text-base font-extrabold text-slate-900">Recent Server Anti-Abuse Vote Log</h3>
          <p className="text-xs text-slate-500 font-medium">
            Displays privacy-hashed IP signals, browser fingerprints, risk scores, and county mapping.
          </p>

          <div className="overflow-x-auto">
            <table className="w-full text-left text-xs font-medium text-slate-700">
              <thead className="bg-slate-100 text-slate-900 uppercase font-extrabold text-[10px]">
                <tr>
                  <th className="p-2.5">Time</th>
                  <th className="p-2.5">Poll</th>
                  <th className="p-2.5">County</th>
                  <th className="p-2.5">Risk Score</th>
                  <th className="p-2.5">Hashed IP Signal</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100 font-mono text-[11px]">
                {recentVotes.map((v, i) => (
                  <tr key={v.id || i} className="hover:bg-slate-50">
                    <td className="p-2.5">{new Date(v.createdAt).toLocaleTimeString()}</td>
                    <td className="p-2.5 font-sans font-bold text-slate-900">{v.pollId}</td>
                    <td className="p-2.5 font-sans">{v.county || 'Nairobi'}</td>
                    <td className="p-2.5">
                      <span className={`px-2 py-0.5 rounded-full font-sans font-extrabold text-[10px] uppercase ${
                        v.riskScore === 'trusted' ? 'bg-emerald-100 text-emerald-800' :
                        v.riskScore === 'suspicious' ? 'bg-amber-100 text-amber-900' :
                        v.riskScore === 'blocked' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-800'
                      }`}>
                        {v.riskScore}
                      </span>
                    </td>
                    <td className="p-2.5 text-slate-400">{v.ipHash?.substring(0, 16)}...</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* TAB 3: MODERATION REPORTS QUEUE */}
      {activeTab === 'reports' && (
        <div className="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
          <h3 className="text-base font-extrabold text-slate-900">User Report Queue</h3>

          {reports.length === 0 ? (
            <p className="text-xs text-slate-400 py-6 text-center">No reports currently pending.</p>
          ) : (
            <div className="space-y-3">
              {reports.map((r) => (
                <div key={r.id} className="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between gap-3 text-xs">
                  <div>
                    <div className="flex items-center gap-2 mb-1">
                      <span className="font-bold text-slate-900 uppercase text-[10px] px-2 py-0.5 rounded-md bg-rose-100 text-rose-800">
                        {r.reason}
                      </span>
                      <span className="text-slate-400 font-medium">Target: {r.targetType} ({r.targetId})</span>
                    </div>
                    <p className="text-slate-500 font-medium">Reported on {new Date(r.createdAt).toLocaleString()}</p>
                  </div>

                  <div className="flex items-center gap-2">
                    {r.targetType === 'post' && (
                      <button
                        onClick={() => handleDeletePost(r.targetId)}
                        className="p-2 text-rose-600 hover:bg-rose-100 rounded-xl transition-colors font-bold flex items-center gap-1"
                      >
                        <Trash2 className="w-3.5 h-3.5" />
                        Delete Post
                      </button>
                    )}
                    <button
                      onClick={() => handleUpdateReport(r.id, 'dismissed')}
                      className="px-3 py-1.5 bg-slate-200 text-slate-800 rounded-xl font-bold hover:bg-slate-300"
                    >
                      Dismiss
                    </button>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      )}

      {/* TAB 4: AUDIT LOGS */}
      {activeTab === 'audit' && (
        <div className="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
          <h3 className="text-base font-extrabold text-slate-900">Append-Only Admin Audit Log</h3>
          <div className="space-y-2 font-mono text-xs">
            {auditLogs.map((log) => (
              <div key={log.id} className="p-3 bg-slate-50 rounded-xl border border-slate-200 flex flex-wrap items-center justify-between gap-2">
                <div>
                  <span className="font-bold text-slate-900">{log.action}</span>
                  <span className="text-slate-500 ml-2">by {log.adminEmail}</span>
                  <span className="text-slate-400 block text-[11px] font-sans">Target: {log.target}</span>
                </div>
                <span className="text-[11px] text-slate-400">{new Date(log.timestamp).toLocaleString()}</span>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};
