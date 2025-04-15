# 🚀 Deploy do Projeto Laravel com PostgreSQL via Docker

Este projeto utiliza Laravel com banco de dados PostgreSQL, gerenciado via Docker Compose. Este guia fornece os passos para preparar e subir o ambiente local.

---

## ✅ Pré-requisitos

-   PHP 8.1 ou superior
-   Composer
-   Docker e Docker Compose instalados
-   Laravel CLI instalado globalmente (opcional)

---

## 📦 Passo a Passo para subir o ambiente

### 1. Clone o projeto

```bash
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio
```

### 2. Instale as dependências do Laravel

```bash
composer install
```

### 3. Copie o arquivo `.env`

```bash
cp .env.example .env
```

### 4. Atualize as variáveis do `.env` com os dados do PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=54320
DB_DATABASE=db_senai
DB_USERNAME=usr_senai
DB_PASSWORD=usr_senai
```

---

### 5. Suba o container do PostgreSQL com Docker Compose

```bash
docker-compose up -d
```

---

### 6. Gere a chave da aplicação

```bash
php artisan key:generate
```

---

### 7. Rode as migrations do banco

```bash
php artisan migrate
```

---

### 8. (Opcional) Popular com seeders

```bash
php artisan db:seed
```

---

### 9. Acesse o sistema

Se você estiver rodando localmente com Laravel serve:

```bash
php artisan serve
```

Acesse em: [http://localhost:8000](http://localhost:8000)

---

## 🐘 Container PostgreSQL

O banco de dados estará disponível na porta `54320`.  
Use um cliente como DBeaver, Postbird ou TablePlus para conectar, se necessário:

-   **Host:** `127.0.0.1`
-   **Porta:** `54320`
-   **Usuário:** `usr_senai`
-   **Senha:** `usr_senai`
-   **Banco:** `db_senai`

---

## 🧹 Comandos úteis

-   Parar os containers:

```bash
docker-compose down
```

-   Ver logs do container:

```bash
docker-compose logs -f
```

---

## 📄 Licença

Este projeto é open-source e está sob a licença [MIT](LICENSE).
