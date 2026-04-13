# 📋 taskToFix.md — Laravel Project Audit Report
**Project:** ProSkill LMS (Inferred from contexts)
**Laravel Version:** 12.50.0
**Audit Date:** 2026-04-13
**Auditor:** LARA-AXIOM-AUDITOR v2.0
**Overall Health Score:** 65/100

***

## 🚨 [SECTION A] CRITICAL ISSUES — أولوية صفر (يجب الإصلاح فوراً)

> هذه المشاكل تشكل خطراً مباشراً على أمان التطبيق أو استقراره.

| # | الملف والسطر | نوع الخلل | الوصف الدقيق | الأثر إذا لم يُصلح | الإصلاح المطلوب |
|---|---|---|---|---|---|
| C-001 | `app/Models/TutorDetail.php:28` | Mass Assignment Vulnerability | الحقول المالية `available_balance`, `pending_balance`, `total_earned` موجودة داخل `$fillable` | إمكانية تلاعب المستخدمين بأرصدتهم المالية عبر استغلال Mass Assignment إذا تم استخدام `$request->all()` | إزالة الحقول المالية من `$fillable` وتحديثها بشكل صريح فقط عبر `FinancialService`. |
| C-002 | `composer.json` / `composer audit` | Vulnerable Component | حزمة `league/commonmark` تحتوي على ثغرات XSS وباي باس لـ `allowed_domains` (CVE-2026-33347) | خطر تنفيذ اختراقات عبر الـ Markdown rendering في الموقع. | تحديث حزمة `league/commonmark` إلى أحدث إصدار آمن أو استبدالها ببديل كامل (مثل `parsedown`). |

***

## ⚠️ [SECTION B] HIGH SEVERITY — أولوية عالية

> مشاكل تؤثر على الأمان أو المنطق البرمجي ويجب إصلاحها في Sprint القادم.

| # | الملف والسطر | نوع الخلل | الوصف | الأثر | الإصلاح |
|---|---|---|---|---|---|
| H-001 | `routes/auth.php` | Missing Rate Limit | مسارات `register` و `forgot-password` لا تحتوي على Middleware `throttle` | تعريض التطبيق لهجمات Brute Force و Spam Users. | إضافة `->middleware('throttle:5,1')` على مسارات التسجيل وطلب كلمة المرور. |
| H-002 | `app/Http/Controllers/Tutor/CourseController.php:41` | Fat Controller / Missing Form Request | يتم استخدام `validate()` مباشرة داخل المتحكم وعملية الـ File Upload تتم بداخله | تضخم الكود، وصعوبة الـ Unit Testing وضعف في إعادة الاستخدام | نقل اللوجيك إلى `StoreCourseRequest` و `UpdateCourseRequest` و Service Layer للـ Upload. |
| H-003 | `.env.example` | Security Misconfiguration | `APP_DEBUG=true` معروضة كافتراضي في الملف التعريفي | كشف بيانات حساسة للمستخدمين في حال نسيان تغييرها في الـ Production | تغييرها إلى `APP_DEBUG=false` للحفاظ على الأمان الافتراضي. |

***

## 🔶 [SECTION C] MEDIUM SEVERITY — أولوية متوسطة

> مشاكل تؤثر على الأداء أو جودة الكود.

| # | الملف والسطر | نوع الخلل | الوصف | الأثر | الإصلاح |
|---|---|---|---|---|---|
| M-001 | `app/Http/Controllers/MessageController.php:241` | N+1 / Unoptimized Queries | استعلامات متكررة لجلب قائمة المستخدمين عبر `User::whereIn()->get()` دون Eager Loading للملفات الشخصية | بطء شديد في نظام المراسلات عند زيادة عدد المستخدمين. | استخدام Eager Loading `->with('tutorDetails')` أو غيره حسب الحاجة. |
| M-002 | `app/Http/Controllers/Tutor/ReportController.php:74` | Raw SQL without bindings in some cases | استخدام `DB::raw("DATE_FORMAT(...)")` بدون توحيد الأنماط | صعوبة الصيانة في حال الانتقال لمحركات قواعد بيانات أخرى (مثل PostgreSQL) | استخدام Carbon Methods أو حزم مثل `Laravel-Date-Scopes` لجعلها Database Agnostic. |

***

## 💡 [SECTION D] LOW / IMPROVEMENTS — تحسينات مقترحة

| # | الملف | التحسين المقترح | السبب |
|---|---|---|---|
| I-001 | `app/Models/User.php` | نقل ثوابت الأدوار (Roles) إلى `Enum` | لجعل الكود أكثر صرامة بدلاً من Hard-coded strings في دوال مثل `isAdmin()`. |
| I-002 | `resources/views/` | توحيد استخدام الـ Translations `__('key')` | لوحظ وجود بعض النصوص العربية بشكل مباشر (Hard-coded) في الـ Controllers والواجهات. |

***

## 🗺️ [SECTION E] REPAIR STRATEGY MAP — خطة الإصلاح التنفيذية

### Phase 1 — Security Hardening (الأسبوع 1)
**الهدف:** إغلاق جميع الثغرات الأمنية الحرجة والعالية.

- [ ] **C-001:** إصلاح Mass Assignment في `TutorDetail`.
      - افتح `app/Models/TutorDetail.php`
      - احذف الحقول `available_balance`, `pending_balance`, `total_earned` من المصفوفة `$fillable`.
- [ ] **C-002:** تشغيل `composer update league/commonmark` أو استبدال الحزمة لغلق ثغرات الـ CVE المكتشفة.
- [ ] **H-001:** إضافة Rate Limiting (`throttle`) لمسارات المصادقة الباقية (`register`, `forgot-password`) في `routes/auth.php`.
- [ ] **H-003:** تحديث `APP_DEBUG=false` في `.env.example`.

### Phase 2 — Logic & Architecture (الأسبوع 2)
**الهدف:** تنظيف المنطق البرمجي وهيكلة الكود.

- [ ] **H-002:** إنشاء Form Requests لمتحكمات `CourseController` (`StoreCourseRequest`, `UpdateCourseRequest`) لنقل الـ Validation خارج المتحكمات.
- [ ] إنشاء `CourseService` لنقل عمليات رفع الملفات والفيديوهات خارج الـ Controller.

### Phase 3 — Performance & Quality (الأسبوع 3)
**الهدف:** تحسين الأداء وجودة الكود.

- [ ] **M-001:** مراجعة الـ Controllers مثل `MessageController` لإضافة `with()` وتجنب استعلامات متكررة للـ Users والـ Avatars.
- [ ] **M-002:** توحيد استعلامات `DB::raw()` الخاصة بالتواريخ.
- [ ] **I-001:** تحويل أنماط الأدوار (Student, Tutor, Admin) إلى PHP 8.1 Enums.

### Phase 4 — Testing & Hardening (الأسبوع 4)
**الهدف:** كتابة Tests وتثبيت الجودة.

- [ ] كتابة Feature Tests لعمليات الإيداع والسحب (`PayoutController`) لضمان عدم وجود ثغرات في النظام المالي.
- [ ] التحقق من استقرار جميع التعديلات عبر `php artisan test`.

***

## 📊 [SECTION F] AUDIT SUMMARY

| المعيار | الحالة | الدرجة |
|---|---|---|
| الأمان (Security) | 🟡 | 15/25 |
| الأداء (Performance) | 🟡 | 15/25 |
| جودة الكود (Code Quality) | 🟡 | 15/25 |
| الهيكلة (Architecture) | 🟡 | 20/25 |
| **المجموع** | | **65/100** |

***

## ✅ [SECTION G] COMPLETION LOG — سجل الإنجاز

| رقم المهمة | الحالة | المنجز بواسطة | تاريخ الإصلاح |
|---|---|---|---|
| C-001 | ⏳ معلّقة | — | — |
| C-002 | ⏳ معلّقة | — | — |
| H-001 | ⏳ معلّقة | — | — |
| H-002 | ⏳ معلّقة | — | — |
| H-003 | ⏳ معلّقة | — | — |

***
*تم توليد هذا التقرير بواسطة LARA-AXIOM-AUDITOR v2.0*
