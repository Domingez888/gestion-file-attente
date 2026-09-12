<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payements', function (Blueprint $table) {
            $table->string('reference')->nullable()->unique();
$table->string('flutterwave_charge_id')->nullable()->unique();
$table->string('flutterwave_customer_id')->nullable();
$table->string('flutterwave_payment_method_id')->nullable();
$table->string('devise', 10)->default('XAF');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::table('payements', function (Blueprint $table) {
    $table->dropColumn([
        'reference',
        'flutterwave_charge_id',
        'flutterwave_customer_id',
        'flutterwave_payment_method_id',
        'devise',
    ]);
});
    }
};
