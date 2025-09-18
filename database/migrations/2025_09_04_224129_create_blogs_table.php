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
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('idx_blogs_user');
            $table->string('title', 500);
            $table->string('slug')->nullable()->unique('uk_blogs_slug');
            $table->longText('contents')->nullable();
            $table->string('image_url', 1024)->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent()->index('idx_blogs_published');
            $table->dateTime('updated_at')->nullable()->useCurrent();

            $table->fullText(['title', 'contents'], 'ft_blogs_title_contents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};
