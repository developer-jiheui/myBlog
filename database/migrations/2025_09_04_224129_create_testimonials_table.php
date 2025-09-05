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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('author_user_id')->nullable()->index('idx_testimonials_author');
            $table->string('author_name', 120);
            $table->string('author_avatar_url', 1024)->nullable();
            $table->string('author_title', 120)->nullable();
            $table->text('body');
            $table->tinyInteger('status')->default(1);
            $table->boolean('pinned')->default(false);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();

            $table->index(['status', 'pinned', 'created_at'], 'idx_testimonials_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testimonials');
    }
};
