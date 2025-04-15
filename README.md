🚀 Deploy do Projeto Laravel com PostgreSQL via Docker
Este projeto utiliza Laravel com banco de dados PostgreSQL, gerenciado via Docker Compose. Este guia fornece os passos para preparar e subir o ambiente local.

✅ Pré-requisitos
PHP 8.1 ou superior

Composer

Docker e Docker Compose instalados

Laravel CLI instalado globalmente (opcional)

📦 Passo a Passo para subir o ambiente

1. Clone o projeto
   bash
   Copiar
   Editar
   git clone https://github.com/seu-usuario/seu-repositorio.git
   cd seu-repositorio
2. Instale as dependências do Laravel
   bash
   Copiar
   Editar
   composer install
3. Copie o arquivo .env
   bash
   Copiar
   Editar
   cp .env.example .env
4. Atualize as variáveis do .env com os dados do PostgreSQL
   env
   Copiar
   Editar
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=54320
   DB_DATABASE=db_senai
   DB_USERNAME=usr_senai
   DB_PASSWORD=usr_senai
5. Suba o container do PostgreSQL com Docker Compose
   bash
   Copiar
   Editar
   docker-compose up -d
6. Gere a chave da aplicação
   bash
   Copiar
   Editar
   php artisan key:generate
7. Rode as migrations do banco
   bash
   Copiar
   Editar
   php artisan migrate
8. (Opcional) Popular com seeders
   bash
   Copiar
   Editar
   php artisan db:seed
9. Acesse o sistema
   Se você estiver rodando localmente com Laravel serve:

bash
Copiar
Editar
php artisan serve
Acesse em: http://localhost:8000

🐘 Container PostgreSQL
O banco de dados estará disponível na porta 54320. Use um cliente como DBeaver, Postbird ou TablePlus para conectar, se necessário.

🧹 Comandos úteis
Parar os containers:

bash
Copiar
Editar
docker-compose down
Ver logs do container:

bash
Copiar
Editar
docker-compose logs -f
