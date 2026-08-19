# Finora

Sistema de controle de contas a pagar e receber desenvolvido como teste técnico.

O Finora permite que cada usuário gerencie seus próprios contatos e lançamentos financeiros, acompanhe contas em aberto, vencidas e quitadas, receba avisos automáticos de vencimento e realize o fechamento de períodos financeiros.

O projeto foi desenvolvido priorizando simplicidade, organização e cumprimento dos requisitos principais do desafio.

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

- Mailpit para captura de e-mails no ambiente Docker

---

## Funcionalidades

### Autenticação

O sistema possui cadastro, login e logout de usuários.

Os dados são isolados por usuário. Dessa forma, um usuário não pode visualizar, alterar ou excluir contatos e lançamentos pertencentes a outro usuário.

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

O sistema permite consultar os lançamentos de um período e visualizar informações consolidadas, como valores:

- a pagar;

- a receber;

- liquidados;

- vencidos.

### Avisos automáticos de vencimento

O Finora possui um processo automático responsável por verificar lançamentos próximos do vencimento.

O Laravel Scheduler executa a verificação e os avisos são enviados por meio de Jobs processados pela fila.

O sistema também mantém registro das notificações para evitar o envio duplicado do mesmo aviso.

### Fechamento de período

É possível solicitar o fechamento de um período financeiro.

O processamento é executado de forma assíncrona por meio da fila.

Ao finalizar, o sistema gera o resumo do período e realiza o envio por e-mail, incluindo o arquivo de fechamento.

O status do fechamento permite acompanhar o resultado do processamento.

---

# Arquitetura e decisões técnicas

## Laravel + React + Inertia

Foi utilizado React integrado ao Laravel através do Inertia.js.

A decisão foi tomada para manter a arquitetura simples e concentrar o desenvolvimento nas regras de negócio solicitadas pelo teste.

Uma API REST separada adicionaria mais camadas e código de integração entre back-end e front-end. Para o escopo e o tempo disponível, o Inertia permitiu utilizar React mantendo o Laravel responsável pelas rotas, autenticação, validação e regras de negócio.

A prioridade durante o desenvolvimento foi fazer o básico bem feito, evitando complexidade que não agregaria valor direto aos requisitos principais.

---

## PostgreSQL

O PostgreSQL foi utilizado como banco de dados da aplicação.

Durante o desenvolvimento local, o banco utilizado foi:

```text

finora

```

na porta padrão:

```text

5432

```

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

Essa decisão evita duplicação de estrutura e simplifica a modelagem.

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

Por isso, para testar todo o fluxo automático localmente, é necessário manter tanto o scheduler quanto o worker em execução.

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

Instale as dependências PHP:

```bash

composer install

```

Instale as dependências do front-end:

```bash

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

Configure no `.env` as credenciais do PostgreSQL.

Exemplo:

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

Inicie o Laravel:

```bash

php artisan serve

```

Em outro terminal, inicie o Vite:

```bash

npm run dev

```

Em outro terminal, inicie a fila:

```bash

php artisan queue:work

```

E em outro terminal, inicie o scheduler:

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

- finora-app — aplicação Laravel;

- finora-postgres — banco PostgreSQL;

- finora-worker — processamento das filas;

- finora-scheduler — execução das tarefas agendadas;

- finora-mailpit — captura dos e-mails de desenvolvimento;

- finora-migrate — execução das migrations antes da inicialização dos demais serviços.

## Configuração da APP_KEY

Por segurança, a APP_KEY não é armazenada diretamente no docker-compose.yml.

Crie na raiz do projeto o arquivo:

.env.docker

Adicione uma chave válida do Laravel:

APP_KEY=base64:SUA_CHAVE_AQUI

O arquivo .env.docker está incluído no .gitignore e não deve ser versionado.

Caso seja necessário gerar uma nova chave, ela pode ser obtida através do Laravel:

php artisan key:generate --show

## Inicialização

Na primeira execução, ou quando houver alterações que exijam reconstrução das imagens:

docker compose --env-file .env.docker up --build

Nas execuções seguintes:

docker compose --env-file .env.docker up

Não é necessário executar manualmente php artisan serve, queue:work ou schedule:work, pois esses processos são executados pelos respectivos containers.

Para encerrar os serviços:

docker compose down

Depois, acesse:

### Finora

http://127.0.0.1:8000

### Mailpit

http://127.0.0.1:8025

O PostgreSQL utiliza internamente a porta 5432 e é exposto na máquina pela porta 5433, evitando conflito com uma instalação local do PostgreSQL.

A configuração Docker foi validada no ambiente de desenvolvimento com a aplicação Laravel, PostgreSQL, Queue Worker, Scheduler e Mailpit funcionando de forma integrada. Também foram validados o acesso à aplicação, persistência dos dados e envio de e-mails para o Mailpit.

---

# E-mails

No ambiente Docker, os e-mails são direcionados ao Mailpit.

A interface pode ser acessada em:

```text

http://localhost:8025

```

Isso permite testar os avisos e fechamentos sem utilizar credenciais reais de serviços externos.

Durante o desenvolvimento local, também foi validado o envio SMTP para um endereço de e-mail real.

---

# Como testar os avisos automáticos

Com o ambiente Docker em execução, o Queue Worker e o Scheduler já são iniciados automaticamente.

Para testar o fluxo:

1. crie um lançamento que atenda ao período de aviso configurado;

2. aguarde a execução do scheduler;

3. acesse o Mailpit em http://127.0.0.1:8025;

4. confira o e-mail gerado.

O sistema registra as notificações já processadas para evitar duplicidade de avisos.

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

Uma das regras principais implementadas é o isolamento entre usuários.

Consultas de contatos e lançamentos são associadas ao usuário autenticado.

Além disso, operações sensíveis validam a propriedade do registro antes de permitir alterações.

Dessa forma:

```text

Usuário A → dados do Usuário A

Usuário B → dados do Usuário B

```

Um usuário não deve conseguir visualizar ou manipular os registros pertencentes a outro usuário.

---

# Consistência dos dados

Algumas regras foram adicionadas para impedir estados inconsistentes.

Um exemplo é a exclusão de contatos.

Caso um contato possua lançamentos relacionados, sua exclusão é bloqueada e o usuário recebe uma mensagem explicando o motivo.

Isso preserva o histórico financeiro e evita referências inválidas no banco.

---

# Problemas encontrados durante o desenvolvimento

Alguns problemas de ambiente foram encontrados durante a implementação.

### PostgreSQL não reconhecido no terminal

O PostgreSQL estava instalado, mas o executável `psql` não estava disponível diretamente no PATH.

A instalação foi validada utilizando o caminho completo do executável e o servidor foi verificado com `pg_isready`.

### Driver PostgreSQL no PHP

Inicialmente, as migrations retornavam:

```text

could not find driver

```

Foi necessário habilitar no `php.ini`:

```ini

extension=pdo_pgsql

extension=pgsql

```

### Configuração antiga do banco

Em determinado momento o Laravel ainda estava utilizando configurações anteriores.

Após corrigir o `.env`, foi utilizado:

```bash

php artisan config:clear

```

para limpar a configuração em cache.

### WSL / Docker

Durante a preparação do ambiente Docker no Windows, o WSL apresentou erro de instalação relacionado a:

```text

Wsl/REGDB_E_CLASSNOTREG

```

Foram realizadas tentativas de reparo do ambiente Windows/WSL. Após a reinstalação e correção do WSL, o ambiente voltou a funcionar normalmente.

Com o WSL corrigido, a configuração Docker do projeto pôde ser validada completamente, incluindo Laravel, PostgreSQL, Queue Worker, Scheduler e Mailpit.

---

# Ambiente utilizado durante o desenvolvimento

```text

Finora

│

├── Laravel / PHP 8.3

├── React + Inertia

├── Composer

├── Node.js / NPM

├── PostgreSQL

├── Queue Worker

└── Scheduler

```

O Laragon foi utilizado como ambiente local para facilitar o gerenciamento do PHP e do projeto.

A escolha do Laragon em vez do XAMPP foi baseada principalmente na praticidade para o fluxo de desenvolvimento Laravel. O XAMPP também seria capaz de executar o projeto.

---

# Uso de Inteligência Artificial

A Inteligência Artificial foi utilizada como ferramenta de apoio durante o desenvolvimento, e não como substituição da implementação e validação do projeto.

Entre os usos realizados:

- discussão e revisão da modelagem do banco;

- análise da separação entre usuários e contatos;

- discussão sobre onde armazenar determinadas informações, como e-mail;

- explicações sobre Laravel, React, Inertia, filas e scheduler;

- apoio na investigação de erros;

- revisão de código e sugestões de melhorias;

- apoio na documentação;

- auxílio na configuração dos arquivos Docker.

Um exemplo foi a discussão sobre a associação do e-mail. A decisão foi manter o e-mail do usuário responsável separado do lançamento, utilizando o relacionamento entre as entidades para obter o destinatário das notificações.

Também foi discutida a diferença entre usuários e contatos. O usuário representa quem possui acesso ao sistema, enquanto os contatos representam clientes e fornecedores utilizados nos lançamentos.

As sugestões fornecidas pela IA foram analisadas durante o desenvolvimento, e as decisões foram aplicadas e testadas no projeto.

---

# Decisões de escopo

O desenvolvimento priorizou os requisitos principais do desafio.

Por esse motivo, algumas possibilidades de evolução não foram priorizadas, como:

- liquidação parcial;

- API REST separada do front-end;

- funcionalidades adicionais que não fossem necessárias para o fluxo principal.

A opção foi manter uma solução menor, compreensível e funcional em vez de aumentar a complexidade apenas para adicionar recursos opcionais.

---

# Segurança

O arquivo `.env` não é versionado.

Credenciais reais do PostgreSQL e SMTP não devem ser adicionadas ao repositório.

O arquivo `.env.example` contém apenas exemplos de configuração.

O arquivo .env.docker também não é versionado e é utilizado para fornecer a APP_KEY ao Docker sem armazená-la diretamente no docker-compose.yml.

As senhas dos usuários são tratadas pelo sistema de autenticação do Laravel.

---

# Autor

**Leonardo Delfino José**

Projeto desenvolvido como teste técnico para uma vaga de desenvolvimento.