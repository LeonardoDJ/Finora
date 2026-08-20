<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Contact;
use Inertia\Inertia;
use App\Models\PeriodClosure;

class TransactionController extends Controller
{
    public function index(Request $request){

        Transaction::where('user_id', Auth::id())
        ->where('status', 'open')
        ->whereDate('due_date', '<', today())
        ->update([
            'status' => 'delay'
        ]);


        $query = Transaction::where('user_id', Auth::id())
        ->with('contact');

        if ($request->start_date) {
            $query->whereDate('due_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('due_date', '<=', $request->end_date);
        }

        $transactions = $query->get();

        $totalReceive = $transactions
            ->where('type', 'receive')
            ->where('status', '!=', 'paid_off')
            ->sum('amount');

        $totalPay = $transactions
            ->where('type', 'pay')
            ->where('status', '!=', 'paid_off')
            ->sum('amount');

        $totalPaid = $transactions
            ->where('status', 'paid_off')
            ->sum('amount');

        $totalOverdue = $transactions
            ->where('status', 'delay')
            ->sum('amount');

        $balance = $totalReceive - $totalPay;

        $openCount = $transactions->where('status', 'open')->count();
        $delayCount = $transactions->where('status', 'delay')->count();
        $paidCount = $transactions->where('status', 'paid_off')->count();

        $contacts = Contact::where('user_id', Auth::id())->get();

        $periodClosures = PeriodClosure::where('user_id', Auth::id())
            ->latest()
            ->get();

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
            'contacts' => $contacts,
            'periodClosures' => $periodClosures,
            'success' => session('success'),
            
            'summary' => [
                'total_receive' => $totalReceive,
                'total_pay' => $totalPay,
                'total_paid' => $totalPaid,
                'total_overdue' => $totalOverdue,       
                'balance' => $balance,
                'open' => $openCount,
                'delay' => $delayCount,
                'paid' => $paidCount,
            ],
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'contact_id' => 'required|integer',
            'type' => 'required|in:pay,receive',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
        ]);

        $contact = Contact::where('id', $request->contact_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Transaction::create([
            'user_id' => Auth::id(),
            'contact_id' => $contact->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => 'open',
            'settled_at' => null,
        ]);

        return redirect('/lancamentos')->with(
            'success',
            'Lançamento cadastrado com sucesso.'
        );
    }

    public function settle(Transaction $transaction){
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->update([
            'status' => 'paid_off',
            'settled_at' => now(),
        ]);

        return redirect('/lancamentos')->with(
            'success',
            'Lançamento liquidado com sucesso.'
        );
    }
}
