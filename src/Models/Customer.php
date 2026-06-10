<?php

namespace Whilesmart\Customers\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whilesmart\Contacts\Traits\HasContacts;
use Whilesmart\Customers\Database\Factories\CustomerFactory;
use Whilesmart\Customers\Enums\CustomerType;

class Customer extends Model
{
    use HasContacts, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $attributes = [
        'type' => 'individual',
    ];

    protected $casts = [
        'type' => CustomerType::class,
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function getTable(): string
    {
        return config('customers.table', 'customers');
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }
}
