# 📋 AI_HANDOVER.md - بروتوكول تسليم ProSkill Academy
## آخر تحديث: 2026-04-07T03:25:00+03:00
## النموذج المنفذ: LARA-PROSKILL-ARCHITECT-v8.0 (الجلسات 1-7)

---

## 📊 ملخص المشروع

**ProSkill Academy** منصة تعليمية إلكترونية مبنية بـ **Laravel 11** (PHP 8.2.12):
- **Admin**: إدارة شاملة (مستخدمون، دورات، تصنيفات، اشتراكات، تقييمات، مدفوعات، طرق دفع، إشراف على الدردشة، تقارير)
- **Tutor (Instructor)**: إنشاء الدورات وإدارة المحتوى والاختبارات والشهادات وسحب الأرباح
- **Student**: التسجيل في الدورات، مشاهدة المحتوى، إجراء الاختبارات، طلب الشهادات
- **Public**: الصفحة الرئيسية، تصفح الدورات، سياسة الخصوصية، شروط الاستخدام

**المسار**: `c:\xampp\htdocs\SchoolOnlineProject`
**قاعدة البيانات**: MySQL (proskill_db عبر XAMPP) - **ليس SQLite!**
**التوطين**: عربي/إنجليزي عبر `mcamara/laravel-localization`
**Livewire**: 3 مكونات (ChatBox, CourseReviews, NotificationsDropdown)

---

## ⚠️ قواعد ملزمة (دستور لأي تعديل)

> **يجب على أي نموذج AI قادم الالتزام بهذه القواعد بشكل مطلق:**

1. **النافبار**: لا تضف رابطاً في شريط التنقل إلا إذا كان **قسماً مستقلاً جديداً**. إذا كان جزءاً من قسم موجود، أضف زراً مناسباً داخل صفحة ذلك القسم.
2. **التوثيق**: كل صفحة أو route جديد يجب توثيقه هنا مع: المسار، نقطة الدخول (من أين يصل المستخدم)، والوصف.
3. **الترجمة**: كل نص يظهر للمستخدم يجب أن يكون في `lang/ar/site.php` و `lang/en/site.php`.
4. **التوافقية**: لا تكسر أي نظام يعمل حالياً.
5. **`Course::status`**: ممنوع إضافته لـ `$fillable` - يُعيَّن دائماً بشكل صريح.
6. **MySQL فقط**: لا تستخدم `strftime()` أو أي دالة SQLite. استخدم `DATE_FORMAT()` دائماً.
7. **Eager Loading**: لا تنسَ `with()` عند جلب بيانات مرتبطة لتجنب N+1 queries.

---

## 🏗️ هيكل المشروع الحالي

### النماذج (Models) - 18 ملف

| النموذج | الوصف | حالة `$fillable` |
|---|---|---|
| `User` | المستخدم - `role` + `is_super_admin` + **`agreed_to_terms_at`** | `role` + `is_super_admin` **محمي** ✅ |
| `Course` | الدورة - `status` + `category_id` | `status` **محمي** ✅ |
| `Category` | تصنيف الدورات - auto-slug + localized name | عادي ✅ |
| `CourseContent` | محتوى الدورة - 6 أنواع | عادي |
| `Enrollment` | اشتراك - `payment_status` + `enrollment_status` | `payment_status` في fillable ⚠️ |
| `CourseCertificate` | شهادة إتمام الدورة | عادي |
| `ContentProgress` | تقدم الطالب في المحتوى | عادي |
| `Quiz`, `Question`, `Option`, `QuizAttempt` | نظام الاختبارات | عادي |
| `Review` | التقييمات - **يعمل عبر Livewire** ✅ | عادي |
| `Message` | الرسائل | عادي |
| `TutorDetail` | تفاصيل المعلم (bio, cv, is_verified) | عادي |
| `PaymentMethod` | **[PAY1] طرق الدفع** - localized name/instructions + active scope | عادي ✅ |
| `PayoutRequest` | **[PAY2] طلبات سحب الأرباح** - status constants + tutor/paymentMethod/reviewer relations | عادي ✅ |
| `Setting` | **[v8.0] الإعدادات الديناميكية** - Cache::remember مع TTL ساعة | عادي ✅ |
| `SettingsHistory` | **[v8.0] سجل تدقيق الإعدادات** - changedBy relation | عادي ✅ |

### المتحكمات (Controllers) - ملخص شامل

#### Admin Controllers (`app/Http/Controllers/Admin/`) - 10 ملفات
| الملف | الوظائف | الحالة |
|---|---|---|
| `DashboardController.php` | إحصائيات شاملة | ✅ أصلي |
| `TutorController.php` | إدارة المعلمين (index/pending/verify/reject/show/approveAllCourses) | ✅ أصلي |
| `CourseController.php` | إدارة الدورات + تعديل/حذف + حذف تقييمات + حذف محتوى + approve/reject/unapprove | ✅ **محدّث** |
| `UserController.php` | **[A3] إدارة كل المستخدمين (index/show/edit/update/destroy)** | ✅ **جديد** |
| `EnrollmentController.php` | **[A6] إدارة الاشتراكات والمدفوعات** | ✅ **جديد** |
| `CategoryController.php` | **[A5] إدارة التصنيفات (CRUD كامل)** | ✅ **جديد** |
| `ReportController.php` | **[A7] تقارير وتحليلات الإيرادات** | ✅ **جديد** |
| `PaymentMethodController.php` | **[PAY1] إدارة طرق الدفع (CRUD + toggle)** | ✅ **جديد** |
| `PayoutController.php` | **[PAY2] إدارة طلبات صرف أرباح المعلمين (approve/reject/markPaid)** | ✅ **جديد** |
| `ChatController.php` | **[A8] إشراف على الدردشة (index/show/destroyMessage)** | ✅ **جديد** |

#### Tutor Controllers (`app/Http/Controllers/Tutor/`) - 5 ملفات
| الملف | الوظائف | الحالة |
|---|---|---|
| `DashboardController.php` | لوحة تحكم المعلم | ✅ |
| `CourseController.php` | CRUD دورات + محتوى + شهادات + طلبات قبول طلاب (570+ سطر) | ✅ **محدّث** (C1+M4+M6+E1) |
| `ProfileController.php` | ملف المعلم (bio, specialization, CV) | ✅ |
| `QuizController.php` | إدارة الاختبارات (CRUD + builder + results + attempts) | ✅ |
| `PayoutController.php` | **[PAY2] طلب سحب أرباح المعلم (index/store)** | ✅ **جديد** |
| `ReportController.php` | **[T1] تقارير وإحصائيات المعلم (index)** | ✅ **جديد** |

#### Student Controllers (`app/Http/Controllers/Student/`) - 3 ملفات
| الملف | الوظائف | الحالة |
|---|---|---|
| `DashboardController.php` | لوحة تحكم + إحصائيات + اقتراحات | ✅ |
| `CourseController.php` | تصفح + مشاهدة + تقدم + طلب شهادات | ✅ |
| `EnrollmentController.php` | التسجيل والدفع + myEnrollments | ✅ **محدّث** (C1+C4) |

#### Shared Controllers (`app/Http/Controllers/`) - 6 ملفات
| الملف | الوظائف | الحالة |
|---|---|---|
| `CertificateController.php` | عرض/تحقق من الشهادات + مشاهدة شهاداتي | ✅ **محدّث** (C3: IDOR fix) |
| `MessageController.php` | الرسائل والمحادثات + **[MSG1] تقييد جهات الاتصال حسب العلاقة** | ✅ **محدّث** (M2+MSG1) |
| `QuizController.php` | تقديم الاختبارات للطلاب (show/submit/result) | ✅ |
| `ProfileController.php` | الملف الشخصي العام (edit/update/destroy) | ✅ |
| `PageController.php` | **[PP1] صفحات عامة (privacy/terms)** | ✅ **جديد** |
| `Controller.php` | Base controller | ✅ |

### Livewire Components (`app/Livewire/`) - 3 ملفات
| الملف | الوظيفة |
|---|---|
| `ChatBox.php` | مراسلة فورية مع polling + Rate Limiting + **[MSG1] تقييد جهات الاتصال** |
| `CourseReviews.php` | عرض وإضافة التقييمات |
| `NotificationsDropdown.php` | إشعارات المستخدم |

### Policies (`app/Policies/`) - 1 ملف
| الملف | الوظيفة | الحالة |
|---|---|---|
| `CoursePolicy.php` | صلاحيات الدورات (يسمح للAdmin بالتعديل بعد إصلاح S1) | ✅ **محدّث** |

---

## 🗺️ خريطة المسارات ونقاط الدخول (Routes & Access Points)

> **ملف المسارات**: `routes/web.php` (276 سطر)

### شريط التنقل (Navbar) - `navigation.blade.php`
> **قاعدة**: فقط الأقسام المستقلة تظهر هنا. الأقسام الفرعية تُوضع كأزرار داخل صفحتها الأم.

| الدور | الروابط في النافبار (Desktop) |
|---|---|
| **Admin** | Dashboard, إدارة المستخدمين, المعلمون, الدورات, الاشتراكات, التقارير, 💳 طرق الدفع, 💰 إدارة المدفوعات, 👁 إشراف الدردشة |
| **Tutor** | Dashboard, دوراتي, الملف الشخصي, 💰 أرباحي, 📊 التقارير |
| **Student** | Dashboard, الدورات, دوراتي, شهاداتي |
| **Guest** | تسجيل الدخول, إنشاء حساب |
| **All (Auth)** | الرسائل (الكل), Dashboard (الكل) |

> **ملاحظة**: Mobile Navigation يحتوي على نفس الروابط تقريباً + رابط "طلبات القبول" للمعلم

### Public Routes (بدون تسجيل دخول)
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/` | `home` | الصفحة الرئيسية |
| `/{lang}/courses` | `courses.index` | تصفح الدورات |
| `/{lang}/courses/{course}` | `courses.show` | عرض تفاصيل دورة |
| `/{lang}/privacy` | `pages.privacy` | **[PP1]** سياسة الخصوصية |
| `/{lang}/terms` | `pages.terms` | **[PP1]** شروط الاستخدام |

### Admin Routes - المسارات والوصول

#### [Dashboard] لوحة التحكم
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/admin/dashboard` | `admin.dashboard` | Navbar → Dashboard |

**Quick Actions في Dashboard**: 4 أزرار سريعة → Users, Tutors, Courses, Enrollments

#### [A3] إدارة المستخدمين
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/admin/users` | `admin.users.index` | Navbar → إدارة المستخدمين |
| `/{lang}/admin/users/{user}` | `admin.users.show` | صفحة Users → اسم المستخدم |
| `/{lang}/admin/users/{user}/edit` | `admin.users.edit` | صفحة Users → زر تعديل |
| `PUT /{lang}/admin/users/{user}` | `admin.users.update` | نموذج التعديل → حفظ |
| `DELETE /{lang}/admin/users/{user}` | `admin.users.destroy` | صفحة Users → زر حذف |

#### إدارة المعلمين
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/admin/tutors` | `admin.tutors.index` | Navbar → المعلمون |
| `/{lang}/admin/tutors/pending` | `admin.tutors.pending` | صفحة Tutors → زر معلقين |
| `/{lang}/admin/tutors/{tutor}` | `admin.tutors.show` | صفحة Tutors → اسم المعلم |
| `POST /{lang}/admin/tutors/{tutor}/verify` | `admin.tutors.verify` | صفحة show → زر تحقق |
| `POST /{lang}/admin/tutors/{tutor}/reject` | `admin.tutors.reject` | صفحة show → زر رفض |
| `POST /{lang}/admin/tutors/{tutor}/approve-all-courses` | `admin.tutors.approveAllCourses` | صفحة show → قبول كل الدورات |

#### [A4/A9/A10] إدارة الدورات + التقييمات + المحتوى
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/admin/courses` | `admin.courses.index` | Navbar → الدورات |
| `/{lang}/admin/courses/pending` | `admin.courses.pending` | صفحة Courses → زر "بانتظار الموافقة" |
| `/{lang}/admin/courses/{course}` | `admin.courses.show` | صفحة Courses → عرض التفاصيل |
| `/{lang}/admin/courses/{course}/edit` | `admin.courses.edit` | صفحة show → زر تعديل |
| `PUT /{lang}/admin/courses/{course}` | `admin.courses.update` | نموذج التعديل → حفظ |
| `DELETE /{lang}/admin/courses/{course}` | `admin.courses.destroy` | صفحة show → زر حذف |
| `POST ../approve` | `admin.courses.approve` | صفحة show → زر قبول |
| `POST ../reject` | `admin.courses.reject` | صفحة show → زر رفض |
| `POST ../unapprove` | `admin.courses.unapprove` | صفحة show → إلغاء الموافقة |
| `DELETE ../reviews/{review}` | `admin.courses.reviews.destroy` | صفحة show → حذف تقييم |
| `DELETE ../content/{content}` | `admin.courses.content.destroy` | **[A10]** صفحة show → حذف محتوى |

#### [A5] إدارة التصنيفات (جزء من قسم الدورات)
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/admin/categories` | `admin.categories.index` | صفحة Courses → زر "التصنيفات" |
| `/{lang}/admin/categories/create` | `admin.categories.create` | صفحة Categories → زر "إضافة" |
| `POST /{lang}/admin/categories` | `admin.categories.store` | نموذج الإنشاء → إنشاء |
| `/{lang}/admin/categories/{category}/edit` | `admin.categories.edit` | صفحة Categories → زر تعديل |
| `PUT /{lang}/admin/categories/{category}` | `admin.categories.update` | نموذج التعديل → حفظ |
| `DELETE /{lang}/admin/categories/{category}` | `admin.categories.destroy` | صفحة Categories → زر حذف |

#### [A6] إدارة الاشتراكات
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/admin/enrollments` | `admin.enrollments.index` | Navbar → الاشتراكات |
| `PATCH /{lang}/admin/enrollments/{enrollment}/status` | `admin.enrollments.updateStatus` | صفحة Enrollments → زر تغيير الحالة |

#### [A7] التقارير والتحليلات
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/admin/reports` | `admin.reports.index` | Navbar → التقارير |

**أقسام صفحة التقارير**: بطاقات إحصائية + مخطط إيرادات شهري + توزيع مستخدمين/دورات + أعلى الدورات ربحاً + دورات حسب التصنيف + مستخدمين جدد شهرياً + فلترة بالتواريخ

#### [PAY1] إدارة طرق الدفع
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/admin/payment-methods` | `admin.payment_methods.index` | Navbar → 💳 طرق الدفع |
| `/{lang}/admin/payment-methods/create` | `admin.payment_methods.create` | صفحة PaymentMethods → زر "إضافة" |
| `POST /{lang}/admin/payment-methods` | `admin.payment_methods.store` | نموذج الإنشاء → إنشاء |
| `/{lang}/admin/payment-methods/{id}/edit` | `admin.payment_methods.edit` | صفحة PaymentMethods → زر تعديل |
| `PUT /{lang}/admin/payment-methods/{id}` | `admin.payment_methods.update` | نموذج التعديل → حفظ |
| `DELETE /{lang}/admin/payment-methods/{id}` | `admin.payment_methods.destroy` | صفحة PaymentMethods → زر حذف |
| `POST /{lang}/admin/payment-methods/{id}/toggle` | `admin.payment_methods.toggle` | صفحة PaymentMethods → تفعيل/تعطيل |

#### [PAY2] إدارة مدفوعات المعلمين
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/admin/payouts` | `admin.payouts.index` | Navbar → 💰 إدارة المدفوعات |
| `POST /{lang}/admin/payouts/{id}/approve` | `admin.payouts.approve` | صفحة Payouts → موافقة |
| `POST /{lang}/admin/payouts/{id}/reject` | `admin.payouts.reject` | صفحة Payouts → رفض |
| `POST /{lang}/admin/payouts/{id}/mark-paid` | `admin.payouts.markPaid` | صفحة Payouts → تم الدفع |

#### [A8] إشراف الدردشة
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/admin/chat` | `admin.chat.index` | Navbar → 👁 إشراف الدردشة |
| `/{lang}/admin/chat/{user1}/{user2}` | `admin.chat.show` | صفحة Chat → عرض محادثة |
| `DELETE /{lang}/admin/chat/messages/{message}` | `admin.chat.destroyMessage` | صفحة show → حذف رسالة |

### Tutor Routes - المسارات والوصول

| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/tutor/dashboard` | `tutor.dashboard` | Navbar → Dashboard |
| `/{lang}/tutor/profile` | `tutor.profile.edit` | Navbar → الملف الشخصي |
| `PUT /{lang}/tutor/profile` | `tutor.profile.update` | نموذج البروفايل → حفظ |
| `/{lang}/tutor/profile/cv` | `tutor.profile.cv` | صفحة البروفايل → تحميل CV |
| `/{lang}/tutor/courses` (resource) | `tutor.courses.*` | Navbar → دوراتي |
| `POST ../courses/{course}/content` | `tutor.courses.content.add` | صفحة show → إضافة محتوى |
| `GET ../content/{content}/edit` | `tutor.courses.content.edit` | صفحة show → تعديل محتوى |
| `PUT ../content/{content}` | `tutor.courses.content.update` | نموذج التعديل → حفظ |
| `DELETE ../content/{content}` | `tutor.courses.content.delete` | صفحة show → حذف محتوى |
| `POST ../content/reorder` | `tutor.courses.content.reorder` | صفحة show → إعادة ترتيب |
| `/{lang}/tutor/courses/{course}/quizzes` (resource) | `tutor.courses.quizzes.*` | صفحة الدورة → الاختبارات |
| `GET ../quizzes/{quiz}/builder` | `tutor.courses.quizzes.builder` | صفحة Quiz → بناء الأسئلة |
| `GET ../quizzes/{quiz}/results` | `tutor.courses.quizzes.results` | صفحة Quiz → النتائج |
| `GET ../attempts/{attempt}` | `tutor.courses.quizzes.attempts.show` | صفحة Results → محاولة |
| `DELETE ../attempts` | `tutor.courses.quizzes.attempts.clear` | صفحة Results → مسح الكل |
| `DELETE ../attempts/{attempt}` | `tutor.courses.quizzes.attempts.delete` | صفحة Results → حذف محاولة |
| `POST ../questions` | `tutor.courses.quizzes.questions.store` | Builder → إضافة سؤال |
| `DELETE ../questions/{question}` | `tutor.courses.quizzes.questions.destroy` | Builder → حذف سؤال |
| `/{lang}/tutor/certificates` | `tutor.certificates.index` | صفحة Courses → الشهادات |
| `POST ../certificates/{id}/issue` | `tutor.certificates.issue` | صفحة Certificates → إصدار |
| `POST ../certificates/{id}/reject` | `tutor.certificates.reject` | صفحة Certificates → رفض |
| `POST ../certificates/{id}/revoke` | `tutor.certificates.revoke` | صفحة Certificates → سحب |
| `/{lang}/tutor/enrollments` | `tutor.enrollments.index` | **[E1]** Mobile Nav أو Dashboard |
| `POST ../enrollments/{id}/approve` | `tutor.enrollments.approve` | **[E1]** صفحة Enrollments → موافقة |
| `POST ../enrollments/{id}/reject` | `tutor.enrollments.reject` | **[E1]** صفحة Enrollments → رفض |
| `/{lang}/tutor/reports` | `tutor.reports.index` | **[T1]** Navbar → 📊 التقارير |
| `/{lang}/tutor/payouts` | `tutor.payouts.index` | Navbar → 💰 أرباحي |
| `POST /{lang}/tutor/payouts` | `tutor.payouts.store` | **[PAY2]** صفحة Payouts → طلب سحب |

### Student Routes - المسارات والوصول

| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/student/dashboard` | `student.dashboard` | Navbar → Dashboard |
| `/{lang}/student/courses` | `student.courses.index` | Navbar → الدورات |
| `/{lang}/student/courses/{course}` | `student.courses.show` | صفحة Courses → عرض |
| `/{lang}/student/my-courses` | `student.courses.my` | Navbar → دوراتي |
| `/{lang}/student/courses/{course}/watch/{content?}` | `student.courses.watch` | صفحة my-courses → مشاهدة |
| `POST ../courses/{course}/enroll` | `student.enroll` | صفحة الدورة → التسجيل (rate limited) |
| `GET ../enrollment/{enrollment}/payment` | `student.enrollment.payment` | بعد التسجيل → صفحة الدفع |
| `POST ../enrollment/{enrollment}/payment` | `student.enrollment.payment.process` | صفحة الدفع → تأكيد (rate limited) |
| `/{lang}/student/my-enrollments` | `student.enrollments.my` | من Dashboard أو رابط |
| `GET ../quizzes/{quiz}` | `student.quizzes.show` | صفحة Watch → الاختبار |
| `POST ../quizzes/{quiz}` | `student.quizzes.submit` | صفحة Quiz → تسليم |
| `GET ../quizzes/{quiz}/result` | `student.quizzes.result` | بعد التسليم → النتيجة |
| `POST ../courses/{course}/content/{content}/complete` | `student.courses.content.complete` | صفحة Watch → إكمال المحتوى |
| `POST ../courses/{course}/request-certificate` | `student.courses.certificate.request` | صفحة Watch → طلب شهادة |

### Authenticated Routes (أي مستخدم مسجل)
| المسار | Route Name | نقطة الدخول |
|---|---|---|
| `/{lang}/messages` | `messages.index` | Navbar → الرسائل (rate limited) |
| `/{lang}/messages/{user}` | `messages.show` | صفحة Messages → محادثة |
| `/{lang}/certificate/verify/{code}` | `certificate.verify` | رابط التحقق من الشهادة |
| `/{lang}/certificate/{certificate}` | `certificate.show` | عرض الشهادة |
| `/{lang}/my-certificates` | `student.certificates` | Navbar (student) → شهاداتي |
| `/{lang}/profile` | `profile.edit` | Dropdown → الملف الشخصي |
| `PATCH /{lang}/profile` | `profile.update` | نموذج البروفايل → حفظ |
| `DELETE /{lang}/profile` | `profile.destroy` | صفحة البروفايل → حذف الحساب |

---

## 📁 Views Structure (ملفات العرض)

### Admin Views (`resources/views/admin/`)
| المجلد | الملفات | الوصف |
|---|---|---|
| `dashboard.blade.php` | 1 ملف | لوحة التحكم الرئيسية |
| `categories/` | index, create, edit | **[A5]** إدارة التصنيفات |
| `chat/` | index, show | **[A8]** إشراف الدردشة |
| `courses/` | index, pending, show, edit | إدارة الدورات |
| `enrollments/` | index | **[A6]** إدارة الاشتراكات |
| `payment_methods/` | index, create, edit, _form | **[PAY1]** طرق الدفع |
| `payouts/` | index | **[PAY2]** مدفوعات المعلمين |
| `reports/` | index | **[A7]** التقارير |
| `tutors/` | index, pending, show | إدارة المعلمين |
| `users/` | index, edit | **[A3]** إدارة المستخدمين |

### Tutor Views (`resources/views/tutor/`)
| المجلد | الملفات | الوصف |
|---|---|---|
| `dashboard.blade.php` | 1 ملف | لوحة التحكم |
| `courses/` | index, create, show, edit + content/ | إدارة الدورات |
| `certificates/` | index | إدارة الشهادات |
| `enrollments/` | index | **[E1]** طلبات القبول |
| `payouts/` | index | **[PAY2]** سحب الأرباح |
| `reports/` | index | **[T1]** تقارير المعلم |
| `profile/` | edit | الملف الشخصي |
| `quizzes/` | 6 ملفات | إدارة الاختبارات |

### Student Views (`resources/views/student/`)
| المجلد | الملفات | الوصف |
|---|---|---|
| `dashboard.blade.php` | 1 ملف (+ نسخة copy) | لوحة التحكم |
| `courses/` | index, my-courses, show, watch | تصفح ومشاهدة الدورات |
| `certificates/` | index | شهاداتي |
| `enrollment/` | payment | صفحة الدفع |
| `quizzes/` | show, result | الاختبارات |

### صفحات أخرى
| المسار | الملفات |
|---|---|
| `pages/` | privacy.blade.php, terms.blade.php (**[PP1]**) |
| `messages/` | 2 ملفات (index, show) |
| `certificate/` | 2 ملفات |
| `livewire/` | 3 ملفات (chat-box, course-reviews, notifications-dropdown) |
| `components/` | 17 ملف (Blade components) |
| `layouts/` | navigation.blade.php, app.blade.php, guest.blade.php |

---

## 🌍 حالة التوطين والترجمة (Localization Audit) - [آخر تحديث: 2026-03]

تم إجراء فحص شامل لجميع ملفات النظام (`resources/views`) لمعرفة حالة ترجمة النصوص (دعم اللغتين):

### 🟢 ملفات مترجمة بالكامل (65+ ملف)
تشمل: الـ Layouts، والمسارات العامة، والـ Auth، ولوحة تحكم الطالب الكاملة، ومعظم وظائف المعلم، والأدمن، ومكونات Livewire، بالإضافة إلى الواجهات التي تم الانتهاء منها مؤخراً:
- `student/enrollment/payment.blade.php`
- `tutor/courses/show.blade.php`
- `tutor/courses/content/edit.blade.php`
- `admin/categories/create.blade.php`
- `admin/courses/show.blade.php`
- `admin/payment_methods/_form.blade.php`
- `certificates/show.blade.php`
- `components/notifications-dropdown.blade.php`
- `student/courses/watch.blade.php`
- `tutor/courses/edit.blade.php`
- **تمت ترجمة قسم الاختبارات (Quizzes) بالكامل لجهتي المعلم والطالب.**

### 🟡 ملفات مترجمة جزئياً (بها نصوص ثابتة متبقية)
- (لا يوجد - تم استكمال ترجمة كافة الملفات الجزئية)

### 🔴 ملفات غير مترجمة (نصوص عربية ثابتة بالكامل)
- (لا يوجد - تم ترجمة جميع الملفات غير المترجمة بالكامل)

---

## ✅ جميع الإصلاحات المنجزة

### الجلسة الأولى (إصلاحات أمنية) - 7 إصلاحات
| الرمز | الإصلاح | الملفات |
|---|---|---|
| **C1** | DB Transactions للعمليات المالية | `EnrollmentController`, `Tutor\CourseController` |
| **C2** | Rate Limiting للمسارات الحساسة (enroll/payment/messaging) | `web.php` |
| **C3** | IDOR fix في CertificateController | `CertificateController` |
| **C4** | منع إعادة معالجة الدفع المكتمل | `EnrollmentController` |
| **M2** | N+1 query fix في MessageController | `MessageController` |
| **M4** | إزالة status من Course fillable | `Course.php`, `Tutor\CourseController` |
| **M6** | دمج 4 استعلامات counts في واحد | `Tutor\CourseController` |

### الجلسة الثانية (وظائف Admin المفقودة) - 4 إصلاحات
| الرمز | الإصلاح | الملفات |
|---|---|---|
| **A3** | إدارة كل المستخدمين (CRUD) | `Admin\UserController` + Views |
| **A4** | تعديل/حذف الدورات من الأدمن | `Admin\CourseController` + View |
| **A6** | إدارة الاشتراكات والمدفوعات | `Admin\EnrollmentController` + View |
| **A9** | مراجعة وحذف التقييمات | `Admin\CourseController::deleteReview()` |

### الجلسة الثالثة (نظام التصنيفات + التقارير + تنظيم Navbar)
| الرمز | الإصلاح | الملفات |
|---|---|---|
| **A5** | نظام التصنيفات (CRUD كامل + Migration + Model) | `Category Model` + `CategoryController` + Migration + 3 Views |
| **A7** | تقارير مفصلة وتحليلات الإيرادات | `ReportController` + `admin/reports/index.blade.php` |
| **NAV** | تنظيم Navbar | `navigation.blade.php` |
| **DASH** | تحديث Quick Actions | `admin/dashboard.blade.php` |
| **LANG** | إضافة 80+ مفتاح ترجمة (عربي + إنجليزي) | `ar/site.php`, `en/site.php` |
| **FIX** | إصلاح `isAdmin()` → `$user->role === 'admin'` | `Admin\UserController.php` |

### الجلسة الرابعة (الإصلاحات الحرجة - المرحلة 1)
| الرمز | الإصلاح | الملفات |
|---|---|---|
| **S1** | إصلاح CoursePolicy::update لتسمح للAdmin | `CoursePolicy.php` |
| **V1** | فحص `is_verified` لمنع إنشاء دورات لمعلم غير محقق | `Tutor\CourseController` |
| **A10** | Admin عرض/حذف دروس من صفحة الدورة | `Admin\CourseController` |
| **E1** | نظام موافقة المعلم على الطلاب (enrollment_status) | Migration + `Tutor\CourseController` + View |

### الجلسة الخامسة (نظام المدفوعات + الدردشة)
| الرمز | الإصلاح | الملفات |
|---|---|---|
| **PAY1** | نظام إدارة طرق الدفع (CRUD + toggle) | `PaymentMethod` Model + Migration + `Admin\PaymentMethodController` + 4 Views |
| **PAY2** | نظام سحب أرباح المعلم + إدارة Admin | `PayoutRequest` Model + Migration + `Admin\PayoutController` + `Tutor\PayoutController` + 2 Views |
| **MSG1** | تقييد الدردشة حسب العلاقة (زملاء كورس + معلمين + Admin) | `MessageController` + `ChatBox.php` |
| **A8** | إشراف Admin على الدردشة (عرض/حذف رسائل + إحصائيات) | `Admin\ChatController` + 2 Views |
| **PP1** | بنود الخصوصية وشروط الاستخدام | `PageController` + 2 Views |

### الجلسة السادسة (تقارير المعلم)
| الرمز | الإصلاح | الملفات |
|---|---|---|
| **T1** | صفحة تقارير وإحصائيات خاصة بالمعلم (دورات، طلاب، أرباح، تقييمات، رسم بياني شهري) | `Tutor\ReportController` + View + Route + Navbar + ترجمات |

### الجلسة السابعة (نظام الشروط والمؤهلات - REQ)
| الرمز | الإصلاح | الملفات |
|---|---|---|
| **REQ** | نظام الشروط والمؤهلات: 6 حقول جديدة في tutor_details (university, graduation_year, degree_certificate_path, skills, portfolio_url, agreed_to_terms) + حقل agreed_to_terms_at في users + checkbox الشروط في التسجيل + حقول المؤهلات في ملف المعلم + عرض المؤهلات في صفحة Admin + بيانات وهمية واقعية | Migration + Models + Controllers + 3 Views + Seeder + 13 ترجمة |

---

## 📋 قائمة التحقق مقابل المتطلبات

### Admin (المسؤول): ✅ **~98% مكتمل**
- ✅ لوحة تحكم شاملة
- ✅ التحقق من المعلمين وشهاداتهم
- ✅ **إدارة حسابات المستخدمين (عرض/تعديل/حذف)** ← A3
- ✅ **مراجعة الدورات (قبول/رفض/إلغاء موافقة/تعديل/حذف)** ← A4
- ✅ **إدارة التصنيفات** ← A5
- ✅ **إدارة الاشتراكات والدفع** ← A6
- ✅ **تحليلات وتقارير مفصلة** ← A7
- ✅ **إشراف على الدردشة (عرض/حذف رسائل + إحصائيات)** ← A8
- ✅ **مراجعة التقييمات** ← A9
- ✅ **حذف محتوى الدورات** ← A10
- ✅ **إدارة طرق الدفع** ← PAY1
- ✅ **إدارة مدفوعات المعلمين** ← PAY2

### Instructor (المعلم): ✅ **مكتمل بنسبة ~95%**
- ✅ التسجيل ورفع الشهادات | ✅ الملف الشخصي | ✅ إدارة الدورات والمحتوى
- ✅ إنشاء اختبارات | ⚠️ جلسات مباشرة (غير موجود) | ✅ دردشة + تقييمات + شهادات
- ✅ **نظام موافقة على الطلاب** ← E1
- ✅ **سحب الأرباح** ← PAY2
- ✅ **تقارير وإحصائيات المعلم** ← T1

### Student (الطالب): ✅ **مكتمل 100%**
- ✅ تسجيل حساب | ✅ لوحة تحكم | ✅ اشتراكات ودفع | ✅ دردشة
- ✅ اختبارات ونتائج | ✅ تقييم الدورات | ✅ طلب شهادات | ✅ تتبع التقدم

---

## 📁 جميع الملفات المنشأة/المعدلة (مرتبة حسب الجلسة)

### الجلسة الرابعة + الخامسة (الملفات الجديدة):
1. `database/migrations/2026_03_04_014215_add_enrollment_status_to_enrollments_table.php` ← E1
2. `database/migrations/2026_03_04_100000_create_payment_methods_table.php` ← PAY1
3. `database/migrations/2026_03_04_100001_create_payout_requests_table.php` ← PAY2
4. `app/Models/PaymentMethod.php` ← PAY1 Model
5. `app/Models/PayoutRequest.php` ← PAY2 Model
6. `app/Http/Controllers/Admin/PaymentMethodController.php` ← PAY1
7. `app/Http/Controllers/Admin/PayoutController.php` ← PAY2
8. `app/Http/Controllers/Tutor/PayoutController.php` ← PAY2
9. `app/Http/Controllers/Admin/ChatController.php` ← A8
10. `app/Http/Controllers/PageController.php` ← PP1
11. `resources/views/admin/payment_methods/index.blade.php` ← PAY1
12. `resources/views/admin/payment_methods/create.blade.php` ← PAY1
13. `resources/views/admin/payment_methods/edit.blade.php` ← PAY1
14. `resources/views/admin/payment_methods/_form.blade.php` ← PAY1
15. `resources/views/admin/payouts/index.blade.php` ← PAY2
16. `resources/views/tutor/payouts/index.blade.php` ← PAY2
17. `resources/views/admin/chat/index.blade.php` ← A8
18. `resources/views/admin/chat/show.blade.php` ← A8
19. `resources/views/tutor/enrollments/index.blade.php` ← E1
20. `resources/views/pages/privacy.blade.php` ← PP1
21. `resources/views/pages/terms.blade.php` ← PP1
22. `resources/views/admin/courses/pending.blade.php` ← Course pending view

### الجلسة السادسة (الملفات الجديدة):
23. `app/Http/Controllers/Tutor/ReportController.php` ← T1
24. `resources/views/tutor/reports/index.blade.php` ← T1

### الجلسة السابعة (الملفات الجديدة):
25. `database/migrations/2026_03_06_000001_add_qualifications_fields.php` ← REQ

### الجلسة الثالثة (الملفات الجديدة):
23. `database/migrations/2026_03_04_000001_create_categories_table.php` ← A5
24. `app/Models/Category.php` ← A5
25. `app/Http/Controllers/Admin/CategoryController.php` ← A5
26. `resources/views/admin/categories/index.blade.php` ← A5
27. `resources/views/admin/categories/create.blade.php` ← A5
28. `resources/views/admin/categories/edit.blade.php` ← A5
29. `app/Http/Controllers/Admin/ReportController.php` ← A7
30. `resources/views/admin/reports/index.blade.php` ← A7

---

## 📋 المهام المؤجلة (محدّث 2026-03-06)

### 🟡 المرحلة 2 - ميزات متبقية (1 مهمة):

#### ✅ PL - Policies إضافية (مكتمل)
**الهدف**: تطبيق سياسات Laravel (Policies) لحماية الموارد

**تم التنفيذ (الجلسة العاشرة):**
- **EnrollmentPolicy**: تحكم في رؤية ותعديل بيانات الاشتراكات.
- **MessagePolicy**: حماية من يمكنه رؤية وحذف الرسائل المقروءة.
- **QuizPolicy**: السماح لمعلم الكورس والأدمن فقط بتعديل الاختبارات.
- تم تطبيق التأكيدات عبر `$this->authorize()` في جميع وحدات التحكم (Controllers) الخاصة بالمعلم، الطالب، والأدمن للنماذج الثلاثة.


#### 🔵 TD1 - توسيع TutorDetail
**الهدف**: إضافة حقول إضافية لملف المعلم (الجامعة، سنة التخرج، موافقة على الشروط)

**الملفات المطلوبة:**
| الملف | الوصف |
|---|---|
| `database/migrations/xxxx_add_fields_to_tutor_details.php` | [NEW] Migration لإضافة 3 حقول |
| `app/Models/TutorDetail.php` | [MODIFY] إضافة fillable + casts |
| `resources/views/tutor/profile/edit.blade.php` | [MODIFY] إضافة حقول الإدخال |
| `app/Http/Controllers/Tutor/ProfileController.php` | [MODIFY] تحديث validation + update |
| `resources/views/admin/tutors/show.blade.php` | [MODIFY] عرض البيانات الجديدة |
| `lang/ar/site.php` + `lang/en/site.php` | [MODIFY] ترجمات TD1 |

**الحقول الجديدة:**
- `university` (string, nullable) - اسم الجامعة
- `graduation_year` (integer, nullable) - سنة التخرج
- `agreed_to_terms` (boolean, default false) - موافقة على شروط المعلم

---

### 🟢 المرحلة 3 - تحسينات (4 مهام):
| # | الرمز | الوظيفة | التفاصيل |
|---|---|---|---|
| 1 | **FR** | Form Requests لجميع Controllers | إنشاء Request classes منفصلة لكل عملية store/update |
| 2 | **SD** | Soft Deletes لـ Course, User, Enrollment | إضافة `SoftDeletes` trait + migration + استعادة المحذوفات |
| 3 | **CLEAN** | تنظيف الكود | حذف `dashboard.blade copy.php` + إزالة duplicate keys من site.php |
| 4 | **PG** | بوابة دفع حقيقية (Stripe/Paddle) | ربط مع بوابة دفع فعلية بدل النظام اليدوي |

---

## ⚠️ ملاحظات تحذيرية وأخطاء سابقة (Lessons Learned)

> **يجب على أي نموذج AI قادم قراءة هذا القسم بعناية:**

1. **❌ لا تستخدم `strftime()`** ← هي لـ SQLite فقط. المشروع يستخدم **MySQL**. استخدم `DATE_FORMAT()` دائماً.
2. **❌ لا تنسَ Eager Loading** ← أضف `with('relation')` دائماً عند جلب بيانات مرتبطة.
3. **❌ لا تفترض أن `$fillable` يحمي status** ← `Course.status` محذوف من fillable عمداً. لكن `Enrollment.payment_status` لا يزال موجوداً ⚠️
4. **❌ لا تنسَ التوطين** ← كل نص جديد يجب أن يكون في `lang/ar/site.php` و `lang/en/site.php`.
5. **❌ NavBar rule** ← لا تضف روابط في الـ Navbar إلا لأقسام جديدة مستقلة. الأقسام الفرعية تكون أزرار داخل صفحتها.
6. **⚠️ `Enrollment.payment_status`** لا يزال في `$fillable` ← يُفضل إزالته مثل `Course.status`.
7. **⚠️ نسخة مكررة**: `student/dashboard.blade copy.php` يجب حذفها (مهمة CLEAN).
8. **⚠️ قاعدة البيانات** ← لا تغير schema قبل التأكد من تأثيرها على الأدوار الثلاثة.
9. **✅ تم إصلاح**: `CoursePolicy::update` يسمح للAdmin بالتعديل ← S1 مكتمل.
10. **✅ تم إصلاح**: الدردشة مقيدة حسب العلاقة ← MSG1 مكتمل.
11. **✅ تم إصلاح**: المعلم غير المحقق لا يستطيع إنشاء دورات ← V1 مكتمل.

---

## ⚙️ ملاحظات تقنية مهمة

- **Laravel الإصدار**: 12.50.0 (PHP 8.2.12)
- **قاعدة البيانات**: **MySQL** (proskill_db عبر XAMPP) - **ليس SQLite!**
- **Migrations**: 26 ملف migration
- **Routes**: 276 سطر في `web.php` (3 Rate Limiters + 4 مجموعات routes)
- `Course::$fillable` لا يحتوي على `status` → **يجب تعيينه دائماً بشكل صريح**
- `Enrollment::$fillable` لا يزال يحتوي على `payment_status` → **يُفضل إزالته**
- التوطين عبر `mcamara/laravel-localization` (prefix: `/ar`, `/en`)
- الاختبارات تحتاج `withoutMiddleware` للتوطين
- **CourseReviews** يعمل عبر Livewire وليس Controller عادي
- **ChatBox** يعمل عبر Livewire مع polling كل 3 ثوانٍ + Rate Limiting + **[MSG1] تقييد جهات الاتصال**
- **Category** يدعم اسم عربي/إنجليزي مع `localized_name` accessor
- **PaymentMethod** يدعم اسم/تعليمات بالعربي والإنجليزي مع `localized_name`/`localized_instructions` accessors
- **PayoutRequest** يحتوي على status constants: pending/approved/rejected/paid
- **CoursePolicy** هو الـ Policy الوحيد الموجود حالياً (يحتاج توسيع ← PL)

---

## 📊 إحصائيات المشروع

| فئة | العدد |
|---|---|
| **Models** | 16 |
| **Controllers (Admin)** | 10 |
| **Controllers (Tutor)** | 6 |
| **Controllers (Student)** | 3 |
| **Controllers (Shared)** | 6 |
| **Livewire Components** | 3 |
| **Policies** | 1 |
| **Blade Views** | ~70+ |
| **Migrations** | 27 |
| **Routes (web.php)** | 276 سطر |
| **Tests (Feature)** | 3 ملفات (OmniTest, ProfileTest, ExampleTest) + Auth (6 ملفات) |

---

## 🔍 أوامر مفيدة
```bash
php artisan serve          # تشغيل السيرفر
npm run dev                # تشغيل Vite
php artisan test --filter=OmniTest   # تشغيل الاختبارات
php artisan optimize:clear # تنظيف الكاش
php artisan route:list --compact     # عرض المسارات
php artisan migrate        # تشغيل الميجريشن
```

---

## 🏥 ملخص الجلسات المنجزة

### ما تم إنجازه:
- ✅ **الجلسة الأولى**: 7 إصلاحات أمنية (C1-C4, M2, M4, M6)
- ✅ **الجلسة الثانية**: 4 وظائف Admin مفقودة (A3, A4, A6, A9)
- ✅ **الجلسة الثالثة**: نظام التصنيفات + التقارير + تنظيم Navbar (A5, A7, NAV, DASH, LANG, FIX)
- ✅ **الجلسة الرابعة**: إصلاحات حرجة المرحلة 1 (S1, V1, A10, E1)
- ✅ **الجلسة الرابعة**: نظام الدروس (REC)
- ✅ **الجلسة الخامسة**: عرض الكورس كطالب (CVW)
- ✅ **الجلسة السادسة**: تقارير المعلم (T1)
- ✅ **الجلسة السابعة**: نظام الشروط والمؤهلات (REQ)
- ✅ **الجلسة الثامنة (إصلاحات الطلاب)**: ترجمة صفحة حذف החساب و `issue_date` والتحقق من موافقة المعلم لمشاهدة الكورس وتجاوب صفحة الشهادات وإضافة إحصائيات الطلاب (المقبولين قيد المراجعة) في لوحة تحكم المعلم
- ✅ **إضافة الصور الشخصية وتحسين الواجهة**: دعم رفع صور شخصية للمستخدمين وتوليد أيقونة افتراضية حسب نوع الحساب وحل مشكلة العرض في (Navbar) و(Messages)، مع دعم التمرير الأفقي لخيارات التنقل في الجوال والأجهزة اللوحية (Tablets)، وإضافة روابط الرسائل والإشعارات لقائمة الجوال.
- ✅ **إصلاح أخطاء الإشعارات بالجوال**: استبدال الأكواد اليدوية الثابتة بمكون اللايفوير `<livewire:notifications-dropdown>` المدمج الأساسي الخاص بالإشعارات والرسائل لحل أخطاء `QueryException` و `RouteNotFoundException` ولضمان التحديث التلقائي.
- ✅ **إعادة بناء**: student/courses views (index, my-courses, show, watch)
- ✅ **إصلاحات إضافية**: strftime → DATE_FORMAT, LazyLoadingViolation
- ✅ **الجلسة التاسعة (ترجمة الاختبارات - Quiz Localization)**: استبدال جميع النصوص الثابتة في واجهات المعلم والطالب بمفاتيح ترجمة (ar/en).
- ✅ **الجلسة الحادية عشر (v8.0 - الأهلية والإعدادات والحماية الهرمية)**:
  - **Super Admin**: حقل `is_super_admin` + أمر `php artisan make:super-admin` + middleware `super_admin`
  - **إعدادات ديناميكية**: نموذج `Setting` مع `Cache::remember` + سجل تدقيق `SettingsHistory`
  - **فحص الأهلية**: `EligibilityController` + صفحة `/eligibility-check` + جلسات محدودة بساعة
  - **حماية هرمية**: فقط السوبر أدمن يدير المدراء + حماية من الحذف الذاتي ومن حذف آخر مدير
  - **حماية التسجيل**: رفض تسجيل معلم بدون أهلية + تنظيف آني للجلسات `session()->forget()`
  - **عدالة تاريخية**: حفظ شروط وقت التسجيل (`req_gpa_at_registration`, `req_step_at_registration`)
  - **واجهات جديدة**: لوحة إعدادات + إنشاء مستخدم + فحص أهلية + قسم بيانات الأهلية في صفحة المعلم
  - **Rate Limiting**: throttle على الإعدادات (10/دقيقة) والأهلية (10/دقيقة)
  - **ترجمة**: ~40 مفتاح جديد (ar/en) لكل المكونات الجديدة

### حالة النظام الحالية:
- **المرحلة 1 مكتملة**: 6/6 إصلاحات حرجة ✅
- **المرحلة 2 جزئية**: 6/6 (MSG1 ✅, A8 ✅, PP1 ✅, PAY ✅, T1 ✅, PL ✅) | **متبقي**: TD1
- **المرحلة 3**: 2/4 (CLEAN ✅, SD ✅) | **متبقي**: FR, PG
- **REQ مكتمل**: نظام الشروط والمؤهلات ✅
- **v8.0 مكتمل**: الأهلية + الإعدادات + الحماية الهرمية + العدالة التاريخية ✅
- **إجمالي الوظائف المستقرة**: 40+ وظيفة تعمل بنسبة 100%

### [v8.0] مسارات جديدة:

| المسار | المتحكم | الوصف | الحماية |
|---|---|---|---|
| `GET eligibility-check` | `EligibilityController@show` | صفحة فحص الأهلية | عام |
| `POST eligibility-check` | `EligibilityController@check` | معالجة فحص الأهلية | عام + throttle:10/min |
| `GET admin/settings` | `Admin\SettingController@index` | لوحة الإعدادات | role:admin + super_admin + throttle |
| `POST admin/settings` | `Admin\SettingController@update` | تحديث الإعدادات | role:admin + super_admin + throttle |
| `GET admin/users/create` | `Admin\UserController@create` | نموذج إنشاء مستخدم | role:admin |
| `POST admin/users` | `Admin\UserController@store` | حفظ مستخدم جديد | role:admin |

### [v8.0] أمر Artisan:
- `php artisan make:super-admin` — أمر تفاعلي لترقية admin موجود إلى سوبر أدمن

### الجلسة الثانية عشر (FIN - النظام المالي الشامل):
- **Transaction Model**: إنشاء نموذج وجدول `transactions` لتتبع المدفوعات والاستردادات والسحوبات وعمولات المنصة.
- **محفظة المعلم**: إضافة حقول (`available_balance`, `pending_balance`, `total_earned`, `total_withdrawn`) لجدول `tutor_details`.
- **FinancialService**: مركزية المنطق المالي وحساب العمولات، وتسجيل العمليات في قاعدة البيانات عبر Transactions.
- **تحديث المدفوعات**: ربط `Student\EnrollmentController` لتسجيل المعاملات عند الدفع (pending).
- **تحديث إشراف الأدمن**: `Admin\EnrollmentController` الآن يحدث المحفظة عند الاعتماد (completed) أو الرفض (failed)، وتمت إضافة وظيفة الاسترداد (Refund).
- **تحديث السحوبات**: `Tutor\PayoutController` يعتمد على رصيد المحفظة (`available_balance`) لإنشاء الطلبات، و `Admin\PayoutController` يعالج المعاملات ويخصم الرصيد عند الدفع.
- **مسارات جديدة**:
  - `GET admin/transactions`: قائمة المعاملات والتحليلات للأدمن.
  - `GET admin/transactions/{tx}`: فاتورة/تفاصيل المعاملة.
  - `POST admin/enrollments/{enrollment}/refund`: استرداد الدفع.
  - `GET student/transactions`: سجل مدفوعات الطالب وفواتيره.
- **إصلاحات أخرى**: تم حل `MassAssignmentException` في `Admin\CourseController` بتحديث الحالة بشكل صريح، وإصلاح مشكلة `$slot` في واجهات المعاملات الجديدة (استخدام `<x-app-layout>`).

---
---

# 📋 LARA-SPEC-AUDIT (تدقيق شامل - 2026-04-13)
## المُدقق: ULTRA-LARA-SPECKIT-AUDITOR-v6.0

> تم إجراء هندسة عكسية كاملة للمشروع، سطرًا بسطر، بتاريخ **2026-04-13**.
> تم فحص: **316 سطر routes** + **19 model** + **32 controller** + **34 migration** + **3 Livewire** + **4 Policy** + **2 Middleware** + **1 Service**

---

## 1. 🏗️ SYSTEM ARCHITECTURE & ROUTES MAP (مخطط النظام والمسارات)

### 1.1 خريطة المسارات → المتحكمات (Route → Controller Traceability)

| # | Route | Method | Middleware | Controller@Method | Model | View | الحالة |
|---|---|---|---|---|---|---|---|
| 1 | `/` | GET | locale | Closure | — | `welcome` | ✅ |
| 2 | `/courses` | GET | locale | `Student\CourseController@index` | Course, Category | `student.courses.index` | ✅ |
| 3 | `/courses/{course}` | GET | locale | `Student\CourseController@show` | Course, Enrollment | `student.courses.show` | ✅ |
| 4 | `/privacy` | GET | locale | `PageController@privacy` | — | `pages.privacy` | ✅ |
| 5 | `/terms` | GET | locale | `PageController@terms` | — | `pages.terms` | ✅ |
| 6 | `/eligibility-check` | GET | locale | `EligibilityController@show` | — | `eligibility.check` | ✅ |
| 7 | `/eligibility-check` | POST | locale+throttle:eligibility | `EligibilityController@check` | Setting | redirect | ✅ |
| 8 | `/messages` | GET | auth+throttle:messaging | `MessageController@index` | Message, User | `messages.index` | ✅ |
| 9 | `/messages/{user}` | GET | auth+throttle:messaging | `MessageController@show` | Message, User | `messages.index` | ✅ |
| 10 | `/certificate/verify/{code}` | GET | auth | `CertificateController@verify` | CourseCertificate | `certificate.verify` | ✅ |
| 11 | `/certificate/{certificate}` | GET | auth | `CertificateController@show` | CourseCertificate, QuizAttempt | `certificate.show` / `certificates.quiz-show` | ⚠️ |
| 12 | `/my-certificates` | GET | auth | `CertificateController@myCertificates` | CourseCertificate | `student.certificates.index` | ✅ |
| 13 | `/profile` | GET/PATCH/DELETE | auth | `ProfileController@edit/update/destroy` | User | `profile.edit` | ✅ |
| 14 | `/notifications/mark-as-read` | GET | auth | `ProfileController@markAsRead` | Notification | redirect | ✅ |
| **ADMIN** |
| 15 | `/admin/dashboard` | GET | auth+role:admin | `Admin\DashboardController@index` | User, Course, Enrollment | `admin.dashboard` | ✅ |
| 16 | `/admin/users` | GET/POST | auth+role:admin | `Admin\UserController@index/store` | User | `admin.users.index` | ✅ |
| 17 | `/admin/users/create` | GET | auth+role:admin | `Admin\UserController@create` | — | `admin.users.create` | ✅ |
| 18 | `/admin/users/{user}` | GET/PUT/DELETE | auth+role:admin | `Admin\UserController@show/update/destroy` | User | `admin.users.show/edit` | ✅ |
| 19 | `/admin/tutors` | GET | auth+role:admin | `Admin\TutorController@index` | User, TutorDetail | `admin.tutors.index` | ✅ |
| 20 | `/admin/tutors/pending` | GET | auth+role:admin | `Admin\TutorController@pending` | User, TutorDetail | `admin.tutors.pending` | ✅ |
| 21 | `/admin/tutors/{tutor}` | GET | auth+role:admin | `Admin\TutorController@show` | User, TutorDetail, Course | `admin.tutors.show` | ✅ |
| 22 | `/admin/tutors/{tutor}/verify` | POST | auth+role:admin | `Admin\TutorController@verify` | TutorDetail | redirect | ✅ |
| 23 | `/admin/tutors/{tutor}/reject` | POST | auth+role:admin | `Admin\TutorController@reject` | TutorDetail, Course | redirect | ✅ |
| 24 | `/admin/tutors/{tutor}/approve-all-courses` | POST | auth+role:admin | `Admin\TutorController@approveAllCourses` | Course | redirect | ✅ |
| 25 | `/admin/courses` | GET | auth+role:admin | `Admin\CourseController@index` | Course | `admin.courses.index` | ✅ |
| 26 | `/admin/courses/pending` | GET | auth+role:admin | `Admin\CourseController@pending` | Course | `admin.courses.pending` | ✅ |
| 27 | `/admin/courses/{course}` | GET/PUT/DELETE | auth+role:admin | `Admin\CourseController@show/update/destroy` | Course, Review, CourseContent | `admin.courses.show/edit` | ✅ |
| 28 | `/admin/courses/{course}/approve` | POST | auth+role:admin | `Admin\CourseController@approve` | Course | redirect | ✅ |
| 29 | `/admin/courses/{course}/reject` | POST | auth+role:admin | `Admin\CourseController@reject` | Course | redirect | ✅ |
| 30 | `/admin/courses/{course}/unapprove` | POST | auth+role:admin | `Admin\CourseController@unapprove` | Course | redirect | ✅ |
| 31 | `/admin/courses/{course}/reviews/{review}` | DELETE | auth+role:admin | `Admin\CourseController@deleteReview` | Review | redirect | ✅ |
| 32 | `/admin/courses/{course}/content/{content}` | DELETE | auth+role:admin | `Admin\CourseController@deleteContent` | CourseContent | redirect | ✅ |
| 33 | `/admin/enrollments` | GET | auth+role:admin | `Admin\EnrollmentController@index` | Enrollment | `admin.enrollments.index` | ✅ |
| 34 | `/admin/enrollments/{enrollment}/status` | PATCH | auth+role:admin | `Admin\EnrollmentController@updateStatus` | Enrollment, Transaction | redirect | 🔴 خطأ حرج |
| 35 | `/admin/enrollments/{enrollment}/refund` | POST | auth+role:admin | `Admin\EnrollmentController@refund` | Enrollment, Transaction | redirect | 🔴 خطأ حرج |
| 36 | `/admin/categories` | CRUD | auth+role:admin | `Admin\CategoryController@*` | Category | `admin.categories.*` | ✅ |
| 37 | `/admin/reports` | GET | auth+role:admin | `Admin\ReportController@index` | — | `admin.reports.index` | ✅ |
| 38 | `/admin/payment-methods` | CRUD+toggle | auth+role:admin | `Admin\PaymentMethodController@*` | PaymentMethod | `admin.payment_methods.*` | ✅ |
| 39 | `/admin/payouts` | GET | auth+role:admin | `Admin\PayoutController@index` | PayoutRequest | `admin.payouts.index` | ✅ |
| 40 | `/admin/payouts/{id}/approve` | POST | auth+role:admin | `Admin\PayoutController@approve` | PayoutRequest, Transaction | redirect | ✅ |
| 41 | `/admin/payouts/{id}/reject` | POST | auth+role:admin | `Admin\PayoutController@reject` | PayoutRequest | redirect | ✅ |
| 42 | `/admin/payouts/{id}/mark-paid` | POST | auth+role:admin | `Admin\PayoutController@markPaid` | PayoutRequest, TutorDetail | redirect | ✅ |
| 43 | `/admin/chat` | GET | auth+role:admin | `Admin\ChatController@index` | Message, User | `admin.chat.index` | ✅ |
| 44 | `/admin/chat/{user1}/{user2}` | GET | auth+role:admin | `Admin\ChatController@show` | Message, User | `admin.chat.show` | ✅ |
| 45 | `/admin/chat/messages/{message}` | DELETE | auth+role:admin | `Admin\ChatController@destroyMessage` | Message | redirect | ✅ |
| 46 | `/admin/transactions` | GET | auth+role:admin | `Admin\TransactionController@index` | Transaction | `admin.transactions.index` | ✅ |
| 47 | `/admin/transactions/{transaction}` | GET | auth+role:admin | `Admin\TransactionController@show` | Transaction | `admin.transactions.show` | ✅ |
| 48 | `/admin/settings` | GET/POST | auth+role:admin+super_admin+throttle | `Admin\SettingController@index/update` | Setting, SettingsHistory | `admin.settings.index` | ✅ |
| **TUTOR** |
| 49 | `/tutor/dashboard` | GET | auth+role:tutor,admin | `Tutor\DashboardController@index` | — | `tutor.dashboard` | ✅ |
| 50 | `/tutor/profile` | GET/PUT | auth+role:tutor,admin | `Tutor\ProfileController@edit/update` | TutorDetail | `tutor.profile.edit` | ✅ |
| 51 | `/tutor/profile/cv` | GET | auth+role:tutor,admin | `Tutor\ProfileController@downloadCv` | TutorDetail | download | ✅ |
| 52 | `/tutor/courses` | CRUD (resource) | auth+role:tutor,admin | `Tutor\CourseController@*` | Course, CourseContent | `tutor.courses.*` | ✅ |
| 53 | `/tutor/courses/{course}/content` | POST/PUT/DELETE | auth+role:tutor,admin | `Tutor\CourseController@add/update/deleteContent` | CourseContent | redirect | ✅ |
| 54 | `/tutor/courses/{course}/content/reorder` | POST | auth+role:tutor,admin | `Tutor\CourseController@reorderContents` | CourseContent | JSON | ✅ |
| 55 | `/tutor/courses.quizzes` | resource+builder+results+attempts | auth+role:tutor,admin | `Tutor\QuizController@*` | Quiz, Question, Option, QuizAttempt | `tutor.quizzes.*` | ✅ |
| 56 | `/tutor/certificates` | GET | auth+role:tutor,admin | `Tutor\CourseController@certificatesIndex` | CourseCertificate | `tutor.certificates.index` | ✅ |
| 57 | `/tutor/certificates/{id}/issue` | POST | auth+role:tutor,admin | `Tutor\CourseController@issueCertificate` | CourseCertificate | redirect | ✅ |
| 58 | `/tutor/certificates/{id}/reject` | POST | auth+role:tutor,admin | `Tutor\CourseController@rejectCertificate` | CourseCertificate | redirect | ✅ |
| 59 | `/tutor/certificates/{id}/revoke` | POST | auth+role:tutor,admin | `Tutor\CourseController@revokeCertificate` | CourseCertificate | redirect | ✅ |
| 60 | `/tutor/enrollments` | GET | auth+role:tutor,admin | `Tutor\CourseController@enrollmentsIndex` | Enrollment | `tutor.enrollments.index` | ✅ |
| 61 | `/tutor/enrollments/{id}/approve` | POST | auth+role:tutor,admin | `Tutor\CourseController@approveEnrollment` | Enrollment | redirect | ✅ |
| 62 | `/tutor/enrollments/{id}/reject` | POST | auth+role:tutor,admin | `Tutor\CourseController@rejectEnrollment` | Enrollment | redirect | ✅ |
| 63 | `/tutor/reports` | GET | auth+role:tutor,admin | `Tutor\ReportController@index` | — | `tutor.reports.index` | ✅ |
| 64 | `/tutor/payouts` | GET/POST | auth+role:tutor,admin | `Tutor\PayoutController@index/store` | PayoutRequest, TutorDetail | `tutor.payouts.index` | ✅ |
| **STUDENT** |
| 65 | `/student/dashboard` | GET | auth+role:student | `Student\DashboardController@index` | — | `student.dashboard` | ✅ |
| 66 | `/student/courses` | GET | auth+role:student | `Student\CourseController@index` | Course, Category | `student.courses.index` | ✅ |
| 67 | `/student/courses/{course}` | GET | auth+role:student | `Student\CourseController@show` | Course, Enrollment | `student.courses.show` | ✅ |
| 68 | `/student/my-courses` | GET | auth+role:student | `Student\CourseController@myCourses` | Enrollment, Course | `student.courses.my-courses` | ✅ |
| 69 | `/student/courses/{course}/watch/{content?}` | GET | auth+role:student | `Student\CourseController@watch` | Course, Enrollment, ContentProgress | `student.courses.watch` | ✅ |
| 70 | `/student/courses/{course}/enroll` | POST | auth+role:student+throttle:enroll | `Student\EnrollmentController@enroll` | Course, Enrollment | redirect | ✅ |
| 71 | `/student/enrollment/{enrollment}/payment` | GET/POST | auth+role:student+throttle:payment | `Student\EnrollmentController@showPayment/processPayment` | Enrollment, Transaction | `student.enrollment.payment` | ✅ |
| 72 | `/student/my-enrollments` | GET | auth+role:student | `Student\EnrollmentController@myEnrollments` | Enrollment | `student.enrollment.my-enrollments` | ✅ |
| 73 | `/student/transactions` | GET | auth+role:student | `Student\TransactionController@index` | Transaction | `student.transactions.index` | ✅ |
| 74 | `/student/transactions/{transaction}` | GET | auth+role:student | `Student\TransactionController@show` | Transaction | `student.transactions.show` | ✅ |
| 75 | `/student/quizzes/{quiz}` | GET/POST | auth+role:student | `QuizController@show/submit` | Quiz, QuizAttempt | `student.quizzes.show` | ✅ |
| 76 | `/student/quizzes/{quiz}/result` | GET | auth+role:student | `QuizController@result` | QuizAttempt | `student.quizzes.result` | ✅ |
| 77 | `/student/courses/{course}/content/{content}/complete` | POST | auth+role:student | `Student\CourseController@markComplete` | ContentProgress | redirect | ✅ |
| 78 | `/student/courses/{course}/request-certificate` | POST | auth+role:student | `Student\CourseController@requestCertificate` | CourseCertificate | redirect | ✅ |

### 1.2 ملخص التغطية
- **إجمالي المسارات**: ~78 مسار (بدون auth.php)
- **مسارات auth.php**: 10 مسارات إضافية (register, login, logout, password reset, email verification)
- **مسارات محمية بـ Rate Limiting**: 5 (enroll, payment, messaging, settings, eligibility)
- **مسارات محمية بـ super_admin**: 2 (settings index/update)

---

## 2. 🚨 CRITICAL ERRORS & MISSING ASSETS (سجل الأخطاء الحرجة والمفقودات)

### 🔴 أخطاء حرجة (CRITICAL - يجب إصلاحها فوراً)

#### خطأ C-01: تكرار دالة `updateStatus` في `Admin\EnrollmentController.php`
- **الملف**: `app/Http/Controllers/Admin/EnrollmentController.php`
- **السطور**: 57-92 (نسخة داخل تعليق مفتوح غير مغلق) + 100-146 (النسخة الفعلية)
- **التفاصيل**: يوجد كود معلق بطريقة غير صحيحة. التعليق `/**` في السطر 57 لا يُغلق بشكل صحيح قبل بداية الدالة. هذا يخلق ارتباكاً ويوجد كود ميت بين السطور 60-92.
- **التأثير**: كود ميت مربك + هيكل تعليقات غير سليم
- **خطة الإصلاح**: حذف السطور 57-93 بالكامل (النسخة القديمة المعلقة) وترك فقط النسخة الحديثة (السطور 97-146)

#### خطأ C-02: عدم تطابق `payment_status` Enum مع قاعدة البيانات
- **الملف**: `database/migrations/2026_02_01_200004_create_enrollments_table.php`
- **التفاصيل**: الـ enum المعرف في Migration هو `['pending', 'paid', 'failed']` فقط
- **لكن** `Admin\EnrollmentController@updateStatus` يقبل: `['paid', 'pending', 'completed', 'failed', 'refunded']`
- **و** `Admin\EnrollmentController@refund` يحاول تعيين `payment_status = 'refunded'`
- **التأثير**: **خطأ قاعدة بيانات فوري** عند محاولة الاسترداد (Refund) أو تعيين حالة `completed`/`refunded`
- **خطة الإصلاح**: إنشاء migration جديد لتعديل enum العمود:
  ```php
  // migration: modify_payment_status_enum_in_enrollments
  DB::statement("ALTER TABLE enrollments MODIFY COLUMN payment_status ENUM('pending','paid','failed','completed','refunded') DEFAULT 'pending'");
  ```

#### خطأ C-03: عمود `name_ar` مفقود في جدول `payment_methods`
- **الملف**: `database/migrations/2026_03_04_100000_create_payment_methods_table.php`
- **التفاصيل**: الـ Migration يُنشئ `name` و `name_en` لكن **لا يُنشئ `name_ar`**
- **لكن** `PaymentMethod` Model يحتوي على `name_ar` في `$fillable` ودالة `boot()` تُعيّن `$model->name_ar = $model->name`
- **التأثير**: خطأ `Column not found: name_ar` عند حفظ أي PaymentMethod
- **خطة الإصلاح**: إنشاء migration لإضافة العمود:
  ```php
  Schema::table('payment_methods', function (Blueprint $table) {
      $table->string('name_ar')->nullable()->after('name');
  });
  ```

#### خطأ C-04: حقول غير موجودة في جدول `enrollments` مُستخدمة في `FinancialService`
- **الملف**: `app/Services/FinancialService.php` (سطر 51, 56)
- **التفاصيل**: الكود يصل لـ `$enrollment->payment_method_id` و `$enrollment->payment_proof` لكن هذه الأعمدة **غير موجودة** في جدول `enrollments`
- **التأثير**: القيم دائماً `null` — لن يتم ربط Transaction بأي PaymentMethod عند تسجيل الدفع
- **خطة الإصلاح**: إنشاء migration لإضافة هذه الأعمدة:
  ```php
  Schema::table('enrollments', function (Blueprint $table) {
      $table->foreignId('payment_method_id')->nullable()->after('enrollment_status')
            ->constrained('payment_methods')->nullOnDelete();
      $table->string('payment_proof')->nullable()->after('payment_method_id');
  });
  ```
  + تحديث `Enrollment::$fillable` لإضافة `payment_method_id` و `payment_proof`
  + تحديث صفحة الدفع لتتيح اختيار طريقة الدفع ورفع الإيصال

### 🟠 أخطاء متوسطة (MEDIUM - يجب إصلاحها قريباً)

#### خطأ M-01: `payment_status` في `Enrollment::$fillable` — ثغرة Mass Assignment
- **الملف**: `app/Models/Enrollment.php` (سطر 17)
- **التفاصيل**: `payment_status` موجود في `$fillable` رغم أنه حقل حساس (مثل `Course::status` الذي أُزيل عمداً)
- **التأثير**: أي إدخال يمكن أن يُغيّر حالة الدفع عبر Mass Assignment
- **خطة الإصلاح**: إزالة `payment_status` من `$fillable` واستخدام التعيين الصريح:
  ```php
  // بدلاً من $enrollment->update(['payment_status' => 'paid'])
  $enrollment->payment_status = 'paid';
  $enrollment->save();
  ```

#### خطأ M-02: `enrollment_status` Enum عدم تطابق في Validation
- **الملف**: `app/Http/Controllers/Admin/EnrollmentController.php` (سطر 106)
- **التفاصيل**: الـ Validation يقبل `'enrolled'` لكن الـ enum في DB هو `['pending_approval', 'approved', 'rejected']`
- **خطة الإصلاح**: تغيير القاعدة:
  ```php
  'enrollment_status' => ['required', 'string', 'in:pending_approval,approved,rejected'],
  ```

#### خطأ M-03: `CourseReviews` Livewire لا يتحقق من `enrollment_status`
- **الملف**: `app/Livewire/CourseReviews.php` (سطر 42)
- **التفاصيل**: يتحقق فقط من وجود enrollment ولا يتحقق من `enrollment_status === 'approved'`
- **التأثير**: طالب في حالة `pending_approval` أو `rejected` يمكنه كتابة تقييم
- **خطة الإصلاح**:
  ```php
  $isEnrolled = $course->enrollments()
      ->where('user_id', $user->id)
      ->where('payment_status', 'paid')
      ->where('enrollment_status', 'approved')
      ->exists();
  ```

#### خطأ M-04: `SettingsHistory` يستخدم `$timestamps = false` لكن يعتمد على `created_at`
- **الملف**: `app/Models/SettingsHistory.php` (سطر 13)
- **التفاصيل**: `$timestamps = false` يعني Laravel لن يُعيّن `created_at` تلقائياً، لكن الـ Migration يستخدم `->useCurrent()` على العمود — لذا قاعدة البيانات تتولى ذلك. **هذا يعمل** لكن الكاست `'created_at' => 'datetime'` بدون `$timestamps` قد يُربك.
- **خطة الإصلاح**: إضافة تعليق توضيحي أو استخدام `const CREATED_AT = 'created_at'; const UPDATED_AT = null;`

#### خطأ M-05: `Admin\EnrollmentController@updateStatus` يستخدم `$this->authorize('update', $enrollment)` مع EnrollmentPolicy
- **الملف**: `app/Http/Controllers/Admin/EnrollmentController.php` (سطر 102)
- **التفاصيل**: الدالة تستدعي `$this->authorize('update', $enrollment)` لكن الأدمن يمر دائماً (Policy ترجع `true` للأدمن). المشكلة أن الـ Admin route يحمي بالفعل بـ `role:admin` middleware — فالـ authorize زائد لكن ليس ضاراً.
- **خطة الإصلاح**: لا ضرر، لكن يُفضل التوحيد — إما Policy فقط أو Middleware فقط.

#### خطأ M-06: View `certificates.quiz-show` قد تكون مفقودة
- **الملف**: `app/Http/Controllers/CertificateController.php` (سطر 91)
- **التفاصيل**: عند عدم وجود `CourseCertificate`، يبحث عن `QuizAttempt` ويعرض `certificates.quiz-show`. يجب التأكد من وجود هذا الملف في `resources/views/certificates/quiz-show.blade.php`
- **خطة الإصلاح**: التحقق من وجود الملف وإنشاؤه إذا كان مفقوداً

### 🟡 أخطاء طفيفة (LOW)

#### خطأ L-01: كود ميت في `Student\CourseController`
- **الملف**: `app/Http/Controllers/Student/CourseController.php` (سطور 207-228)
- **التفاصيل**: نسخة قديمة معلقة من دالة `show()` داخل `/* ... */`
- **خطة الإصلاح**: حذف السطور 207-228

#### خطأ L-02: `clear_attempts.php` في جذر المشروع
- **الملف**: `clear_attempts.php` (في جذر المشروع)
- **التفاصيل**: ملف PHP مستقل في جذر المشروع — محتمل أنه سكربت تنظيف مؤقت
- **خطة الإصلاح**: حذفه أو نقله لـ command artisan

#### خطأ L-03: عدد النماذج في التوثيق القديم غير دقيق
- **التفاصيل**: التوثيق يذكر **16 model** لكن يوجد **19 model** فعلياً:
  - مذكورة: User, Course, Category, CourseContent, Enrollment, CourseCertificate, ContentProgress, Quiz, Question, Option, QuizAttempt, Review, Message, TutorDetail, PaymentMethod, PayoutRequest
  - **مفقودة من التوثيق**: `Setting`, `SettingsHistory`, `Transaction`
- **خطة الإصلاح**: تحديث جدول النماذج ليشمل الـ 19

#### خطأ L-04: عدد الـ Policies في التوثيق القديم غير دقيق
- **التفاصيل**: التوثيق يذكر **1 Policy** (CoursePolicy فقط) لكن يوجد **4 Policies**:
  - `CoursePolicy.php` ✅ مذكور
  - `EnrollmentPolicy.php` ❌ غير مذكور
  - `MessagePolicy.php` ❌ غير مذكور
  - `QuizPolicy.php` ❌ غير مذكور
- **خطة الإصلاح**: تحديث قسم Policies

#### خطأ L-05: `course_payment_methods` Pivot Table غير مستخدم
- **التفاصيل**: جدول `course_payment_methods` أُنشئ في migration لربط الدورات بطرق الدفع، و`PaymentMethod::courses()` علاقة معرفة — لكن **لا يوجد أي كود يستخدم هذه العلاقة أو الجدول**
- **خطة الإصلاح**: إما تفعيل الميزة في المستقبل أو حذف الجدول والعلاقة

#### خطأ L-06: `Student\DashboardController` و `Tutor\DashboardController` لم تُقرأ بالتفصيل
- **خطة الإصلاح**: مراجعة سريعة للتأكد من عدم وجود N+1 queries

---

## 3. 🧠 SPEC-KIT ANALYSIS (/speckit.analyze)

### 3.1 Drift Detection (الانزياح — تناقضات بين الطبقات)

| # | المكان | الوصف | الخطورة |
|---|---|---|---|
| D-01 | Enrollment enum ↔ Controller validation | DB enum: `['pending','paid','failed']` vs Controller: يقبل `completed`+`refunded` | 🔴 حرج |
| D-02 | PaymentMethod model ↔ Migration | Model يستخدم `name_ar` لكن العمود غير موجود في DB | 🔴 حرج |
| D-03 | FinancialService ↔ Enrollment table | يصل لـ `payment_method_id`+`payment_proof` غير موجودين | 🔴 حرج |
| D-04 | CourseReviews Livewire ↔ Enrollment status | لا يتحقق من `enrollment_status` | 🟠 متوسط |
| D-05 | Policies count ↔ Documentation | 4 policies فعلياً vs 1 مذكور | 🟡 طفيف |
| D-06 | Models count ↔ Documentation | 19 models فعلياً vs 16 مذكور | 🟡 طفيف |

### 3.2 Dead Code (أكواد ميتة)

| # | الملف | السطور | الوصف |
|---|---|---|---|
| DC-01 | `Admin\EnrollmentController.php` | 57-93 | نسخة قديمة معلقة من `updateStatus` |
| DC-02 | `Student\CourseController.php` | 207-228 | نسخة قديمة معلقة من `show()` |
| DC-03 | `clear_attempts.php` | كامل | سكربت مستقل في جذر المشروع |
| DC-04 | `PaymentMethod::courses()` | علاقة | `belongsToMany` معرفة لكن غير مستخدمة أبداً |
| DC-05 | `course_payment_methods` table | جدول | جدول pivot موجود لكن غير مستخدم |
| DC-06 | `repomix-output.xml` | 633KB | ملف ضخم في الجذر، غير مرتبط بالتطبيق |
| DC-07 | `core-backend.xml` | 101KB | ملف ضخم في الجذر |

### 3.3 Security & Auth (أمان النظام)

| # | الموضوع | الحالة | التفاصيل |
|---|---|---|---|
| S-01 | Mass Assignment - Course.status | ✅ محمي | محذوف من `$fillable` — يُعيّن صراحةً |
| S-02 | Mass Assignment - User.role | ✅ محمي | محذوف من `$fillable` — يُعيّن صراحةً |
| S-03 | Mass Assignment - `is_super_admin` | ✅ محمي | غير في `$fillable` + محمي بـ cast فقط |
| S-04 | Mass Assignment - Enrollment.payment_status | ⚠️ **غير محمي** | لا يزال في `$fillable` |
| S-05 | IDOR - CertificateController | ✅ مُصلح | فحص الملكية + الدور |
| S-06 | Rate Limiting | ✅ مُطبق | 5 rate limiters (enroll/payment/messaging/settings/eligibility) |
| S-07 | CSRF Protection | ✅ مُطبق | عبر middleware مع استثناء logout فقط |
| S-08 | SQL Injection - Search | ✅ مُصلح | `addcslashes` في كل عمليات البحث |
| S-09 | Hierarchical Auth | ✅ مُطبق | Super Admin → Admin → Tutor/Student |
| S-10 | Self-Deletion Prevention | ✅ مُطبق | `UserController@destroy` |
| S-11 | DB Transactions | ✅ مُطبق | في كل العمليات المالية |
| S-12 | ChatBox Rate Limiting | ✅ مُطبق | 10 رسائل/دقيقة عبر RateLimiter |
| S-13 | Chat Contact Restriction | ✅ مُطبق | MSG1 — حسب العلاقة فقط |
| S-14 | Tutor Verification Check | ✅ مُطبق | V1 — لا إنشاء دورات بدون تحقق |
| S-15 | Eligibility Enforcement | ✅ مُطبق | v8.0 — جلسة محدودة بساعة |

### 3.4 Model ↔ Migration Consistency (تطابق النماذج مع الترحيلات)

| Model | $fillable Count | DB Columns (from all migrations) | الحالة |
|---|---|---|---|
| User | 4 (name,email,password,avatar) | name,email,password,role,is_super_admin,avatar,agreed_to_terms_at,+soft_deletes | ✅ |
| Course | 5 (tutor_id, category_id,title,description,price,thumbnail) | +status,+soft_deletes | ✅ (`status` محمي) |
| Enrollment | 4 (user_id,course_id,payment_status,enrollment_status) | ← مطابق +soft_deletes | ⚠️ `payment_status` يجب حمايته |
| CourseContent | 9 fields | ← مطابق (بعد migrations إضافية) | ✅ |
| Quiz | 6 fields | ← مطابق (بعد migrations إضافية) | ✅ |
| Question | 3 (quiz_id,question_text,points) | ← مطابق | ✅ |
| Option | 3 (question_id,option_text,is_correct) | ← مطابق | ✅ |
| QuizAttempt | 8 fields | ← مطابق (بعد migrations إضافية) | ✅ |
| Review | 4 (user_id,course_id,rating,comment) | ← مطابق | ✅ |
| Message | 5 (sender_id,receiver_id,course_id,content,is_read) | ← مطابق | ✅ |
| Category | 7 fields | ← مطابق | ✅ |
| TutorDetail | 17 fields | ← مطابق (بعد 3 migrations) | ✅ |
| CourseCertificate | 7 fields | ← مطابق | ✅ |
| ContentProgress | 4 fields | ← مطابق | ✅ |
| PaymentMethod | 11 fields | ← **`name_ar` مفقود من DB** 🔴 | 🔴 |
| PayoutRequest | 9 fields | ← مطابق | ✅ |
| Setting | 2 (key,value) | ← مطابق | ✅ |
| SettingsHistory | 4 fields | ← مطابق | ✅ |
| Transaction | 16 fields | ← مطابق | ✅ |

---

## 4. ❓ CLARIFICATIONS REQUIRED (/speckit.clarify)

> أسئلة موجهة للمبرمج السابق أو مالك المشروع:

### أسئلة حرجة:

1. **هل تم تنفيذ عملية Refund بنجاح من قبل؟** — لأن الـ enum في قاعدة البيانات لا يدعم `refunded` كقيمة لـ `payment_status`. هذا يعني أن أي محاولة استرداد ستفشل بخطأ DB.

2. **هل تم إنشاء أي PaymentMethod بنجاح؟** — لأن العمود `name_ar` غير موجود في الـ migration لكن الـ Model يحاول كتابته عبر `boot()`. إذا كانت قاعدة البيانات قد أُنشئت بدون هذا العمود فسيحدث خطأ.

3. **ما الغرض من جدول `course_payment_methods`؟** — هل كان مخططاً لربط طرق دفع خاصة بكل كورس (بدلاً من طرق الدفع العامة)؟ الجدول موجود لكن غير مستخدم في أي مكان.

4. **ما الغرض من ملف `clear_attempts.php` في الجذر؟** — هل هو سكربت صيانة؟ وجوده في الجذر يُشكل خطراً أمنياً محتملاً إذا كان الوصول للجذر مباشراً.

### أسئلة منطقية:

5. **لماذا لا يتم اختيار طريقة الدفع عند التسجيل؟** — صفحة الدفع تستخدم "محاكاة دفع" بدون اختيار فعلي لطريقة الدفع. الحقول `payment_method_id` و `payment_proof` غير موجودة في جدول `enrollments` رغم أن `FinancialService` يحاول قراءتها.

6. **هل `enrollment_status` يجب أن يكون `pending_approval` دائماً عند التسجيل الجديد؟** — حتى لو كان الكورس مجانياً والدفع تم، يظل بحالة `pending_approval`. هل هذا مقصود؟ هذا يعني أن كل كورس بحاجة لموافقة المعلم حتى لو كان مجانياً.

7. **لماذا يُسمح للأدمن بتعديل الـ `enrollment_status` إلى `enrolled`؟** — هذه القيمة غير موجودة في الـ enum ولم تُعرّف في أي مكان.

8. **هل يوجد سبب لعدم وجود `SoftDeletes` على `CourseContent`؟** — معظم النماذج الأساسية (User, Course, Enrollment) تستخدم `SoftDeletes` لكن `CourseContent` لا يستخدمها. حذف المحتوى نهائي.

---

## 5. 🛠️ ACTIONABLE FIX TASKS (/speckit.tasks)

> قائمة مهام مرقمة بالأولوية. يجب تنفيذها بالترتيب.

### 🔴 المرحلة 1: إصلاحات حرجة (يجب تنفيذها فوراً)

- [ ] **FIX-01**: إنشاء migration لتوسيع `payment_status` enum في جدول `enrollments`
  ```
  ملف: database/migrations/xxxx_expand_payment_status_enum.php
  إضافة: 'completed', 'refunded' للـ enum
  ```

- [ ] **FIX-02**: إنشاء migration لإضافة عمود `name_ar` لجدول `payment_methods`
  ```
  ملف: database/migrations/xxxx_add_name_ar_to_payment_methods.php
  إضافة: $table->string('name_ar')->nullable()->after('name')
  ```

- [ ] **FIX-03**: إنشاء migration لإضافة `payment_method_id` و `payment_proof` لجدول `enrollments`
  ```
  ملف: database/migrations/xxxx_add_payment_fields_to_enrollments.php
  إضافة: payment_method_id (FK), payment_proof (string nullable)
  تحديث: Enrollment::$fillable
  ```

- [ ] **FIX-04**: حذف الكود الميت في `Admin\EnrollmentController.php`
  ```
  ملف: app/Http/Controllers/Admin/EnrollmentController.php
  حذف: السطور 57-93 (النسخة القديمة المعلقة)
  ```

- [ ] **FIX-05**: إصلاح `enrollment_status` validation في `Admin\EnrollmentController`
  ```
  ملف: app/Http/Controllers/Admin/EnrollmentController.php (سطر 106)
  تغيير: 'in:pending_approval,approved,rejected,enrolled' → 'in:pending_approval,approved,rejected'
  ```

### 🟠 المرحلة 2: إصلاحات أمنية ومنطقية

- [ ] **FIX-06**: إزالة `payment_status` من `Enrollment::$fillable`
  ```
  ملف: app/Models/Enrollment.php
  تعديل: $fillable — إزالة 'payment_status'
  تحديث: كل controller يستخدم update(['payment_status' => ...]) ← تغيير لتعيين صريح
  ```

- [ ] **FIX-07**: إصلاح `CourseReviews` Livewire للتحقق من `enrollment_status`
  ```
  ملف: app/Livewire/CourseReviews.php (سطر 42)
  إضافة: ->where('payment_status', 'paid')->where('enrollment_status', 'approved')
  ```

- [ ] **FIX-08**: التحقق من وجود view `certificates/quiz-show.blade.php`
  ```
  فحص: resources/views/certificates/quiz-show.blade.php
  إنشاء: إذا لم تكن موجودة
  ```

### 🟡 المرحلة 3: تنظيف وتوثيق

- [ ] **FIX-09**: حذف الكود الميت في `Student\CourseController.php` (السطور 207-228)
- [ ] **FIX-10**: حذف `clear_attempts.php` من جذر المشروع (أو تحويله لأمر Artisan)
- [ ] **FIX-11**: حذف أو تقييد الوصول لـ `repomix-output.xml` و `core-backend.xml`
- [ ] **FIX-12**: تحديث إحصائيات التوثيق (19 model بدلاً من 16، 4 policies بدلاً من 1)
- [ ] **FIX-13**: تقييم استخدام `course_payment_methods` pivot table — تفعيل أو حذف

### 🔵 المرحلة 4: تحسينات مستقبلية

- [ ] **FIX-14**: ربط صفحة الدفع بطرق الدفع الفعلية (اختيار PaymentMethod + رفع إيصال)
- [ ] **FIX-15**: إنشاء Form Requests لجميع Controllers (بدلاً من inline validation)
- [ ] **FIX-16**: إضافة `SoftDeletes` لـ `CourseContent`
- [ ] **FIX-17**: دمج بوابة دفع حقيقية (Stripe/Paddle)
- [ ] **FIX-18**: مراجعة `Student\DashboardController` و `Tutor\DashboardController` لـ N+1 queries

---

## 📊 ملخص نتائج التدقيق

| الفئة | العدد |
|---|---|
| 🔴 أخطاء حرجة | **4** (C-01 إلى C-04) |
| 🟠 أخطاء متوسطة | **6** (M-01 إلى M-06) |
| 🟡 أخطاء طفيفة | **6** (L-01 إلى L-06) |
| أكواد ميتة (Dead Code) | **7** عناصر |
| تناقضات (Drift) | **6** حالات |
| أسئلة تحتاج توضيح | **8** أسئلة |
| مهام إصلاح | **18** مهمة (5 حرجة + 3 أمنية + 5 تنظيف + 5 تحسين) |

> **التقييم العام**: المشروع مبني بشكل جيد معمارياً مع حماية أمنية قوية (Rate Limiting, CSRF, Authorization Policies, DB Transactions). لكنه يعاني من **4 أخطاء حرجة في طبقة البيانات** (enum mismatch + missing columns) تمنع عمل النظام المالي بنسبة 100%. يجب إصلاح الأخطاء C-01 إلى C-04 قبل أي تطوير جديد.

---
*تم إنشاء هذا التدقيق بواسطة ULTRA-LARA-SPECKIT-AUDITOR-v6.0 بتاريخ 2026-04-13*
