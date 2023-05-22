<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->text('name')->unique();
            $table->unsignedTinyInteger('floor')->default(1);
            $table->float('valve_value', 3, 2)->default(0);
            $table->dateTime('last_valve_value_sub')->nullable();
            $table->float('goal_temp', 3, 1)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
