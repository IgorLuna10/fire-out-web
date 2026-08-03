<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // e.g. FO-A1B2C3D4
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('status')->default('pending'); // pending, confirmed, processing, completed, cancelled
            // customer info (for guests or snapshot of logged-in user)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('company')->nullable();
            // delivery address (snapshot at time of order)
            $table->string('ship_street')->nullable();
            $table->string('ship_number')->nullable();
            $table->string('ship_complement')->nullable();
            $table->string('ship_neighborhood')->nullable();
            $table->string('ship_city')->nullable();
            $table->string('ship_state', 2)->nullable();
            $table->string('ship_zipcode', 9)->nullable();
            // payment
            $table->string('payment_method')->nullable(); // cartao, pix, boleto
            $table->integer('installments')->default(1);
            // totals
            $table->decimal('subtotal', 10, 2)->default(0); // sum of priced items
            $table->boolean('has_consulta')->default(false); // true if any item is Sob Consulta
            // notes
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
