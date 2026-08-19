# Finora

Sistema de controle de contas a pagar e receber desenvolvido como teste técnico.

O Finora permite que cada usuário gerencie seus próprios contatos e lançamentos financeiros, acompanhe contas em aberto, vencidas e quitadas, visualize um resumo financeiro no dashboard, receba avisos automáticos de vencimento e realize o fechamento de períodos financeiros.

O projeto foi desenvolvido priorizando simplicidade, organização, isolamento dos dados por usuário e cumprimento dos requisitos principais do desafio.

---

## Ambiente online

Uma versão online do Finora está disponível em:

**https://finora-production-0eda.up.railway.app**

O deploy foi realizado no Railway com PostgreSQL gerenciado pela própria plataforma.

> Observação: o ambiente online foi configurado para demonstrar a aplicação web e a persistência em PostgreSQL. Os fluxos de Queue Worker, Scheduler e captura de e-mails foram validados no ambiente Docker/local.

---

## Tecnologias utilizadas

### Back-end

- PHP 8.3
- Laravel
- PostgreSQL
- Laravel Queue
- Laravel Scheduler

### Front-end

- React
- Inertia.js
- Tailwind CSS
- Vite

### Desenvolvimento e infraestrutura

- Laragon
- Composer
- Node.js / NPM
- Docker / Docker Compose
- Mailpit
- Railway

---

## Funcionalidades

### Autenticação

O sistema possui cadastro, login e logout de usuários.

Os dados são isolados por usuário. Dessa forma, um usuário não pode visualizar, alterar ou excluir contatos e lançamentos pertencentes a outro usuário.

### Dashboard

O dashboard apresenta um resumo dos dados financeiros do usuário autenticado, incluindo:

- total a receber;
- total a pagar;
- saldo previsto;
- quantidade de lançamentos em atraso;
- atalhos para lançamentos e contatos.

Os valores são calculados a partir dos lançamentos armazenados no banco de dados.

### Contatos

O usuário pode:

- cadastrar clientes e fornecedores;
- editar contatos;
- excluir contatos;
- classificar o contato como cliente ou fornecedor.

Um contato que já possui lançamentos vinculados não pode ser excluído, evitando a perda de referência e mantendo a consistência do histórico financeiro.

### Lançamentos

O usuário pode cadastrar contas:

- a pagar;
- a receber.

Cada lançamento possui:

- contato relacionado;
- tipo;
- valor;
- data de vencimento;
- status;
- data de liquidação, quando aplicável.

Os principais estados são:

- em aberto;
- em atraso;
- quitado.

### Liquidação

Um lançamento em aberto pode ser marcado como quitado.

Ao realizar a liquidação, o sistema registra a data em que o pagamento ou recebimento foi realizado.

### Visão financeira por período

O sistema permite consultar lançamentos por período e visualizar informações consolidadas, como:

- valores a pagar;
- valores a receber;
- valores liquidados;
- valores vencidos;
- saldo do período.

### Avisos automáticos de vencimento

O Finora possui um processo automático responsável por verificar lançamentos próximos do vencimento.

O Laravel Scheduler executa a verificação e os avisos são enviados por meio de Jobs processados pela fila.

O sistema também mantém registro das notificações para evitar o envio duplicado do mesmo aviso.

### Fechamento de período

É possível solicitar o fechamento de um período financeiro.

O processamento é executado de forma assíncrona por meio da fila.

Ao finalizar, o sistema gera o resumo do período e realiza o envio por e-mail, incluindo o arquivo de fechamento.

---

# Arquitetura e decisões técnicas

## Laravel + React + Inertia

Foi utilizado React integrado ao Laravel através do Inertia.js.

A decisão foi tomada para manter a arquitetura simples e concentrar o desenvolvimento nas regras de negócio solicitadas pelo teste.

Uma API REST separada adicionaria mais camadas e código de integração entre back-end e front-end. Para o escopo e o tempo disponível, o Inertia permitiu utilizar React mantendo o Laravel responsável por rotas, autenticação, validação e regras de negócio.

A prioridade durante o desenvolvimento foi fazer o básico bem feito, evitando complexidade que não agregaria valor direto aos requisitos principais.

---

## PostgreSQL

O PostgreSQL é utilizado como banco de dados da aplicação.

Durante o desenvolvimento local, o banco utilizado foi:

```text
finora
```

na porta padrão:

```text
5432
```

No ambiente Docker, o PostgreSQL utiliza internamente a porta `5432` e é exposto na máquina pela porta `5433`.

No ambiente online, o PostgreSQL é provisionado e gerenciado pelo Railway.

As credenciais reais não são armazenadas no repositório.

---

## Modelagem

A estrutura principal utiliza usuários, contatos e lançamentos.

### Usuários

Representam as pessoas que possuem acesso ao Finora.

Cada usuário possui seus próprios contatos e lançamentos.

### Contatos

Representam clientes ou fornecedores.

Foi utilizada uma única entidade de contatos com um campo de tipo em vez de criar tabelas separadas para clientes e fornecedores.

### Lançamentos

Representam contas a pagar ou receber.

Cada lançamento pertence a:

- um usuário;
- um contato.

O relacionamento com o usuário permite aplicar o isolamento dos dados.

### Relacionamentos principais

```text
User
 ├── possui vários Contacts
 └── possui várias Transactions

Contact
 └── pode possuir várias Transactions

Transaction
 ├── pertence a um User
 └── pertence a um Contact
```

Também existem estruturas auxiliares utilizadas para os fechamentos de período e para o controle das notificações automáticas.

---

# Filas e processamento assíncrono

O Finora utiliza o sistema de filas do Laravel.

O Queue Worker é responsável por processar tarefas que não precisam bloquear a requisição principal, como:

- envio de notificações;
- processamento de fechamento;
- envio dos resultados por e-mail.

Para executar manualmente:

```bash
php artisan queue:work
```

---

# Scheduler

O Laravel Scheduler é utilizado para executar verificações automáticas de vencimentos.

Durante o desenvolvimento, ele pode ser iniciado com:

```bash
php artisan schedule:work
```

O scheduler identifica os lançamentos que precisam gerar avisos e envia o processamento para a fila.

---

# Executando o projeto localmente

## Requisitos

Para executar sem Docker:

- PHP 8.3+
- Composer
- Node.js / NPM
- PostgreSQL
- extensão `pdo_pgsql` habilitada no PHP

Clone o repositório e entre na pasta:

```bash
git clone https://github.com/LeonardoDJ/Finora.git
cd Finora
```

Instale as dependências:

```bash
composer install
npm install
```

Crie o arquivo de ambiente:

### Windows

```bash
copy .env.example .env
```

### Linux/macOS

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

Configure no `.env` as credenciais do PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=finora
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

Execute as migrations:

```bash
php artisan migrate
```

Inicie a aplicação:

```bash
php artisan serve
```

Em outro terminal:

```bash
npm run dev
```

Para os fluxos assíncronos, mantenha também:

```bash
php artisan queue:work
```

e:

```bash
php artisan schedule:work
```

A aplicação estará disponível em:

```text
http://127.0.0.1:8000
```

---

# Executando com Docker

O projeto possui configuração Docker para executar a aplicação e seus serviços auxiliares de forma integrada.

A configuração utiliza os seguintes serviços:

- `finora-app` — aplicação Laravel;
- `finora-postgres` — PostgreSQL;
- `finora-worker` — processamento das filas;
- `finora-scheduler` — tarefas agendadas;
- `finora-mailpit` — captura de e-mails;
- `finora-migrate` — execução das migrations.

## Configuração da APP_KEY

Por segurança, a `APP_KEY` não é armazenada diretamente no `docker-compose.yml`.

Crie na raiz do projeto:

```text
.env.docker
```

e adicione:

```env
APP_KEY=base64:SUA_CHAVE_AQUI
```

O arquivo `.env.docker` está incluído no `.gitignore`.

Caso seja necessário gerar uma nova chave:

```bash
php artisan key:generate --show
```

## Inicialização

Na primeira execução ou após alterações que exijam rebuild:

```bash
docker compose --env-file .env.docker up --build
```

Nas execuções seguintes:

```bash
docker compose --env-file .env.docker up
```

Para encerrar:

```bash
docker compose down
```

Acesse:

### Finora

```text
http://127.0.0.1:8000
```

### Mailpit

```text
http://127.0.0.1:8025
```

A configuração Docker foi validada com Laravel, PostgreSQL, Queue Worker, Scheduler e Mailpit funcionando de forma integrada.

---

# Deploy no Railway

O Finora também foi publicado no Railway.

A aplicação utiliza:

- serviço web Laravel;
- PostgreSQL provisionado pelo Railway;
- migrations executadas no pre-deploy;
- domínio HTTPS público;
- variáveis de ambiente configuradas diretamente na plataforma.

Domínio:

```text
https://finora-production-0eda.up.railway.app
```

Para funcionamento correto atrás do proxy HTTPS do Railway, o Laravel foi configurado para confiar nos proxies encaminhados pela plataforma.

O build utiliza o `Dockerfile` do projeto, que compila os assets do Vite e instala as dependências PHP.

O processo de instalação do Composer também possui tentativas adicionais para reduzir falhas temporárias de download durante builds remotos.

---

# E-mails

No ambiente Docker, os e-mails são direcionados ao Mailpit.

A interface pode ser acessada em:

```text
http://127.0.0.1:8025
```

Isso permite testar avisos e fechamentos sem utilizar credenciais reais de serviços externos.

Durante o desenvolvimento local, também foi validado o envio SMTP para um endereço de e-mail real.

> O ambiente online do Railway não utiliza o Mailpit do ambiente local. Para envio de e-mails em produção, é necessário configurar um provedor SMTP na plataforma.

---

# Como testar os avisos automáticos

Com o ambiente Docker em execução, o Queue Worker e o Scheduler já são iniciados automaticamente.

1. crie um lançamento que atenda ao período de aviso configurado;
2. aguarde a execução do scheduler;
3. acesse o Mailpit em `http://127.0.0.1:8025`;
4. confira o e-mail gerado.

O sistema registra notificações já processadas para evitar duplicidade.

---

# Como testar o fechamento de período

Na página de lançamentos:

1. escolha o período desejado;
2. solicite o fechamento;
3. mantenha o Queue Worker em execução;
4. acompanhe o processamento;
5. confira o status do fechamento;
6. verifique o e-mail e o arquivo gerado.

---

# Isolamento dos dados

Consultas de contatos e lançamentos são associadas ao usuário autenticado.

Além disso, operações sensíveis validam a propriedade do registro antes de permitir alterações.

```text
Usuário A → dados do Usuário A
Usuário B → dados do Usuário B
```

Um usuário não deve conseguir visualizar ou manipular registros pertencentes a outro usuário.

---

# Consistência dos dados

Algumas regras foram adicionadas para impedir estados inconsistentes.

Um exemplo é a exclusão de contatos.

Caso um contato possua lançamentos relacionados, sua exclusão é bloqueada e o usuário recebe uma mensagem explicando o motivo.

Isso preserva o histórico financeiro e evita referências inválidas no banco.

---

# Problemas encontrados durante o desenvolvimento

### PostgreSQL não reconhecido no terminal

O PostgreSQL estava instalado, mas o executável `psql` não estava disponível diretamente no PATH.

### Driver PostgreSQL no PHP

Inicialmente, as migrations retornavam:

```text
could not find driver
```

Foi necessário habilitar:

```ini
extension=pdo_pgsql
extension=pgsql
```

### Configuração antiga do banco

Após corrigir o `.env`, foi utilizado:

```bash
php artisan config:clear
```

### WSL / Docker

Durante a preparação do ambiente Docker no Windows, o WSL apresentou:

```text
Wsl/REGDB_E_CLASSNOTREG
```

Após a reinstalação e correção do WSL, o ambiente Docker pôde ser validado completamente.

### Vite no ambiente Docker/Railway

Durante os testes foi necessário alinhar as entradas do Vite para atender tanto as páginas Blade de autenticação quanto as páginas React/Inertia.

### HTTPS atrás do proxy do Railway

O Laravel inicialmente gerava assets em HTTP mesmo com o domínio público em HTTPS. O proxy confiável foi configurado para que o framework reconhecesse corretamente o protocolo original da requisição.

### Build remoto do Composer

Durante um deploy no Railway, alguns downloads do Composer receberam erro HTTP 504. O Dockerfile foi ajustado para realizar novas tentativas de instalação, aumentando a resiliência do build.

---

# Uso de Inteligência Artificial

A Inteligência Artificial foi utilizada como ferramenta de apoio durante o desenvolvimento, e não como substituição da implementação e validação do projeto.

Entre os usos realizados:

- discussão e revisão da modelagem do banco;
- análise da separação entre usuários e contatos;
- explicações sobre Laravel, React, Inertia, filas e scheduler;
- apoio na investigação de erros;
- revisão de código e sugestões de melhorias;
- apoio na documentação;
- auxílio na configuração do Docker e do deploy.

As sugestões fornecidas pela IA foram analisadas durante o desenvolvimento, e as decisões foram aplicadas e testadas no projeto.

---

# Decisões de escopo

O desenvolvimento priorizou os requisitos principais do desafio.

Por esse motivo, algumas possibilidades de evolução não foram priorizadas, como:

- liquidação parcial;
- API REST separada do front-end;
- funcionalidades adicionais que não fossem necessárias para o fluxo principal.

A opção foi manter uma solução menor, compreensível e funcional.

---

# Segurança

- o arquivo `.env` não é versionado;
- o arquivo `.env.docker` não é versionado;
- credenciais reais do PostgreSQL e SMTP não devem ser adicionadas ao repositório;
- a `APP_KEY` é configurada por variável de ambiente;
- senhas dos usuários são tratadas pelo sistema de autenticação do Laravel.

---

# Autor

**Leonardo Delfino José**

Projeto desenvolvido como teste técnico para uma vaga de desenvolvimento.