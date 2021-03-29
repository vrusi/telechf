<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('parameter_id')->constrained();
            $table->integer('measurement_times')->nullable();
            $table->enum('measurement_span', ['hour', 'day', 'week', 'month'])->nullable();
            $table->float('threshold_safety_min')->nullable();
            $table->float('threshold_safety_max')->nullable();
            $table->float('threshold_therapeutic_min')->nullable();
            $table->float('threshold_therapeutic_max')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_parameters');
    }
}
