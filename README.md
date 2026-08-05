# Azmona Telegram Bot

[فارسی (Persian)](README-fa.md)

Azmona is a Telegram Bot built with PHP that helps teachers manage and conduct online quizzes and exams seamlessly within Telegram. It includes features for tracking students' time, submitting answers, and generating automated PDF results.

## 🚀 Version 2.2.0 (Latest)
This version introduces major structural refactoring for better maintainability, security enhancements, and bug fixes:
- **Modular Architecture**: Core bot logic is now separated into `bot.php`, `includes/message_handler.php`, and `includes/callback_handler.php`.
- **Organized Includes**: All library dependencies (`tcpdf`, `Telegram.php`, `jdf.php`) are moved to `includes/libs/`. Cron job scripts are centralized in `includes/cron/`.
- **Enhanced Security**: Complete eradication of hardcoded sensitive data (Bot Tokens, Admin Chat IDs) from both the codebase and the entire Git history. All configuration relies securely on `.env`.
- **Bug Fixes**: Resolved persistent PHP syntax errors related to template variables and backticks.

### Features
- **Exam Management**: Teachers can create and manage their lessons and exams.
- **Time Tracking**: Live tracking of students' time spent on a quiz via Telegram inline keyboard updates.
- **Automated Results**: Generates PDF reports of exam results and sends them directly in the chat using `tcpdf`.
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
