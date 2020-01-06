<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id');
            $table->string('name')->unique();
            $table->text('description');
            $table->string('max_capacity');
            $table->string('searchable');
            $table->string('group_link');
            $table->string('amount');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->enum('status',['onboarding', 'running', 'ended'])->default('onboarding');

            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
