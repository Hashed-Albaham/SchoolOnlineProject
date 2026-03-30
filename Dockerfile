FROM php:8.2-apache

# تثبيت الحزم الأساسية للنظام
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# تثبيت أداة Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تحديد مجلد العمل
WORKDIR /var/www/html

# نسخ كل ملفات المشروع
COPY . .

# تثبيت حزم Laravel
RUN composer install --no-dev --optimize-autoloader

# تثبيت حزم التصميم وتفعيل Vite
RUN npm install && npm run build

# إعطاء صلاحيات القراءة والكتابة لمجلدات النظام
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# توجيه الزوار إلى مجلد public الخاص بـ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# فتح المنفذ 80 للإنترنت
EXPOSE 80
