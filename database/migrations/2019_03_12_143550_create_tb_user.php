<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('groupID');
            $table->string('name');
            $table->boolean('gender')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps('deleted_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_user');
    }
}
