<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicaController;
use App\Http\Controllers\ClinicaPerfilController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/**
 * Arquivo principal de rotas do Laravel.
 * Aqui são definidas as URLs do sistema e o que cada uma executa.
 * A sintaxe Route::get(), Route::post(), Route::patch() e Route::delete()
 * descreve o método HTTP, o caminho e o controller/método responsável.
 */

/**
 * Rota inicial da aplicação.
 * Retorna a view welcome para a página inicial.
 */
Route::get('/', function () {
    return view('welcome');
})->name('home');

/**
 * Rotas de autenticação para visitantes.
 * O middleware guest permite que apenas usuários não autenticados acessem login e cadastro.
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);
});

/**
 * Rota de logout para usuários autenticados.
 * O middleware auth garante que somente quem estiver logado possa encerrar a sessão.
 */
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/**
 * Rotas públicas de clínicas.
 * Ambas são acessíveis sem autenticação e exibem listagem e detalhes das clínicas aprovadas.
 */
Route::get('/clinicas', [ClinicaController::class, 'index'])->name('clinicas.index');
Route::get('/clinicas/{clinica}', [ClinicaController::class, 'show'])->name('clinicas.show');

/**
 * Dashboard protegido por autenticação.
 * O usuário logado é direcionado para a página principal do seu perfil.
 */
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/**
 * Rotas de agendamentos acessadas somente por usuários autenticados.
 * Cada método HTTP representa uma ação específica: criar, atualizar e cancelar.
 */
Route::middleware('auth')->group(function () {
    Route::post('/agendamentos', [AgendamentoController::class, 'store'])->name('agendamentos.store');
    Route::patch('/agendamentos/{agendamento}', [AgendamentoController::class, 'update'])->name('agendamentos.update');
    Route::delete('/agendamentos/{agendamento}', [AgendamentoController::class, 'destroy'])->name('agendamentos.destroy');
});

/**
 * Rota para criar avaliações.
 * Também exige autenticação, pois a avaliação pertence a um paciente logado.
 */
Route::middleware('auth')->group(function () {
    Route::post('/avaliacoes', [AvaliacaoController::class, 'store'])->name('avaliacoes.store');
});

/**
 * Rotas do perfil da clínica.
 * O middleware role:clinica restringe o acesso a usuários com papel de clínica.
 * O prefixo e o nome ajudam a organizar as URLs e os helpers de rota.
 */
Route::middleware(['auth', 'role:clinica'])->prefix('clinica-perfil')->name('clinica.perfil.')->group(function () {
    Route::get('/criar', [ClinicaPerfilController::class, 'create'])->name('create');
    Route::post('/criar', [ClinicaPerfilController::class, 'store'])->name('store');
    Route::get('/editar', [ClinicaPerfilController::class, 'edit'])->name('edit');
    Route::put('/editar', [ClinicaPerfilController::class, 'update'])->name('update');
});

/**
 * Rotas administrativas.
 * O middleware role:admin restringe o acesso ao painel de gestão.
 * O prefixo admin organiza todas as URLs do módulo administrativo.
 */
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
