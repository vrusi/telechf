<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('mobile');
            $table->enum('type', ['general practitioner', 'cardiologist']);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE contacts ADD CONSTRAINT chk_contact_info CHECK ((email IS NOT NULL AND mobile IS NULL) OR (email IS NULL AND mobile IS NOT NULL))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
