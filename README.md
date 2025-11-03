# Sistema de Lista de Jogos Pessoal

## 1. Objetivo

Desenvolver um sistema web completo para gerenciamento de uma lista de jogos pessoal, permitindo que usuários cadastrem-se, autentiquem-se e organizem seus jogos em diferentes categorias ("Quero Comprar", "Quero Jogar", "Finalizado", "Platinado").

Este projeto foi desenvolvido como trabalho para a disciplina de WEB 2, aplicando conceitos de programação back-end com PHP puro, banco de dados MySQL, front-end (HTML, CSS, Bootstrap, JavaScript) e boas práticas de segurança.

## 2. Tecnologias Utilizadas

* **Front-end**: HTML5, CSS3, JavaScript.
* **Framework CSS**: Bootstrap 5 (via CDN).
* **Back-end**: PHP Puro (v7.x ou superior).
* **Banco de Dados**: MySQL.
* **Conexão (PHP)**: PDO (PHP Data Objects).
* **Geração de Relatórios**: Biblioteca FPDF (para geração de PDF).
* **Servidor Local**: XAMPP (ou WAMP, MAMP).

## 3. Funcionalidades Obrigatórias Implementadas

O sistema cumpre todos os requisitos obrigatórios do trabalho:

* **Autenticação de Usuários**:
    * Cadastro de novos usuários.
    * Login seguro com verificação de senha.
    * Logout.
* **Controle de Acesso**:
    * Uso de Sessões (`$_SESSION`) para proteger páginas internas.
    * Redirecionamento de usuários não autenticados.
* **CRUD Completo (Lista de Jogos)**:
    * **Create**: Adicionar novos jogos a uma das 4 listas.
    * **Read**: Exibir todos os jogos do usuário, separados por categoria.
    * **Update**: Mover um jogo de uma lista para outra.
    * **Delete**: Remover um jogo da lista.
* **Validação de Formulários**:
    * Front-end (com atributos HTML `required`).
    * Back-end (verificação de campos vazios e dados duplicados, como e-mail).
* **Geração de Relatórios**:
    * Um botão "Baixar Relatório em PDF" que gera um documento com todos os jogos do usuário, organizados por lista, usando a biblioteca FPDF.

## 4. Medidas de Segurança Implementadas

* **Hash de Senhas**: Todas as senhas de usuários são armazenadas no banco de dados usando `password_hash()` (PASSWORD\_DEFAULT). A verificação é feita com `password_verify()`.
* **Proteção contra SQL Injection**: Todas as consultas ao banco de dados são feitas utilizando **Prepared Statements** (consultas parametrizadas) com PDO, prevenindo injeção de SQL.
* **Proteção contra XSS (Cross-Site Scripting)**: Todos os dados vindos do banco ou do usuário (como nomes de jogos ou nome do usuário) são sanitizados com `htmlspecialchars()` antes de serem exibidos no HTML.
* **Controle de Acesso no Back-end**: Os controladores `game_controller.php` e `report_controller.php` verificam ativamente se existe uma `$_SESSION['usuario_id']` antes de executar qualquer ação, garantindo que um usuário não possa modificar ou ver dados de outro.

## 5. Diagrama do Banco de Dados (ER)

A modelagem do banco de dados consiste em duas tabelas principais: `usuarios` e `lista_pessoal_jogos`.

[usuarios]

id (INT, PK, AI)

nome (VARCHAR)

email (VARCHAR, UNIQUE)

senha (VARCHAR)

data_cadastro (TIMESTAMP) | | 1..N | [lista_pessoal_jogos]

id (INT, PK, AI)

id_usuario (INT, FK -> usuarios.id)

nome_jogo (VARCHAR)

plataforma (VARCHAR)

status (VARCHAR) -- ('quero_jogar', 'quero_comprar', etc.)

data_adicionado (TIMESTAMP)

## 6. Instruções de Instalação e Execução

Para executar este projeto localmente, siga os passos abaixo.

### Pré-requisitos

* Um ambiente de servidor local, como **XAMPP**, **WAMP** ou **MAMP**, que inclua Apache, PHP 7+ e MySQL.

### Passos

1.  **Clone ou Baixe o Repositório:**
    * Faça o download ou clone este projeto para uma pasta em seu computador.

2.  **Mova os Arquivos:**
    * Mova a pasta inteira do projeto (ex: `lista-jogos-php`) para dentro da pasta `htdocs` (no XAMPP) ou `www` (no WAMP/MAMP) do seu servidor local.

3.  **Inicie o Servidor:**
    * Abra seu painel de controle (ex: XAMPP Control Panel) e inicie os serviços **Apache** e **MySQL**.

4.  **Importe o Banco de Dados:**
    * Abra o seu gerenciador de banco de dados (ex: `http://localhost/phpmyadmin/`).
    * Crie um novo banco de dados. O nome recomendado está no arquivo SQL: `lista_jogos_db`.
    * Selecione este banco de dados e vá para a aba "Importar".
    * Selecione o arquivo `seu_banco.sql` (ou `lista_jogos_db.sql`) que está na raiz do projeto e execute a importação. As tabelas `usuarios` e `lista_pessoal_jogos` serão criadas.

5.  **Configure a Conexão:**
    * Abra o arquivo `config/database.php` no seu editor de código.
    * Altere as variáveis `$host`, `$db_name`, `$username` e `$password` para que correspondam às suas credenciais do MySQL.

    ```php
    // config/database.php
    
    $host = 'localhost';
    $db_name = 'lista_jogos_db'; // O nome do banco que você criou/importou
    $username = 'root';          // Seu usuário do MySQL (padrão do XAMPP)
    $password = '';               // Sua senha do MySQL (padrão do XAMPP é vazio)
    ```

6.  **Acesse o Site:**
    * Abra seu navegador e acesse o URL do projeto.

    **`http://localhost/nome-da-pasta-do-projeto/`**

    *(Exemplo: `http://localhost/lista-jogos-php/`)*

*Você será automaticamente redirecionado para a página de login.*