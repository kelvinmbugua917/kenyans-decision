export type CandidateOption = {
  id: string;
  name: string;
  party: string;
  partyShort?: string;
  avatarColor: string;
  photoUrl?: string;
};

export type PollCategory =
  | '2027 Elections'
  | 'Cost of Living'
  | 'Jobs & Economy'
  | 'Education'
  | 'Healthcare'
  | 'Housing'
  | 'Technology'
  | 'Governance & Corruption'
  | 'General Kenya';

export type Poll = {
  id: string;
  slug: string;
  title: string;
  description: string;
  category: PollCategory;
  creatorType: 'official' | 'community';
  creatorName?: string;
  creatorId?: string;
  options: CandidateOption[];
  totalVotes: number;
  allowVoteChange: boolean;
  closingDate?: string;
  status: 'active' | 'closed';
  createdAt: string;
  updatedAt: string;
  isFeatured?: boolean;
};

export type OptionResult = {
  optionId: string;
  name: string;
  party: string;
  avatarColor: string;
  votes: number;
  percentage: number;
};

export type PollResult = {
  pollId: string;
  totalVotes: number;
  updatedAt: string;
  optionResults: OptionResult[];
  countyBreakdown?: Record<string, Record<string, number>>; // county -> optionId -> count
  ageBreakdown?: Record<string, Record<string, number>>; // ageGroup -> optionId -> count
};

export type VoteRiskStatus = 'trusted' | 'normal' | 'suspicious' | 'blocked';

export type User = {
  id: string;
  email: string;
  displayName: string;
  role: 'admin' | 'user';
  county?: string;
  createdAt: string;
};

export type DiscussionPost = {
  id: string;
  title: string;
  content: string;
  category: PollCategory;
  authorId: string;
  authorName: string;
  likesCount: number;
  commentsCount: number;
  createdAt: string;
  isLiked?: boolean;
};

export type DiscussionComment = {
  id: string;
  discussionId: string;
  authorId: string;
  authorName: string;
  content: string;
  createdAt: string;
};

export type ContentReport = {
  id: string;
  targetType: 'post' | 'comment' | 'vote';
  targetId: string;
  reason: string;
  reporterId?: string;
  status: 'pending' | 'reviewed' | 'dismissed';
  createdAt: string;
  details?: string;
};

export type AdminAuditLog = {
  id: string;
  adminEmail: string;
  action: string;
  target: string;
  beforeState?: string;
  afterState?: string;
  timestamp: string;
};

export type PlatformAnalytics = {
  totalVisitors: number;
  totalVotes: number;
  totalPolls: number;
  totalDiscussions: number;
  totalRegisteredUsers: number;
  votesToday: number;
  visitorsToday: number;
  sharesTotal: number;
  suspiciousVotesCount: number;
};

export const KENYAN_COUNTIES = [
  'Nairobi',
  'Mombasa',
  'Kisumu',
  'Nakuru',
  'Kiambu',
  'Machakos',
  'Uasin Gishu',
  'Kilifi',
  'Nyeri',
  'Garissa',
  'Kakamega',
  'Meru',
  'Murang\'a',
  'Kericho',
  'Bungoma',
  'Kajiado',
  'Narok',
  'Turkana',
  'Trans Nzoia',
  'Siaya',
  'Homa Bay',
  'Migori',
  'Kisii',
  'Nyamira',
  'Bomet',
  'Embu',
  'Kitui',
  'Makueni',
  'Nyandarua',
  'Laikipia',
  'Kirinyaga',
  'Taita Taveta',
  'Kwale',
  'Lamu',
  'Tana River',
  'Wajir',
  'Mandera',
  'Marsabit',
  'Isiolo',
  'Samburu',
  'West Pokot',
  'Baringo',
  'Elgeyo Marakwet',
  'Nandi',
  'Vihiga',
  'Busia',
  'Tharaka Nithi'
] as const;

export const AGE_GROUPS = ['18-24', '25-34', '35-49', '50+'] as const;
