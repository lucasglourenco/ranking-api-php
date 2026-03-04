# Ranking API PHP

API simples para geração de ranking de recordes pessoais por movimento.

## Requisitos

- PHP **>= 8.1**
- **Docker** e **Docker Compose**
- **Composer**

## Setup

1. Subir banco:
   docker compose up -d

2. Instalar dependências:
   composer install

3. Adicionar `.env`:
   composer env

4. Rodar migrations:
   composer migrate

5. Rodar aplicação:
   php -S localhost:8000 -t public


## Exemplo 

- Busca por ID: 
http://localhost:8000/ranking/1
- Busca por nome: http://localhost:8000/ranking/Bench Press

## Bibliotecas Utilizadas

- **nikic/fast-route** — biblioteca leve para roteamento HTTP.
- **nyholm/psr7** — implementação das interfaces PSR-7 para manipulação de requests e responses.
- **nyholm/psr7-server** — criação de objetos PSR-7 a partir do ambiente PHP.
- **vlucas/phpdotenv** — carregamento de variáveis de ambiente através do `.env`.
- **PDO (ext-pdo)** — acesso ao banco de dados utilizando prepared statements.

Essas bibliotecas ajudam a manter a aplicação desacoplada e alinhada com padrões PHP modernos.

## Decisões Técnicas

- Implementação de um **Query Builder simples** para construção de queries SQL e melhor organização do acesso ao banco.

- Uso da classe **Condition** para padronizar cláusulas `WHERE`, evitando concatenação direta de SQL.

- Implementação de **migrations próprias**, responsáveis por:
   - criação do banco
   - criação das tabelas
   - inserção de dados iniciais

- Utilização de **Window Functions (`RANK()`)** para cálculo de ranking com suporte a empates.
- Utilização de prepared statements via PDO para prevenção de SQL Injection.

## Próximos Passos

Algumas melhorias que poderiam ser implementadas:

- Adicionar **autenticação na API** para proteger os endpoints.
- Separar **seeders das migrations**, isolando a responsabilidade de criação de dados iniciais.
- Criar **constantes para nomes de tabelas e colunas**, evitando strings espalhadas nas queries.
- Implementar **controle de migrations executadas** através de uma tabela `migrations`.

## Observação

As credenciais do banco estão definidas diretamente no
docker-compose.yml apenas para simplificar a execução do teste técnico.
Em um ambiente de produção, essas configurações deveriam ser
externalizadas em variáveis de ambiente.