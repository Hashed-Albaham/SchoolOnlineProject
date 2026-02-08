# دليل تثبيت المشروع - Installation Guide

## 📋 المتطلبات الأساسية

### البرمجيات المطلوبة

| البرنامج | الإصدار المطلوب | التحميل |
|----------|-----------------|---------|
| PHP | 8.2 أو أعلى | [php.net](https://php.net) |
| Composer | 2.0+ | [getcomposer.org](https://getcomposer.org) |
| Node.js | 18.0+ | [nodejs.org](https://nodejs.org) |
| MySQL | 8.0+ | [mysql.com](https://mysql.com) |
| XAMPP (Windows) | 8.2+ | [apachefriends.org](https://apachefriends.org) |

### إضافات PHP المطلوبة
```
bcmath, ctype, curl, dom, fileinfo, gd, json, 
mbstring, openssl, pdo, pdo_mysql, tokenizer, xml, zip
```

---

## 🖥️ التثبيت على Windows (XAMPP)

### الخطوة 1: تحميل وتثبيت XAMPP
1. حمل XAMPP من [apachefriends.org](https://apachefriends.org)
2. ثبت XAMPP في `C:\xampp`
3. شغل Apache و MySQL من لوحة تحكم XAMPP

### الخطوة 2: تحميل المشروع
```cmd
cd C:\xampp\htdocs
git clone https://github.com/Hashed-Albaham/SchoolOnlineProject.git
cd SchoolOnlineProject
```

### الخطوة 3: تثبيت التبعيات
```cmd
composer install
npm install
```

### الخطوة 4: إعداد البيئة
```cmd
copy .env.example .env
php artisan key:generate
```

### الخطوة 5: إعداد قاعدة البيانات
1. افتح phpMyAdmin: http://localhost/phpmyadmin
2. أنشئ قاعدة بيانات باسم `school_online`
3. عدل ملف `.env`:
```env
DB_DATABASE=school_online
DB_USERNAME=root
DB_PASSWORD=
```

### الخطوة 6: تشغيل Migrations
```cmd
php artisan migrate
php artisan storage:link
```

### الخطوة 7: تشغيل المشروع
```cmd
:: نافذة 1 - الخادم
php artisan serve

:: نافذة 2 - Vite
npm run dev
```

### الخطوة 8: فتح المتصفح
```
http://127.0.0.1:8000
```

---

## 🐧 التثبيت على Linux/Ubuntu

### الخطوة 1: تثبيت المتطلبات
```bash
# تحديث النظام
sudo apt update && sudo apt upgrade -y

# تثبيت PHP والإضافات
sudo apt install php8.2 php8.2-cli php8.2-common php8.2-mysql \
    php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml \
    php8.2-bcmath php8.2-tokenizer -y

# تثبيت Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# تثبيت Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# تثبيت MySQL
sudo apt install mysql-server -y
```

### الخطوة 2: إعداد MySQL
```bash
sudo mysql -u root -p
```
```sql
CREATE DATABASE school_online CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'school_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON school_online.* TO 'school_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### الخطوة 3: تحميل وإعداد المشروع
```bash
cd /var/www/html
sudo git clone https://github.com/Hashed-Albaham/SchoolOnlineProject.git
cd SchoolOnlineProject
sudo chown -R $USER:$USER .

composer install
npm install

cp .env.example .env
php artisan key:generate
```

### الخطوة 4: تعديل `.env`
```env
DB_DATABASE=school_online
DB_USERNAME=school_user
DB_PASSWORD=your_password
```

### الخطوة 5: التشغيل
```bash
php artisan migrate
php artisan storage:link
php artisan serve &
npm run dev
```

---

## 🍎 التثبيت على macOS

### الخطوة 1: تثبيت Homebrew
```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

### الخطوة 2: تثبيت المتطلبات
```bash
brew install php@8.2 composer node mysql
brew services start mysql
```

### الخطوة 3: إكمال الإعداد
اتبع نفس خطوات Linux من الخطوة 2.

---

## 🐳 التثبيت باستخدام Docker

### الخطوة 1: تثبيت Docker
- Windows/Mac: [Docker Desktop](https://docker.com/products/docker-desktop)
- Linux: `sudo apt install docker.io docker-compose`

### الخطوة 2: تشغيل المشروع
```bash
git clone https://github.com/Hashed-Albaham/SchoolOnlineProject.git
cd SchoolOnlineProject

# تثبيت Sail
composer require laravel/sail --dev
php artisan sail:install

# تشغيل Docker
./vendor/bin/sail up -d

# تشغيل Migrations
./vendor/bin/sail artisan migrate
```

### الخطوة 3: الوصول
```
http://localhost
```

---

## 🔧 حل المشاكل الشائعة

### مشكلة: Permission denied (storage)
```bash
chmod -R 775 storage bootstrap/cache
```

### مشكلة: Class not found
```bash
composer dump-autoload
php artisan optimize:clear
```

### مشكلة: Vite manifest not found
```bash
npm run build
```

### مشكلة: 419 Page Expired
```bash
php artisan cache:clear
php artisan config:clear
```

### مشكلة: SQLSTATE Access denied
تأكد من:
1. صحة اسم قاعدة البيانات في `.env`
2. صحة اسم المستخدم وكلمة المرور
3. أن MySQL يعمل

---

## 📱 بيانات الدخول الافتراضية

بعد تشغيل `php artisan db:seed`:

| الدور | البريد | كلمة المرور |
|-------|--------|-------------|
| Admin | admin@example.com | password |
| Tutor | tutor@example.com | password |
| Student | student@example.com | password |

---

## ✅ التحقق من التثبيت

```bash
# التحقق من PHP
php -v

# التحقق من Composer
composer -V

# التحقق من Node
node -v

# التحقق من Laravel
php artisan --version

# التحقق من قاعدة البيانات
php artisan migrate:status
```
