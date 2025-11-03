<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('assigned_to')->constrained('users'); // Monteur
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['periodiek', 'reparatie', 'installatie']);
            $table->enum('status', ['gepland', 'in_uitvoering', 'voltooid', 'geannuleerd'])->default('gepland');
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenances');
    }
};
