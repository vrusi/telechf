<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDrugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_drugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('drug_id')->constrained();
            $table->integer('dosage_volume')->nullable();
            $table->string('dosage_unit')->nullable();
            $table->integer('dosage_times')->nullable();
            $table->enum('dosage_span', ['hour', 'day', 'week', 'month'])->nullable();
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
        Schema::dropIfExists('user_drugs');
    }
}
