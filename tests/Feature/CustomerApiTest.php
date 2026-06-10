<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Whilesmart\Customers\Models\Customer;

class CustomerApiTest extends TestCase
{
    private const OWNER = 'App\\Models\\Workspace';

    #[Test]
    public function it_creates_a_customer(): void
    {
        $this->postJson('/api/customers', [
            'owner_type' => self::OWNER,
            'owner_id' => 1,
            'name' => 'Acme Corp',
            'email' => 'ap@acme.test',
        ])->assertCreated()
            ->assertJsonPath('data.name', 'Acme Corp');

        $this->assertDatabaseHas('customers', [
            'owner_type' => self::OWNER,
            'owner_id' => 1,
            'name' => 'Acme Corp',
        ]);
    }

    #[Test]
    public function it_lists_customers_filtered_by_owner(): void
    {
        Customer::create(['owner_type' => self::OWNER, 'owner_id' => 1, 'name' => 'Mine']);
        Customer::create(['owner_type' => self::OWNER, 'owner_id' => 2, 'name' => 'Theirs']);

        $this->getJson('/api/customers?owner_type='.urlencode(self::OWNER).'&owner_id=1')
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.name', 'Mine');
    }

    #[Test]
    public function it_searches_customers_case_insensitively(): void
    {
        Customer::create(['owner_type' => self::OWNER, 'owner_id' => 1, 'name' => 'Maison Amani']);

        $this->getJson('/api/customers?q=maison')
            ->assertOk()
            ->assertJsonPath('data.data.0.name', 'Maison Amani');
    }

    #[Test]
    public function it_shows_updates_and_deletes_a_customer(): void
    {
        $customer = Customer::create(['owner_type' => self::OWNER, 'owner_id' => 1, 'name' => 'Acme']);

        $this->getJson("/api/customers/{$customer->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $customer->id);

        $this->putJson("/api/customers/{$customer->id}", ['name' => 'Acme Inc'])
            ->assertOk()
            ->assertJsonPath('data.name', 'Acme Inc');

        $this->deleteJson("/api/customers/{$customer->id}")->assertOk();
        $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    }
}
