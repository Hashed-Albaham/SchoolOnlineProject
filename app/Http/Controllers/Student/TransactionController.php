<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['enrollment.course', 'paymentMethod'])
            ->where('student_id', auth()->id())
            ->whereIn('type', ['enrollment', 'refund'])
            ->latest()
            ->paginate(15);

        return view('student.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        // التحقق: الطالب يرى فقط معاملاته
        abort_if($transaction->student_id !== auth()->id(), 403);

        $transaction->load(['enrollment.course', 'paymentMethod', 'processor']);
        return view('student.transactions.show', compact('transaction'));
    }
}