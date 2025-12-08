<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lease_contract_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lease_contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('description');
            $table->enum('type', ['machine', 'coffee', 'service']);
            $table->integer('quantity')->default(1);
            $table->integer('coffee_bags_per_month')->nullable(); // voor koffiebonen
            $table->decimal('monthly_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lease_contract_items');
    }
};
