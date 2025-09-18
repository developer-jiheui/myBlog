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
        Schema::create('blog_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('blog_id')->index('idx_blog_images_blog');
            $table->string('url', 1024);
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();

            $table->index(['blog_id', 'is_cover', 'position'], 'idx_blog_images_cover');
            $table->unique(['blog_id', 'position'], 'uk_blog_images_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_images');
    }
};
