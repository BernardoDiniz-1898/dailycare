<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Clinica;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardClinicaSmokeTest extends TestCase
{
    use RefreshDatabase;

    private function criarUsuario(array $dados = []): Usuario
    {
        return Usuario::create(array_merge([
            'nome' => 'Usuario Teste',
            'email' => fake()->unique()->safeEmail(),
            'senha' => Hash::make('password'),
            'cpf' => fake()->unique()->numerify('###.###.###-##'),
            'role' => 'paciente',
        ], $dados));
    }

    public function test_dashboard_clinica_renderiza_com_estatisticas_e_filtro(): void
    {
        $user = $this->criarUsuario(['role' => 'fisioterapeuta', 'nome' => 'Fisio Acessivel']);
        $clinica = Clinica::create([
            'usuario_id' => $user->id,
            'razao_social' => 'Fisio Acessivel LTDA',
            'nome_fantasia' => 'Fisio Acessivel',
            'cnpj' => '11.222.333/0001-44',
            'logradouro' => 'Rua das Flores',
            'numero' => '100',
            'bairro' => 'Centro',
            'cidade' => 'Sao Paulo',
            'estado' => 'SP',
            'cep' => '01000-000',
            'status' => 'aprovada',
            'ativa' => true,
            'preco_sessao' => 180.00,
        ]);

        $paciente = $this->criarUsuario(['role' => 'paciente', 'telefone' => '(11) 99999-0000', 'condicao' => 'Parkinson']);

        Agendamento::create(['paciente_id' => $paciente->id, 'clinica_id' => $clinica->id, 'data' => '2026-08-10', 'hora' => '09:00', 'status' => 'solicitado']);
        Agendamento::create(['paciente_id' => $paciente->id, 'clinica_id' => $clinica->id, 'data' => '2026-08-11', 'hora' => '10:00', 'status' => 'confirmado']);
        Agendamento::create(['paciente_id' => $paciente->id, 'clinica_id' => $clinica->id, 'data' => '2026-08-12', 'hora' => '11:00', 'status' => 'concluido']);

        $this->actingAs($user);

        $resposta = $this->get(route('dashboard'));
        $resposta->assertOk();
        $resposta->assertSee('Fisio Acessivel');
        $resposta->assertSee('Solicitados');
        $resposta->assertSee('Confirmados');
        $resposta->assertSee('360,00', false);
        $resposta->assertSee('Parkinson');

        $filtrado = $this->get(route('dashboard', ['status' => 'solicitado']));
        $filtrado->assertOk();
        $filtrado->assertSee('09:00');
    }
}
