<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('unit')->nullable();
            $table->integer('measurement_times')->nullable();
            $table->enum('measurement_span', ['hour', 'day', 'week', 'month'])->nullable();
            $table->float('threshold_min')->nullable();
            $table->float('threshold_max')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('fillable')->default(true);
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
        Schema::dropIfExists('parameters');
    }
}
