<?php
declare(strict_types=1);

use App\Http\Controllers\AtivoController;
use Illuminate\Support\Facades\Route;

Route::get("/", [AtivoController::class, "index"])->name("ativos.index");

//rota de Infrastrutura json
route::get("/status", [AtivoController::class, "status"])->name("ativos.status");