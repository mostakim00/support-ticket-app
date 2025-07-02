<?php
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/index', [TicketController::class, 'index'])->name('index');
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
//Route::get('/top-support-agents', [TicketController::class, 'topSupportAgents'])->name('top.support.agents');
