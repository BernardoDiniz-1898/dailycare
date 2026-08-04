<?php

namespace Tests\Feature;

use App\Models\Clinica;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClinicasVividasSmokeTest extends TestCase
{
    use RefreshDatabase;

    private function criarClinica(): Clinica
    {
        $user = Usuario::create([
            'nome' => 'Fisio Teste',
            'email' => fake()->unique()->safeEmail(),
            'senha' => Hash::make('password'),
            'cpf' => fake()->unique()->numerify('###.###.###-##'),
            'role' => 'fisioterapeuta',
            'crefito' => 'CREFITO-3 12345-F',
        ]);

        return Clinica::create([
            'usuario_id' => $user->id,
            'razao_social' => 'Fisio Teste LTDA',
            'nome_fantasia' => 'Fisio Teste',
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
            'descricao' => 'Clinica de fisioterapia acessivel no centro da cidade.',
        ]);
    }

    public function test_listagem_de_clinicas_renderiza(): void
    {
        $this->criarClinica();
        $response = $this->get(route('clinicas.index'));
        $response->assertOk();
        $response->assertSee('Fisio Teste');
    }

    public function test_perfil_de_clinica_renderiza(): void
    {
        $clinica = $this->criarClinica();
        $response = $this->get(route('clinicas.show', $clinica));
        $response->assertOk();
        $response->assertSee('Fisio Teste');
        $response->assertSee('Agendar atendimento');
    }
}
