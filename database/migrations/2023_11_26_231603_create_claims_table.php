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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salesID')->constrained('sales', 'id');
            $table->foreignId('productID')->constrained('products', 'id');
            $table->date('date');
            $table->integer('qty');
            $table->text('reason')->nullable();
            $table->string('status');
            $table->integer('ref');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
