<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Whilesmart\Customers\Enums\CustomerType;
use Whilesmart\Customers\Models\Customer;

class CustomerContactsTest extends TestCase
{
    private const OWNER = 'App\\Models\\Workspace';

    #[Test]
    public function it_stores_and_casts_the_customer_type(): void
    {
        $this->postJson('/api/customers', [
            'owner_type' => self::OWNER,
            'owner_id' => 1,
            'name' => 'Acme Corp',
            'type' => 'organization',
        ])->assertCreated()->assertJsonPath('data.type', 'organization');

        $this->assertSame(CustomerType::Organization, Customer::first()->type);
    }

    #[Test]
    public function it_defaults_the_type_to_individual(): void
    {
        $this->postJson('/api/customers', [
            'owner_type' => self::OWNER,
            'owner_id' => 1,
            'name' => 'Jane Doe',
        ])->assertCreated()->assertJsonPath('data.type', 'individual');
    }

    #[Test]
    public function a_customer_has_contacts_with_a_single_primary(): void
    {
        $customer = Customer::create([
            'owner_type' => self::OWNER,
            'owner_id' => 1,
            'name' => 'Acme Corp',
            'type' => 'organization',
        ]);

        $customer->contacts()->create([
            'owner_type' => self::OWNER,
            'owner_id' => 1,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@acme.test',
            'is_primary' => true,
        ]);
        $customer->contacts()->create([
            'owner_type' => self::OWNER,
            'owner_id' => 1,
            'first_name' => 'Bob',
            'email' => 'bob@acme.test',
        ]);

        $this->assertCount(2, $customer->contacts);
        $this->assertSame('Jane', $customer->primaryContact->first_name);
        $this->assertSame('jane@acme.test', $customer->primaryContact->email);
    }
}
