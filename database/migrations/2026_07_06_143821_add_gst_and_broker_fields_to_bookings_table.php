<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('agreement_date')->nullable();
            $table->date('registration_date')->nullable();
            $table->foreignId('broker_id')->nullable()->constrained('brokers')->nullOnDelete();
            $table->decimal('sale_rate_per_sqft', 15, 2)->nullable();
            $table->string('gst_behavior', 20)->default('none'); // none, inclusive, exclusive
            $table->decimal('gst_amount', 15, 2)->default(0.00);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['broker_id']);
            $table->dropColumn([
                'agreement_date',
                'registration_date',
                'broker_id',
                'sale_rate_per_sqft',
                'gst_behavior',
                'gst_amount',
            ]);
        });
    }
};
