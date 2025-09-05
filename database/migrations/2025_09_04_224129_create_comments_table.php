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
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('blog_id')->index('idx_comments_blog');
            $table->unsignedBigInteger('user_id')->index('idx_comments_user');
            $table->unsignedBigInteger('parent_id')->nullable()->index('idx_comments_parent');
            $table->unsignedBigInteger('group_no')->nullable()->index('idx_comments_group');
            $table->tinyInteger('depth')->default(0);
            $table->tinyInteger('state')->default(1);
            $table->string('contents', 4000);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();

            $table->index(['blog_id', 'created_at'], 'idx_comments_blog_created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
