# Organizr

O Organizr é uma plataforma web do tipo lista de tarefas (to do list), desenvolvida para a disciplina de Desenvolvimento de Aplicações Web da UDESC CCT.
O projeto foi desenvolvido pelos estudantes André Eduardo Schmitz e Mariana Rossdeutscher Waltrick Lima.
## Especificações Técnicas

### Frontend

  * **Linguagem:** TypeScript
  * **Framework:** Angular 20.0.0
  * **Gerenciador de Pacotes:** npm
  * **Estilização:** SCSS
  * **Principais Dependências:**
      * RxJS
      * Zone.js

### Backend

  * **Linguagem:** PHP 8.2.12
  * **Gerenciador de Pacotes:** Composer
  * **Principais Dependências:**
      * `vlucas/phpdotenv`: Para gerenciar variáveis de ambiente.
      * `firebase/php-jwt`: Para autenticação com JSON Web Tokens.

### Banco de Dados

  * **Sistema:** PostgreSQL
  * **Tabelas Principais:**
      * `users`: Armazena informações dos usuários.
      * `task`: Armazena os detalhes das tarefas.
      * `task_user`: Tabela de associação para tarefas compartilhadas.

## Instruções para Execução

### Pré-requisitos

  * Node.js e npm
  * PHP e Composer
  * PostgreSQL

### Backend

1.  **Navegue até o diretório do backend:**

    ```bash
    cd backend
    ```

2.  **Instale as dependências do Composer:**

    ```bash
    composer install
    ```

3.  **Configure as variáveis de ambiente:**

      * Renomeie ou copie o arquivo `.env.example` para `.env`.
      * Configure as variáveis de ambiente no arquivo `.env`, incluindo as credenciais do banco de dados e um segredo para o JWT.

4.  **Configure o banco de dados:**

      * Crie um banco de dados no PostgreSQL.
      * Execute o script `src/Config/db.sql` para criar as tabelas necessárias.

5.  **Inicie o servidor PHP:**

    ```bash
    php -S localhost:8000
    ```
    ou pelo XAMPP, iniciando o apache (caso utilizar o XAMPP, criar o projeto dentro do diretório htdocs)

### Frontend

1.  **Navegue até o diretório do frontend:**

    ```bash
    cd organizr-frontend
    ```

2.  **Instale as dependências do npm:**

    ```bash
    npm install
    ```

3.  **Inicie o servidor de desenvolvimento do Angular:**

    ```bash
    ng serve
    ```
     ```bash
    *incluir comando q eu esqueci*
    ```

    A aplicação estará disponível em `http://localhost:4200/`. O proxy configurado em `proxy.conf.json` irá redirecionar as chamadas de API para o backend.
