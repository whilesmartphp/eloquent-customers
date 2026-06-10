<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = config('customers.table', 'customers');

        Schema::table($table, function (Blueprint $table) {
            $table->string('type')->default('individual')->after('owner_id');
        });

        Schema::table($table, function (Blueprint $table) {
            $table->dropColumn('company_name');
        });
    }

    public function down(): void
    {
        Schema::table(config('customers.table', 'customers'), function (Blueprint $table) {
            $table->string('company_name')->nullable();
            $table->dropColumn('type');
        });
    }
};
