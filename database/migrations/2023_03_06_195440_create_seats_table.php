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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unique();

            $table->unsignedBigInteger('bus_id');
            $table->foreign('bus_id')
                ->references('id')->on('buses')->onDelete('cascade');

            $table->tinyInteger('status')->default(0)->comment('1 => busy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
