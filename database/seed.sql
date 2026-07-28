-- ==========================================================
-- Kenyans Decision - Database Seed File
-- ==========================================================

-- Admin User (Password: AdminPassword2027!)
INSERT INTO `users` (`id`, `email`, `password_hash`, `display_name`, `role`, `county`, `created_at`)
VALUES (
  'usr_admin_001',
  'admin@kenyansdecision.co.ke',
  '$2y$10$e.wE6N/o3aTqK1UqfE9RxeH3gJmB1l/9O1d0s/kYp4eN1b0w1c1m.', -- password_hash for 'AdminPassword2027!'
  'Kenyans Decision Admin',
  'admin',
  'Nairobi',
  NOW()
) ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- Featured Kenya 2027 Presidential Poll
INSERT INTO `polls` (`id`, `slug`, `title`, `description`, `category`, `creator_type`, `creator_name`, `creator_id`, `allow_vote_change`, `closing_date`, `status`, `is_featured`, `created_at`, `updated_at`)
VALUES (
  'kenya-2027-presidential-opinion-poll',
  'kenya-2027-presidential-opinion-poll',
  'Kenya 2027 Presidential Opinion Poll',
  'If the presidential election were held today, who would you vote for?',
  '2027 Elections',
  'official',
  'Kenyans Decision Editorial',
  'usr_admin_001',
  1,
  NULL,
  'active',
  1,
  NOW(),
  NOW()
) ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Candidates Options
INSERT INTO `poll_options` (`id`, `poll_id`, `name`, `party`, `party_short`, `avatar_color`, `photo_url`, `sort_order`)
VALUES
  ('opt_ruto', 'kenya-2027-presidential-opinion-poll', 'Dr. William Samoei Ruto', 'United Democratic Alliance (UDA / Kenya Kwanza)', 'UDA', '#16a34a', NULL, 1),
  ('opt_raila', 'kenya-2027-presidential-opinion-poll', 'Raila Amolo Odinga', 'Azimio la Umoja - One Kenya Coalition / ODM', 'Azimio', '#2563eb', NULL, 2),
  ('opt_kalonzo', 'kenya-2027-presidential-opinion-poll', 'Stephen Kalonzo Musyoka', 'Wiper Democratic Movement - Kenya', 'Wiper', '#d97706', NULL, 3),
  ('opt_matiangi', 'kenya-2027-presidential-opinion-poll', 'Dr. Fred Matiang''i', 'Independent / Civic Movement', 'Independent', '#0284c7', NULL, 4),
  ('opt_wanjigi', 'kenya-2027-presidential-opinion-poll', 'Jimi Wanjigi', 'Safina Party', 'Safina', '#9333ea', NULL, 5),
  ('opt_wajackoyah', 'kenya-2027-presidential-opinion-poll', 'Prof. George Wajackoyah', 'Roots Party of Kenya', 'Roots Party', '#059669', NULL, 6),
  ('opt_undecided', 'kenya-2027-presidential-opinion-poll', 'Undecided / Other Candidate', 'None / Non-partisan', 'Undecided', '#64748b', NULL, 7)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Additional Community Poll 1: Priority Poll
INSERT INTO `polls` (`id`, `slug`, `title`, `description`, `category`, `creator_type`, `creator_name`, `creator_id`, `allow_vote_change`, `closing_date`, `status`, `is_featured`, `created_at`, `updated_at`)
VALUES (
  'kenya-top-priority-2026',
  'kenya-top-priority-2026',
  'What should Kenya prioritize most urgently in 2026/2027?',
  'A public opinion poll on the most critical national challenges facing Kenyans today.',
  'Cost of Living',
  'official',
  'Kenyans Decision Editorial',
  'usr_admin_001',
  1,
  NULL,
  'active',
  0,
  NOW(),
  NOW()
) ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

INSERT INTO `poll_options` (`id`, `poll_id`, `name`, `party`, `party_short`, `avatar_color`, `photo_url`, `sort_order`)
VALUES
  ('opt_col', 'kenya-top-priority-2026', 'Reducing Cost of Living & Food Prices', 'Economic Priority', 'Economy', '#ef4444', NULL, 1),
  ('opt_jobs', 'kenya-top-priority-2026', 'Youth Unemployment & Job Creation', 'Economic Priority', 'Jobs', '#3b82f6', NULL, 2),
  ('opt_shif', 'kenya-top-priority-2026', 'Healthcare Reform & Fixing SHIF', 'Social Priority', 'Healthcare', '#10b981', NULL, 3),
  ('opt_corrupt', 'kenya-top-priority-2026', 'Fighting Corruption & Financial Waste', 'Governance Priority', 'Governance', '#f59e0b', NULL, 4),
  ('opt_debt', 'kenya-top-priority-2026', 'Managing National Debt & Taxation', 'Economic Priority', 'Debt', '#8b5cf6', NULL, 5)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Discussions Seed
INSERT INTO `discussions` (`id`, `title`, `content`, `category`, `author_id`, `author_name`, `likes_count`, `comments_count`, `created_at`)
VALUES
  (
    'disc_001',
    'What key qualities should Kenyans look for in 2027 presidential candidates?',
    'As we approach 2027, economic stability, job creation for youth, and institutional integrity stand out as main concerns. What specific track records should voters evaluate when making up their minds?',
    '2027 Elections',
    'usr_admin_001',
    'Kenyans Decision Admin (Nairobi)',
    34,
    3,
    NOW()
  ),
  (
    'disc_002',
    'Impact of the Social Health Authority (SHIF) transition on households',
    'How has the SHIF transition affected healthcare accessibility in your county? Are local clinics and hospitals registering patients smoothly or encountering delays?',
    'Healthcare',
    'usr_admin_001',
    'Kenyans Decision Admin (Nairobi)',
    42,
    2,
    NOW()
  )
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Comments Seed
INSERT INTO `comments` (`id`, `discussion_id`, `author_id`, `author_name`, `content`, `created_at`)
VALUES
  ('cmnt_001', 'disc_001', 'usr_admin_001', 'Amina O. (Nairobi)', 'Voters must demand realistic economic plans rather than broad promises. Transparency in national debt management is vital.', NOW()),
  ('cmnt_002', 'disc_001', 'usr_admin_001', 'Kevin M. (Nakuru)', 'Youth representation and digital economy support should also be high on the agenda.', NOW()),
  ('cmnt_003', 'disc_001', 'usr_admin_001', 'David K. (Kisumu)', 'The most essential factor is unity and peace across all 47 counties before and after the ballot.', NOW())
ON DUPLICATE KEY UPDATE `content` = VALUES(`content`);

-- Seed Audit Log
INSERT INTO `admin_audit_logs` (`id`, `admin_email`, `action`, `target`, `timestamp`)
VALUES (
  'log_001',
  'admin@kenyansdecision.co.ke',
  'CREATE_OFFICIAL_POLL',
  'kenya-2027-presidential-opinion-poll',
  NOW()
) ON DUPLICATE KEY UPDATE `action` = VALUES(`action`);
