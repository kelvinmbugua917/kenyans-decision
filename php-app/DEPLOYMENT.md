# Kenyans Decision 🇰🇪 - Single-Deployment PHP/LAMP Deployment Guide

This document provides step-by-step instructions for deploying **Kenyans Decision** to standard, affordable PHP shared hosting (cPanel, DirectAdmin, Apache, MySQL/MariaDB).

---

## 📋 System Requirements

* **PHP Version:** 8.2 or higher
* **PHP Extensions:** `pdo`, `pdo_mysql`, `mbstring`, `json`, `session`, `openssl`
* **Database:** MySQL 8.0+ or MariaDB 10.5+
* **Web Server:** Apache 2.4+ with `mod_rewrite` enabled

---

## 🚀 Step-by-Step Deployment Guide

### Step 1: Upload Application Files

1. Compress all project files into a `.zip` archive (or upload via FTP/SFTP).
2. Extract all files into your web server root directory (typically `public_html` or `/var/www/html/`).

Ensure the following file structure is present:
```
/ (public_html)
├── .htaccess
├── index.php
├── config/
│   ├── config.example.php
│   └── config.php
├── app/
│   ├── Controllers/
│   ├── Core/
│   ├── Middleware/
│   ├── Models/
│   └── Services/
├── views/
│   ├── admin/
│   ├── auth/
│   ├── discussions/
│   ├── errors/
│   ├── home/
│   ├── info/
│   ├── layouts/
│   └── polls/
├── database/
│   ├── schema.sql
│   └── seed.sql
└── DEPLOYMENT.md
```

---

### Step 2: Set Up MySQL / MariaDB Database

1. Log into your hosting control panel (cPanel / phpMyAdmin).
2. Create a new MySQL database named `kenyans_decision_db`.
3. Create a database user (e.g. `kd_db_user`) and assign a secure password. Grant all privileges on `kenyans_decision_db` to this user.
4. Open **phpMyAdmin**, select `kenyans_decision_db`, and import:
   - First: `database/schema.sql`
   - Second: `database/seed.sql`

---

### Step 3: Configure Database Credentials & Security Keys

1. Copy `/config/config.example.php` to `/config/config.php`.
2. Edit `/config/config.php` with your production settings:

```php
return [
    'app_name' => 'Kenyans Decision',
    'app_url' => 'https://kenyansdecision.co.ke',
    'env' => 'production',

    'db' => [
        'host' => 'localhost',
        'port' => '3306',
        'name' => 'kenyans_decision_db',
        'user' => 'kd_db_user',
        'password' => 'YOUR_STRONG_DB_PASSWORD',
        'charset' => 'utf8mb4',
    ],

    'security' => [
        'app_key' => 'GENERATE_A_RANDOM_64_CHAR_APP_KEY',
        'vote_hmac_key' => 'GENERATE_A_RANDOM_64_CHAR_HMAC_KEY',
        'session_lifetime' => 604800, // 7 days
    ],
];
```

---

### Step 4: Verify Apache `.htaccess` Rewrite Rules

Ensure `.htaccess` is present in the root directory. This handles clean URL routing via `index.php`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?route=$1 [QSA,L]
</IfModule>
```

---

## 🔐 Default Admin Account

Upon executing `database/seed.sql`, the following initial administrator account is provisioned:

* **Email:** `admin@kenyansdecision.co.ke`
* **Password:** `AdminPassword2027!`

> ⚠️ **CRITICAL SECURITY STEP:** Immediately upon deployment, log into `/login` using the default credentials and update the password or update the password hash directly in the `users` table using PHP's `password_hash()`.

---

## 🛡️ Production Security Checklist

- [x] **CSRF Protection:** Tokens enforced on all POST/PUT/DELETE forms and AJAX endpoints via `CsrfMiddleware`.
- [x] **Keyed HMAC IP Digesting:** Raw IP addresses are never written to permanent storage; vote rate limiting uses HMAC SHA-256 digests.
- [x] **Append-Only Audit Logs:** Admin actions are stored in an append-only audit trail with cryptographic log hash chaining.
- [x] **Secure Password Hashing:** User passwords hashed using `password_hash()` with BCrypt cost 10.
- [x] **Directory Protection:** `.htaccess` blocks direct browser access to `.env`, `config/`, and `.sql` database dumps.

---

## 📊 Summary of Required Environment Values

| Setting | Purpose | Example Value |
|---|---|---|
| `app_url` | Canonical domain URL | `https://kenyansdecision.co.ke` |
| `db.host` | MySQL Host | `localhost` or `127.0.0.1` |
| `db.name` | Database Name | `kenyans_decision_db` |
| `db.user` | Database Username | `kd_db_user` |
| `db.password` | Database Password | `StrongSecretPass2027!` |
| `security.vote_hmac_key` | Secret key for IP HMAC rate limiting | Random 64-char string |
