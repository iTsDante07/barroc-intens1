<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('user_id')->constrained(); // Sales medewerker
            $table->string('quote_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['concept', 'verzonden', 'geaccepteerd', 'afgewezen'])->default('concept');
            $table->date('valid_until');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('quote_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quote_product');
        Schema::dropIfExists('quotes');
    }
};
