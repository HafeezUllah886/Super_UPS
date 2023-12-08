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
        Schema::create('amount_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendorID')->constrained('accounts', 'id');
            $table->unsignedBigInteger('customer')->nullable();
            $table->unsignedBigInteger('account')->nullable();
            $table->foreignId('productID')->constrained('products', 'id');
            $table->string('walkin')->nullable();
            $table->date('date');
            $table->integer('qty');
            $table->integer('amount');
            $table->text('reason')->nullable();
            $table->string('status');
            $table->string('payment_status');
            $table->integer('ref');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amount_claims');
    }
};
