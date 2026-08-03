# Azmona Telegram Bot

[فارسی (Persian)](README-fa.md)

Azmona is a Telegram Bot built with PHP that helps teachers manage and conduct online quizzes and exams seamlessly within Telegram. It includes features for tracking students' time, submitting answers, and generating automated PDF results.

### Features
- **Exam Management**: Teachers can create and manage their lessons and exams.
- **Time Tracking**: Live tracking of students' time spent on a quiz via Telegram inline keyboard updates.
- **Automated Results**: Generates PDF reports of exam results and sends them directly in the chat using `tcpdf`.
- **Environment Configuration**: Configurable using `.env` (so no sensitive data like Bot tokens or DB credentials is exposed in code).
- **Cron Jobs Integration**: Regular updates and reminders using `cron10.php` and `cronh.php`.

### Installation & Setup
1. Clone the repository:
   ```bash
   git clone https://github.com/s-heravian/TBOT_Azmona.git
   ```
2. Copy the `.env.example` file to `.env` and fill in your Bot Token and Database credentials:
   ```bash
   cp .env.example .env
   ```
3. Set your webhook to `bot.php`.
4. Ensure cron jobs are set up for `cron10.php` (every 10 seconds) and `cronh.php` (hourly).
