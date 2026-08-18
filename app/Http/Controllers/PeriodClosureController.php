<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPeriodClosure;
use App\Models\PeriodClosure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriodClosureController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $periodClosure = PeriodClosure::firstOrCreate([
                'user_id' => Auth::id(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ],
            [
                'status' => 'pending',
            ]
        );

        if ($periodClosure->wasRecentlyCreated) {
            ProcessPeriodClosure::dispatch($periodClosure);
        } elseif ($periodClosure->status === 'failed') {
            $periodClosure->update([
                'status' => 'pending',
                'error_message' => null,
            ]);

            ProcessPeriodClosure::dispatch($periodClosure);
        }

        return redirect('/lancamentos');
    }
}
