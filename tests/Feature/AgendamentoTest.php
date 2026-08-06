<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Clinica;
use App\Models\Usuario;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendamentoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function paciente(): Usuario
    {
        return Usuario::where('email', 'maria@email.com')->first();
    }

    private function clinicaAprovada(): Clinica
    {
        return Clinica::where('nome_fantasia', 'Fisio Acessivel')->first();
    }

    private function clinicaPendente(): Clinica
    {
        return Clinica::where('nome_fantasia', 'Reabilitar Almeida')->first();
    }

    private function totalAgendamentos(): int
    {
        return Agendamento::count();
    }

    public function test_usuario_de_clinica_nao_pode_criar_agendamento(): void
    {
        $usuario = Usuario::where('email', 'clinica@email.com')->first();
        $clinica = $this->clinicaAprovada();
        $total = $this->totalAgendamentos();

        $this->actingAs($usuario)
            ->post(route('agendamentos.store'), [
                'clinica_id' => $clinica->id,
                'data' => today()->addDays(1)->toDateString(),
                'hora' => '09:00',
            ])
            ->assertForbidden();

        $this->assertSame($total, $this->totalAgendamentos());
    }

    public function test_paciente_nao_pode_agendar_em_clinica_pendente(): void
    {
        $clinica = $this->clinicaPendente();
        $total = $this->totalAgendamentos();

        $this->actingAs($this->paciente())
            ->post(route('agendamentos.store'), [
                'clinica_id' => $clinica->id,
                'data' => today()->addDays(1)->toDateString(),
                'hora' => '09:00',
            ])
            ->assertNotFound();

        $this->assertSame($total, $this->totalAgendamentos());
    }

    public function test_paciente_agenda_em_clinica_aprovada(): void
    {
        $clinica = $this->clinicaAprovada();

        $this->actingAs($this->paciente())
            ->post(route('agendamentos.store'), [
                'clinica_id' => $clinica->id,
                'data' => today()->addDays(6)->toDateString(),
                'hora' => '16:00',
            ])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('agendamentos', [
            'paciente_id' => $this->paciente()->id,
            'clinica_id' => $clinica->id,
            'status' => 'solicitado',
        ]);
    }

    public function test_horario_duplicado_nao_e_criado(): void
    {
        $clinica = $this->clinicaAprovada();
        $data = today()->addDays(6)->toDateString();

        $this->actingAs($this->paciente())
            ->post(route('agendamentos.store'), [
                'clinica_id' => $clinica->id,
                'data' => $data,
                'hora' => '16:00',
            ])
            ->assertRedirect(route('dashboard'));

        $this->actingAs($this->paciente())
            ->post(route('agendamentos.store'), [
                'clinica_id' => $clinica->id,
                'data' => $data,
                'hora' => '16:00',
            ])
            ->assertSessionHasErrors('hora');

        $this->assertSame(1, Agendamento::where('clinica_id', $clinica->id)
            ->whereDate('data', $data)
            ->where('hora', '16:00')
            ->count());
    }

    public function test_horario_ja_ocupado_por_agendamento_semeado_e_recusado(): void
    {
        $clinica = $this->clinicaAprovada();
        $total = $this->totalAgendamentos();

        // Maria ja tem sessao confirmada no mesmo horario (seed)
        $this->actingAs($this->paciente())
            ->post(route('agendamentos.store'), [
                'clinica_id' => $clinica->id,
                'data' => today()->addDays(1)->toDateString(),
                'hora' => '09:00',
            ])
            ->assertSessionHasErrors('hora');

        $this->assertSame($total, $this->totalAgendamentos());
    }
}
