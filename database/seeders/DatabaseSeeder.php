<?php

namespace Database\Seeders;

use App\Models\Especialidade;
use App\Models\ServicoAcessibilidade;
use App\Models\Usuario;
use App\Models\Clinica;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Especialidades
        $especialidades = [
            ['nome' => 'Fisioterapia Neurofuncional', 'descricao' => 'Reabilitacao de disfuncoes neurológicas como AVC, Parkinson e LESMED'],
            ['nome' => 'Fisioterapia Ortopedica', 'descricao' => 'Tratamento de lesões musculoesqueleticas e pos-operatorio ortopedico'],
            ['nome' => 'Fisioterapia Respiratoria', 'descricao' => 'Reabilitacao pulmonar e tratamento de disfuncoes respiratorias'],
            ['nome' => 'Fisioterapia Pediaatrica', 'descricao' => 'Atendimento focado em criancas com atrasos no desenvolvimento motor'],
            ['nome' => 'Fisioterapia Geriatrica', 'descricao' => 'Atendimento para idosos com foco em mobilidade e prevencao de quedas'],
            ['nome' => 'Pilates Terapeutico', 'descricao' => 'Exercicios de Pilates adaptados para reabilitacao fisica'],
            ['nome' => 'Fisioterapia Esportiva', 'descricao' => 'Prevencao e tratamento de lesões esportivas'],
            ['nome' => 'Terapia Ocupacional', 'descricao' => 'Reabilitacao para atividades da vida diaria'],
        ];

        foreach ($especialidades as $esp) {
            Especialidade::create($esp);
        }

        // Servicos de Acessibilidade
        $servicos = [
            ['nome' => 'Rampa de Acesso', 'icone' => 'ramp'],
            ['nome' => 'Banheiro Adaptado', 'icone' => 'bathroom'],
            ['nome' => 'Elevador', 'icone' => 'elevator'],
            ['nome' => 'Portas Largas', 'icone' => 'door'],
            ['nome' => 'Vaga de Estacionamento', 'icone' => 'parking'],
            ['nome' => 'Sinalizacao Tatil', 'icone' => 'tactile'],
            ['nome' => 'Piso Tactil', 'icone' => 'floor'],
            ['nome' => 'Corrimaos', 'icone' => 'handrail'],
            ['nome' => 'Guindaste/Transferidor', 'icone' => 'lift'],
            ['nome' => 'Macas Eletricas', 'icone' => 'bed'],
            ['nome' => 'Barras Paralelas', 'icone' => 'bars'],
            ['nome' => 'Sala de Espera Acessivel', 'icone' => 'waiting'],
        ];

        foreach ($servicos as $servico) {
            ServicoAcessibilidade::create($servico);
        }

        // Admin
        $admin = Usuario::create([
            'nome' => 'Administrador',
            'email' => 'admin@dailycare.com',
            'senha' => Hash::make('password'),
            'cpf' => '000.000.000-00',
            'role' => 'admin',
        ]);

        // Paciente de teste
        $paciente = Usuario::create([
            'nome' => 'Maria Silva',
            'email' => 'maria@email.com',
            'senha' => Hash::make('password'),
            'cpf' => '111.111.111-11',
            'telefone' => '(11) 99999-0000',
            'role' => 'paciente',
            'condicao' => 'Lesao medular incompleta',
        ]);

        // Clinica de teste
        $usuarioClinica = Usuario::create([
            'nome' => 'Dr. Joao Santos',
            'email' => 'clinica@email.com',
            'senha' => Hash::make('password'),
            'cpf' => '222.222.222-22',
            'telefone' => '(11) 88888-0000',
            'role' => 'clinica',
        ]);

        $clinica = Clinica::create([
            'usuario_id' => $usuarioClinica->id,
            'razao_social' => 'Centro de Fisioterapia Integrada LTDA',
            'nome_fantasia' => 'Fisio Acessivel',
            'cnpj' => '12.345.678/0001-90',
            'telefone' => '(11) 3333-4444',
            'email_contato' => 'contato@fisioacessivel.com.br',
            'logradouro' => 'Rua da Acessibilidade',
            'numero' => '150',
            'bairro' => 'Centro',
            'cidade' => 'Sao Paulo',
            'estado' => 'SP',
            'cep' => '01001-000',
            'descricao' => 'Clinica de fisioterapia especializada em atendimento a pessoas com deficiencia motora. Infraestrutura 100% acessivel com equipamentos modernos e equipe multidisciplinar.',
            'status' => 'aprovada',
        ]);

        $clinica->especialidades()->sync([1, 2, 6]);
        $clinica->servicosAcessibilidade()->attach([1, 2, 3, 4, 5, 8, 9, 10, 11]);
    }
}
