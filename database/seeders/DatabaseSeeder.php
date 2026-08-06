<?php

namespace Database\Seeders;

use App\Models\Agendamento;
use App\Models\Avaliacao;
use App\Models\Clinica;
use App\Models\Especialidade;
use App\Models\HorarioDisponivel;
use App\Models\ServicoAcessibilidade;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedEspecialidades();
        $this->seedServicos();

        $this->usuario([
            'nome' => 'Administrador',
            'email' => 'admin@dailycare.com',
            'cpf' => '000.000.000-00',
            'role' => 'admin',
        ]);

        $pacientes = collect([
            ['nome' => 'Maria Silva', 'email' => 'maria@email.com', 'cpf' => '111.111.111-11', 'telefone' => '(11) 99999-0000', 'condicao' => 'Lesao medular incompleta'],
            ['nome' => 'Joao Pereira', 'email' => 'joao.paciente@email.com', 'cpf' => '333.333.333-33', 'telefone' => '(11) 98888-0000', 'condicao' => 'Sequelas de AVC'],
            ['nome' => 'Beatriz Souza', 'email' => 'beatriz.paciente@email.com', 'cpf' => '444.444.444-44', 'telefone' => '(11) 97777-0000', 'condicao' => 'Paralisia cerebral'],
            ['nome' => 'Carlos Lima', 'email' => 'carlos.paciente@email.com', 'cpf' => '555.555.555-55', 'telefone' => '(11) 96666-0000', 'condicao' => 'Lesao medular incompleta'],
            ['nome' => 'Marina Costa', 'email' => 'marina.paciente@email.com', 'cpf' => '666.666.666-66', 'telefone' => '(11) 95555-0000', 'condicao' => 'Parkinson'],
        ])->map(fn (array $dados) => $this->usuario($dados + ['role' => 'paciente']));

        // Fisioterapeuta sem clinica vinculada
        $this->usuario([
            'nome' => 'Dr. Pedro Alves',
            'email' => 'pedro.fisio@email.com',
            'cpf' => '777.777.777-77',
            'telefone' => '(11) 94444-0000',
            'role' => 'fisioterapeuta',
            'crefito' => 'CREFITO-3 45678-F',
        ]);

        $clinicas = collect([
            [
                'usuario' => ['nome' => 'Dr. Joao Santos', 'email' => 'clinica@email.com', 'cpf' => '222.222.222-22', 'telefone' => '(11) 88888-0000', 'role' => 'fisioterapeuta', 'crefito' => 'CREFITO-3 12345-F'],
                'clinica' => [
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
                    'preco_sessao' => 150.00,
                    'status' => 'aprovada',
                ],
                'especialidades' => ['Fisioterapia Neurofuncional', 'Fisioterapia Ortopedica', 'Pilates Terapeutico'],
                'servicos' => ['Rampa de Acesso', 'Banheiro Adaptado', 'Elevador', 'Portas Largas', 'Vaga de Estacionamento', 'Corrimãos', 'Guindaste/Transferidor', 'Macas Eletricas', 'Barras Paralelas'],
                'horarios' => [
                    ['segunda', '08:00', '18:00'],
                    ['quarta', '08:00', '18:00'],
                    ['sexta', '08:00', '18:00'],
                ],
            ],
            [
                'usuario' => ['nome' => 'Dra. Renata Nunes', 'email' => 'clinica2@email.com', 'cpf' => '888.888.888-88', 'telefone' => '(11) 77777-0000', 'role' => 'fisioterapeuta', 'crefito' => 'CREFITO-3 23456-F'],
                'clinica' => [
                    'razao_social' => 'Reabilita Neuro Fisioterapia LTDA',
                    'nome_fantasia' => 'Reabilita Neuro',
                    'cnpj' => '98.765.432/0001-10',
                    'telefone' => '(11) 2222-1111',
                    'email_contato' => 'contato@reabilitaneuro.com.br',
                    'logradouro' => 'Av. das Reabilitacoes',
                    'numero' => '980',
                    'bairro' => 'Vila Mariana',
                    'cidade' => 'Sao Paulo',
                    'estado' => 'SP',
                    'cep' => '04001-000',
                    'descricao' => 'Especializada em reabilitacao neurologica, com fisioterapeutas dedicados a pacientes com AVC, Parkinson e lesoes medulares.',
                    'preco_sessao' => 180.00,
                    'status' => 'aprovada',
                ],
                'especialidades' => ['Fisioterapia Neurofuncional', 'Fisioterapia Pediatrica'],
                'servicos' => ['Rampa de Acesso', 'Banheiro Adaptado', 'Elevador', 'Portas Largas', 'Sinalizacao Tatil', 'Piso Tactil', 'Barras Paralelas'],
                'horarios' => [
                    ['terca', '08:00', '20:00'],
                    ['quinta', '08:00', '20:00'],
                ],
            ],
            [
                'usuario' => ['nome' => 'Dr. Marcos Vieira', 'email' => 'clinica3@email.com', 'cpf' => '999.999.999-99', 'telefone' => '(11) 66666-0000', 'role' => 'fisioterapeuta', 'crefito' => 'CREFITO-3 34567-F'],
                'clinica' => [
                    'razao_social' => 'Movimenta Pilates e Fisioterapia LTDA',
                    'nome_fantasia' => 'Movimenta Pilates & Fisio',
                    'cnpj' => '87.654.321/0001-22',
                    'telefone' => '(11) 1111-2222',
                    'email_contato' => 'contato@movimenta.com.br',
                    'logradouro' => 'Rua dos Esportes',
                    'numero' => '320',
                    'bairro' => 'Tatuape',
                    'cidade' => 'Sao Paulo',
                    'estado' => 'SP',
                    'cep' => '03301-000',
                    'descricao' => 'Fisioterapia ortopedica e Pilates terapeutico em ambiente acessivel, focado em reabilitacao funcional e prevencao de lesoes.',
                    'preco_sessao' => 120.00,
                    'status' => 'aprovada',
                ],
                'especialidades' => ['Fisioterapia Ortopedica', 'Pilates Terapeutico', 'Fisioterapia Esportiva'],
                'servicos' => ['Rampa de Acesso', 'Banheiro Adaptado', 'Portas Largas', 'Vaga de Estacionamento', 'Corrimãos', 'Barras Paralelas'],
                'horarios' => [
                    ['segunda', '07:00', '19:00'],
                    ['terca', '07:00', '19:00'],
                    ['quarta', '07:00', '19:00'],
                    ['quinta', '07:00', '19:00'],
                    ['sexta', '07:00', '19:00'],
                ],
            ],
            [
                'usuario' => ['nome' => 'Dra. Lucia Almeida', 'email' => 'clinica4@email.com', 'cpf' => '101.010.101-01', 'telefone' => '(11) 55555-0000', 'role' => 'fisioterapeuta', 'crefito' => 'CREFITO-3 56789-F'],
                'clinica' => [
                    'razao_social' => 'Clinica Reabilitar Almeida LTDA',
                    'nome_fantasia' => 'Reabilitar Almeida',
                    'cnpj' => '76.543.210/0001-33',
                    'telefone' => '(11) 3333-9999',
                    'email_contato' => 'contato@reabilitaralmeida.com.br',
                    'logradouro' => 'Rua da Fisioterapia',
                    'numero' => '450',
                    'bairro' => 'Moema',
                    'cidade' => 'Sao Paulo',
                    'estado' => 'SP',
                    'cep' => '04501-000',
                    'descricao' => 'Clinica em processo de validacao, com foco em atendimento neurofuncional e ortopedico.',
                    'preco_sessao' => 160.00,
                    'status' => 'pendente',
                ],
                'especialidades' => ['Fisioterapia Neurofuncional', 'Fisioterapia Ortopedica'],
                'servicos' => ['Rampa de Acesso', 'Portas Largas', 'Corrimãos'],
                'horarios' => [
                    ['segunda', '08:00', '18:00'],
                    ['sexta', '08:00', '18:00'],
                ],
            ],
        ])->map(fn (array $dados) => $this->clinica($dados));

        $this->seedAvaliacoes($clinicas, $pacientes);
        $this->seedAgendamentos($clinicas, $pacientes);
    }

    private function seedEspecialidades(): void
    {
        $especialidades = [
            'Fisioterapia Neurofuncional' => 'Reabilitacao de disfuncoes neurologicas como AVC, Parkinson e lesoes medulares',
            'Fisioterapia Ortopedica' => 'Tratamento de lesoes musculoesqueleticas e pos-operatorio ortopedico',
            'Fisioterapia Respiratoria' => 'Reabilitacao pulmonar e tratamento de disfuncoes respiratorias',
            'Fisioterapia Pediatrica' => 'Atendimento focado em criancas com atrasos no desenvolvimento motor',
            'Fisioterapia Geriatrica' => 'Atendimento para idosos com foco em mobilidade e prevencao de quedas',
            'Pilates Terapeutico' => 'Exercicios de Pilates adaptados para reabilitacao fisica',
            'Fisioterapia Esportiva' => 'Prevencao e tratamento de lesoes esportivas',
            'Terapia Ocupacional' => 'Reabilitacao para atividades da vida diaria',
        ];

        foreach ($especialidades as $nome => $descricao) {
            Especialidade::updateOrCreate(['nome' => $nome], ['descricao' => $descricao]);
        }
    }

    private function seedServicos(): void
    {
        $servicos = [
            ['nome' => 'Rampa de Acesso', 'icone' => 'ramp'],
            ['nome' => 'Banheiro Adaptado', 'icone' => 'bathroom'],
            ['nome' => 'Elevador', 'icone' => 'elevator'],
            ['nome' => 'Portas Largas', 'icone' => 'door'],
            ['nome' => 'Vaga de Estacionamento', 'icone' => 'parking'],
            ['nome' => 'Sinalizacao Tatil', 'icone' => 'tactile'],
            ['nome' => 'Piso Tactil', 'icone' => 'floor'],
            ['nome' => 'Corrimãos', 'icone' => 'handrail'],
            ['nome' => 'Guindaste/Transferidor', 'icone' => 'lift'],
            ['nome' => 'Macas Eletricas', 'icone' => 'bed'],
            ['nome' => 'Barras Paralelas', 'icone' => 'bars'],
            ['nome' => 'Sala de Espera Acessivel', 'icone' => 'waiting'],
        ];

        foreach ($servicos as $servico) {
            ServicoAcessibilidade::updateOrCreate(['nome' => $servico['nome']], ['icone' => $servico['icone']]);
        }
    }

    /**
     * Cria (ou atualiza) um usuario a partir de um array de atributos.
     */
    private function usuario(array $dados): Usuario
    {
        $dados['senha'] = Hash::make('password');

        return Usuario::updateOrCreate(
            ['email' => $dados['email']],
            $dados
        );
    }

    /**
     * Cria (ou atualiza) uma clinica com especialidades, servicos e horarios.
     */
    private function clinica(array $dados): Clinica
    {
        $usuario = $this->usuario($dados['usuario']);

        $clinica = Clinica::updateOrCreate(
            ['cnpj' => $dados['clinica']['cnpj']],
            $dados['clinica'] + ['usuario_id' => $usuario->id, 'ativa' => true]
        );

        $clinica->especialidades()->sync(
            Especialidade::whereIn('nome', $dados['especialidades'])->pluck('id')
        );

        $clinica->servicosAcessibilidade()->sync(
            ServicoAcessibilidade::whereIn('nome', $dados['servicos'])->pluck('id')
        );

        HorarioDisponivel::where('clinica_id', $clinica->id)->delete();
        foreach ($dados['horarios'] as [$dia, $inicio, $fim]) {
            HorarioDisponivel::create([
                'clinica_id' => $clinica->id,
                'dia_semana' => $dia,
                'hora_inicio' => $inicio,
                'hora_fim' => $fim,
                'ativo' => true,
            ]);
        }

        return $clinica;
    }

    private function seedAvaliacoes($clinicas, $pacientes): void
    {
        $porFantasia = fn (string $nome) => $clinicas->firstWhere('nome_fantasia', $nome);

        $avaliacoes = [
            ['clinica' => $porFantasia('Fisio Acessivel'), 'paciente' => $pacientes->firstWhere('email', 'maria@email.com'), 'nota' => 5, 'comentario' => 'Atendimento excelente e infraestrutura totalmente acessivel.'],
            ['clinica' => $porFantasia('Fisio Acessivel'), 'paciente' => $pacientes->firstWhere('email', 'joao.paciente@email.com'), 'nota' => 4, 'comentario' => 'Profissionais atenciosos, otima evolucao no tratamento.'],
            ['clinica' => $porFantasia('Fisio Acessivel'), 'paciente' => $pacientes->firstWhere('email', 'beatriz.paciente@email.com'), 'nota' => 5, 'comentario' => 'Me sinto segura e bem acolhida em todas as sessoes.'],
            ['clinica' => $porFantasia('Reabilita Neuro'), 'paciente' => $pacientes->firstWhere('email', 'carlos.paciente@email.com'), 'nota' => 5, 'comentario' => 'Equipe especializada em neuro, recomendo demais.'],
            ['clinica' => $porFantasia('Reabilita Neuro'), 'paciente' => $pacientes->firstWhere('email', 'marina.paciente@email.com'), 'nota' => 4, 'comentario' => 'Muito bom atendimento e estrutura acessivel.'],
            ['clinica' => $porFantasia('Movimenta Pilates & Fisio'), 'paciente' => $pacientes->firstWhere('email', 'maria@email.com'), 'nota' => 4, 'comentario' => 'Pilates terapeutico me ajudou muito na mobilidade.'],
            ['clinica' => $porFantasia('Movimenta Pilates & Fisio'), 'paciente' => $pacientes->firstWhere('email', 'joao.paciente@email.com'), 'nota' => 5, 'comentario' => 'Ambiente acolhedor e profissionais qualificados.'],
        ];

        foreach ($avaliacoes as $avaliacao) {
            if (! $avaliacao['clinica'] || ! $avaliacao['paciente']) {
                continue;
            }

            Avaliacao::updateOrCreate(
                ['paciente_id' => $avaliacao['paciente']->id, 'clinica_id' => $avaliacao['clinica']->id],
                ['nota' => $avaliacao['nota'], 'comentario' => $avaliacao['comentario'], 'visivel' => true]
            );
        }
    }

    private function seedAgendamentos($clinicas, $pacientes): void
    {
        $porFantasia = fn (string $nome) => $clinicas->firstWhere('nome_fantasia', $nome);
        $porEmail = fn (string $email) => $pacientes->firstWhere('email', $email);

        $agendamentos = [
            ['clinica' => $porFantasia('Fisio Acessivel'), 'paciente' => $porEmail('maria@email.com'), 'data' => today()->addDays(1), 'hora' => '09:00', 'status' => 'confirmado'],
            ['clinica' => $porFantasia('Fisio Acessivel'), 'paciente' => $porEmail('maria@email.com'), 'data' => today()->addDays(3), 'hora' => '14:00', 'status' => 'solicitado'],
            ['clinica' => $porFantasia('Fisio Acessivel'), 'paciente' => $porEmail('maria@email.com'), 'data' => today()->subDays(5), 'hora' => '10:00', 'status' => 'concluido'],
            ['clinica' => $porFantasia('Fisio Acessivel'), 'paciente' => $porEmail('beatriz.paciente@email.com'), 'data' => today()->addDays(2), 'hora' => '11:00', 'status' => 'confirmado'],
            ['clinica' => $porFantasia('Reabilita Neuro'), 'paciente' => $porEmail('joao.paciente@email.com'), 'data' => today()->addDays(2), 'hora' => '10:00', 'status' => 'solicitado'],
            ['clinica' => $porFantasia('Reabilita Neuro'), 'paciente' => $porEmail('carlos.paciente@email.com'), 'data' => today()->addDays(4), 'hora' => '15:00', 'status' => 'confirmado'],
            ['clinica' => $porFantasia('Reabilita Neuro'), 'paciente' => $porEmail('marina.paciente@email.com'), 'data' => today()->subDays(2), 'hora' => '16:00', 'status' => 'concluido'],
            ['clinica' => $porFantasia('Movimenta Pilates & Fisio'), 'paciente' => $porEmail('maria@email.com'), 'data' => today()->addDays(5), 'hora' => '08:00', 'status' => 'solicitado'],
        ];

        foreach ($agendamentos as $agendamento) {
            if (! $agendamento['clinica'] || ! $agendamento['paciente']) {
                continue;
            }

            Agendamento::updateOrCreate(
                [
                    'paciente_id' => $agendamento['paciente']->id,
                    'clinica_id' => $agendamento['clinica']->id,
                    'data' => $agendamento['data'],
                    'hora' => $agendamento['hora'],
                ],
                ['status' => $agendamento['status']]
            );
        }
    }
}
