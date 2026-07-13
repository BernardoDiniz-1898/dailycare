# Daily Care

**Marketplace acessível de fisioterapia que conecta clínicas independentes a pacientes com deficiências motoras.**

[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![SQLite](https://img.shields.io/badge/SQLite-3.x-003B57?style=flat-square&logo=sqlite&logoColor=white)](https://www.sqlite.org/)
[![WCAG](https://img.shields.io/badge/WCAG-2.1%20AA-green?style=flat-square)](https://www.w3.org/WAI/WCAG21/quickref/)
[![NBR](https://img.shields.io/badge/NBR-16001-blueviolet?style=flat-square)](https://www.abnt.org.br/)
[![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)](LICENSE)

---

## Sumário

- [Sobre](#sobre)
- [Objetivos](#objetivos)
- [Resultados Esperados](#resultados-esperados)
- [Metodologia](#metodologia)
- [Normas Técnicas](#normas-técnicas)
- [Problema e Solução](#problema-e-solução)
- [Funcionalidades](#funcionalidades)
- [Acessibilidade](#acessibilidade)
- [Tema e Customização](#tema-e-customização)
- [Stack Tecnológica](#stack-tecnológica)
- [Arquitetura](#arquitetura)
- [Instalação Rápida](#instalação-rápida)
- [Credenciais de Teste](#credenciais-de-teste)
- [Comandos Disponíveis](#comandos-disponíveis)
- [Variáveis de Ambiente](#variáveis-de-ambiente)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Diagrama de Caso de Uso](#diagrama-de-caso-de-uso)
- [Riscos Técnicos](#riscos-técnicos)
- [Equipe](#equipe)
- [Referências](#referências)

---

## Sobre

O **Daily Care** é uma plataforma web marketplace projetada para apoiar pessoas com deficiência (PcD) e seus cuidadores. O foco principal é remover barreiras de comunicação e informação no acesso a serviços de fisioterapia, conectando pacientes a clínicas independentes validadas com critérios reais de acessibilidade física.

Projeto desenvolvido como **Trabalho de Conclusão de Curso (TCC)** do curso de **Análise e Desenvolvimento de Sistemas** do SENAI, com foco em acessibilidade web e inclusão digital.

---

## Objetivos

### Objetivo Geral

Desenvolver uma plataforma web marketplace que conecte pacientes com deficiências motoras a clínicas de fisioterapia independentes, garantindo acessibilidade digital conforme as normas WCAG 2.1 e NBR 16001.

### Objetivos Específicos

1. **Elaborar** um sistema de busca e filtragem que permita localizar clínicas por especialidade, localização e critérios de acessibilidade física.
2. **Implementar** interface web com conformidade WCAG 2.1 Nível AA, incluindo navegação por teclado, leitores de tela e alto contraste.
3. **Integrar** widget V-Libras para tradução automática em Língua Brasileira de Sinais.
4. **Desenvolver** sistema de agendamento com gestão de horários e status em tempo real.
5. **Criar** painéis diferenciados para os três perfis de usuário (Paciente, Clínica e Administrador).
6. **Validar** a infraestrutura de acessibilidade física das clínicas cadastradas através de checklist e evidências fotográficas.

---

## Resultados Esperados

| Resultado | Indicador |
|-----------|-----------|
| Plataforma funcional com 3 perfis de usuário | Login, cadastro e painéis operacionais |
| Conformidade WCAG 2.1 Nível AA | Auditoria com ferramentas automatizadas (Lighthouse, axe) |
| Temas acessíveis (Claro, Escuro, Alto Contraste) | Troca em tempo real com persistência |
| Sistema de busca e filtro funcional | Busca por especialidade, CEP e acessibilidade |
| Agendamento com validação de horários | Conflitos de horário impedidos no backend |
| Integração com V-Libras | Widget funcional em todas as páginas |
| Documentação técnica completa | README com arquitetura, ER e instruções de uso |

---

## Metodologia

O desenvolvimento seguiu as fases do **Processo Unificado (UP)** adaptado para projetos acadêmicos:

### Fases do Projeto

| Fase | Atividades | Entregáveis |
|------|-----------|-------------|
| **1. Iniciação** | Levantamento de requisitos, estudo de acessibilidade web, definição do escopo | Documentação de requisitos, estudo do problema |
| **2. Elaboração** | Prototipação, definição de arquitetura, modelagem de dados | Diagramas UML, modelo ER, protótipo de interface |
| **3. Construção** | Desenvolvimento iterativo com ciclos curtos | Sistema funcional com incremental de funcionalidades |
| **4. Transição** | Testes, validação de acessibilidade, ajustes finais | Sistema final, documentação, apresentação |

### Ferramentas e Técnicas Utilizadas

| Categoria | Ferramenta |
|-----------|------------|
| **IDE** | Visual Studio Code |
| **Controle de versão** | Git / GitHub |
| **Banco de dados** | SQLite (dev) / MySQL (produção) |
| **Teste de acessibilidade** | Lighthouse, axe DevTools, NVDA Screen Reader |
| **Prototipação** | Figma |
| **Gestão de projeto** | Trello / GitHub Projects |

---

## Normas Técnicas

O projeto atende às seguintes normas e diretrizes:

| Norma | Descrição | Aplicação no Projeto |
|-------|-----------|---------------------|
| **NBR 16001:2012** | Acessibilidade na web — Requisitos | Requisitos obrigatórios para websites acessíveis |
| **WCAG 2.1 Nível AA** | Web Content Accessibility Guidelines | Padrão internacional de acessibilidade web |
| **WAI-ARIA 1.2** | Accessible Rich Internet Applications | Atributos semânticos para interfaces dinâmicas |
| **NBR 16002:2016** | Acessibilidade em edificações — Requisitos | Referência para critérios de acessibilidade física das clínicas |

### Conformidade NBR 16001 — Principais Requisitos Atendidos

| Requisito NBR 16001 | Implementação no Daily Care |
|----------------------|----------------------------|
| **Informação e navegação** | Semântica HTML5, ARIA landmarks, skip links |
| **Operação** | Navegação completa por teclado, `:focus-visible`, atalhos |
| **Percepção** | Contraste ≥ 4.5:1, resize de fonte, 3 temas visuais |
| **Comprensão** | Linguagem simples, design minimalista, labels associados |
| **Segurança** | Feedback visual e sonoro, mensagens de erro claras |
| **Comunicação** | V-Libras para Libras, textos alternativos em imagens |

---

## Problema e Solução

### Problema

- **Falta de visibilidade:** Clínicas de fisioterapia independentes têm baixa presença digital, enquanto grandes redes hospitalares centralizam a atenção à saúde.
- **Barreira de deslocamento:** Pacientes com disfunções motoras enfrentam frustração ao tentar encontrar locais que unam a especialidade médica necessária à acessibilidade arquitetônica real.
- **Acessibilidade digital precária:** A maioria dos sites não atende às normas WCAG, impedindo que PcDs naveguem com autonomia para agendar consultas.
- **Falta de validação:** Não existe um mecanismo confiável para verificar se uma clínica possui rampa de acesso, banheiro adaptado, elevador ou portas largas antes do paciente se deslocar.

### Solução

O Daily Care resolve essa fragmentação através de um **marketplace centralizado** que permite ao usuário:

1. **Encontrar** clínicas de fisioterapia por especialidade e localização.
2. **Filtrar** por critérios de acessibilidade física (rampa, banheiro adaptado, elevador, etc.).
3. **Validar** a infraestrutura real da clínica através de fotos e checklist de acessibilidade.
4. **Agendar** sessões de forma autônoma com total acessibilidade digital.

---

## Funcionalidades

### Fluxo Principal

```
Login/Cadastro → Busca por Especialidade/CEP → Filtros de Acessibilidade → Perfil da Clínica → Verificação de Infraestrutura → Solicitação de Agendamento
```

| Funcionalidade | Descrição |
|----------------|-----------|
| **Busca inteligente** | Busca por especialidade, cidade e CEP com paginação |
| **Filtros de acessibilidade** | Rampa de acesso, banheiro adaptado, elevador, portas largas, vaga de estacionamento, sinalização tátil |
| **Perfil da clínica** | Informações de contato, fotos da infraestrutura, horários disponíveis, checklist de acessibilidade |
| **Agendamento** | Seleção de horários disponíveis e solicitação de sessão com status em tempo real |
| **Avaliações** | Pacientes podem avaliar clínicas com nota e comentário |
| **V-Libras** | Widget de tradução automática para Língua Brasileira de Sinais |
| **3 perfis de usuário** | Paciente, Clínica e Administrador com painéis dedicados |

### Painéis por Perfil

| Perfil | Funcionalidades |
|--------|-----------------|
| **Paciente** | Buscar clínicas, ver agendamentos, avaliar clínicas |
| **Clínica** | Gerenciar perfil, especialidades, horários, confirmar/recusar agendamentos |
| **Administrador** | Aprovar/rejeitar clínicas, gerenciar especialidades e serviços de acessibilidade |

---

## Acessibilidade

O Daily Care atende às diretrizes **WCAG 2.1 Nível AA**:

| Recurso | Implementação |
|---------|---------------|
| **3 temas visuais** | Claro, Escuro e Alto Contraste — trocáveis pelo usuário com persistência em `localStorage` |
| **Semântica HTML e ARIA** | Tags estruturais (`<main>`, `<nav>`, `<article>`) e atributos `aria-label` para leitores de tela |
| **Navegação por Teclado** | Estados `:focus-visible` com borda azul, skip link, ordenação lógica de `tabindex` |
| **Atalhos de teclado** | `Alt+1` (conteúdo), `Alt+2` (navegação), `Alt+T` (trocar tema), `Alt+/-` (fonte) |
| **V-Libras** | Widget de tradução para Libras integrado em todas as páginas |
| **Redimensionamento de Fonte** | Controles para aumento (70%–150%) e diminuição do tamanho do texto |
| **Design Minimalista** | Layout limpo com baixa carga cognitiva para neurodivergentes |
| **Textos Alternativos** | Atributo `alt` obrigatório em todas as imagens |
| **Contraste** | Paleta com taxa mínima de 4.5:1 (texto) e 3:1 (texto grande) |
| **Mínimo 44px** | Todos os elementos interativos atendem ao tamanho mínimo de toque |
| **`prefers-reduced-motion`** | Respeita a preferência do sistema para desativar animações |
| **`prefers-color-scheme`** | Tema escuro ativado automaticamente se o sistema operacional estiver em dark mode |
| **Impressão** | Estilos de impressão ocultam navegação e botões, exibem apenas conteúdo |

---

## Tema e Customização

O sistema de temas está na barra de acessibilidade no topo de todas as páginas:

| Tema | Atalho | Descrição |
|------|--------|-----------|
| **Claro** | — | Fundo branco, texto escuro (padrão) |
| **Escuro** | `Alt+T` | Fundo `#111827`, cards `#1F2937`, texto claro |
| **Alto Contraste** | `Alt+T` (cicla) | Fundo preto, texto amarelo/branco |

A preferência do usuário é salva em `localStorage` e restaurada automaticamente.

---

## Stack Tecnológica

### Back-end

| Tecnologia | Versão | Uso |
|------------|--------|-----|
| **PHP** | 8.3+ | Linguagem server-side |
| **Laravel** | 13.x | Framework PHP (routing, ORM, auth, middleware) |
| **SQLite** | 3.x | Banco de dados (desenvolvimento) |
| **MySQL** | 8.x | Banco de dados (produção) |

### Front-end

| Tecnologia | Versão | Uso |
|------------|--------|-----|
| **Tailwind CSS** | 4.x | Utility-first CSS framework |
| **Vite** | 8.x | Build tool e dev server |
| **JavaScript** | ES6+ | Interatividade e acessibilidade dinâmica |
| **Google Fonts** | — | Tipografia Roboto (400–800) |

---

## Arquitetura

Arquitetura **monolítica MVC** com autenticação baseada em sessão e 3 perfis de acesso.

```
┌─────────────────────────────────────────────────┐
│              CLIENTE (Navegador)                 │
│   Blade Templates + CSS (WCAG AA) + JS A11y     │
│   + V-Libras Widget                             │
└────────────────────┬────────────────────────────┘
                     │ HTTPS
                     ▼
┌─────────────────────────────────────────────────┐
│              LARAVEL 13 (PHP 8.3)               │
│                                                 │
│   Rotas ─→ Middleware (CheckRole) ─→ Controllers │
│                                       ↓         │
│                              Models (Eloquent)   │
│                                       ↓         │
│                              Views (Blade)       │
│                                       ↓         │
│                            SQLite / MySQL        │
└─────────────────────────────────────────────────┘
```

### Diagrama de Entidade-Relacionamento (simplificado)

```
┌──────────┐    ┌──────────────┐    ┌──────────────────┐
│ usuarios │───→│   clinicas   │←───│ clinica_especial. │
│          │    │              │    └──────────────────┘
│          │    │              │←───┐
│          │    └──────┬───────┘    │ clinica_acessib.  │
│          │           │            └──────────────────┘
│          │    ┌──────┴───────┐
│          │    │ agendamentos │
│          │    └──────────────┘
│          │    ┌──────────────┐
│          │    │  avaliacoes  │
└──────────┘    └──────────────┘
```

---

## Instalação Rápida

### Pré-requisitos

- PHP 8.3+ com extensões: `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- [Composer](https://getcomposer.org/)
- [Node.js 18+](https://nodejs.org/) e NPM

### Setup

```bash
# Clone o repositório
git clone https://github.com/seu-usuario/daily-care.git
cd daily-care

# Instale dependências PHP
composer install

# Configure o ambiente
cp .env.example .env
php artisan key:generate

# Crie o banco SQLite
touch database/database.sqlite

# Execute migrações e seeders
php artisan migrate --seed

# Instale dependências JS e compile assets
npm install
npm run build
```

### Iniciar o servidor

```bash
php artisan serve
```

Acesse: [http://localhost:8000](http://localhost:8000)

---

## Credenciais de Teste

| Perfil | E-mail | Senha |
|--------|--------|-------|
| **Administrador** | `admin@dailycare.com` | `password` |
| **Paciente** | `maria@email.com` | `password` |
| **Clínica** | `clinica@email.com` | `password` |

---

## Comandos Disponíveis

| Comando | Descrição |
|---------|-----------|
| `php artisan serve` | Inicia o servidor de desenvolvimento |
| `php artisan migrate --seed` | Executa migrações e popula dados iniciais |
| `php artisan route:list` | Lista todas as rotas registradas |
| `npm run dev` | Compila assets em modo de desenvolvimento (hot reload) |
| `npm run build` | Compila assets para produção |
| `php artisan view:clear` | Limpa cache de views compiladas |

---

## Variáveis de Ambiente

Edite o arquivo `.env` após a instalação:

| Variável | Descrição | Padrão (Dev) |
|----------|-----------|---------------|
| `APP_NAME` | Nome da aplicação | `Daily Care` |
| `APP_URL` | URL base | `http://localhost:8000` |
| `DB_CONNECTION` | Tipo de banco | `sqlite` |
| `DB_DATABASE` | Caminho do banco | `database/database.sqlite` |

> Para produção, altere `DB_CONNECTION` para `mysql` e configure as variáveis `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD`.

---

## Estrutura do Projeto

```
dailycare/
├── app/
│   ├── Http/
│   │   ├── Controllers/           # 7 controllers (Auth, Dashboard, Clinica, etc.)
│   │   └── Middleware/            # CheckRole (controle de acesso por perfil)
│   └── Models/                    # 8 models Eloquent com relationships
├── database/
│   ├── factories/                 # Factories para teste
│   ├── migrations/                # 13 migrações do banco de dados
│   └── seeders/                   # Seeders com dados iniciais
├── public/
│   ├── build/                     # Assets compilados (Vite)
│   └── js/
│       └── acessibilidade.js      # JS de acessibilidade (tema, fonte, atalhos)
├── resources/
│   ├── css/
│   │   └── app.css                # Estilos WCAG 2.1 AA (~1300 linhas)
│   └── views/
│       ├── layouts/app.blade.php  # Layout principal com barra de acessibilidade
│       ├── auth/                  # Login e registro
│       ├── clinicas/              # Busca e perfil da clínica
│       ├── clinica/               # Cadastro e edição de perfil (clínica)
│       ├── dashboard/             # Painéis por perfil (paciente/clínica/admin)
│       └── admin/                 # Painel administrativo
├── routes/
│   └── web.php                    # 29 rotas nomeadas
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

### Models e Tabelas

| Model | Tabela | Relacionamentos |
|-------|--------|-----------------|
| `Usuario` | `usuarios` | clinica, agendamentos, avaliacoes |
| `Clinica` | `clinicas` | usuario, especialidades, servicosAcessibilidade, fotos, horarios, agendamentos, avaliacoes |
| `Especialidade` | `especialidades` | clinicas (N:N) |
| `ServicoAcessibilidade` | `servicos_acessibilidade` | clinicas (N:N com pivot) |
| `ClinicaFoto` | `clinica_fotos` | clinica |
| `HorarioDisponivel` | `horarios_disponiveis` | clinica |
| `Agendamento` | `agendamentos` | paciente, clinica |
| `Avaliacao` | `avaliacoes` | paciente, clinica |

---

## Diagrama de Caso de Uso

### Atores

| Ator | Descrição |
|------|-----------|
| **Paciente (PcD)** | Busca clínicas acessíveis e agenda sessões |
| **Clínica (Parceiro)** | Cadastra clínica, especialidades, infraestrutura e horários |
| **Administrador** | Gerencia conteúdo, validação de clínicas e configurações |

### Casos de Uso — Paciente

- Buscar clínicas por especialidade, cidade ou CEP
- Filtrar por critérios de acessibilidade física
- Visualizar perfil detalhado da clínica (fotos, infraestrutura, especialidades, horários)
- Solicitar agendamento de sessão de fisioterapia
- Avaliar clínicas com nota e comentário

### Casos de Uso — Clínica

- Cadastrar perfil com informações de contato e localização
- Registrar especialidades oferecidas
- Cadastrar itens de acessibilidade da infraestrutura física
- Gerenciar disponibilidade de horários
- Confirmar, recusar ou concluir agendamentos

### Casos de Uso — Administrador

- Aprovar ou rejeitar cadastro de novas clínicas
- Gerenciar lista de especialidades
- Gerenciar serviços de acessibilidade
- Monitorar agendamentos da plataforma

---

## Riscos Técnicos

| Risco | Mitigação |
|-------|-----------|
| Incompatibilidade com leitores de tela | Regiões `aria-live`, testes com NVDA/VoiceOver, semântica ARIA |
| Sobrecarga do banco com imagens pesadas | Compressão automática de imagem no upload |
| Baixa performance em dispositivos limitados | Lazy loading, otimização de assets, `prefers-reduced-motion` |
| Instabilidade do V-Libras | Widget com fallback silencioso |
| Tema escuro com contraste insuficiente | Paleta validada com ferramentas de contraste (WCAG AA) |
| Não conformidade com NBR 16001 | Checklist de requisitos da norma aplicado em cada funcionalidade |

---

## Equipe

| Nome |
|------|
| Bernardo |
| Fabio |
| João |
| Kaio |
| Samuel |

---

## Referências

### Normas e Padrões

- **ABNT NBR 16001:2012** — Acessibilidade na web — Requisitos
- **ABNT NBR 16002:2016** — Acessibilidade em edificações — Requisitos
- [WCAG 2.1](https://www.w3.org/WAI/WCAG21/quickref/) — Web Content Accessibility Guidelines
- [WAI-ARIA 1.2](https://www.w3.org/TR/wai-aria/) — Accessible Rich Internet Applications

### Tecnologias e Ferramentas

- [Laravel](https://laravel.com/) — Framework PHP
- [Tailwind CSS](https://tailwindcss.com/) — CSS utility-first
- [Vite](https://vitejs.dev/) — Build tool
- [V-Libras](https://vlibras.gov.br/) — Tradução para Língua Brasileira de Sinais

### Referências de Acessibilidade

- [Laramara](https://laramara.org.br/) — Produtos para deficiência visual
- [Portal PCD](https://www.pcd.com.br/) — Notícias e direitos de PcDs
- [AbilityNet](https://abilitynet.org.uk/) — Apoio tecnológico inclusivo
- [MDN Web Docs — Acessibilidade](https://developer.mozilla.org/pt-BR/docs/Web/Accessibility)

---

## Licença

Este projeto está sob a licença MIT. Consulte o arquivo [LICENSE](LICENSE) para mais detalhes.

---

> **Daily Care** — Conectando acessibilidade à saúde com autonomia digital.
