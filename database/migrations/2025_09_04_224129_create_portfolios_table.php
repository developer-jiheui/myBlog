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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('idx_portfolios_user');
            $table->string('title');
            $table->string('slug')->nullable()->unique('uk_portfolios_slug');
            $table->text('description')->nullable();
            $table->string('project_url', 1024)->nullable();
            $table->string('image_url', 1024)->nullable();
            $table->integer('like_count')->default(0);
            $table->dateTime('created_at')->nullable()->useCurrent()->index('idx_portfolios_created');
            $table->dateTime('updated_at')->nullable()->useCurrent();

            $table->fullText(['title', 'description'], 'ft_portfolios_search');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolios');
    }
};
