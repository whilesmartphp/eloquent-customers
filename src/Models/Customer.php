<?php

namespace Whilesmart\Customers\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whilesmart\Customers\Database\Factories\CustomerFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
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
