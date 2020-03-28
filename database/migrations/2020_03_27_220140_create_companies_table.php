<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 255)->index();
            $table->unsignedBigInteger('market_category_id', false);
            $table->unsignedBigInteger('round_lot_size', false);
            $table->string('security_name', 255)->index();
            $table->string('symbol', 8)->unique();
            $table->string('test_issue', 1);
            $table->string('financial_status', 1);

            $table->foreign('market_category_id')->references('id')->on('market_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
