<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>شهادة إتمام - {{ $course->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1a1a1a;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .certificate-container {
            width: 900px;
            height: 600px;
            background: #fff;
            padding: 20px;
            position: relative;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
        }

        .border-pattern {
            border: 10px solid #c5a059;
            height: 100%;
            box-sizing: border-box;
            position: relative;
            padding: 40px;
            text-align: center;
            background: linear-gradient(135deg, #fff 0%, #fcfcfc 100%);
        }

        .logo {
            font-size: 24px;
            font-weight: 900;
            color: #1a1a1a;
            margin-bottom: 40px;
        }

        .logo span {
            color: #c5a059;
        }

        h1 {
            font-size: 48px;
            color: #1a1a1a;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }

        .presented-to {
            font-size: 32px;
            font-weight: bold;
            color: #c5a059;
            margin: 20px 0;
            border-bottom: 2px solid #eee;
            display: inline-block;
            padding: 0 40px 10px 40px;
        }

        .course-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a1a;
            margin: 20px 0;
        }

        .date {
            font-size: 14px;
            color: #888;
            margin-top: 40px;
        }

        .signatures {
            margin-top: 60px;
            display: flex;
            justify-content: space-around;
        }

        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            padding-top: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .print-btn {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #c5a059;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Cairo';
            font-weight: bold;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                background: none;
            }

            .certificate-container {
                box-shadow: none;
                margin: 0;
                width: 100%;
                height: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="certificate-container">
        <div class="border-pattern">
            <div class="logo">Pro<span>Skill</span></div>

            <h1>شهادة إتمام</h1>
            <div class="subtitle">تُمنح هذه الشهادة تقديراً لإتمام</div>

            <div class="presented-to">{{ $user->name }}</div>

            <div class="subtitle">لاجتيازه بنجاح اختبار الكورس التدريبي</div>

            <div class="course-name">{{ $course->title }}</div>

            <div class="date">تاريخ المنح: {{ $attempt->completed_at->format('Y/m/d') }}</div>

            <div class="signatures">
                <div class="signature-line">
                    {{ $course->tutor->name ?? 'المعلم' }}<br>
                    <span style="font-weight: normal; font-size: 12px; color: #666;">المعلم</span>
                </div>
                <div class="signature-line">
                    ProSkill Platform<br>
                    <span style="font-weight: normal; font-size: 12px; color: #666;">إدارة المنصة</span>
                </div>
            </div>
        </div>
    </div>

    <button class="print-btn" onclick="window.print()">طباعة الشهادة</button>

</body>

</html>