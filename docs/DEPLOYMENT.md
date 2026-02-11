# Deployment Guide | دليل النشر

**[English](#english) | [العربية](#arabic)**

---

<a name="english"></a>
# 🌐 English Deployment Guide

## Hosting Options

| Platform | Type | Price | Best For |
|----------|------|-------|----------|
| DigitalOcean | VPS | $6/mo | Full control |
| Laravel Forge | PaaS | $12/mo | Easy management |
| AWS EC2 | IaaS | Variable | Large projects |
| Railway | PaaS | Free tier | Quick testing |

---

## 📦 VPS Deployment (DigitalOcean/Linode)

### Step 1: Server Setup
```bash
ssh root@your_server_ip

apt update && apt upgrade -y
apt install nginx mysql-server php8.2-fpm php8.2-cli \
    php8.2-common php8.2-mysql php8.2-zip php8.2-gd \
    php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath \
    git supervisor redis-server -y
```

### Step 2: MySQL Setup
```sql
CREATE DATABASE school_online CHARACTER SET utf8mb4;
CREATE USER 'school_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL ON school_online.* TO 'school_user'@'localhost';
```

### Step 3: Clone and Install
```bash
cd /var/www
git clone https://github.com/Hashed-Albaham/SchoolOnlineProject.git
cd SchoolOnlineProject

chown -R www-data:www-data .
chmod -R 775 storage bootstrap/cache

composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### Step 4: Environment Setup
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### Step 5: Nginx Configuration
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/SchoolOnlineProject/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Step 6: SSL Setup
```bash
apt install certbot python3-certbot-nginx -y
certbot --nginx -d your-domain.com
```

### Step 7: Optimize
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ⚡ Performance Optimization

```bash
# Enable OPcache
opcache.enable=1
opcache.memory_consumption=256

# Use Redis for caching
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

---

## 🔒 Production Security Checklist

- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] SSL/HTTPS enabled
- [ ] Firewall configured
- [ ] Strong passwords
- [ ] Backup scheduled

---

---

<a name="arabic"></a>
# 🌐 دليل النشر بالعربية

## خيارات الاستضافة

| المنصة | النوع | السعر | الأفضل لـ |
|--------|-------|-------|-----------|
| DigitalOcean | VPS | $6/شهر | التحكم الكامل |
| Laravel Forge | PaaS | $12/شهر | سهولة الإدارة |
| AWS EC2 | IaaS | متغير | المشاريع الكبيرة |
| Railway | PaaS | مجاني | التجربة السريعة |

---

## 📦 النشر على VPS (DigitalOcean/Linode)

### الخطوة 1: إعداد الخادم
```bash
ssh root@your_server_ip

apt update && apt upgrade -y
apt install nginx mysql-server php8.2-fpm php8.2-cli \
    php8.2-common php8.2-mysql php8.2-zip php8.2-gd \
    php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath \
    git supervisor redis-server -y
```

### الخطوة 2: إعداد MySQL
```sql
CREATE DATABASE school_online CHARACTER SET utf8mb4;
CREATE USER 'school_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL ON school_online.* TO 'school_user'@'localhost';
```

### الخطوة 3: نسخ وتثبيت المشروع
```bash
cd /var/www
git clone https://github.com/Hashed-Albaham/SchoolOnlineProject.git
cd SchoolOnlineProject

chown -R www-data:www-data .
chmod -R 775 storage bootstrap/cache

composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### الخطوة 4: إعداد البيئة
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### الخطوة 5: إعداد Nginx
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/SchoolOnlineProject/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### الخطوة 6: إعداد SSL
```bash
apt install certbot python3-certbot-nginx -y
certbot --nginx -d your-domain.com
```

### الخطوة 7: تحسين الأداء
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ⚡ تحسينات الأداء

```bash
# تفعيل OPcache
opcache.enable=1
opcache.memory_consumption=256

# استخدام Redis للكاش
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

---

## 🔒 قائمة فحص الأمان للإنتاج

- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] SSL/HTTPS مفعل
- [ ] Firewall معد
- [ ] كلمات مرور قوية
- [ ] النسخ الاحتياطي مجدول
