# 🎓 School Online Platform | منصة التعليم الإلكتروني

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind-3.1-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

**[English](#english) | [العربية](#arabic)**

A comprehensive e-learning platform built with Laravel 12, featuring Arabic RTL support.

منصة تعليمية إلكترونية متكاملة مبنية بـ Laravel 12 مع دعم كامل للغة العربية.

</div>

---

<a name="english"></a>
# 📖 English Documentation

## ✨ Features

### For Students
- ✅ Browse and search courses
- ✅ Enroll and pay for courses
- ✅ Watch content (video, text, files, images)
- ✅ Take quizzes with attempt limits
- ✅ Track progress and request certificates
- ✅ Message tutors directly

### For Tutors
- ✅ Create and manage courses
- ✅ Add diverse content (YouTube, files, text)
- ✅ Build quizzes with MCQ questions
- ✅ View student results and answers
- ✅ Issue certificates to students
- ✅ Dashboard with detailed statistics

### For Admins
- ✅ Manage all users
- ✅ Review and approve courses
- ✅ Verify tutors
- ✅ Platform-wide statistics

---

## 🏗️ Tech Stack

| Layer | Technology | Version |
|-------|------------|---------|
| **Backend** | Laravel | 12.0 |
| **Language** | PHP | 8.2+ |
| **Frontend** | TailwindCSS | 3.1 |
| **Interactivity** | Alpine.js | 3.4 |
| **Live Components** | Livewire | 4.1 |
| **Build Tool** | Vite | 7.0 |
| **Database** | MySQL | 8.0 |

---

## 📋 Requirements

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.0
- MySQL >= 8.0

### Required PHP Extensions
```
BCMath, Ctype, Fileinfo, JSON, Mbstring, 
OpenSSL, PDO, PDO_MySQL, Tokenizer, XML, cURL, GD
```

---

## 🚀 Quick Installation

### 1. Clone the repository
```bash
git clone https://github.com/Hashed-Albaham/SchoolOnlineProject.git
cd SchoolOnlineProject
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database in `.env`
```env
DB_DATABASE=school_online
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run migrations
```bash
php artisan migrate
php artisan storage:link
```

### 6. Start development servers
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

### 7. Open browser
```
http://127.0.0.1:8000
```

---

## 📁 Project Structure

```
SchoolOnlineProject/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Student/        # Student controllers
│   │   ├── Tutor/          # Tutor controllers
│   │   └── Auth/           # Authentication
│   ├── Models/             # Eloquent models
│   ├── Notifications/      # Notification classes
│   └── Policies/           # Authorization policies
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Test data seeders
├── resources/views/
│   ├── admin/              # Admin views
│   ├── student/            # Student views
│   ├── tutor/              # Tutor views
│   └── components/         # Blade components
├── routes/web.php          # Web routes
└── docs/                   # Documentation
```

---

## 👥 User Roles

| Role | Permissions |
|------|-------------|
| **admin** | Full platform management |
| **tutor** | Manage own courses, view student results |
| **student** | Browse, enroll, watch, take quizzes |

---

## 🔒 Security Features

- ✅ CSRF Protection
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ XSS Protection (Blade Escaping)
- ✅ Mass Assignment Protection
- ✅ Role-based Authorization
- ✅ Password Hashing (Bcrypt)

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# With coverage
php artisan test --coverage
```

---

## 🌐 Production Deployment

```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# Set environment
APP_ENV=production
APP_DEBUG=false
```

See [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md) for detailed deployment guide.

---

---

<a name="arabic"></a>
# 📖 التوثيق بالعربية

## ✨ المميزات

### 👨‍🎓 للطلاب
- ✅ تصفح الكورسات والبحث فيها
- ✅ التسجيل والدفع في الكورسات
- ✅ مشاهدة المحتوى (فيديو، نصوص، ملفات، صور)
- ✅ خوض الاختبارات مع عدد محاولات محدد
- ✅ تتبع التقدم وطلب الشهادات
- ✅ مراسلة المعلمين مباشرة

### 👨‍🏫 للمعلمين
- ✅ إنشاء وإدارة الكورسات
- ✅ إضافة محتوى متنوع (YouTube، ملفات، نصوص)
- ✅ بناء الاختبارات مع أسئلة متعددة الخيارات
- ✅ عرض نتائج وإجابات الطلاب
- ✅ إصدار الشهادات للطلاب
- ✅ لوحة تحكم مع إحصائيات مفصلة

### 👨‍💼 للمسؤولين
- ✅ إدارة جميع المستخدمين
- ✅ مراجعة والموافقة على الكورسات
- ✅ التحقق من المعلمين
- ✅ إحصائيات شاملة للمنصة

---

## 🏗️ البنية التقنية

| الطبقة | التقنية | الإصدار |
|--------|---------|---------|
| **الخلفية** | Laravel | 12.0 |
| **اللغة** | PHP | 8.2+ |
| **الواجهة** | TailwindCSS | 3.1 |
| **التفاعلية** | Alpine.js | 3.4 |
| **المكونات الحية** | Livewire | 4.1 |
| **البناء** | Vite | 7.0 |
| **قاعدة البيانات** | MySQL | 8.0 |

---

## 📋 المتطلبات

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.0
- MySQL >= 8.0

### إضافات PHP المطلوبة
```
BCMath, Ctype, Fileinfo, JSON, Mbstring, 
OpenSSL, PDO, PDO_MySQL, Tokenizer, XML, cURL, GD
```

---

## 🚀 التثبيت السريع

### 1. استنساخ المشروع
```bash
git clone https://github.com/Hashed-Albaham/SchoolOnlineProject.git
cd SchoolOnlineProject
```

### 2. تثبيت التبعيات
```bash
composer install
npm install
```

### 3. إعداد البيئة
```bash
cp .env.example .env
php artisan key:generate
```

### 4. إعداد قاعدة البيانات في `.env`
```env
DB_DATABASE=school_online
DB_USERNAME=root
DB_PASSWORD=
```

### 5. تشغيل الـ Migrations
```bash
php artisan migrate
php artisan storage:link
```

### 6. تشغيل الخوادم
```bash
# نافذة 1
php artisan serve

# نافذة 2
npm run dev
```

### 7. فتح المتصفح
```
http://127.0.0.1:8000
```

---

## 📁 هيكل المشروع

```
SchoolOnlineProject/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # وحدات تحكم المسؤول
│   │   ├── Student/        # وحدات تحكم الطالب
│   │   ├── Tutor/          # وحدات تحكم المعلم
│   │   └── Auth/           # المصادقة
│   ├── Models/             # نماذج Eloquent
│   ├── Notifications/      # فئات الإشعارات
│   └── Policies/           # سياسات الصلاحيات
├── database/
│   ├── migrations/         # ملفات Migration
│   └── seeders/            # بيانات تجريبية
├── resources/views/
│   ├── admin/              # واجهات المسؤول
│   ├── student/            # واجهات الطالب
│   ├── tutor/              # واجهات المعلم
│   └── components/         # مكونات Blade
├── routes/web.php          # المسارات
└── docs/                   # التوثيق
```

---

## 👥 الأدوار والصلاحيات

| الدور | الصلاحيات |
|-------|-----------|
| **admin** | إدارة كاملة للمنصة |
| **tutor** | إدارة كورساته، عرض نتائج طلابه |
| **student** | تصفح، تسجيل، مشاهدة، اختبارات |

---

## 🔒 ميزات الأمان

- ✅ حماية CSRF
- ✅ منع SQL Injection (Eloquent ORM)
- ✅ حماية XSS (Blade Escaping)
- ✅ حماية Mass Assignment
- ✅ صلاحيات مبنية على الأدوار
- ✅ تشفير كلمات المرور (Bcrypt)

---

## 🧪 الاختبارات

```bash
# تشغيل جميع الاختبارات
php artisan test

# مع التغطية
php artisan test --coverage
```

---

## 🌐 النشر للإنتاج

```bash
# تحسين للإنتاج
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# إعدادات البيئة
APP_ENV=production
APP_DEBUG=false
```

راجع [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md) لدليل النشر المفصل.

---

## 📄 License | الرخصة

This project is licensed under the [MIT License](LICENSE).

هذا المشروع مرخص تحت [رخصة MIT](LICENSE).

---

<p align="center">
  Made with ❤️ in Yemen 🇾🇪 | صنع بـ ❤️ في اليمن
</p>
