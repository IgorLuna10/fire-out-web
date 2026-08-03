<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique(); // matches JS catalogue key, e.g. 'mangueira-t1-15'
            $table->string('name');
            $table->string('category');
            $table->decimal('price', 10, 2)->nullable(); // null = Sob Consulta
            $table->integer('stock_qty')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
