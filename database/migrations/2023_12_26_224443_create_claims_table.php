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
            $table->string("customer");
            $table->foreignId("return_product")->constrained('products', 'id');
            $table->integer("return_qty");
            $table->foreignId("issue_product")->constrained('products', 'id');
            $table->integer("issue_qty");
            $table->foreignId("vendor")->constrained('accounts', 'id');
            $table->float("amount");
            $table->date('date');
            $table->text("notes")->nullable();
            $table->integer("ref");
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
