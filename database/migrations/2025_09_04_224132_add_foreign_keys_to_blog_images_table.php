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
        Schema::table('blog_images', function (Blueprint $table) {
            $table->foreign(['blog_id'], 'fk_blog_images_blog')->references(['id'])->on('blogs')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_images', function (Blueprint $table) {
            $table->dropForeign('fk_blog_images_blog');
        });
    }
};
