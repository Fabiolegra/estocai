Sistema de Controle de Estoque (estocAI)
Documento de Requisitos Funcionais
Versão: 1.0

1. Visão Geral
- Este documento descreve os requisitos funcionais do sistema de controle de estoque desenvolvido em PHP, com foco em autenticação de usuários e gestão de produtos.

2. Atores
- Visitante: usuário não autenticado, com acesso apenas às páginas públicas (ex.: login, cadastro).
- Usuário autenticado: usuário que realizou login e pode acessar funcionalidades internas (dashboard, produtos).

3. Requisitos Funcionais

RF-01 — Cadastro de Usuário
Descrição: O sistema deve permitir que novos usuários se cadastrem informando dados mínimos (nome, e-mail, senha).
Critérios de aceite:
- Deve existir uma tela/formulário de cadastro acessível a visitantes.
- Campos obrigatórios não preenchidos devem impedir o envio e exibir mensagens de erro.
- E-mail deve ser único; tentativa de cadastro com e-mail já existente deve ser rejeitada com mensagem adequada.

RF-02 — Autenticação (Login)
Descrição: O sistema deve permitir que usuários façam login com e-mail e senha válidos.
Critérios de aceite:
- Deve existir uma tela de login que valide credenciais no banco de dados.
- Em caso de sucesso, o usuário é redirecionado para o dashboard.
- Em caso de falha, uma mensagem de erro clara deve ser exibida.

RF-03 — Logout
Descrição: O sistema deve permitir que o usuário encerre sua sessão de forma segura.
Critérios de aceite:
- Deve existir uma ação de logout que destrua a sessão do usuário.
- Após logout, o usuário não deve acessar rotas restritas sem nova autenticação.

RF-04 — Proteção de Rotas
Descrição: Páginas internas (dashboard, gestão de produtos) devem exigir sessão autenticada.
Critérios de aceite:
- Acesso direto a rotas internas sem sessão deve redirecionar para o login.

RF-05 — Dashboard
Descrição: O sistema deve apresentar uma página inicial (dashboard) para usuários autenticados, com visão geral do estoque.
Critérios de aceite:
- O dashboard deve ser acessível após login.
- Deve exibir, no mínimo, um resumo ou atalhos para operações com produtos.

RF-06 — Cadastrar Produto
Descrição: O sistema deve permitir o cadastro de novos produtos com campos essenciais (ex.: nome, descrição, quantidade, fornecedor, preço, código).
Critérios de aceite:
- Campos obrigatórios devem ser validados com mensagens claras.
- Quantidade deve aceitar apenas valores numéricos válidos.
- Após salvar, o usuário deve receber confirmação e poder visualizar o item na listagem.

RF-07 — Listar Produtos
Descrição: O sistema deve listar os produtos cadastrados em uma tabela.
Critérios de aceite:
- A listagem deve exibir, no mínimo: ID, nome, quantidade e ações (editar/excluir).
- A listagem deve estar disponível apenas para usuários autenticados.

RF-08 — Editar Produto
Descrição: O sistema deve permitir a edição dos dados de um produto existente.
Critérios de aceite:
- Deve haver uma tela de edição acessível a partir da listagem.
- Alterações válidas devem ser persistidas no banco e confirmadas ao usuário.

RF-09 — Excluir Produto
Descrição: O sistema deve permitir a exclusão de um produto.
Critérios de aceite:
- Deve haver uma ação de exclusão na listagem ou na tela de edição.
- O sistema deve solicitar confirmação antes de excluir (pelo menos por meio de link/rota clara).
- Após excluir, o item não deve mais aparecer na listagem.

RF-10 — Visualizar Detalhes do Produto
Descrição: O sistema pode disponibilizar uma página de detalhes de produto.
Critérios de aceite:
- Ao acessar um produto específico, exibir todos os campos cadastrados.

RF-11 — Atualização de Quantidade em Estoque
Descrição: O sistema deve permitir atualizar a quantidade em estoque de um produto por meio de edição do produto.
Critérios de aceite:
- Alterações na quantidade devem ser salvas e refletidas imediatamente na listagem.
- Deve haver validação para impedir quantidades inválidas (negativas, quando não permitido).

RF-12 — Pesquisa/Filtragem de Produtos (Opcional)
Descrição: O sistema pode oferecer busca por nome/código e filtros básicos na listagem.
Critérios de aceite:
- A busca deve retornar resultados correspondentes ao termo informado.

RF-13 — Mensagens de Feedback
Descrição: O sistema deve exibir mensagens claras de sucesso e erro para operações (login, cadastro, salvar, excluir).
Critérios de aceite:
- Mensagens devem ser visíveis, objetivas e contextualizadas à ação executada.

RF-14 — Persistência em Banco de Dados
Descrição: Todas as operações de CRUD de usuários e produtos devem refletir no banco de dados configurado.
Critérios de aceite:
- Conexão e parâmetros do banco devem ser lidos do arquivo de configuração do projeto.
- Operações devem falhar de forma controlada quando houver erro de conexão ou consulta.

RF-15 — Consistência de Dados
Descrição: O sistema deve validar dados de entrada e evitar inconsistências (ex.: campos obrigatórios, formatos válidos).
Critérios de aceite:
- Validações no backend devem impedir persistência de registros inválidos.

RF-16 — Navegação e Acessibilidade Básica
Descrição: O sistema deve prover navegação clara entre login, dashboard e telas de produtos.
Critérios de aceite:
- Links/menus devem permitir chegar às principais funcionalidades com poucos cliques.
