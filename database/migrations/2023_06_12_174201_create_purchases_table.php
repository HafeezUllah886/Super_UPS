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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor');
            $table->unsignedBigInteger('paidFrom')->nullable();
            $table->date('date');
            $table->text('desc');
            $table->string('isPaid');
            $table->foreign('vendor')->references('id')->on('accounts');
            $table->foreign('paidFrom')->references('id')->on('accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
