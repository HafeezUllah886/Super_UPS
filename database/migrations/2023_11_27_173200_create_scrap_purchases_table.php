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
        Schema::create('scrap_purchases', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string("customerName")->default("Walkin Customer");
            $table->float('weight', 10,2);
            $table->float("rate", 10);
            $table->text("desc")->nullable();
            $table->integer("ref");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrap_purchases');
    }
};
