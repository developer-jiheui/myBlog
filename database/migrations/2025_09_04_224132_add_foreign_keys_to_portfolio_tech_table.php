<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('portfolio_tech', function (Blueprint $table) {
            $table->foreign(['portfolio_id'], 'fk_pt_portfolio')->references(['id'])->on('portfolios')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['tech_id'], 'fk_pt_tech')->references(['id'])->on('techs')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('portfolio_tech', function (Blueprint $table) {
            $table->dropForeign('fk_pt_portfolio');
            $table->dropForeign('fk_pt_tech');
        });
    }
};
