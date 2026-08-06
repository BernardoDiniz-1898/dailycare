<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicaController;
use App\Http\Controllers\ClinicaPerfilController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
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

// Agenda da clinica
Route::middleware(['auth', 'role:clinica,fisioterapeuta'])->group(function () {
    Route::get('/agenda', [DashboardController::class, 'agenda'])->name('agenda');
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

/**
 * Rotas do chat entre paciente e clinica.
 * Todas exigem autenticacao, ja que so participantes da conversa podem ve-la.
 */
Route::middleware('auth')->group(function () {
    Route::get('/mensagens', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/mensagens/nova/{clinica}', [\App\Http\Controllers\ChatController::class, 'iniciar'])->name('chat.iniciar');
    Route::get('/mensagens/{conversa}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/mensagens/{conversa}', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
});

/**
 * Rota de configuracoes da conta (fonte, contraste, senha).
 * Disponivel para qualquer usuario autenticado.
 */
Route::middleware('auth')->group(function () {
    Route::get('/configuracoes', [\App\Http\Controllers\ConfiguracoesController::class, 'index'])->name('configuracoes.index');
    Route::put('/configuracoes/senha', [\App\Http\Controllers\ConfiguracoesController::class, 'atualizarSenha'])->name('configuracoes.senha');
});

/**
 * Rotas de posts (publicacoes) da clinica/fisioterapeuta.
 * Somente quem tem papel de clinica ou fisioterapeuta pode criar/remover.
 */
Route::middleware(['auth', 'role:clinica,fisioterapeuta'])->group(function () {
    Route::post('/posts', [\App\Http\Controllers\PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [\App\Http\Controllers\PostController::class, 'destroy'])->name('posts.destroy');
});

/**
 * Rotas de planos (assinatura da clinica na plataforma).
 * A clinica "aluga o espaco" pra divulgar seu trabalho, com planos mensal ou anual.
 */
Route::middleware(['auth', 'role:clinica,fisioterapeuta'])->prefix('planos')->name('planos.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PlanoController::class, 'index'])->name('index');
    Route::post('/{plano}/assinar', [\App\Http\Controllers\PlanoController::class, 'assinar'])->name('assinar');
    Route::delete('/cancelar', [\App\Http\Controllers\PlanoController::class, 'cancelar'])->name('cancelar');
});

// Perfil Clinica
Route::middleware(['auth', 'role:clinica,fisioterapeuta'])->prefix('clinica-perfil')->name('clinica.perfil.')->group(function () {
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
