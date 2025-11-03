<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Controleer en voeg ontbrekende kolommen toe
        if (!Schema::hasColumn('customers', 'bkr_checked')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->boolean('bkr_checked')->default(false)->after('postal_code');
            });
        }

        if (!Schema::hasColumn('customers', 'bkr_approved')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->boolean('bkr_approved')->default(false)->after('bkr_checked');
            });
        }

        if (!Schema::hasColumn('customers', 'bkr_notes')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->text('bkr_notes')->nullable()->after('bkr_approved');
            });
        }
    }

    public function down()
    {
        // Optioneel: verwijder de kolommen als je wilt rollback
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['bkr_checked', 'bkr_approved', 'bkr_notes']);
        });
    }
};
