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
        Schema::create('invoice_keys', function (Blueprint $table) {
            $table->id();
            
            $table->string('invoice_number_key');  // not nullable
            $table->string('invoice_date_key');
            $table->string('payment_terms_key');
            $table->string('due_date_key');
            $table->string('po_number_key');
            $table->string('bill_to_key');
            $table->string('ship_to_key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_keys');
    }
};
