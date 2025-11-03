<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            // Add priority column
            $table->enum('priority', ['laag', 'normaal', 'hoog', 'urgent'])->default('normaal')->after('type');

            // Add technician_notes column
            $table->text('technician_notes')->nullable()->after('notes');

            // Add costs column
            $table->decimal('costs', 10, 2)->nullable()->after('technician_notes');

            // Update existing enum columns if needed (optional)
            // If the enum values are different, you might need to modify them too
        });
    }

    public function down()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn(['priority', 'technician_notes', 'costs']);
        });
    }
};
