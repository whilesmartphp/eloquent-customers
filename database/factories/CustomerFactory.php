<?php

namespace Whilesmart\Customers\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Whilesmart\Customers\Models\Customer;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => 'individual',
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'tax_id' => $this->faker->numerify('TAX-########'),
            'billing_address' => $this->faker->address(),
            'currency' => 'USD',
            'is_active' => true,
        ];
    }
}
