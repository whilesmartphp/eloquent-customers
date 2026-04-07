<?php

namespace Whilesmart\Customers\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Whilesmart\Customers\Models\Customer;

trait HasCustomers
{
    public function customers(): MorphMany
    {
        return $this->morphMany(Customer::class, 'owner');
    }
}
