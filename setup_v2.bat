@echo off
chcp 65001 >nul
echo 🚀 جاري إعداد النسخة الجديدة SchoolOnlineProject_v2...
echo ---------------------------------------------------

:: 1. Copy Files
echo [1/3] نسخ الملفات إلى المجلد الجديد...
xcopy /E /I /Y "." "..\SchoolOnlineProject_v2" >nul
if %errorlevel% neq 0 (
    echo [ERROR] فشل نسخ الملفات. تأكد من الصلاحيات.
    pause
    exit /b
)

:: 2. Update Environment
echo [2/3] تحديث إعدادات قاعدة البيانات...
cd "..\SchoolOnlineProject_v2"

:: Replace DB_DATABASE in .env using PowerShell (reliable way)
powershell -Command "(Get-Content .env) -replace 'DB_DATABASE=.*', 'DB_DATABASE=proskill_v2' | Set-Content .env"

:: 3. Setup New Database
echo [3/3] تهيئة قاعدة البيانات الجديدة (proskill_v2)...
call php artisan migrate:fresh --seed --force

echo.
echo ✅ تم الإعداد بنجاح!
echo 📂 مسار المشروع الجديد: c:\xampp\htdocs\SchoolOnlineProject_v2
echo.
echo يرجى فتح المجلد الجديد في VS Code للبدء في العمل عليه.
echo.
pause
