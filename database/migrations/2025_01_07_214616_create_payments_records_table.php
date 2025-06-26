<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments_records', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3);
            $table->dateTime('payed_date');
            $table->enum('status', ['CREATED', 'DELIVERED']);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('dni', 20);
            $table->uuid('order_id');
            $table->uuid('payments_schedule_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments_records');
    }
};
