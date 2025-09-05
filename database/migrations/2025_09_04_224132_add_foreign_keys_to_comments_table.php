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
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign(['blog_id'], 'fk_comments_blog')->references(['id'])->on('blogs')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['parent_id'], 'fk_comments_parent')->references(['id'])->on('comments')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign(['user_id'], 'fk_comments_user')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('fk_comments_blog');
            $table->dropForeign('fk_comments_parent');
            $table->dropForeign('fk_comments_user');
        });
    }
};
