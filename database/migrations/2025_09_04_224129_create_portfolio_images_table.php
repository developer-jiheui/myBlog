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
        Schema::create('portfolio_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('portfolio_id')->index('idx_portfolio_images_portfolio');
            $table->string('url', 1024);
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();

            $table->index(['portfolio_id', 'is_cover', 'position'], 'idx_portfolio_images_cover');
            $table->unique(['portfolio_id', 'position'], 'uk_portfolio_images_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolio_images');
    }
};
