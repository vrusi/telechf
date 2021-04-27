<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('id_external')->nullable();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->boolean('is_coordinator')->default(false);
            $table->string('password');
            $table->enum('sex', ['male', 'female'])->nullable();
            $table->date('dob')->nullable();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->string('recommendations')->nullable();
            $table->string('mac')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('coordinator_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
