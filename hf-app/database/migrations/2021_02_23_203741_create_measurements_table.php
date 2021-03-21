<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('parameter_id')->constrained();
            $table->float('value');
            $table->integer('swellings')->default(1);
            $table->integer('exercise_tolerance')->default(1);
            $table->integer('dyspnoea')->default(1);
            $table->boolean('triggered_safety_alarm')->default(false);
            $table->boolean('triggered_therapeutic_alarm')->default(false);
            $table->boolean('checked')->default(false);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE measurements ADD CONSTRAINT chk_alarm_type CHECK ((triggered_safety_alarm IS NOT NULL AND triggered_therapeutic_alarm IS NULL) OR (triggered_safety_alarm IS NULL AND triggered_therapeutic_alarm IS NOT NULL) OR (triggered_safety_alarm IS NOT NULL AND triggered_therapeutic_alarm IS NOT NULL))');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurements');
    }
}
