<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('person_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->double('amount');
            $table->enum('type',['debit','credit']);
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('no action');
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
        Schema::dropIfExists('transaction_account');
    }
}
