<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManajerHasSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manajer_has_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manajer_id'); //-> value user_id
            $table->unsignedBigInteger('sales_id'); //-> value user_id
            $table->timestamps();

            // $table('manajer_id', 'sales_id')
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manajer_has_sales');
    }
}
