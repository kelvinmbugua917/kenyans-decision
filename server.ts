import express from 'express';
import { spawn } from 'child_process';
import http from 'http';
import path from 'path';

const app = express();
const PORT = 3000;
const PHP_PORT = 8000;

// Spawn PHP Built-in Web Server
const phpProcess = spawn('php', ['-S', `127.0.0.1:${PHP_PORT}`, 'index.php'], {
  cwd: process.cwd(),
  stdio: 'inherit',
});

phpProcess.on('error', (err) => {
  console.error('Failed to start PHP process:', err);
});

// Proxy HTTP requests to PHP Built-in Server
app.use((req, res) => {
  const options: http.RequestOptions = {
    hostname: '127.0.0.1',
    port: PHP_PORT,
    path: req.url,
    method: req.method,
    headers: {
      ...req.headers,
      host: req.headers.host || `localhost:${PORT}`,
    },
  };

  const proxyReq = http.request(options, (proxyRes) => {
    res.writeHead(proxyRes.statusCode || 200, proxyRes.headers);
    proxyRes.pipe(res, { end: true });
  });

  proxyReq.on('error', (err) => {
    console.error('Proxy request error:', err);
    if (!res.headersSent) {
      res.status(502).send('PHP Server Connection Error');
    }
  });

  req.pipe(proxyReq, { end: true });
});

app.listen(PORT, '0.0.0.0', () => {
  console.log(`Node Express reverse proxy listening on http://0.0.0.0:${PORT} -> PHP 127.0.0.1:${PHP_PORT}`);
});
