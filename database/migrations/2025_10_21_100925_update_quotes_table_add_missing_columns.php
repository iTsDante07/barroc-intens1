<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Voeg ontbrekende kolommen toe als ze nog niet bestaan
            if (!Schema::hasColumn('quotes', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('quote_number');
            }

            if (!Schema::hasColumn('quotes', 'vat_amount')) {
                $table->decimal('vat_amount', 10, 2)->default(0)->after('subtotal');
            }

            if (!Schema::hasColumn('quotes', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0)->after('vat_amount');
            }

            if (!Schema::hasColumn('quotes', 'status')) {
                $table->enum('status', ['concept', 'verzonden', 'geaccepteerd', 'afgewezen'])->default('concept')->after('total_amount');
            }

            if (!Schema::hasColumn('quotes', 'valid_until')) {
                $table->date('valid_until')->after('status');
            }

            if (!Schema::hasColumn('quotes', 'notes')) {
                $table->text('notes')->nullable()->after('valid_until');
            }

            if (!Schema::hasColumn('quotes', 'terms')) {
                $table->text('terms')->nullable()->after('notes');
            }
        });
    }

    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'vat_amount', 'total_amount', 'status', 'valid_until', 'notes', 'terms']);
        });
    }
};
