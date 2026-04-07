<?php

use Illuminate\Support\Facades\Route;
use Whilesmart\Customers\Http\Controllers\CustomerController;

Route::apiResource('customers', CustomerController::class);
