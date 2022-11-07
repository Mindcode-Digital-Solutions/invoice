<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->enum('type',['income','expense']);
            $table->date('date');
            $table->double('amount');
            $table->string('description');
            $table->string('ref')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('no action');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('no action');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('no action');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
