<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('idUsers');
            $table->string('merchantName');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('address');
            $table->integer('idCategory'); // cemilan, fast food, minuman, satay, dll
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
        Schema::dropIfExists('merchant');
    }
}
