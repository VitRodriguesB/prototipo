# Sistema de Gestão de Eventos

Uma plataforma completa para gerenciamento de eventos acadêmicos e profissionais, desenvolvida em Laravel. O sistema gerencia todo o ciclo de vida de um evento, desde a inscrição e pagamento até a submissão e avaliação de trabalhos.

## 🚀 Funcionalidades Principais

O sistema possui controle de acesso baseado em papéis (ACL) para três perfis distintos:

### 👤 Participante
- Visualização de eventos disponíveis.
- Inscrição em eventos e atividades.
- Pagamento de inscrições.
- Submissão de trabalhos acadêmicos.
- Download de certificados (se disponível).

### 🛠️ Organizador
- Criação e edição de eventos.
- Gerenciamento de atividades e cronogramas.
- Definição de tipos de inscrição e valores.
- Validação manual de pagamentos (Aprovar/Recusar).
- Distribuição de trabalhos para avaliadores.

### 📋 Avaliador
- Visualização de trabalhos atribuídos.
- Realização de avaliações e emissão de pareceres.

## 🚧 Status do Projeto

O projeto encontra-se em fase de desenvolvimento. Abaixo, o status detalhado das funcionalidades planejadas:

### ✅ Funcionalidades Implementadas (MVP)
- **Autenticação**: Cadastro e Login de usuários (Participante, Organizador, Avaliador).
- **Gestão de Eventos**: CRUD completo para organizadores.
- **Inscrições**: Fluxo de inscrição e escolha de tipo de ingresso.
- **Atividades**: Cadastro de programação (palestras, minicursos).
- **Submissões**: Envio de trabalhos acadêmicos pelos participantes.
- **Avaliação**: Interface para avaliadores revisarem trabalhos.

### ⏳ Funcionalidades Pendentes (Roadmap)
As seguintes "Features de Saída" e "Automações" listadas no escopo ainda precisam ser desenvolvidas:
- [ ] **Confirmação de E-mail**: Envio de link de ativação no cadastro (RF_B1).
- [ ] **Notificações Automáticas**: E-mails de confirmação de inscrição, pagamento e prazos (RF_S6).
- [ ] **Upload de Comprovante**: Melhoria no fluxo de envio e validação de arquivos de pagamento (RF_F2).
- **Certificados**:
    - [ ] Geração automática de PDF para Participação (RF_S7).
    - [ ] Geração automática de PDF para Apresentação de Trabalho.
- **Controle de Presença**:
    - [ ] Geração de QR Code por inscrito.
    - [ ] Leitura/Validação de QR Code no dia do evento (RF_F10).

## 💡 Melhorias Sugeridas (Code Quality & Security)

Para garantir escalabilidade, segurança e manutenibilidade, sugerimos as seguintes refatorações:

### Arquitetura e Código
- **Service Pattern**: Extrair regras de negócio complexas dos Controllers (ex: `PaymentController`, `InscriptionController`) para Classes de Serviço (`PaymentService`).
- **Form Requests**: Implementar validação dedicada para cada formulário, retirando-a dos Controllers.
- **Testes**: Criar bateria de testes automatizados (Unitários e Feature) para garantir estabilidade.

### Segurança
- **Sanitização**: Reforçar proteção contra XSS e revisar uploads de arquivos.
- **ACL**: Verificar middlewares de permissão em todas as rotas sensíveis.

## 🛠️ Stack Tecnológico

- **Backend:** Laravel 10+ (PHP 8.2+)
- **Banco de Dados:** MySQL
- **Frontend:** Blade Templates, Tailwind CSS, Alpine.js
- **Build Tool:** Vite

## ⚙️ Instalação e Configuração

Siga os passos abaixo para rodar o projeto em seu ambiente local:

### Pré-requisitos
- PHP 8.2 ou superior
- Composer
- Node.js & NPM
- MySQL

### Passo a Passo

1. **Clone o repositório**
   ```bash
   git clone <url-do-repositorio>
   cd events-project
   ```

2. **Instale as dependências**
   ```bash
   composer install
   npm install
   ```

3. **Configure o ambiente**
   Copie o arquivo de exemplo e configure o banco de dados:
   ```bash
   cp .env.example .env
   ```
   Edite o arquivo `.env` e ajuste as credenciais do banco:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=events_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Gere a chave da aplicação**
   ```bash
   php artisan key:generate
   ```

5. **Execute as migrações**
   Crie as tabelas no banco de dados (certifique-se que o banco `events_db` existe):
   ```bash
   php artisan migrate
   ```

6. **Inicie o servidor**
   Você precisará de dois terminais:

   *Terminal 1 (Backend):*
   ```bash
   php artisan serve
   ```

   *Terminal 2 (Frontend/Assets):*
   ```bash
   npm run dev
   ```

O projeto estará acessível em `http://localhost:8000`.

## 📂 Estrutura do Projeto

- **Models:** `app/Models` - Definição das entidades (Event, Inscription, Work, etc).
- **Controllers:** `app/Http/Controllers` - Lógica de negócios.
- **Views:** `resources/views` - Templates Blade.
- **Routes:** `routes/web.php` - Definição de rotas e middleware.

## 📝 Licença

Este projeto é open-source.
