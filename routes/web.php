<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PeriodClosureController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', function () {
    return auth()->check()
        ? redirect('/lancamentos')
        : redirect('/login');
});

Route::get('/dashboard', function () {
    $transactions = Transaction::where('user_id', Auth::id())->get();

    $totalReceber = $transactions
        ->where('type', 'receber')
        ->where('status', '!=', 'quitado')
        ->sum('amount');

    $totalPagar = $transactions
        ->where('type', 'pagar')
        ->where('status', '!=', 'quitado')
        ->sum('amount');

    $emAtraso = $transactions
        ->where('status', 'em atraso')
        ->count();

    return Inertia::render('Dashboard', [
        'totalReceber' => $totalReceber,
        'totalPagar' => $totalPagar,
        'saldoPrevisto' => $totalReceber - $totalPagar,
        'emAtraso' => $emAtraso,
    ]);
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post("/contatos", [ContactController::class, 'store']);
    Route::get("/contatos", [ContactController::class, 'index']);
    Route::delete('/contatos/{contact}', [ContactController::class, 'destroy']);
    Route::put('/contatos/{contact}', [ContactController::class, 'update']);
    Route::get('/lancamentos', [TransactionController::class, 'index']);
    Route::post('/lancamentos', [TransactionController::class, 'store']);
    Route::patch('/lancamentos/{transaction}/liquidar', [TransactionController::class, 'settle']);
    Route::post('/fechamentos', [PeriodClosureController::class, 'store']);
    
});

require __DIR__.'/auth.php';
