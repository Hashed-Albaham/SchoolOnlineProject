# دليل النشر والإنتاج - Deployment Guide

## 🌐 النشر على الخوادم

### خيارات الاستضافة الموصى بها

| المنصة | النوع | السعر | الأفضل لـ |
|--------|-------|-------|-----------|
| DigitalOcean | VPS | $6/شهر | التحكم الكامل |
| Laravel Forge | PaaS | $12/شهر | سهولة الإدارة |
| AWS EC2 | IaaS | متغير | المشاريع الكبيرة |
| Hostinger | Shared | $3/شهر | الميزانية المحدودة |
| Railway | PaaS | مجاني للبداية | التجربة السريعة |

---

## 📦 النشر على VPS (DigitalOcean / Linode)

### الخطوة 1: إعداد الخادم
```bash
# تسجيل الدخول
ssh root@your_server_ip

# تحديث النظام
apt update && apt upgrade -y

# تثبيت المتطلبات
apt install nginx mysql-server php8.2-fpm php8.2-cli \
    php8.2-common php8.2-mysql php8.2-zip php8.2-gd \
    php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath \
    git supervisor redis-server -y
```

### الخطوة 2: إعداد MySQL
```bash
mysql_secure_installation
mysql -u root -p
```
```sql
CREATE DATABASE school_online CHARACTER SET utf8mb4;
CREATE USER 'school_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL ON school_online.* TO 'school_user'@'localhost';
FLUSH PRIVILEGES;
```

### الخطوة 3: تثبيت Composer و Node.js
```bash
# Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt install nodejs -y
```

### الخطوة 4: نسخ المشروع
```bash
cd /var/www
git clone https://github.com/Hashed-Albaham/SchoolOnlineProject.git
cd SchoolOnlineProject

# الصلاحيات
chown -R www-data:www-data .
chmod -R 775 storage bootstrap/cache
```

### الخطوة 5: تثبيت التبعيات
```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### الخطوة 6: إعداد البيئة
```bash
cp .env.example .env
php artisan key:generate
```

عدل ملف `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_DATABASE=school_online
DB_USERNAME=school_user
DB_PASSWORD=strong_password_here

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### الخطوة 7: تشغيل الإعدادات
```bash
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

### الخطوة 8: إعداد Nginx
```bash
nano /etc/nginx/sites-available/school_online
```

```nginx
server {
    listen 80;
    listen [::]:80;
    
    server_name your-domain.com www.your-domain.com;
    root /var/www/SchoolOnlineProject/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # حماية المجلدات الحساسة
    location ~ ^/(\.env|composer\.json|composer\.lock|package\.json) {
        deny all;
    }

    # الملفات الثابتة
    location ~* \.(jpg|jpeg|gif|png|css|js|ico|webp|svg|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

تفعيل الموقع:
```bash
ln -s /etc/nginx/sites-available/school_online /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
```

### الخطوة 9: إعداد SSL (Let's Encrypt)
```bash
apt install certbot python3-certbot-nginx -y
certbot --nginx -d your-domain.com -d www.your-domain.com
```

### الخطوة 10: إعداد Queue Worker (Supervisor)
```bash
nano /etc/supervisor/conf.d/school-worker.conf
```

```ini
[program:school-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/SchoolOnlineProject/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/SchoolOnlineProject/storage/logs/worker.log
stopwaitsecs=3600
```

تشغيل:
```bash
supervisorctl reread
supervisorctl update
supervisorctl start school-worker:*
```

---

## ⚡ تحسينات الأداء

### 1. تخزين الإعدادات
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 2. إعداد OPcache
```bash
nano /etc/php/8.2/fpm/conf.d/10-opcache.ini
```
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=64
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

### 3. إعداد Redis للـ Caching
```bash
apt install redis-server php8.2-redis -y
systemctl enable redis-server
```

---

## 🔄 التحديث التلقائي (CI/CD)

### إعداد GitHub Actions
أنشئ `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to server
        uses: appleboy/ssh-action@v1.0.0
        with:
          host: ${{ secrets.SERVER_IP }}
          username: ${{ secrets.SERVER_USER }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /var/www/SchoolOnlineProject
            git pull origin main
            composer install --no-dev --optimize-autoloader
            npm install && npm run build
            php artisan migrate --force
            php artisan optimize
            sudo supervisorctl restart school-worker:*
```

---

## 📊 المراقبة والصيانة

### أوامر الصيانة اليومية
```bash
# مسح الكاش القديم
php artisan cache:clear

# تنظيف السجلات القديمة
find /var/www/SchoolOnlineProject/storage/logs -name "*.log" -mtime +30 -delete

# التحقق من Queue
supervisorctl status
```

### النسخ الاحتياطي
```bash
# نسخ قاعدة البيانات
mysqldump -u school_user -p school_online > backup_$(date +%Y%m%d).sql

# نسخ الملفات المرفوعة
tar -czvf uploads_$(date +%Y%m%d).tar.gz /var/www/SchoolOnlineProject/storage/app/public
```

---

## 🔒 قائمة فحص الأمان للإنتاج

- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] SSL/HTTPS مفعل
- [ ] Firewall معد (UFW)
- [ ] كلمات مرور قوية
- [ ] النسخ الاحتياطي مجدول
- [ ] المجلدات الحساسة محمية
- [ ] Rate limiting مفعل
