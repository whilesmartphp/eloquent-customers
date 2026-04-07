<?php

return [
    'register_routes' => env('CUSTOMERS_REGISTER_ROUTES', true),
    'route_prefix' => env('CUSTOMERS_ROUTE_PREFIX', 'api'),
    'route_middleware' => ['api', 'auth:sanctum'],
    'table' => env('CUSTOMERS_TABLE', 'customers'),
];
