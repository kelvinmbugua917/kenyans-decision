import fs from 'fs';
import path from 'path';
import crypto from 'crypto';
import {
  Poll,
  CandidateOption,
  PollResult,
  OptionResult,
  User,
  DiscussionPost,
  DiscussionComment,
  ContentReport,
  AdminAuditLog,
  PlatformAnalytics,
  VoteRiskStatus
} from '../src/types';

interface VoteRecord {
  id: string;
  pollId: string;
  optionId: string;
  voterHash: string;
  ipHash: string;
  fingerprint: string;
  userId?: string;
  county?: string;
  ageGroup?: string;
  riskScore: VoteRiskStatus;
  createdAt: string;
}

interface DBData {
  users: User[];
  passwords: Record<string, string>; // userId -> hash
  polls: Poll[];
  votes: VoteRecord[];
  discussions: DiscussionPost[];
  comments: DiscussionComment[];
  reports: ContentReport[];
  adminAuditLogs: AdminAuditLog[];
  analytics: PlatformAnalytics;
  voterFingerprints: Record<string, number>; // fingerprint -> total vote count
}

const DATA_DIR = path.join(process.cwd(), 'data');
const DB_FILE = path.join(DATA_DIR, 'db.json');

function hashPassword(password: string): string {
  return crypto.createHash('sha256').update(password + 'KD_SECRET_SALT_2027').digest('hex');
}

// Initial seed
const defaultAdminId = 'usr_admin_001';
const initialAdminPasswordHash = hashPassword('AdminPassword2027!');

const initialPolls: Poll[] = [
  {
    id: 'kenya-2027-presidential-opinion-poll',
    slug: 'kenya-2027-presidential-opinion-poll',
    title: 'Kenya 2027 Presidential Opinion Poll',
    description: 'If the presidential election were held today, who would you vote for?',
    category: '2027 Elections',
    creatorType: 'official',
    creatorName: 'Kenyans Decision Editorial',
    creatorId: defaultAdminId,
    allowVoteChange: true,
    status: 'active',
    isFeatured: true,
    createdAt: new Date(Date.now() - 7 * 24 * 3600 * 1000).toISOString(),
    updatedAt: new Date().toISOString(),
    totalVotes: 0,
    options: [
      {
        id: 'opt_ruto',
        name: 'Dr. William Samoei Ruto',
        party: 'United Democratic Alliance (UDA / Kenya Kwanza)',
        partyShort: 'UDA',
        avatarColor: '#16a34a' // Green accent
      },
      {
        id: 'opt_raila',
        name: 'Raila Amolo Odinga',
        party: 'Azimio la Umoja - One Kenya Coalition / ODM',
        partyShort: 'Azimio',
        avatarColor: '#2563eb' // Blue
      },
      {
        id: 'opt_kalonzo',
        name: 'Stephen Kalonzo Musyoka',
        party: 'Wiper Democratic Movement - Kenya',
        partyShort: 'Wiper',
        avatarColor: '#d97706' // Amber
      },
      {
        id: 'opt_matiangi',
        name: 'Dr. Fred Matiang\'i',
        party: 'Independent / Civic Movement',
        partyShort: 'Independent',
        avatarColor: '#0284c7' // Sky
      },
      {
        id: 'opt_wanjigi',
        name: 'Jimi Wanjigi',
        party: 'Safina Party',
        partyShort: 'Safina',
        avatarColor: '#9333ea' // Purple
      },
      {
        id: 'opt_wajackoyah',
        name: 'Prof. George Wajackoyah',
        party: 'Roots Party of Kenya',
        partyShort: 'Roots Party',
        avatarColor: '#059669' // Emerald
      },
      {
        id: 'opt_undecided',
        name: 'Undecided / Other Candidate',
        party: 'None / Non-partisan',
        partyShort: 'Undecided',
        avatarColor: '#64748b' // Slate
      }
    ]
  },
  {
    id: 'kenya-top-priority-2026',
    slug: 'kenya-top-priority-2026',
    title: 'What should Kenya prioritize most urgently in 2026/2027?',
    description: 'A public opinion poll on the most critical national challenges facing Kenyans today.',
    category: 'Cost of Living',
    creatorType: 'official',
    creatorName: 'Kenyans Decision Editorial',
    creatorId: defaultAdminId,
    allowVoteChange: true,
    status: 'active',
    isFeatured: false,
    createdAt: new Date(Date.now() - 3 * 24 * 3600 * 1000).toISOString(),
    updatedAt: new Date().toISOString(),
    totalVotes: 0,
    options: [
      { id: 'opt_col', name: 'Reducing Cost of Living & Food Prices', party: 'Economic Priority', avatarColor: '#ef4444' },
      { id: 'opt_jobs', name: 'Youth Unemployment & Job Creation', party: 'Economic Priority', avatarColor: '#3b82f6' },
      { id: 'opt_shif', name: 'Healthcare Reform & Fixing SHIF', party: 'Social Priority', avatarColor: '#10b981' },
      { id: 'opt_corrupt', name: 'Fighting Corruption & Financial Waste', party: 'Governance Priority', avatarColor: '#f59e0b' },
      { id: 'opt_debt', name: 'Managing National Debt & Taxation', party: 'Economic Priority', avatarColor: '#8b5cf6' }
    ]
  },
  {
    id: 'digital-voting-kenya-2027',
    slug: 'digital-voting-kenya-2027',
    title: 'Should Kenya implement Electronic / Online Voting for 2027?',
    description: 'Debating whether electronic voting systems could enhance electoral transparency or introduce security risks.',
    category: 'Technology',
    creatorType: 'official',
    creatorName: 'Kenyans Decision Editorial',
    creatorId: defaultAdminId,
    allowVoteChange: false,
    status: 'active',
    isFeatured: false,
    createdAt: new Date(Date.now() - 2 * 24 * 3600 * 1000).toISOString(),
    updatedAt: new Date().toISOString(),
    totalVotes: 0,
    options: [
      { id: 'opt_yes_full', name: 'Yes - Fully Electronic Voting', party: 'Tech Reform', avatarColor: '#10b981' },
      { id: 'opt_yes_hybrid', name: 'Yes - Hybrid (Digital Transmission + Paper Ballot)', party: 'Electoral Reform', avatarColor: '#3b82f6' },
      { id: 'opt_no_paper', name: 'No - Stick strictly to Manual Paper Voting', party: 'Traditional Safety', avatarColor: '#f43f5e' },
      { id: 'opt_undecided_tech', name: 'Undecided / Needs More Security Guarantees', party: 'Neutral', avatarColor: '#64748b' }
    ]
  }
];

// Seed realistic initial votes across counties for the presidential poll
const seedCounties = ['Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Kiambu', 'Machakos', 'Uasin Gishu', 'Kilifi', 'Nyeri', 'Garissa', 'Kakamega', 'Meru'];
const seedAgeGroups = ['18-24', '25-34', '35-49', '50+'];

const seedVotes: VoteRecord[] = [];
let voteCounter = 1;

// Generate balanced sample votes for realistic distribution
const optionDistribution = [
  { optionId: 'opt_ruto', count: 485 },
  { optionId: 'opt_raila', count: 410 },
  { optionId: 'opt_kalonzo', count: 180 },
  { optionId: 'opt_matiangi', count: 125 },
  { optionId: 'opt_wanjigi', count: 45 },
  { optionId: 'opt_wajackoyah', count: 35 },
  { optionId: 'opt_undecided', count: 95 }
];

optionDistribution.forEach((dist) => {
  for (let i = 0; i < dist.count; i++) {
    const county = seedCounties[i % seedCounties.length];
    const ageGroup = seedAgeGroups[i % seedAgeGroups.length];
    const voterHash = crypto.createHash('sha256').update(`seed_voter_${dist.optionId}_${i}`).digest('hex');
    seedVotes.push({
      id: `v_${voteCounter++}`,
      pollId: 'kenya-2027-presidential-opinion-poll',
      optionId: dist.optionId,
      voterHash,
      ipHash: crypto.createHash('sha256').update(`ip_${i % 50}`).digest('hex'),
      fingerprint: `fp_seed_${i % 100}`,
      county,
      ageGroup,
      riskScore: 'trusted',
      createdAt: new Date(Date.now() - Math.floor(Math.random() * 5 * 24 * 3600 * 1000)).toISOString()
    });
  }
});

// Seed sample votes for the priority poll
[
  { optionId: 'opt_col', count: 320 },
  { optionId: 'opt_jobs', count: 280 },
  { optionId: 'opt_shif', count: 190 },
  { optionId: 'opt_corrupt', count: 210 },
  { optionId: 'opt_debt', count: 110 }
].forEach((dist) => {
  for (let i = 0; i < dist.count; i++) {
    const county = seedCounties[i % seedCounties.length];
    seedVotes.push({
      id: `v_${voteCounter++}`,
      pollId: 'kenya-top-priority-2026',
      optionId: dist.optionId,
      voterHash: crypto.createHash('sha256').update(`seed_voter_prio_${i}`).digest('hex'),
      ipHash: crypto.createHash('sha256').update(`ip_prio_${i % 30}`).digest('hex'),
      fingerprint: `fp_prio_${i % 60}`,
      county,
      riskScore: 'trusted',
      createdAt: new Date(Date.now() - Math.floor(Math.random() * 3 * 24 * 3600 * 1000)).toISOString()
    });
  }
});

// Update poll total votes based on seeds
initialPolls.forEach((p) => {
  p.totalVotes = seedVotes.filter((v) => v.pollId === p.id).length;
});

const initialDiscussions: DiscussionPost[] = [
  {
    id: 'disc_001',
    title: 'What key qualities should Kenyans look for in 2027 presidential candidates?',
    content: 'As we approach 2027, economic stability, job creation for youth, and institutional integrity stand out as main concerns. What specific track records should voters evaluate when making up their minds?',
    category: '2027 Elections',
    authorId: defaultAdminId,
    authorName: 'Kenyans Decision Admin',
    likesCount: 34,
    commentsCount: 3,
    createdAt: new Date(Date.now() - 2 * 24 * 3600 * 1000).toISOString()
  },
  {
    id: 'disc_002',
    title: 'Impact of the Social Health Authority (SHIF) transition on households',
    content: 'How has the SHIF transition affected healthcare accessibility in your county? Are local clinics and hospitals registering patients smoothly or encountering delays?',
    category: 'Healthcare',
    authorId: defaultAdminId,
    authorName: 'Kenyans Decision Admin',
    likesCount: 42,
    commentsCount: 2,
    createdAt: new Date(Date.now() - 1 * 24 * 3600 * 1000).toISOString()
  }
];

const initialComments: DiscussionComment[] = [
  {
    id: 'cmnt_001',
    discussionId: 'disc_001',
    authorId: defaultAdminId,
    authorName: 'Amina O. (Nairobi)',
    content: 'Voters must demand realistic economic plans rather than broad promises. Transparency in national debt management is vital.',
    createdAt: new Date(Date.now() - 1.5 * 24 * 3600 * 1000).toISOString()
  },
  {
    id: 'cmnt_002',
    discussionId: 'disc_001',
    authorId: defaultAdminId,
    authorName: 'Kevin M. (Nakuru)',
    content: 'Youth representation and digital economy support should also be high on the agenda.',
    createdAt: new Date(Date.now() - 1 * 24 * 3600 * 1000).toISOString()
  },
  {
    id: 'cmnt_003',
    discussionId: 'disc_001',
    authorId: defaultAdminId,
    authorName: 'David K. (Kisumu)',
    content: 'The most essential factor is unity and peace across all 47 counties before and after the ballot.',
    createdAt: new Date(Date.now() - 0.5 * 24 * 3600 * 1000).toISOString()
  }
];

const initialAuditLogs: AdminAuditLog[] = [
  {
    id: 'log_001',
    adminEmail: 'admin@kenyansdecision.co.ke',
    action: 'CREATE_OFFICIAL_POLL',
    target: 'kenya-2027-presidential-opinion-poll',
    timestamp: new Date(Date.now() - 7 * 24 * 3600 * 1000).toISOString()
  }
];

class DatabaseService {
  private data: DBData;

  constructor() {
    this.data = {
      users: [
        {
          id: defaultAdminId,
          email: 'admin@kenyansdecision.co.ke',
          displayName: 'Kenyans Decision Admin',
          role: 'admin',
          county: 'Nairobi',
          createdAt: new Date().toISOString()
        }
      ],
      passwords: {
        [defaultAdminId]: initialAdminPasswordHash
      },
      polls: initialPolls,
      votes: seedVotes,
      discussions: initialDiscussions,
      comments: initialComments,
      reports: [],
      adminAuditLogs: initialAuditLogs,
      analytics: {
        totalVisitors: 4820,
        totalVotes: seedVotes.length,
        totalPolls: initialPolls.length,
        totalDiscussions: initialDiscussions.length,
        totalRegisteredUsers: 1,
        votesToday: 142,
        visitorsToday: 680,
        sharesTotal: 310,
        suspiciousVotesCount: 0
      },
      voterFingerprints: {}
    };

    this.loadFromDisk();
  }

  private loadFromDisk() {
    try {
      if (fs.existsSync(DB_FILE)) {
        const raw = fs.readFileSync(DB_FILE, 'utf-8');
        const parsed = JSON.parse(raw);
        if (parsed.polls && parsed.votes) {
          this.data = parsed;
        }
      } else {
        this.saveToDisk();
      }
    } catch (err) {
      console.warn('Could not load existing db file, using seed state:', err);
    }
  }

  private saveToDisk() {
    try {
      if (!fs.existsSync(DATA_DIR)) {
        fs.mkdirSync(DATA_DIR, { recursive: true });
      }
      fs.writeFileSync(DB_FILE, JSON.stringify(this.data, null, 2), 'utf-8');
    } catch (err) {
      console.error('Error writing database to disk:', err);
    }
  }

  // --- ANALYTICS & VISITORS ---
  public recordVisit() {
    this.data.analytics.totalVisitors += 1;
    this.data.analytics.visitorsToday += 1;
    this.saveToDisk();
  }

  public recordShare() {
    this.data.analytics.sharesTotal += 1;
    this.saveToDisk();
  }

  public getAnalytics(): PlatformAnalytics {
    return {
      ...this.data.analytics,
      totalVotes: this.data.votes.filter(v => v.riskScore !== 'blocked').length,
      totalPolls: this.data.polls.length,
      totalDiscussions: this.data.discussions.length,
      totalRegisteredUsers: this.data.users.length,
      suspiciousVotesCount: this.data.votes.filter(v => v.riskScore === 'suspicious').length
    };
  }

  // --- POLLS ---
  public getAllPolls(category?: string, creatorType?: string): Poll[] {
    let result = [...this.data.polls];
    if (category && category !== 'All') {
      result = result.filter(p => p.category === category);
    }
    if (creatorType && creatorType !== 'all') {
      result = result.filter(p => p.creatorType === creatorType);
    }
    // recalculate totals
    result.forEach(p => {
      p.totalVotes = this.data.votes.filter(v => v.pollId === p.id && v.riskScore !== 'blocked').length;
    });
    return result.sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime());
  }

  public getPollById(idOrSlug: string): Poll | null {
    const poll = this.data.polls.find(p => p.id === idOrSlug || p.slug === idOrSlug);
    if (!poll) return null;
    poll.totalVotes = this.data.votes.filter(v => v.pollId === poll.id && v.riskScore !== 'blocked').length;
    return poll;
  }

  public createPoll(pollData: Partial<Poll>, creatorUser?: User): Poll {
    const id = pollData.slug || `poll_${Date.now()}_${Math.random().toString(36).substring(2, 6)}`;
    const newPoll: Poll = {
      id,
      slug: id,
      title: pollData.title || 'Untitled Poll',
      description: pollData.description || '',
      category: pollData.category || 'General Kenya',
      creatorType: creatorUser?.role === 'admin' ? 'official' : 'community',
      creatorName: creatorUser ? creatorUser.displayName : 'Anonymous Community Member',
      creatorId: creatorUser?.id,
      options: pollData.options || [],
      totalVotes: 0,
      allowVoteChange: pollData.allowVoteChange ?? true,
      closingDate: pollData.closingDate,
      status: 'active',
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString(),
      isFeatured: creatorUser?.role === 'admin' ? (pollData.isFeatured || false) : false
    };

    if (newPoll.isFeatured) {
      // Unfeature other polls
      this.data.polls.forEach(p => { p.isFeatured = false; });
    }

    this.data.polls.unshift(newPoll);
    this.saveToDisk();

    if (creatorUser?.role === 'admin') {
      this.addAuditLog(creatorUser.email, 'CREATE_OFFICIAL_POLL', newPoll.id);
    }

    return newPoll;
  }

  public updatePoll(pollId: string, updates: Partial<Poll>, adminUser: User): Poll | null {
    const index = this.data.polls.findIndex(p => p.id === pollId);
    if (index === -1) return null;

    const beforeState = JSON.stringify(this.data.polls[index]);
    this.data.polls[index] = {
      ...this.data.polls[index],
      ...updates,
      updatedAt: new Date().toISOString()
    };

    if (updates.isFeatured) {
      this.data.polls.forEach((p, i) => {
        if (i !== index) p.isFeatured = false;
      });
    }

    this.saveToDisk();
    this.addAuditLog(adminUser.email, 'UPDATE_POLL', pollId, beforeState, JSON.stringify(this.data.polls[index]));
    return this.data.polls[index];
  }

  // --- VOTING & ANTI-ABUSE ENGINE ---
  public submitVote(params: {
    pollId: string;
    optionId: string;
    ip: string;
    fingerprint: string;
    userAgent: string;
    userId?: string;
    county?: string;
    ageGroup?: string;
  }): { success: boolean; message: string; riskScore: VoteRiskStatus; pollResult: PollResult; selectedOptionId: string } {
    const { pollId, optionId, ip, fingerprint, userAgent, userId, county, ageGroup } = params;

    const poll = this.getPollById(pollId);
    if (!poll) throw new Error('Poll not found');
    if (poll.status === 'closed') throw new Error('This poll is closed');

    // Generate SHA-256 voter hash based on IP + Fingerprint + UserAgent
    const ipHash = crypto.createHash('sha256').update(ip + '_KD_IP_SALT').digest('hex');
    const voterHash = crypto.createHash('sha256').update(`${ipHash}_${fingerprint}_${pollId}`).digest('hex');

    // Risk Scoring Logic
    let riskScore: VoteRiskStatus = 'normal';
    const recentVotesFromIp = this.data.votes.filter(
      v => v.ipHash === ipHash && new Date(v.createdAt).getTime() > Date.now() - 60000
    );

    if (recentVotesFromIp.length > 8) {
      riskScore = 'suspicious';
    } else if (recentVotesFromIp.length > 20) {
      riskScore = 'blocked';
    } else if (userId || fingerprint.length > 10) {
      riskScore = 'trusted';
    }

    // Check existing vote
    const existingIndex = this.data.votes.findIndex(v => v.pollId === pollId && v.voterHash === voterHash);

    if (existingIndex !== -1) {
      if (!poll.allowVoteChange) {
        return {
          success: true,
          message: 'You have already voted in this poll.',
          riskScore: this.data.votes[existingIndex].riskScore,
          pollResult: this.getPollResults(pollId),
          selectedOptionId: this.data.votes[existingIndex].optionId
        };
      } else {
        // Change vote atomically
        this.data.votes[existingIndex].optionId = optionId;
        this.data.votes[existingIndex].county = county || this.data.votes[existingIndex].county;
        this.data.votes[existingIndex].ageGroup = ageGroup || this.data.votes[existingIndex].ageGroup;
        this.data.votes[existingIndex].createdAt = new Date().toISOString();
        this.saveToDisk();

        return {
          success: true,
          message: 'Your vote choice has been updated.',
          riskScore: this.data.votes[existingIndex].riskScore,
          pollResult: this.getPollResults(pollId),
          selectedOptionId: optionId
        };
      }
    }

    // Record new vote
    const newVote: VoteRecord = {
      id: `v_${Date.now()}_${Math.random().toString(36).substring(2, 6)}`,
      pollId,
      optionId,
      voterHash,
      ipHash,
      fingerprint,
      userId,
      county: county || 'Nairobi',
      ageGroup: ageGroup || '25-34',
      riskScore,
      createdAt: new Date().toISOString()
    };

    if (riskScore !== 'blocked') {
      this.data.votes.push(newVote);
      this.data.analytics.votesToday += 1;
      this.saveToDisk();
    }

    return {
      success: true,
      message: 'Your vote has been counted.',
      riskScore,
      pollResult: this.getPollResults(pollId),
      selectedOptionId: optionId
    };
  }

  public getVotedStatus(pollId: string, ip: string, fingerprint: string): { hasVoted: boolean; selectedOptionId?: string } {
    const ipHash = crypto.createHash('sha256').update(ip + '_KD_IP_SALT').digest('hex');
    const voterHash = crypto.createHash('sha256').update(`${ipHash}_${fingerprint}_${pollId}`).digest('hex');

    const existing = this.data.votes.find(v => v.pollId === pollId && v.voterHash === voterHash && v.riskScore !== 'blocked');
    if (existing) {
      return { hasVoted: true, selectedOptionId: existing.optionId };
    }
    return { hasVoted: false };
  }

  public getPollResults(pollId: string): PollResult {
    const poll = this.getPollById(pollId);
    if (!poll) throw new Error('Poll not found');

    const activeVotes = this.data.votes.filter(v => v.pollId === pollId && v.riskScore !== 'blocked');
    const totalVotes = activeVotes.length;

    const optionCounts: Record<string, number> = {};
    poll.options.forEach(opt => { optionCounts[opt.id] = 0; });

    const countyBreakdown: Record<string, Record<string, number>> = {};
    const ageBreakdown: Record<string, Record<string, number>> = {};

    activeVotes.forEach(v => {
      if (optionCounts[v.optionId] !== undefined) {
        optionCounts[v.optionId] += 1;
      }
      if (v.county) {
        if (!countyBreakdown[v.county]) countyBreakdown[v.county] = {};
        countyBreakdown[v.county][v.optionId] = (countyBreakdown[v.county][v.optionId] || 0) + 1;
      }
      if (v.ageGroup) {
        if (!ageBreakdown[v.ageGroup]) ageBreakdown[v.ageGroup] = {};
        ageBreakdown[v.ageGroup][v.optionId] = (ageBreakdown[v.ageGroup][v.optionId] || 0) + 1;
      }
    });

    const optionResults: OptionResult[] = poll.options.map(opt => {
      const votes = optionCounts[opt.id] || 0;
      const percentage = totalVotes > 0 ? parseFloat(((votes / totalVotes) * 100).toFixed(1)) : 0;
      return {
        optionId: opt.id,
        name: opt.name,
        party: opt.party,
        avatarColor: opt.avatarColor,
        votes,
        percentage
      };
    });

    // Sort by votes descending
    optionResults.sort((a, b) => b.votes - a.votes);

    return {
      pollId,
      totalVotes,
      updatedAt: new Date().toISOString(),
      optionResults,
      countyBreakdown,
      ageBreakdown
    };
  }

  // --- DISCUSSIONS & COMMENTS ---
  public getDiscussions(category?: string): DiscussionPost[] {
    let result = [...this.data.discussions];
    if (category && category !== 'All') {
      result = result.filter(d => d.category === category);
    }
    return result.sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime());
  }

  public createDiscussion(title: string, content: string, category: any, user: User): DiscussionPost {
    const post: DiscussionPost = {
      id: `disc_${Date.now()}_${Math.random().toString(36).substring(2, 6)}`,
      title,
      content,
      category,
      authorId: user.id,
      authorName: `${user.displayName} (${user.county || 'Kenya'})`,
      likesCount: 0,
      commentsCount: 0,
      createdAt: new Date().toISOString()
    };
    this.data.discussions.unshift(post);
    this.saveToDisk();
    return post;
  }

  public likeDiscussion(id: string): number {
    const disc = this.data.discussions.find(d => d.id === id);
    if (disc) {
      disc.likesCount += 1;
      this.saveToDisk();
      return disc.likesCount;
    }
    return 0;
  }

  public getComments(discussionId: string): DiscussionComment[] {
    return this.data.comments
      .filter(c => c.discussionId === discussionId)
      .sort((a, b) => new Date(a.createdAt).getTime() - new Date(b.createdAt).getTime());
  }

  public addComment(discussionId: string, content: string, user: User): DiscussionComment {
    const comment: DiscussionComment = {
      id: `cmnt_${Date.now()}_${Math.random().toString(36).substring(2, 6)}`,
      discussionId,
      authorId: user.id,
      authorName: `${user.displayName} (${user.county || 'Kenya'})`,
      content,
      createdAt: new Date().toISOString()
    };
    this.data.comments.push(comment);

    const disc = this.data.discussions.find(d => d.id === discussionId);
    if (disc) disc.commentsCount += 1;

    this.saveToDisk();
    return comment;
  }

  // --- USERS & AUTH ---
  public registerUser(email: string, pass: string, displayName: string, county?: string): { user: User; token: string } {
    const existing = this.data.users.find(u => u.email.toLowerCase() === email.toLowerCase());
    if (existing) throw new Error('User with this email already exists');

    const id = `usr_${Date.now()}_${Math.random().toString(36).substring(2, 6)}`;
    const user: User = {
      id,
      email: email.toLowerCase(),
      displayName,
      role: 'user',
      county: county || 'Nairobi',
      createdAt: new Date().toISOString()
    };

    this.data.users.push(user);
    this.data.passwords[id] = hashPassword(pass);
    this.saveToDisk();

    const token = crypto.createHash('sha256').update(`${id}_SESSION_SECRET_${Date.now()}`).digest('hex');
    return { user, token };
  }

  public loginUser(email: string, pass: string): { user: User; token: string } {
    const user = this.data.users.find(u => u.email.toLowerCase() === email.toLowerCase());
    if (!user) throw new Error('Invalid email or password');

    const hash = hashPassword(pass);
    if (this.data.passwords[user.id] !== hash) {
      throw new Error('Invalid email or password');
    }

    const token = crypto.createHash('sha256').update(`${user.id}_SESSION_SECRET_${Date.now()}`).digest('hex');
    return { user, token };
  }

  public getUserById(id: string): User | null {
    return this.data.users.find(u => u.id === id) || null;
  }

  // --- MODERATION & REPORTS ---
  public submitReport(targetType: 'post' | 'comment' | 'vote', targetId: string, reason: string, reporterId?: string): ContentReport {
    const report: ContentReport = {
      id: `rep_${Date.now()}_${Math.random().toString(36).substring(2, 6)}`,
      targetType,
      targetId,
      reason,
      reporterId,
      status: 'pending',
      createdAt: new Date().toISOString()
    };
    this.data.reports.unshift(report);
    this.saveToDisk();
    return report;
  }

  public getReports(): ContentReport[] {
    return this.data.reports;
  }

  public updateReportStatus(reportId: string, status: 'pending' | 'reviewed' | 'dismissed', adminUser: User): ContentReport | null {
    const rep = this.data.reports.find(r => r.id === reportId);
    if (!rep) return null;
    rep.status = status;
    this.saveToDisk();
    this.addAuditLog(adminUser.email, 'MODERATE_REPORT', reportId, undefined, `status:${status}`);
    return rep;
  }

  public deleteDiscussion(discussionId: string, adminUser: User) {
    this.data.discussions = this.data.discussions.filter(d => d.id !== discussionId);
    this.data.comments = this.data.comments.filter(c => c.discussionId !== discussionId);
    this.saveToDisk();
    this.addAuditLog(adminUser.email, 'DELETE_DISCUSSION', discussionId);
  }

  public deleteComment(commentId: string, adminUser: User) {
    const comment = this.data.comments.find(c => c.id === commentId);
    if (comment) {
      const disc = this.data.discussions.find(d => d.id === comment.discussionId);
      if (disc && disc.commentsCount > 0) disc.commentsCount -= 1;
      this.data.comments = this.data.comments.filter(c => c.id !== commentId);
      this.saveToDisk();
      this.addAuditLog(adminUser.email, 'DELETE_COMMENT', commentId);
    }
  }

  public getRecentVotesForAudit(): VoteRecord[] {
    return this.data.votes.slice(-100).reverse(); // Last 100 votes
  }

  public getAuditLogs(): AdminAuditLog[] {
    return this.data.adminAuditLogs;
  }

  private addAuditLog(adminEmail: string, action: string, target: string, beforeState?: string, afterState?: string) {
    this.data.adminAuditLogs.unshift({
      id: `log_${Date.now()}_${Math.random().toString(36).substring(2, 6)}`,
      adminEmail,
      action,
      target,
      beforeState,
      afterState,
      timestamp: new Date().toISOString()
    });
    this.saveToDisk();
  }
}

export const db = new DatabaseService();
