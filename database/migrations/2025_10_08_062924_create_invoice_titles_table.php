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
        Schema::create('invoice_titles', function (Blueprint $table) {
            $table->id();

            // Foreign key to users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            // Invoice field titles
            $table->string('invoice_number_title')->default('Invoice #');
            $table->string('invoice_date_title')->default('Date');
            $table->string('payment_terms_title')->default('Payment Terms');
            $table->string('due_date_title')->default('Due Date');
            $table->string('po_number_title')->default('PO Number');
            $table->string('bill_to_title')->default('Bill To');
            $table->string('ship_to_title')->default('Ship To');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_titles');
    }
};
