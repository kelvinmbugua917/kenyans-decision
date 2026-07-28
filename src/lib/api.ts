import {
  Poll,
  PollResult,
  User,
  DiscussionPost,
  DiscussionComment,
  ContentReport,
  AdminAuditLog,
  PlatformAnalytics,
  VoteRiskStatus
} from '../types';
import { getBrowserFingerprint } from './fingerprint';

const AUTH_KEY = 'kd_auth_user_token';

export function getAuthToken(): string | null {
  return localStorage.getItem(AUTH_KEY);
}

export function setAuthToken(token: string | null) {
  if (token) {
    localStorage.setItem(AUTH_KEY, token);
  } else {
    localStorage.removeItem(AUTH_KEY);
  }
}

async function apiRequest<T>(endpoint: string, options: RequestInit = {}): Promise<T> {
  const token = getAuthToken();
  const headers: Record<string, string> = {
    'Content-Type': 'application/json',
    ...(options.headers as Record<string, string> || {})
  };

  if (token) {
    headers['Authorization'] = `Bearer ${token}`;
  }

  const response = await fetch(endpoint, {
    ...options,
    headers
  });

  const data = await response.json();
  if (!response.ok) {
    throw new Error(data.error || 'An unexpected error occurred');
  }

  return data as T;
}

export const api = {
  getAnalytics: () => apiRequest<PlatformAnalytics>('/api/analytics'),

  trackShare: () => apiRequest<{ success: boolean }>('/api/share', { method: 'POST' }),

  getPolls: (category?: string, creatorType?: string) => {
    const params = new URLSearchParams();
    if (category) params.append('category', category);
    if (creatorType) params.append('creatorType', creatorType);
    return apiRequest<Poll[]>(`/api/polls?${params.toString()}`);
  },

  getPoll: (idOrSlug: string) => apiRequest<Poll>(`/api/polls/${idOrSlug}`),

  getPollResults: (idOrSlug: string) => apiRequest<PollResult>(`/api/polls/${idOrSlug}/results`),

  getVotedStatus: (idOrSlug: string) => {
    const fp = getBrowserFingerprint();
    return apiRequest<{ hasVoted: boolean; selectedOptionId?: string }>(
      `/api/polls/${idOrSlug}/voted-status?fingerprint=${encodeURIComponent(fp)}`
    );
  },

  castVote: (pollId: string, optionId: string, county?: string, ageGroup?: string) => {
    const fp = getBrowserFingerprint();
    return apiRequest<{
      success: boolean;
      message: string;
      riskScore: VoteRiskStatus;
      pollResult: PollResult;
      selectedOptionId: string;
    }>(`/api/polls/${pollId}/vote`, {
      method: 'POST',
      body: JSON.stringify({
        optionId,
        fingerprint: fp,
        county,
        ageGroup
      })
    });
  },

  createPoll: (pollData: Partial<Poll>) =>
    apiRequest<Poll>('/api/polls', {
      method: 'POST',
      body: JSON.stringify(pollData)
    }),

  getDiscussions: (category?: string) => {
    const params = new URLSearchParams();
    if (category) params.append('category', category);
    return apiRequest<DiscussionPost[]>(`/api/discussions?${params.toString()}`);
  },

  createDiscussion: (title: string, content: string, category: string) =>
    apiRequest<DiscussionPost>('/api/discussions', {
      method: 'POST',
      body: JSON.stringify({ title, content, category })
    }),

  likeDiscussion: (id: string) =>
    apiRequest<{ likesCount: number }>(`/api/discussions/${id}/like`, { method: 'POST' }),

  getComments: (discussionId: string) =>
    apiRequest<DiscussionComment[]>(`/api/discussions/${discussionId}/comments`),

  addComment: (discussionId: string, content: string) =>
    apiRequest<DiscussionComment>(`/api/discussions/${discussionId}/comments`, {
      method: 'POST',
      body: JSON.stringify({ content })
    }),

  register: (email: string, pass: string, displayName: string, county?: string) =>
    apiRequest<{ user: User; token: string }>('/api/auth/register', {
      method: 'POST',
      body: JSON.stringify({ email, password: pass, displayName, county })
    }),

  login: (email: string, pass: string) =>
    apiRequest<{ user: User; token: string }>('/api/auth/login', {
      method: 'POST',
      body: JSON.stringify({ email, password: pass })
    }),

  getCurrentUser: () => apiRequest<User>('/api/auth/me'),

  submitReport: (targetType: 'post' | 'comment' | 'vote', targetId: string, reason: string) =>
    apiRequest<ContentReport>('/api/reports', {
      method: 'POST',
      body: JSON.stringify({ targetType, targetId, reason })
    }),

  // Admin
  getAdminVotes: () => apiRequest<any[]>('/api/admin/votes'),
  getAdminReports: () => apiRequest<ContentReport[]>('/api/admin/reports'),
  updateReportStatus: (id: string, status: 'pending' | 'reviewed' | 'dismissed') =>
    apiRequest<ContentReport>(`/api/admin/reports/${id}`, {
      method: 'POST',
      body: JSON.stringify({ status })
    }),
  getAdminAuditLogs: () => apiRequest<AdminAuditLog[]>('/api/admin/audit-logs'),
  deleteDiscussion: (id: string) =>
    apiRequest<{ success: boolean }>(`/api/admin/discussions/${id}`, { method: 'DELETE' }),
  deleteComment: (id: string) =>
    apiRequest<{ success: boolean }>(`/api/admin/comments/${id}`, { method: 'DELETE' }),
  togglePollStatus: (id: string, status: 'active' | 'closed') =>
    apiRequest<Poll>(`/api/admin/polls/${id}/status`, {
      method: 'POST',
      body: JSON.stringify({ status })
    })
};
