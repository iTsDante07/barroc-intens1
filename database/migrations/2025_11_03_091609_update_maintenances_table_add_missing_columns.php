<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->enum('priority', ['laag', 'normaal', 'hoog', 'urgent'])->default('normaal')->after('type');

            $table->text('technician_notes')->nullable()->after('notes');

            $table->decimal('costs', 10, 2)->nullable()->after('technician_notes');

        });
    }

    public function down()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn(['priority', 'technician_notes', 'costs']);
        });
    }
};
