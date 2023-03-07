<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trip_station', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trip_id');
            $table->foreign('trip_id')
                ->references('id')->on('trips')->onDelete('cascade');

            $table->unsignedBigInteger('from_station');
            $table->foreign('from_station')
                ->references('id')->on('stations');

            $table->unsignedBigInteger('to_station');
            $table->foreign('to_station')
                ->references('id')->on('stations');

            $table->integer('num_reservation')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_station');
    }
};
