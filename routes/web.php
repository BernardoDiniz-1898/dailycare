<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicaController;
use App\Http\Controllers\ClinicaPerfilController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Clinicas publicas
Route::get('/clinicas', [ClinicaController::class, 'index'])->name('clinicas.index');
Route::get('/clinicas/{clinica}', [ClinicaController::class, 'show'])->name('clinicas.show');

// Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Agendamentos
Route::middleware('auth')->group(function () {
    Route::post('/agendamentos', [AgendamentoController::class, 'store'])->name('agendamentos.store');
    Route::patch('/agendamentos/{agendamento}', [AgendamentoController::class, 'update'])->name('agendamentos.update');
    Route::delete('/agendamentos/{agendamento}', [AgendamentoController::class, 'destroy'])->name('agendamentos.destroy');
});

// Avaliacoes
Route::middleware('auth')->group(function () {
    Route::post('/avaliacoes', [AvaliacaoController::class, 'store'])->name('avaliacoes.store');
});

// Perfil Clinica
Route::middleware(['auth', 'role:clinica'])->prefix('clinica-perfil')->name('clinica.perfil.')->group(function () {
    Route::get('/criar', [ClinicaPerfilController::class, 'create'])->name('create');
    Route::post('/criar', [ClinicaPerfilController::class, 'store'])->name('store');
    Route::get('/editar', [ClinicaPerfilController::class, 'edit'])->name('edit');
    Route::put('/editar', [ClinicaPerfilController::class, 'update'])->name('update');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/clinicas-pendentes', [AdminController::class, 'clinicasPendentes'])->name('clinicas-pendentes');
    Route::patch('/clinicas/{clinica}/aprovar', [AdminController::class, 'aprovarClinica'])->name('clinicas.aprovar');
    Route::patch('/clinicas/{clinica}/rejeitar', [AdminController::class, 'rejeitarClinica'])->name('clinicas.rejeitar');

    Route::get('/especialidades', [AdminController::class, 'especialidades'])->name('especialidades');
    Route::post('/especialidades', [AdminController::class, 'storeEspecialidade'])->name('especialidades.store');
    Route::delete('/especialidades/{especialidade}', [AdminController::class, 'destroyEspecialidade'])->name('especialidades.destroy');

    Route::get('/servicos-acessibilidade', [AdminController::class, 'servicosAcessibilidade'])->name('servicos-acessibilidade');
    Route::post('/servicos-acessibilidade', [AdminController::class, 'storeServicoAcessibilidade'])->name('servicos-acessibilidade.store');
    Route::delete('/servicos-acessibilidade/{servico}', [AdminController::class, 'destroyServicoAcessibilidade'])->name('servicos-acessibilidade.destroy');
});
