# whilesmart/eloquent-customers

Polymorphic customer management for Laravel applications.

## Install

```bash
composer require whilesmart/eloquent-customers
php artisan migrate
```

## Use

Add `HasCustomers` to any model that should own customers (Workspace, Organization, User, etc.):

```php
use Whilesmart\Customers\Traits\HasCustomers;

class Workspace extends Model
{
    use HasCustomers;
}
```

The trait gives you a `morphMany` relation:

```php
$workspace->customers()->create([
    'name' => 'Acme Corp',
    'email' => 'billing@acme.com',
    'currency' => 'USD',
]);
```

## Endpoints

`GET    /api/customers` — list (filter by `owner_type` + `owner_id`, search via `?q=`)
`POST   /api/customers` — create
`GET    /api/customers/{id}` — show
`PUT    /api/customers/{id}` — update
`DELETE /api/customers/{id}` — soft delete

## Schema

`customers` table:

| column | type |
|---|---|
| id | bigint |
| owner_type / owner_id | morphs |
| name | string |
| email, phone, company_name, tax_id, website | nullable strings |
| billing_address, shipping_address, notes | text |
| currency | char(3) |
| is_active | boolean |
| metadata | json |
| timestamps + soft deletes | |

## Config

Publish with `php artisan vendor:publish --tag=customers-config`. Override `register_routes`, `route_prefix`, `route_middleware`, and `table` via env or the published config file.
