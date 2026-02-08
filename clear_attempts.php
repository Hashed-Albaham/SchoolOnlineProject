<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Clear all quiz attempts
\App\Models\QuizAttempt::truncate();
echo "Done - cleared all quiz attempts\n";
