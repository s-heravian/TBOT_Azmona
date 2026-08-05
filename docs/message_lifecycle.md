# منطق برنامه و چرخه پیام‌ها در ربات آزمونا

این دیاگرام مسیر دریافت، پردازش و پاسخ‌دهی به پیام‌های کاربران (مدیران، معلمان و دانش‌آموزان) را در ربات نشان می‌دهد.

```mermaid
flowchart TD
    %% رنگ‌بندی و استایل‌ها
    classDef user fill:#e1f5fe,stroke:#0288d1,stroke-width:2px;
    classDef telegram fill:#bbdefb,stroke:#1976d2,stroke-width:2px;
    classDef webhook fill:#fff3e0,stroke:#f57c00,stroke-width:2px;
    classDef logic fill:#e8f5e9,stroke:#388e3c,stroke-width:2px;
    classDef db fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px;
    classDef cron fill:#ffebee,stroke:#d32f2f,stroke-width:2px;

    User([👤 کاربر: تایپ متن یا فشردن دکمه]):::user --> TelegramAPI([☁️ سرور تلگرام]):::telegram

    TelegramAPI -- "Webhook HTTP POST" --> BotPHP(bot.php - Entry Point):::webhook
    
    BotPHP --> Nutgram{فریم‌ورک Nutgram}:::webhook

    Nutgram -- "آیا کاربر دکمه شیشه‌ای زده است؟" --> IsCallback{بررسی نوع پیام}:::logic
    
    IsCallback -- بله (CallbackQuery) --> CallbackHandler(includes/callback_handler.php):::logic
    IsCallback -- خیر (متن / دستور) --> MessageHandler(includes/message_handler.php):::logic

    %% مسیر پردازش پیام‌های متنی
    MessageHandler --> CheckRole{بررسی سطح دسترسی}:::logic
    CheckRole -- دانش‌آموز --> StudentLogic[ثبت پاسخ، نمایش زمان، پایان آزمون]:::logic
    CheckRole -- معلم --> TeacherLogic[ساخت آزمون، مدیریت دروس، گزارش‌گیری]:::logic
    CheckRole -- ادمین کل --> AdminLogic[ارسال پیام همگانی، مدیریت کاربران]:::logic

    %% مسیر پردازش دکمه‌های شیشه‌ای
    CallbackHandler --> SwitchAction{تشخیص دکمه بر اساس دیتا}:::logic
    SwitchAction -- "sec_list" --> PDFGen[تولید کارنامه با mPDF]:::logic
    SwitchAction -- "popen" --> StartQuiz[نمایش دکمه ورود به آزمون]:::logic
    SwitchAction -- "سایر موارد" --> OtherCB[ویرایش پیام، حذف پیام و ...]:::logic

    %% اتصال به دیتابیس
    StudentLogic --> Database[(MySQL Database)]:::db
    TeacherLogic --> Database
    AdminLogic --> Database
    PDFGen --> Database
    OtherCB --> Database
    StartQuiz --> Database

    %% خروجی و پاسخ
    Database --> ResponseBuilder[تولید متن پاسخ یا کیبورد جدید]:::logic
    PDFGen --> ResponseBuilder
    
    ResponseBuilder --> TelegramAPI2([☁️ ارسال پاسخ به تلگرام]):::telegram
    TelegramAPI2 --> User

    %% کرون‌جاب‌ها
    CronServer([سرور: زمان‌بندی لینوکس]):::cron -. "هر 10 ثانیه" .-> Cron10(includes/cron/cron10.php):::cron
    CronServer -. "هر ساعت" .-> CronH(includes/cron/cronh.php):::cron
    
    Cron10 --> CronFunc(includes/cron_functions.php):::logic
    CronH --> CronFunc
    CronFunc -. "بررسی زمان باقی‌مانده و آپدیت پیام تلگرام" .-> Database
    CronFunc -. "ارسال هشدار اتمام زمان" .-> TelegramAPI2

```

### توضیحات:
1. **نقطه ورود (Entry Point)**: تلگرام تمام آپدیت‌ها را به `bot.php` که به عنوان وب‌هوک تنظیم شده است، ارسال می‌کند.
2. **Nutgram**: این فریم‌ورک تمام پیچیدگی‌های ارتباط با API را مدیریت کرده و درخواست‌ها را به هندلرهای مربوطه (`message_handler` یا `callback_handler`) هدایت می‌کند.
3. **پردازش پیام (Message Handler)**: بر اساس وضعیت (State) فعلی کاربر در دیتابیس و سطح دسترسی او (معلم/دانش‌آموز/ادمین)، پیام را تحلیل و ذخیره می‌کند.
4. **پردازش دکمه‌ها (Callback Handler)**: تمام کلیک‌های روی دکمه‌های شیشه‌ای را مدیریت می‌کند. برای مثال تولید PDF کارنامه‌ها در همین بخش با کمک `mPDF` صورت می‌گیرد.
5. **کارگران پس‌زمینه (Cron Jobs)**: به صورت مستقل از وب‌هوک کار می‌کنند و وظیفه دارند به طور مداوم دیتابیس را برای آزمون‌های فعال چک کنند و زمان باقی‌مانده را روی پیام تلگرام آپدیت نمایند.
