<?php

namespace Tests\Feature;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Whilesmart\Customers\Models\Customer;
use Whilesmart\OwnerAccess\Contracts\OwnerAuthorizer;

class CustomerAuthorizationTest extends TestCase
{
    private const OWNER = 'App\\Models\\Workspace';

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(OwnerAuthorizer::class, new class implements OwnerAuthorizer
        {
            public function authorize(?Authenticatable $user, string $ownerType, mixed $ownerId): bool
            {
                return false;
            }

            public function scope(Builder $query, ?Authenticatable $user, string $ownerTypeColumn = 'owner_type', string $ownerIdColumn = 'owner_id'): Builder
            {
                return $query->whereRaw('0 = 1');
            }
        });
    }

    #[Test]
    public function store_is_forbidden_when_authorizer_denies(): void
    {
        $this->postJson('/api/customers', [
            'owner_type' => self::OWNER,
            'owner_id' => 1,
            'name' => 'Hijacked',
        ])->assertForbidden();

        $this->assertDatabaseCount('customers', 0);
    }

    #[Test]
    public function show_update_destroy_are_forbidden_when_authorizer_denies(): void
    {
        $customer = Customer::create(['owner_type' => self::OWNER, 'owner_id' => 1, 'name' => 'Private']);

        $this->getJson("/api/customers/{$customer->id}")->assertForbidden();
        $this->putJson("/api/customers/{$customer->id}", ['name' => 'Hijacked'])->assertForbidden();
        $this->deleteJson("/api/customers/{$customer->id}")->assertForbidden();

        $this->assertSame('Private', $customer->fresh()->name);
    }

    #[Test]
    public function index_returns_nothing_when_scope_denies(): void
    {
        Customer::create(['owner_type' => self::OWNER, 'owner_id' => 1, 'name' => 'Private']);

        $this->getJson('/api/customers')
            ->assertOk()
            ->assertJsonPath('data.meta.total', 0);
    }
}
