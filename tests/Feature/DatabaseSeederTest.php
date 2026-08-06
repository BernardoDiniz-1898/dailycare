<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Avaliacao;
use App\Models\Clinica;
use App\Models\Especialidade;
use App\Models\HorarioDisponivel;
use App\Models\ServicoAcessibilidade;
use App\Models\Usuario;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_cria_os_usuarios_e_clinicas_do_readme(): void
    {
        $this->seed(DatabaseSeeder::class);

        $emails = [
            'admin@dailycare.com',
            'maria@email.com',
            'joao.paciente@email.com',
            'beatriz.paciente@email.com',
            'carlos.paciente@email.com',
            'marina.paciente@email.com',
            'clinica@email.com',
            'clinica2@email.com',
            'clinica3@email.com',
            'clinica4@email.com',
            'pedro.fisio@email.com',
        ];

        foreach ($emails as $email) {
            $this->assertDatabaseHas('usuarios', ['email' => $email]);
        }

        $this->assertDatabaseMissing('usuarios', ['email' => 'clinica@email.coclinica@email.com']);

        $this->assertSame(8, Especialidade::count());
        $this->assertSame(12, ServicoAcessibilidade::count());
        $this->assertSame(3, Clinica::where('status', 'aprovada')->count());
        $this->assertSame(1, Clinica::where('status', 'pendente')->count());

        $this->assertGreaterThan(0, Avaliacao::count());
        $this->assertGreaterThan(0, Agendamento::count());
        $this->assertGreaterThan(0, HorarioDisponivel::count());

        $fisio = Clinica::where('nome_fantasia', 'Fisio Acessivel')->first();
        $this->assertSame(3, $fisio->especialidades()->count());
        $this->assertSame(9, $fisio->servicosAcessibilidade()->count());
    }

    public function test_seed_e_idempotente(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(11, Usuario::count());
        $this->assertSame(4, Clinica::count());
        $this->assertSame(8, Especialidade::count());
        $this->assertSame(12, ServicoAcessibilidade::count());

        $agendamentos = Agendamento::count();
        $avaliacoes = Avaliacao::count();

        $this->seed(DatabaseSeeder::class);

        $this->assertSame($agendamentos, Agendamento::count());
        $this->assertSame($avaliacoes, Avaliacao::count());
    }
}
