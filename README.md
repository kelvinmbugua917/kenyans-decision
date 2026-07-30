# 🗳️ Kenyans Decision — Independent Public Opinion Engine

[![Live Demo](https://img.shields.io/badge/Live%20Demo-kenyansdecision.online-059669?style=for-the-badge&logo=googlechrome&logoColor=white)](https://kenyansdecision.online)
[![Build Status](https://img.shields.io/badge/CI-Passing-brightgreen?style=for-the-badge&logo=github-actions&logoColor=white)](https://github.com/kenyansdecision/public-opinion-engine/actions)
[![Security Audited](https://img.shields.io/badge/Security-HMAC--SHA256%20%2B%20CSP%20%2B%20HSTS-1e293b?style=for-the-badge&logo=shield&logoColor=white)](https://kenyansdecision.online/privacy)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge)](LICENSE)

**Kenyans Decision** is a high-performance, independent public opinion and civic engagement platform tailored for Kenya's 47 counties. Built with a lightweight, secure PHP architecture, modern Tailwind CSS layout design, and real-time voter fraud prevention, it provides real-time polling analytics, demographic breakdowns, historical momentum tracking, and civic forums.

---

## 🚀 Live Demo & Production URL

Experience the live application in real-time:
👉 **[https://kenyansdecision.online](https://kenyansdecision.online)**

- **XML Sitemap:** [https://kenyansdecision.online/sitemap.xml](https://kenyansdecision.online/sitemap.xml)
- **Robots Policy:** [https://kenyansdecision.online/robots.txt](https://kenyansdecision.online/robots.txt)
- **Verification & Methodology:** [https://kenyansdecision.online/methodology](https://kenyansdecision.online/methodology)

---

## 📸 Key Application Interface Views

### 1. 🏠 Homepage & Presidential Bento Dashboard
> **Hero Bento Layout featuring live national candidate standings, county selection, real-time percentages, and viral WhatsApp sharing.**

```
+-----------------------------------------------------------------------------------+
|  [🛡️ KenyansDecision]  [Dashboard] [Polls] [Forum]       [Sign In]  [Create Poll] |
+-----------------------------------------------------------------------------------+
| 🟢 LIVE PUBLIC OPINION POLL • NATIONAL SAMPLE                                    |
| 2027 Kenyan Presidential Election Preference Survey                               |
|                                                                                   |
|  (•) Prof. George Wajackoyah (Roots Party)     [+3.2% 30d]  42.5% (1,250 votes)    |
|  ( ) Undecided / Other Candidate               [-1.5% 30d]  40.0% (1,175 votes)    |
|  ( ) Dr. William Samoei Ruto (UDA / Kenya Kwanza)           17.5% (  515 votes)    |
|                                                                                   |
|  [ Select County: Nairobi ▾ ]                       [ Cast Vote → ]               |
+-----------------------------------------------------------------------------------+
|  [ 📊 47-County Geographic Heat Map ]    |   [ 💬 Trending Civic Discussions ]     |
|  Nairobi: 44.2% Leading                  |   "National Assembly Finance Bill"    |
|  Kiambu: 51.0% Leading                   |   "Energy Infrastructure Reform"      |
+-----------------------------------------------------------------------------------+
```

---

### 2. 📊 Detailed Poll Analytics & County Matrix View
> **Granular candidate metrics filtered by individual counties (e.g., Nairobi, Mombasa, Nakuru, Kisumu) and age brackets.**

```
+-----------------------------------------------------------------------------------+
| 🗳️ National & County Poll Standings                                                |
| Filter Breakdown: [ All Counties (National Total) ▾ ]                             |
+-----------------------------------------------------------------------------------+
|  #1 Prof. George Wajackoyah  ████████████████████ 42.5% (+3.2% 30d) 🥇 LEADING    |
|  #2 Undecided / Other       ██████████████████   40.0% (-1.5% 30d)                |
|  #3 Dr. William Samoei Ruto ████████             17.5% (+0.8% 30d)                |
+-----------------------------------------------------------------------------------+
| 📈 Historical Results & Momentum Trends                                          |
|  - Today: 2,940 Total Votes                                                       |
|  - Yesterday: 2,587 Total Votes (Wajackoyah +1.8% shift)                         |
|  - Last 7 Days: 1,880 Total Votes                                                |
+-----------------------------------------------------------------------------------+
```

---

### 3. 💬 Civic Discussion Forum
> **Community discourse hub with thread categorization, upvoting, and verified voter badges.**

```
+-----------------------------------------------------------------------------------+
| 💬 Civic Forum — Public Policy & Governance                                       |
+-----------------------------------------------------------------------------------+
|  [ 🔥 Popular ] [ 🆕 Recent ] [ 🎯 High Engagement ]        [ + Start Discussion ]|
|                                                                                   |
|  📌 Proposed National Infrastructure & Youth Employment Bill                     |
|  Started by @AmaniVoter • 42 comments • 128 likes • County: Nakuru               |
|                                                                                   |
|  💬 "What key accountability metrics should be introduced to lower tax burdens?"   |
+-----------------------------------------------------------------------------------+
```

---

### 4. 📱 Mobile Adaptive Layout
> **Optimized for single-hand mobile interactions (down to 320px width) with sticky bottom navigation and responsive candidate cards.**

```
+--------------------+
| 🛡️ KenyansDecision |
+--------------------+
| 🟢 LIVE POLL       |
| 2027 Presidential  |
|                    |
| (•) Wajackoyah     |
| ( ) Undecided      |
| ( ) Ruto           |
|                    |
| [ Cast Vote ]      |
+--------------------+
| 🏠  📊  💬  👤    | (Bottom Bar)
+--------------------+
```

---

## 🔒 Security Architecture & Fraud Mitigation

1. **HMAC-SHA256 Anonymized Voter Digesting**
   - Raw IP addresses are **never stored in cleartext**.
   - Voter IP addresses are passed through a secret-keyed `hash_hmac('sha256', $ip, $hmacKey)` before DB persistence.
2. **Double-Blind Composite Voter Fingerprinting**
   - Combines IP HMAC, browser device tokens, and poll identifiers into an uninvertible hash signature (`hash_hmac('sha256', $ipHmac . '_' . $deviceToken . '_' . $pollId, $hmacKey)`).
3. **Strict Rate Limiting & Risk Scoring**
   - `RateLimitMiddleware` restricts API calls to max **60 requests/minute per IP**.
   - Risk classification engine assigns `trusted`, `normal`, `suspicious`, or `blocked` scores based on velocity.
4. **Comprehensive Security Headers**
   - **Content Security Policy (CSP):** Restricts script execution to validated origins.
   - **Strict-Transport-Security (HSTS):** Enforces HTTPS over 1 year (`max-age=31536000`).
   - **X-Content-Type-Options:** Prevents MIME-type sniffing (`nosniff`).
   - **X-XSS-Protection:** Browser XSS filter enabled (`1; mode=block`).
   - **SameSite Cookies:** Session cookies set to `SameSite=Lax` and `HttpOnly`.
5. **CSRF Protection**
   - Standard 32-byte cryptographically secure session CSRF tokens (`CsrfMiddleware`) verified with `hash_equals()`.

---

## 🌐 SEO & Open Graph Compliance

- **Dynamic XML Sitemap:** Automatically rendered at `/sitemap.xml` listing all active polls, discussions, and static policy pages.
- **Search Engine Directive:** Configured at `/robots.txt`.
- **OpenGraph & Twitter Cards:** Full meta tag coverage in `/views/layouts/header.php` with title, description, canonical URL, and preview image annotations.
- **Structured Data (JSON-LD):** Implemented schema.org `WebSite` and `Poll` markup for organic indexing.

---

## 🧪 Automated Testing & CI/CD Pipeline

The project includes PHPUnit unit test suites covering Authentication, Voting Logic, CSRF Validation, and Poll Computations.

### Running Tests Locally

```bash
# Run syntax checks across all PHP files
composer check-syntax

# Execute full PHPUnit test suite
composer test
```

### GitHub Actions Workflow

Located in `.github/workflows/ci.yml`, the workflow automatically checks:
- PHP syntax (`php -l`)
- Composer configuration validity
- Security manifest checks (`.env.example`, `sitemap.xml`)
- Unit test execution on every push and pull request

---

## ⚙️ Stack & Prerequisites

- **PHP:** >= 8.1 (native PDO SQLite / MySQL drivers)
- **Frontend Styling:** Tailwind CSS v3 via Vite / CDN
- **Database:** SQLite (dev/embedded) or MySQL / MariaDB (production)
- **Server:** Apache (`.htaccess` rewriting) or Nginx / Express proxy

---

## 📜 License

Distributed under the **MIT License**. Created for independent civic transparency and public opinion research in Kenya.
