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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();

            $table->unsignedBigInteger('start_station');
            $table->foreign('start_station')
                ->references('id')->on('stations')->onDelete('cascade');

            $table->unsignedBigInteger('end_station');
            $table->foreign('end_station')
                ->references('id')->on('stations')->onDelete('cascade');

            $table->unsignedBigInteger('bus_id');
            $table->foreign('bus_id')
                ->references('id')->on('buses');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
