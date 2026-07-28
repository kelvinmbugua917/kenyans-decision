import express from 'express';
import fs from 'fs';
import path from 'path';

const app = express();
const PORT = 3000;

app.use(express.json());

// API endpoint to inspect PHP project files
app.get('/api/files', (req, res) => {
  const getFiles = (dir: string): string[] => {
    let results: string[] = [];
    const list = fs.readdirSync(dir);
    list.forEach(file => {
      const filePath = path.join(dir, file);
      const stat = fs.statSync(filePath);
      if (stat && stat.isDirectory()) {
        if (!file.startsWith('.') && file !== 'node_modules' && file !== 'dist') {
          results = results.concat(getFiles(filePath));
        }
      } else {
        results.push(filePath);
      }
    });
    return results;
  };

  try {
    const files = getFiles(process.cwd())
      .map(f => f.replace(process.cwd() + '/', ''))
      .filter(f => !f.startsWith('node_modules') && !f.startsWith('dist') && !f.startsWith('.git'));
    res.json({ files });
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

// Serve PHP app preview dashboard
app.get('*', (req, res) => {
  const html = `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kenyans Decision 🇰🇪 - Pure PHP/LAMP Stack Application</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen p-6 sm:p-12">
  <div class="max-w-4xl mx-auto space-y-8">
    
    <div class="border border-emerald-500/30 bg-emerald-950/30 p-8 rounded-3xl backdrop-blur-md">
      <div class="inline-flex items-center gap-2 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-4">
        Pure LAMP Stack Codebase
      </div>
      <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
        Kenyans Decision 🇰🇪
      </h1>
      <p class="text-slate-300 text-sm sm:text-base mt-2 leading-relaxed">
        This repository contains a <strong>100% pure PHP/LAMP stack application</strong> for public opinion polling, civic discussions, and 2027 Kenyan national priority analytics.
      </p>
    </div>

    <div class="bg-slate-800/80 border border-slate-700/80 p-6 sm:p-8 rounded-3xl space-y-6">
      <h2 class="text-xl font-bold text-white flex items-center gap-2">
        <span>📂</span> Project File Architecture
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
        <div class="p-4 bg-slate-900/80 border border-slate-700 rounded-2xl">
          <span class="text-emerald-400 font-bold block mb-1">/index.php</span>
          <span class="text-slate-400">Front Controller & PDO Routing Entry Point</span>
        </div>
        <div class="p-4 bg-slate-900/80 border border-slate-700 rounded-2xl">
          <span class="text-emerald-400 font-bold block mb-1">/.htaccess</span>
          <span class="text-slate-400">Apache mod_rewrite clean URL rules</span>
        </div>
        <div class="p-4 bg-slate-900/80 border border-slate-700 rounded-2xl">
          <span class="text-emerald-400 font-bold block mb-1">/app/</span>
          <span class="text-slate-400">Controllers, Models, Middleware & Services</span>
        </div>
        <div class="p-4 bg-slate-900/80 border border-slate-700 rounded-2xl">
          <span class="text-emerald-400 font-bold block mb-1">/views/</span>
          <span class="text-slate-400">Tailwind CSS Views (Polls, Forum, Admin, Auth)</span>
        </div>
        <div class="p-4 bg-slate-900/80 border border-slate-700 rounded-2xl">
          <span class="text-emerald-400 font-bold block mb-1">/config/</span>
          <span class="text-slate-400">Database & Security Configuration Files</span>
        </div>
        <div class="p-4 bg-slate-900/80 border border-slate-700 rounded-2xl">
          <span class="text-emerald-400 font-bold block mb-1">/database/</span>
          <span class="text-slate-400">schema.sql & seed.sql schema dumps</span>
        </div>
      </div>
    </div>

    <div class="bg-slate-800/80 border border-slate-700/80 p-6 sm:p-8 rounded-3xl space-y-4">
      <h2 class="text-xl font-bold text-white flex items-center gap-2">
        <span>🚀</span> Ready for LAMP / Shared Hosting Deployment
      </h2>
      <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
        Upload these files directly to any PHP 8.2+ server running Apache and MySQL/MariaDB (e.g. cPanel, DirectAdmin, LAMP stack). Consult <code class="text-emerald-300">DEPLOYMENT.md</code> for setup instructions.
      </p>
    </div>

  </div>
</body>
</html>`;
  res.send(html);
});

app.listen(PORT, '0.0.0.0', () => {
  console.log(`Server running on http://0.0.0.0:${PORT}`);
});

