import express from 'express';
import path from 'path';
import { createServer as createViteServer } from 'vite';
import { db } from './server/db';
import { User } from './src/types';

async function startServer() {
  const app = express();
  const PORT = 3000;

  app.use(express.json());

  // Helper middleware to extract user header/token if present
  const getUserFromHeader = (req: express.Request): User | null => {
    const authHeader = req.headers.authorization;
    if (authHeader && authHeader.startsWith('Bearer ')) {
      const userId = authHeader.replace('Bearer ', '').trim();
      return db.getUserById(userId);
    }
    return null;
  };

  // --- PUBLIC API ROUTES ---

  // Analytics & Stats
  app.get('/api/analytics', (req, res) => {
    db.recordVisit();
    const analytics = db.getAnalytics();
    res.json(analytics);
  });

  // Track share
  app.post('/api/share', (req, res) => {
    db.recordShare();
    res.json({ success: true });
  });

  // Polls List
  app.get('/api/polls', (req, res) => {
    const category = req.query.category as string | undefined;
    const creatorType = req.query.creatorType as string | undefined;
    const polls = db.getAllPolls(category, creatorType);
    res.json(polls);
  });

  // Get single poll
  app.get('/api/polls/:id', (req, res) => {
    const poll = db.getPollById(req.params.id);
    if (!poll) {
      res.status(404).json({ error: 'Poll not found' });
      return;
    }
    res.json(poll);
  });

  // Get poll results
  app.get('/api/polls/:id/results', (req, res) => {
    try {
      const results = db.getPollResults(req.params.id);
      res.json(results);
    } catch (err: any) {
      res.status(404).json({ error: err.message || 'Poll results not found' });
    }
  });

  // Check voted status
  app.get('/api/polls/:id/voted-status', (req, res) => {
    const clientIp = (req.headers['x-forwarded-for'] as string) || req.socket.remoteAddress || '127.0.0.1';
    const fingerprint = (req.query.fingerprint as string) || 'anon_fp';
    const status = db.getVotedStatus(req.params.id, clientIp, fingerprint);
    res.json(status);
  });

  // Vote on poll
  app.post('/api/polls/:id/vote', (req, res) => {
    const { optionId, fingerprint, county, ageGroup } = req.body;
    if (!optionId || !fingerprint) {
      res.status(400).json({ error: 'Option ID and browser fingerprint are required' });
      return;
    }

    const clientIp = (req.headers['x-forwarded-for'] as string) || req.socket.remoteAddress || '127.0.0.1';
    const userAgent = req.headers['user-agent'] || 'unknown';
    const user = getUserFromHeader(req);

    try {
      const result = db.submitVote({
        pollId: req.params.id,
        optionId,
        ip: clientIp,
        fingerprint,
        userAgent,
        userId: user?.id,
        county,
        ageGroup
      });
      res.json(result);
    } catch (err: any) {
      res.status(400).json({ error: err.message || 'Failed to submit vote' });
    }
  });

  // Create community or official poll
  app.post('/api/polls', (req, res) => {
    const user = getUserFromHeader(req);
    const { title, description, category, options, allowVoteChange, closingDate, isFeatured } = req.body;

    if (!title || !options || !Array.isArray(options) || options.length < 2) {
      res.status(400).json({ error: 'Poll title and at least two options are required' });
      return;
    }

    try {
      const poll = db.createPoll(
        {
          title,
          description,
          category,
          options,
          allowVoteChange: allowVoteChange ?? true,
          closingDate,
          isFeatured
        },
        user || undefined
      );
      res.json(poll);
    } catch (err: any) {
      res.status(500).json({ error: err.message || 'Failed to create poll' });
    }
  });

  // Discussions List
  app.get('/api/discussions', (req, res) => {
    const category = req.query.category as string | undefined;
    const discussions = db.getDiscussions(category);
    res.json(discussions);
  });

  // Create Discussion Post
  app.post('/api/discussions', (req, res) => {
    const user = getUserFromHeader(req);
    if (!user) {
      res.status(401).json({ error: 'You must be signed in to post a discussion' });
      return;
    }

    const { title, content, category } = req.body;
    if (!title || !content) {
      res.status(400).json({ error: 'Title and content are required' });
      return;
    }

    const post = db.createDiscussion(title, content, category || 'General Kenya', user);
    res.json(post);
  });

  // Like Discussion
  app.post('/api/discussions/:id/like', (req, res) => {
    const likesCount = db.likeDiscussion(req.params.id);
    res.json({ likesCount });
  });

  // Get Comments
  app.get('/api/discussions/:id/comments', (req, res) => {
    const comments = db.getComments(req.params.id);
    res.json(comments);
  });

  // Add Comment
  app.post('/api/discussions/:id/comments', (req, res) => {
    const user = getUserFromHeader(req);
    if (!user) {
      res.status(401).json({ error: 'You must be signed in to add a comment' });
      return;
    }

    const { content } = req.body;
    if (!content) {
      res.status(400).json({ error: 'Comment content is required' });
      return;
    }

    const comment = db.addComment(req.params.id, content, user);
    res.json(comment);
  });

  // Auth: Register
  app.post('/api/auth/register', (req, res) => {
    const { email, password, displayName, county } = req.body;
    if (!email || !password || !displayName) {
      res.status(400).json({ error: 'Email, password, and display name are required' });
      return;
    }

    try {
      const result = db.registerUser(email, password, displayName, county);
      res.json(result);
    } catch (err: any) {
      res.status(400).json({ error: err.message || 'Registration failed' });
    }
  });

  // Auth: Login
  app.post('/api/auth/login', (req, res) => {
    const { email, password } = req.body;
    if (!email || !password) {
      res.status(400).json({ error: 'Email and password are required' });
      return;
    }

    try {
      const result = db.loginUser(email, password);
      res.json(result);
    } catch (err: any) {
      res.status(401).json({ error: err.message || 'Login failed' });
    }
  });

  // Auth: Get Current User
  app.get('/api/auth/me', (req, res) => {
    const user = getUserFromHeader(req);
    if (!user) {
      res.status(401).json({ error: 'Not authenticated' });
      return;
    }
    res.json(user);
  });

  // Submit Report
  app.post('/api/reports', (req, res) => {
    const user = getUserFromHeader(req);
    const { targetType, targetId, reason } = req.body;
    if (!targetType || !targetId || !reason) {
      res.status(400).json({ error: 'Target type, target ID, and reason are required' });
      return;
    }

    const report = db.submitReport(targetType, targetId, reason, user?.id);
    res.json(report);
  });

  // --- ADMIN ROUTES ---
  const requireAdmin = (req: express.Request, res: express.Response, next: express.NextFunction) => {
    const user = getUserFromHeader(req);
    if (!user || user.role !== 'admin') {
      res.status(403).json({ error: 'Admin access required' });
      return;
    }
    (req as any).adminUser = user;
    next();
  };

  app.get('/api/admin/votes', requireAdmin, (req, res) => {
    const votes = db.getRecentVotesForAudit();
    res.json(votes);
  });

  app.get('/api/admin/reports', requireAdmin, (req, res) => {
    const reports = db.getReports();
    res.json(reports);
  });

  app.post('/api/admin/reports/:id', requireAdmin, (req, res) => {
    const adminUser = (req as any).adminUser;
    const { status } = req.body;
    const report = db.updateReportStatus(req.params.id, status, adminUser);
    res.json(report);
  });

  app.get('/api/admin/audit-logs', requireAdmin, (req, res) => {
    const logs = db.getAuditLogs();
    res.json(logs);
  });

  app.delete('/api/admin/discussions/:id', requireAdmin, (req, res) => {
    const adminUser = (req as any).adminUser;
    db.deleteDiscussion(req.params.id, adminUser);
    res.json({ success: true });
  });

  app.delete('/api/admin/comments/:id', requireAdmin, (req, res) => {
    const adminUser = (req as any).adminUser;
    db.deleteComment(req.params.id, adminUser);
    res.json({ success: true });
  });

  app.post('/api/admin/polls/:id/status', requireAdmin, (req, res) => {
    const adminUser = (req as any).adminUser;
    const { status } = req.body;
    const updated = db.updatePoll(req.params.id, { status }, adminUser);
    res.json(updated);
  });

  // --- VITE / STATIC SERVING ---
  if (process.env.NODE_ENV !== 'production') {
    const vite = await createViteServer({
      server: { middlewareMode: true },
      appType: 'spa',
    });
    app.use(vite.middlewares);
  } else {
    const distPath = path.join(process.cwd(), 'dist');
    app.use(express.static(distPath));
    app.get('*', (req, res) => {
      res.sendFile(path.join(distPath, 'index.html'));
    });
  }

  app.listen(PORT, '0.0.0.0', () => {
    console.log(`Server running on http://0.0.0.0:${PORT}`);
  });
}

startServer();
