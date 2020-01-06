<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('member_id');
            $table->string('week');
            $table->string('startDate');
            $table->string('endDate');
            $table->enum('payment_status',['Pending', 'Paid'])->default('Pending');
            $table->string('payment_amount')->nullable();
            $table->string('date')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('member_id')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
