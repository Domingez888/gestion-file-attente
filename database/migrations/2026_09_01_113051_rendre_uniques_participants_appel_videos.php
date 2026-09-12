<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appel_videos', function (Blueprint $table) {
            $table->unique('client_id');
            $table->unique('employe_id');
        });
    }

    public function down(): void
    {
        Schema::table('appel_videos', function (Blueprint $table) {
            $table->dropUnique(['client_id']);
            $table->dropUnique(['employe_id']);
        });
    }
};