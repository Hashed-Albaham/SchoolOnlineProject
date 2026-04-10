<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with([
            'student:id,name,email',
            'tutor:id,name,email',
            'paymentMethod:id,name_ar,name_en',
            'processor:id,name',
        ]);

        // فلترة
        if ($request->type)   $query->where('type', $request->type);
        if ($request->status) $query->where('status', $request->status);
        if ($request->date_from) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->date_to)   $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->search) {
            $query->where('reference_number', 'like', "%{$request->search}%");
        }

        $transactions = $query->latest()->paginate(20)->withQueryString();

        // إحصائيات سريعة
        $stats = [
            'total_revenue'  => Transaction::completed()->enrollments()->sum('gross_amount'),
            'platform_fees'  => Transaction::completed()->enrollments()->sum('platform_fee_amount'),
            'total_payouts'  => Transaction::completed()->payouts()->sum('tutor_amount'),
            'pending_count'  => Transaction::where('status', 'pending')->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load([
            'student', 'tutor', 'paymentMethod',
            'enrollment.course', 'payoutRequest', 'processor',
        ]);

        return view('admin.transactions.show', compact('transaction'));
    }
}