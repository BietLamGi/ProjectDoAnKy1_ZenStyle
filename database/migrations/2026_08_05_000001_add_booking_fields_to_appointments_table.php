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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('fullname')->after('id');
            $table->string('phone')->after('fullname');
            $table->string('service')->after('phone');
            $table->date('appointment_date')->nullable()->after('service');
            $table->string('appointment_time')->nullable()->after('appointment_date');
            $table->text('note')->nullable()->after('appointment_time');
            $table->string('status')->default('pending')->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'fullname',
                'phone',
                'service',
                'appointment_date',
                'appointment_time',
                'note',
                'status',
            ]);
        });
    }
};
