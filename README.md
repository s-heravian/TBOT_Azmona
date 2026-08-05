# Azmona Telegram Bot

[فارسی (Persian)](README-fa.md)

Azmona is a Telegram Bot built with PHP that helps teachers manage and conduct online quizzes and exams seamlessly within Telegram. It includes features for tracking students' time, submitting answers, and generating automated PDF results.

## 🚀 Version 2.3.0 (Latest)
This version finalizes the modernization of the Azmona bot:
- **Nutgram Framework Migration**: Completely migrated from a custom legacy Telegram API handler to the modern, robust Nutgram framework, supporting named arguments and PHP 8.x flawlessly.
- **mPDF Integration**: Replaced the legacy, 32MB hardcoded `tcpdf-master` library with `mPDF` via Composer. This sets the foundation for modern HTML/CSS report cards with excellent RTL/Persian support.
- **PHP 8.4 Compatibility**: Fixed all warnings, undefined constants, and deprecated syntax issues (like naked array keys and `mysqli_close` conflicts) to ensure 100% stability on PHP 8.4.
- **Security & Modularity**: Fully relies on `.env` for secrets. Codebase is much leaner with Composer handling dependencies.

### Features
- **Exam Management**: Teachers can create and manage their lessons and exams.
- **Time Tracking**: Live tracking of students' time spent on a quiz via Telegram inline keyboard updates.
- **Automated Results**: Generates beautiful PDF reports of exam results and sends them directly in the chat using `mPDF`.
- **Environment Configuration**: Configurable using `.env` (no sensitive data exposed in code).
- **Cron Jobs Integration**: Regular updates and reminders using `cron10.php` and `cronh.php`.

### Prerequisites
- **PHP 8.1 or higher (PHP 8.4 Recommended)**
- **Composer** (PHP Package Manager)
- Web Server with SSL (Apache/Nginx/aaPanel)
- MySQL Database

## Deployment & Installation

1. **Clone the repository:**
   ```bash
   git clone <repository_url>
   cd TBOT_Azmona
   ```

2. **Install Dependencies:**
   Since this bot uses the Nutgram framework, you must install dependencies via Composer:
   ```bash
   composer install --no-dev
   ```

3. **Database Setup:**
   Import `db.sql` into your MySQL database.

4. **Configuration:**
   Rename `.env.example` to `.env` and configure your credentials and Admin Chat ID:
   ```bash
   cp .env.example .env
   ```
3. Set your webhook to `bot.php`.
4. Ensure cron jobs are set up for `includes/cron/cron10.php` (every 10 seconds) and `includes/cron/cronh.php` (hourly).

---
*Developed for managing seamless Telegram-based quizzes.*
