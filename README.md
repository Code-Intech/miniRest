# miniRest

## Router:

- Definição de rotas com diferentes métodos HTTP (GET, POST, PUT, DELETE, PATCH).
- Suporte a parâmetros na URL.
- Uso de Expressions Regulares para mapear URL com parâmetros.
- Middleware para executar código antes de chegar ao controller.
- Suporte para vários middlewares encadeados em uma rota.
- Implementação de grupos de rotas para definir middlewares e prefixos.
- Sistema de autenticação baseado em token JWT.
- Autenticação via Middleware.
- MiddlewarePipeline para execução ordenada de middlewares.
- Paginação de resultados usando Eloquent ORM.
  
## Controller:

- BaseController com métodos comuns a serem utilizados pelos controllers.
- Extensão de BaseController para os controllers específicos.
- Tratamento da lógica de negócios em métodos de controllers.

## Request:

- Classe Request para encapsular dados da requisição HTTP.
Tratamento de parâmetros de rota e corpo da requisição.
Response:

- Classe Response para gerar respostas HTTP em formato JSON.

## Model:

- Utilização do Eloquent ORM para interação com o banco de dados.
- Definição de models para mapear tabelas do banco de dados.

## Validação de Dados:

- Implementação de um sistema de validação de dados para requisições.
- Criação de regras de validação personalizadas.
- Geração de mensagens de erro de validação.

## Middlewares:

- Criação de middlewares para aplicar lógica antes da execução dos controllers.
- Uso de um MiddlewarePipeline para executar middlewares em ordem.

## Autenticação e Autorização:

- Implementação de autenticação baseada em tokens JWT.
- Middleware de autenticação para proteger rotas.
- Sistema de autorização em nível de controllers.

## CORS:

- Implementação de suporte a CORS (Cross-Origin Resource Sharing) para permitir requisições de origens diferentes.

## Pagination:

- Adição de funcionalidade de paginação para lidar com grandes conjuntos de resultados.

## CLI:

- Criação de um script de linha de comando para inicialização do servidor.

## Config
- Configuração com .env:
- Implementação de um sistema para carregar configurações de ambiente a partir de um arquivo .env.