# Installation Guide | دليل التثبيت

**[English](#english) | [العربية](#arabic)**

---

<a name="english"></a>
# 📦 English Installation Guide

## System Requirements

| Software | Required Version | Download |
|----------|------------------|----------|
| PHP | 8.2 or higher | [php.net](https://php.net) |
| Composer | 2.0+ | [getcomposer.org](https://getcomposer.org) |
| Node.js | 18.0+ | [nodejs.org](https://nodejs.org) |
| MySQL | 8.0+ | [mysql.com](https://mysql.com) |
| XAMPP (Windows) | 8.2+ | [apachefriends.org](https://apachefriends.org) |

### Required PHP Extensions
```
bcmath, ctype, curl, dom, fileinfo, gd, json, 
mbstring, openssl, pdo, pdo_mysql, tokenizer, xml, zip
```

---

## 🖥️ Installation on Windows (XAMPP)

### Step 1: Download and Install XAMPP
1. Download XAMPP from [apachefriends.org](https://apachefriends.org)
2. Install XAMPP in `C:\xampp`
3. Start Apache and MySQL from XAMPP Control Panel

### Step 2: Clone the Project
```cmd
cd C:\xampp\htdocs
git clone https://github.com/Hashed-Albaham/SchoolOnlineProject.git
cd SchoolOnlineProject
```

### Step 3: Install Dependencies
```cmd
composer install
npm install
```

### Step 4: Setup Environment
```cmd
copy .env.example .env
php artisan key:generate
```

### Step 5: Configure Database
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Create database named `school_online`
3. Edit `.env` file:
```env
DB_DATABASE=school_online
DB_USERNAME=root
DB_PASSWORD=
```

### Step 6: Run Migrations
```cmd
php artisan migrate
php artisan storage:link
```

### Step 7: Start the Project
```cmd
:: Terminal 1 - Server
php artisan serve

:: Terminal 2 - Vite
npm run dev
```

### Step 8: Open Browser
```
http://127.0.0.1:8000
```

---

## 🐧 Installation on Linux/Ubuntu

### Step 1: Install Requirements
```bash
sudo apt update && sudo apt upgrade -y

sudo apt install php8.2 php8.2-cli php8.2-common php8.2-mysql \
    php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml \
    php8.2-bcmath php8.2-tokenizer -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install MySQL
sudo apt install mysql-server -y
```

### Step 2: Setup MySQL
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

### Steps 3-7: Same as Windows

---

## 🐳 Installation with Docker

```bash
git clone https://github.com/Hashed-Albaham/SchoolOnlineProject.git
cd SchoolOnlineProject

composer require laravel/sail --dev
php artisan sail:install

./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

---

## 🔧 Troubleshooting

| Problem | Solution |
|---------|----------|
| Permission denied | `chmod -R 775 storage bootstrap/cache` |
| Class not found | `composer dump-autoload` |
| Vite manifest not found | `npm run build` |
| 419 Page Expired | `php artisan cache:clear` |

---

## 📱 Default Login Credentials

After running `php artisan db:seed`:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Tutor | tutor@example.com | password |
| Student | student@example.com | password |

---

---

<a name="arabic"></a>
# 📦 دليل التثبيت بالعربية

## متطلبات النظام

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
sudo apt update && sudo apt upgrade -y

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

### الخطوات 3-7: نفس خطوات Windows

---

## 🐳 التثبيت باستخدام Docker

```bash
git clone https://github.com/Hashed-Albaham/SchoolOnlineProject.git
cd SchoolOnlineProject

composer require laravel/sail --dev
php artisan sail:install

./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

---

## 🔧 حل المشاكل الشائعة

| المشكلة | الحل |
|---------|------|
| Permission denied | `chmod -R 775 storage bootstrap/cache` |
| Class not found | `composer dump-autoload` |
| Vite manifest not found | `npm run build` |
| 419 Page Expired | `php artisan cache:clear` |

---

## 📱 بيانات الدخول الافتراضية

بعد تشغيل `php artisan db:seed`:

| الدور | البريد | كلمة المرور |
|-------|--------|-------------|
| Admin | admin@example.com | password |
| Tutor | tutor@example.com | password |
| Student | student@example.com | password |
