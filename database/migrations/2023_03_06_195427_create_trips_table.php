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

            $table->unsignedBigInteger('from_city_id');
            $table->foreign('from_city_id')
                ->references('id')->on('cities')->onDelete('cascade');

            $table->unsignedBigInteger('to_city_id');
            $table->foreign('to_city_id')
                ->references('id')->on('cities')->onDelete('cascade');

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
