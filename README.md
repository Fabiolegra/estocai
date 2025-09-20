# estocAI - Sistema de Controle de Estoque

**estocAI** é um sistema web para controle de estoque, desenvolvido em PHP puro. Ele oferece uma solução simples e eficiente para gerenciar produtos, monitorar níveis de estoque e registrar movimentações de entrada e saída. O projeto foi estruturado com foco na separação de responsabilidades, segurança e clareza do código.

## ✨ Funcionalidades Principais

O sistema atende a uma série de requisitos funcionais, incluindo:

- **Autenticação de Usuário**:
  - Cadastro de novos usuários (RF-01).
  - Login seguro com e-mail e senha (RF-02).
  - Logout e destruição de sessão (RF-03).
  - Proteção de rotas para garantir que apenas usuários autenticados acessem as páginas internas (RF-04).

- **Dashboard**:
  - Uma visão geral do estoque com estatísticas rápidas, como total de produtos, itens em estoque crítico/baixo e total de unidades (RF-05).
  - Atalhos para as principais ações do sistema.

- **Gestão de Produtos (CRUD)**:
  - Cadastro de novos produtos com validação de dados (RF-06).
  - Listagem de todos os produtos com busca e visualização responsiva (RF-07, RF-12).
  - Edição dos detalhes de um produto existente (RF-08).
  - Exclusão de produtos com confirmação (RF-09).

- **Controle de Estoque**:
  - Registro de movimentações de entrada e saída para cada produto (RF-11).
  - Histórico completo de todas as movimentações, indicando produto, tipo, quantidade e data (relacionado ao RF-11).
  - Destaque visual para produtos com estoque baixo ou crítico.

- **Usabilidade e Feedback**:
  - Mensagens claras de sucesso e erro para todas as operações (RF-13).
  - Navegação intuitiva entre as telas do sistema (RF-16).

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 7.4+ (sem frameworks)
- **Banco de Dados**: MySQL, com acesso via PDO para maior segurança (prevenção de SQL Injection).
- **Frontend**: HTML5, CSS3 (com uso de Flexbox e Grid para responsividade).
- **Servidor**: Ambiente de desenvolvimento local como XAMPP, WAMP ou MAMP.

## 🚀 Instalação e Configuração

Siga os passos abaixo para executar o projeto em seu ambiente local.

### 1. Pré-requisitos

- Um ambiente de servidor web local (XAMPP, WAMP, etc.) com Apache e MySQL.
- PHP 7.4 ou superior.
- Acesso ao phpMyAdmin ou outro cliente de banco de dados.

### 2. Passos para Instalação

1.  **Clone ou Baixe o Projeto**
    - Coloque todos os arquivos do projeto no diretório raiz do seu servidor web (ex: `C:/xampp/htdocs/estocai`).

2.  **Crie o Banco de Dados**
    - Abra o phpMyAdmin.
    - Crie um novo banco de dados chamado `estocaidb` com o collation `utf8mb4_general_ci`.

3.  **Importe a Estrutura do Banco**
    - Dentro do banco `estocaidb`, vá para a aba "Importar".
    - Importe o arquivo `schema.sql` (que deve estar na raiz do projeto) para criar automaticamente as tabelas `usuarios`, `produtos` e `movimentos`.

4.  **Configure a Conexão**
    - Abra o arquivo `config.php`.
    - Verifique se as credenciais de acesso ao banco de dados correspondem às do seu ambiente. As configurações padrão são para uma instalação XAMPP típica:
      ```php
      define('DB_SERVER', 'localhost');
      define('DB_USERNAME', 'root');
      define('DB_PASSWORD', '');
      define('DB_NAME', 'estocaidb');
      ```

5.  **Execute o Sistema**
    - Inicie os serviços Apache e MySQL no painel de controle do seu servidor (ex: XAMPP Control Panel).
    - Abra seu navegador e acesse: `http://localhost/estocai/`

A partir daí, você pode se cadastrar e começar a usar o sistema.

## 📂 Estrutura do Projeto

O projeto é organizado para separar a lógica de apresentação, validação e acesso a dados.

```
estocai/
├── css/                  # Arquivos de estilização (CSS)
│   ├── auth.css
│   ├── dashboard.css
│   └── ...
├── model/                # Scripts PHP para buscar dados do banco (lógica de leitura)
│   ├── dashboard_data.php
│   ├── movements_data.php
│   └── products_data.php
├── validators/           # Scripts PHP para validar e processar formulários (lógica de escrita)
│   ├── login_validator.php
│   ├── product_validator.php
│   └── ...
├── config.php            # Configuração da conexão com o banco de dados
├── dashboard.php         # Página principal do usuário logado
├── index.php             # Landing page (redireciona para login ou dashboard)
├── login.php             # Página de login
├── register.php          # Página de cadastro de usuário
├── products.php          # Página de listagem de produtos
├── product_create.php    # Formulário para criar produto
├── product_edit.php      # Formulário para editar produto
├── product_delete.php    # Script para excluir produto
├── product_movement.php  # Formulário para registrar entrada/saída
├── movements.php         # Página com o histórico de movimentações
├── logout.php            # Script para encerrar a sessão
├── requisitos_funcionais.md # Documento com os requisitos do sistema
└── README.md             # Este arquivo
```

- **Arquivos `.php` na raiz**: Atuam como "Controllers/Views", renderizando o HTML e incluindo a lógica necessária dos diretórios `model` e `validators`.
- **Diretório `model/`**: Contém a lógica de consulta (`SELECT`) para popular as páginas com dados do banco.
- **Diretório `validators/`**: Contém a lógica de processamento de formulários (`POST`), incluindo validação de dados e operações de escrita no banco (`INSERT`, `UPDATE`, `DELETE`).

---

Este projeto serve como um excelente exemplo de uma aplicação PHP estruturada sem o uso de um framework, focando em boas práticas como o uso de PDO, separação de responsabilidades e feedback claro ao usuário.