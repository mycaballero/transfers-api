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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('address')->nullable();
            $table->foreignId('city_id')->nullable();
            $table->double('credit')->nullable();
            $table->double('credit_limit')->nullable();
            $table->string('display_name');
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->bigInteger('parenthood_id')->nullable();
            $table->string('phone');
            $table->bigInteger('state_id');
            $table->boolean('use_partner_credit_limit')->nullable();
            $table->string('vat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
