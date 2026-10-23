# Book Registry API

A **Laravel 13** REST API for user-owned book records. The design follows **Clean Architecture** and pragmatic **DDD**, inspired by production patterns (layered use cases, repository contracts, JSON envelope responses).

## Requirements

- PHP 8.3+
- Composer
- MySQL or SQLite

## Setup

```bash
cd book-registry-api
composer install
cp .env.example .env   # if needed
php artisan key:generate
php artisan migrate
php artisan db:seed
```

Demo user after seeding:

| Field    | Value                    |
|----------|--------------------------|
| Email    | `demo@bookregistry.test` |
| Password | `password`               |

Run locally:

```bash
php artisan serve
```

API base URL: `http://127.0.0.1:8000/api`

## Swagger (OpenAPI)

With the server running, open the interactive UI:

**http://127.0.0.1:8000/api/documentation**

Suggested flow:

1. Call `POST /api/auth/login` with the demo user.
2. Copy `data.access_token` and click **Authorize**.
3. Enter `Bearer {your_token}` and try the **Books** endpoints.

OpenAPI annotations live under `app/Drivers/Http/Swagger/` (`darkaonline/l5-swagger`).

Regenerate the spec:

```bash
composer docs
# or: php artisan l5-swagger:generate
```

Useful `.env` keys:

```env
L5_SWAGGER_CONST_HOST=http://127.0.0.1:8000
L5_SWAGGER_GENERATE_ALWAYS=true
```

### MySQL

Example `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_registry
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

## Authentication

Email and password login; response includes a **Bearer** token (Laravel Sanctum).

```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "demo@bookregistry.test",
  "password": "password"
}
```

Protected routes: header `Authorization: Bearer {access_token}`.

| Method | Route               | Description              |
|--------|---------------------|--------------------------|
| POST   | `/api/auth/login`   | Login                    |
| POST   | `/api/auth/logout`  | Revoke current token     |
| GET    | `/api/auth/me`      | Current user             |
| GET    | `/api/books`        | List own books           |
| POST   | `/api/books`        | Create book              |
| GET    | `/api/books/{id}`   | Show book                |
| PUT    | `/api/books/{id}`   | Update book              |
| DELETE | `/api/books/{id}`   | Delete book              |

### Book payload

```json
{
  "name": "Domain-Driven Design",
  "isbn": "9780321125217",
  "value": 89.90
}
```

- `name`: required  
- `isbn`: digits only  
- `value`: decimal ≥ 0  

## JSON responses

Standard envelope:

```json
{
  "success": true,
  "message": "...",
  "data": { }
}
```

Validation failures return `422` with an `errors` object.

## Tests

```bash
php artisan test
```

## Architecture

```
app/
├── Domain/           # Entities, repository contracts, domain exceptions
├── Application/      # Use cases, DTOs
├── Infrastructure/   # Eloquent, mappers, IoC bindings
└── Drivers/          # HTTP (controllers, requests, resources)
```

### Key decisions

1. **Layers** — Domain at the center; controllers never use Eloquent; persistence behind `BookRepository`.
2. **Invokable use cases** — One class per action (`CreateBook`, `LoginUser`).
3. **Shared bases** — `BaseEntityRepository`, `BaseEloquentEntityRepository`, `BaseCrudEntityUseCase` to grow without CRUD duplication.
4. **Sanctum** — Right-sized for the assessment; OAuth2/Passport if the product grows.
5. **User scope** — `user_id` on books and `*ForUser` repository methods; generic `404` to avoid leaking other users’ records.
6. **`App\Models\User`** — Laravel auth model; business entities (`Book`) stay POPOs in Domain.

### Scaling notes

| Current              | As the product grows        |
|----------------------|-----------------------------|
| Single DB            | Replicas, list caching      |
| Sanctum tokens       | OAuth2, refresh-token flow  |
| No public signup     | Registration, email verify  |
| Digits-only ISBN     | ISBN-10/13 value object     |
| Simple pagination    | Filters, search, read models |

## Assumptions

- Users are created via seed/admin (no public registration endpoint).
- A user can only read and mutate **their own** books.
- Logout revokes only the current token (multiple sessions allowed).
- ISBN is stored as a numeric string without checksum validation.

## License

MIT
