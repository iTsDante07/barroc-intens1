<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Verwijder eerst de bestaande customers tabel als die problemen geeft
        Schema::dropIfExists('customers');

        // Maak de tabel opnieuw aan met de juiste structuur
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->string('postal_code');
            $table->boolean('bkr_checked')->default(false);
            $table->boolean('bkr_approved')->default(false);
            $table->text('bkr_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
