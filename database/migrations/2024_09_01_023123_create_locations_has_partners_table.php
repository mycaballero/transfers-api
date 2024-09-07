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
        Schema::create('locations_has_partners', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('location_id');
            $table->bigInteger('partner_id');
            $table->boolean('principal')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations_has_partners');
    }
};
