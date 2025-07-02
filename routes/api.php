<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/index', [TicketController::class])->name('tickets.index');
Route::post('/store', [TicketController::class])->name('tickets.store');
Route::get('/top-support-agents', [TicketController::class, 'topSupportAgents'])->name('top.support.agents');
