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
        Schema::create('outbounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('picking_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('warehouse')->nullable();
            $table->string('order_number')->nullable();
            $table->string('order_name')->nullable();
            $table->string('carrier')->nullable();
            $table->integer('boxes')->nullable();
            $table->string('status')->nullable();
            $table->integer('requested_quantity')->nullable();
            $table->integer('dispatched_quantity')->nullable();
            $table->string('guide')->nullable();
            $table->string('truck_status')->nullable();
            $table->string('client')->nullable();
            $table->float('length')->nullable();
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->float('volume')->nullable();
            $table->float('weight')->nullable();
            $table->dateTime('order_date')->nullable();
            $table->dateTime('packing_date')->nullable();
            $table->dateTime('dispatch_date')->nullable();
            $table->dateTime('delivered_date')->nullable();
            $table->dateTime('shipping_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbounds');
    }
};
