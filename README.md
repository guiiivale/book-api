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

## Rate limiting (login & public auth)

Two layers protect public auth endpoints:

1. **Failed login lockout** — After 5 failed attempts for the same email + IP (`LOGIN_MAX_ATTEMPTS`, `LOGIN_DECAY_SECONDS`), the API returns **429** with `Retry-After`. A successful login clears the counter.
2. **Route throttle** — `POST /api/auth/login` is limited to **20 requests/minute per IP** (`PUBLIC_AUTH_REQUESTS_PER_MINUTE`) via middleware.

The same `AuthenticationThrottle` contract supports a future **register** flow (`auth_limits.register.*`) without changing the architecture.

## Client apps (web, mobile) & CORS

Rate limiting uses the **caller's IP** (`$request->ip()`), not a configured “frontend server IP”. Each phone or browser sends its own address. Behind a reverse proxy, set `TRUSTED_PROXIES` so Laravel reads `X-Forwarded-For` correctly.

**Browser / SPA (multiple sites):** list every origin in `.env`:

```env
APP_CLIENT_ORIGINS=https://app.example.com,https://www.example.com,http://localhost:3000
```

Optional wildcards for subdomains:

```env
APP_CLIENT_ORIGIN_PATTERNS=#^https://.*\.example\.com$#
```

These values drive `config/cors.php`. Sanctum stateful domains are derived from the same origins unless you override `SANCTUM_STATEFUL_DOMAINS`.

**Native mobile** apps call the API with Bearer tokens; CORS does not apply. No extra IP config is required—use the public `APP_URL` of the API in the app.

**Production example:**

```env
APP_URL=https://api.example.com
TRUSTED_PROXIES=*
APP_CLIENT_ORIGINS=https://web.example.com,https://admin.example.com
L5_SWAGGER_CONST_HOST=https://api.example.com
```

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
