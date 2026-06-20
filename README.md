# bookloan

سیستم ساده مدیریت کتابخانه با **Symfony 8** و **Docker**.

با این پروژه می‌توان اعضا، کتاب‌ها، ناشران، موضوعات و امانت کتاب را مدیریت کرد.

## راه‌اندازی سریع

### ۱. دریافت پروژه

```bash
git clone https://github.com/saeedgolchinmanshadi/bookloan.git
cd bookloan
```

### ۲. ساخت کلید امنیتی (فقط یک‌بار)

فایل `.env.local` بسازید و داخلش این خط را بگذارید:

```env
APP_SECRET=یک_مقدار_تصادفی_۳۲_کاراکتری
```

برای ساخت مقدار تصادفی:

```bash
php -r "echo bin2hex(random_bytes(16));"
```

### ۳. اجرای Docker

```bash
docker compose up -d --build
```

اولین بار ممکن است چند دقیقه طول بکشد.

### ۴. نصب وابستگی‌های PHP

```bash
docker compose exec php composer install
```

### ۵. ساخت جداول دیتابیس

```bash
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

### ۶. ساخت کاربر مدیر

در مرورگر باز کنید:

```
http://localhost:8080/setup-admin
```

اگر پیام موفقیت دیدید، کاربر مدیر ساخته شده است.

---

## ورود به سیستم

| مورد       | مقدار                       |
| ---------- | --------------------------- |
| آدرس       | http://localhost:8080/login |
| نام کاربری | `admin`                     |
| رمز عبور   | `123456`                    |

---

## آدرس‌های مفید

| آدرس                              | توضیح                          |
| --------------------------------- | ------------------------------ |
| http://localhost:8080             | داشبورد                        |
| http://localhost:8081             | phpMyAdmin                     |
| http://localhost:8080/setup-admin | ساخت کاربر مدیر (فقط محیط dev) |

---

## امکانات پروژه

- مدیریت اعضا (ثبت، ویرایش، لیست)
- مدیریت کتاب‌ها، ناشران و موضوعات
- ثبت امانت و بازگشت کتاب
- ورود امن با Symfony Security
- رابط کاربری فارسی
- اعتبارسنجی کد ملی ایران

---

## تکنولوژی‌ها

- PHP 8.4
- Symfony 8.1
- Doctrine ORM
- MariaDB
- Nginx
- Docker & Docker Compose
- Twig

---

## مشکلات رایج

**خطای `APP_SECRET`**
→ فایل `.env.local` را بسازید (مرحله ۲).

**خطای `Table user doesn't exist`**
→ migrationها را اجرا کنید (مرحله ۵).

**نمی‌توانم لاگین کنم**
→ یک‌بار `/setup-admin` را باز کنید (مرحله ۶).

**خطای تداخل نام کانتینر**
→ کانتینرهای قدیمی با نام `symfony_*` را حذف کنید:

```bash
docker rm -f symfony_db symfony_php symfony_nginx symfony_phpmyadmin
```
