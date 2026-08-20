# API REST — Gestão de Funcionários

API RESTful para gestão de funcionários, departamentos e usuários, construída com Laravel 13 e autenticação via Laravel Sanctum.

## Tecnologias

- **PHP** 8.3+
- **Laravel** 13.x
- **Laravel Sanctum** 4.x (autenticação via tokens)
- **Banco de dados**: MySQL / SQLite

## Instalação

```bash
# Instalar dependências
composer install

# Configurar ambiente
cp .env.example .env
php artisan key:generate

# Rodar migrações
php artisan migrate

# Iniciar servidor
php artisan serve
```

## Autenticação

Todas as rotas de CRUD exigem autenticação via Bearer Token.

### Registrar usuário

```
POST /api/register
```

| Campo      | Tipo   | Regras                  |
|------------|--------|-------------------------|
| `name`     | string | obrigatório             |
| `email`    | email  | obrigatório, único      |
| `password` | string | obrigatório, mín. 6     |

**Resposta (201):**
```json
{
  "user": { "id": 1, "name": "João", "email": "joao@email.com" },
  "token": "1|abc123..."
}
```

### Iniciar sessão

```
POST /api/login
```

| Campo      | Tipo   | Regras            |
|------------|--------|-------------------|
| `email`    | email  | obrigatório       |
| `password` | string | obrigatório       |

**Resposta (200):**
```json
{
  "token": "1|abc123..."
}
```

### Usar o token

Envie no cabeçalho de todas as requisições autenticadas:

```
Authorization: Bearer <token>
```

---

## Endpoints

Base URL: `http://localhost:8000/api`

Todos os endpoints abaixo requerem autenticação.

---

### Usuários

| Método | Rota                        | Descrição                    |
|--------|-----------------------------|------------------------------|
| GET    | `/users`                    | Listar usuários (paginado)   |
| GET    | `/users/{id}`               | Detalhar usuário             |
| POST   | `/users`                    | Criar usuário                |
| PUT    | `/users/{id}`               | Atualizar usuário            |
| DELETE | `/users/{id}`               | Mover para lixeira           |
| GET    | `/users/trashed/list`       | Listar usuários na lixeira   |
| PUT    | `/users/{id}/restore`       | Restaurar usuário            |
| DELETE | `/users/{id}/force-delete`  | Eliminar definitivamente     |

**Campos — Usuário:**

| Campo      | Tipo   | Regras                              |
|------------|--------|-------------------------------------|
| `name`     | string | obrigatório                         |
| `email`    | email  | obrigatório, único                  |
| `password` | string | obrigatório no POST, opcional no PUT|

---

### Funcionários

| Método | Rota                             | Descrição                         |
|--------|----------------------------------|-----------------------------------|
| GET    | `/funcionarios`                  | Listar funcionários (paginado)    |
| GET    | `/funcionarios/{id}`             | Detalhar funcionário              |
| POST   | `/funcionarios`                  | Criar funcionário                 |
| PUT    | `/funcionarios/{id}`             | Atualizar funcionário             |
| DELETE | `/funcionarios/{id}`             | Mover para lixeira                |
| GET    | `/funcionarios/trashed/list`     | Listar funcionários na lixeira    |
| PUT    | `/funcionarios/{id}/restore`     | Restaurar funcionário             |
| DELETE | `/funcionarios/{id}/force-delete`| Eliminar definitivamente          |

**Campos — Funcionário:**

| Campo              | Tipo    | Regras                                         |
|--------------------|---------|------------------------------------------------|
| `nome`             | string  | obrigatório                                    |
| `email`            | email   | obrigatório, único                             |
| `salario`          | decimal | obrigatório, mínimo 50.000 Kz                  |
| `data_nascimento`  | date    | obrigatório, funcionário deve ter ≥ 18 anos    |
| `department_id`    | integer | obrigatório, deve existir na tabela departamentos |
| `image`            | file    | opcional, jpg/jpeg/png/webp, máx. 2MB          |

**Exemplo de payload (POST/PUT):**
```json
{
  "nome": "Carlos Mendes",
  "email": "carlos@email.com",
  "salario": 75000,
  "data_nascimento": "1998-06-15",
  "department_id": 1
}
```

> O campo `image` deve ser enviado como `multipart/form-data`.

---

### Departamentos

| Método | Rota                              | Descrição                          |
|--------|-----------------------------------|------------------------------------|
| GET    | `/departamento`                   | Listar departamentos (paginado)    |
| GET    | `/departamento/{id}`              | Detalhar departamento              |
| POST   | `/departamento`                   | Criar departamento                 |
| PUT    | `/departamento/{id}`              | Atualizar departamento             |
| DELETE | `/departamento/{id}`              | Mover para lixeira                 |
| GET    | `/departamento/trashed/list`      | Listar departamentos na lixeira    |
| PUT    | `/departamento/{id}/restore`      | Restaurar departamento             |
| DELETE | `/departamento/{id}/force-delete` | Eliminar definitivamente           |

**Campos — Departamento:**

| Campo       | Tipo   | Regras        |
|-------------|--------|---------------|
| `nome`      | string | obrigatório   |
| `descricao` | string | opcional      |

---

## Banco de Dados

### Tabela `users`

| Coluna            | Tipo         | Descrição              |
|-------------------|--------------|------------------------|
| `id`              | bigint (PK)  | Identificador único    |
| `name`            | string       | Nome do usuário        |
| `email`           | string       | Email (único)          |
| `password`        | string       | Senha (hash)           |
| `created_at`      | timestamp    | Data de criação        |
| `updated_at`      | timestamp    | Data de atualização    |
| `deleted_at`      | timestamp    | Soft delete            |

### Tabela `funcionarios`

| Coluna             | Tipo         | Descrição                          |
|--------------------|--------------|------------------------------------|
| `id`               | bigint (PK)  | Identificador único                |
| `user_id`          | bigint (FK)  | Referência ao usuário (cascata)    |
| `department_id`    | bigint (FK)  | Referência ao departamento          |
| `nome`             | string       | Nome do funcionário                |
| `email`            | string       | Email do funcionário               |
| `salario`          | decimal      | Salário em Kz (≥ 50.000)          |
| `data_nascimento`  | date         | Data de nascimento (≥ 18 anos)     |
| `image`            | string       | Caminho da imagem (opcional)       |
| `created_at`       | timestamp    | Data de criação                    |
| `updated_at`       | timestamp    | Data de atualização                |
| `deleted_at`       | timestamp    | Soft delete                        |

### Tabela `departamentos`

| Coluna       | Tipo         | Descrição              |
|--------------|--------------|------------------------|
| `id`         | bigint (PK)  | Identificador único    |
| `nome`       | string       | Nome do departamento    |
| `descricao`  | string       | Descrição (opcional)   |
| `created_at` | timestamp    | Data de criação        |
| `updated_at` | timestamp    | Data de atualização    |
| `deleted_at` | timestamp    | Soft delete            |

### Relacionamentos

```
User  ──(1:N)──  Funcionario  ──(N:1)──  Departamento
```

- Um **User** pode ter vários **Funcionários**.
- Um **Departamento** pode ter vários **Funcionários**.
- Ao eliminar um User, os seus Funcionários são eliminados em cascata.
- Ao eliminar um Departamento, a operação é bloqueada se tiver Funcionários associados.

---

## Funcionalidades

- **CRUD completo** para Usuários, Funcionários e Departamentos
- **Autenticação** via Laravel Sanctum (tokens Bearer)
- **Soft Delete** — registros são movidos para lixeira antes de serem eliminados
- **Lixeira** — visualizar, restaurar ou eliminar definitivamente
- **Upload de imagem** para avatar do funcionário
- **Validação robusta** de dados em todas as requisições
- **Paginação** em todas as listagens (5 itens por página)
- **Transações de banco** — operações CRUD executadas dentro de transações
