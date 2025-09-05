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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email', 191)->unique('uk_users_email');
            $table->string('password');
            $table->tinyInteger('user_type')->default(1)->index('idx_users_user_type');
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('avatar', 1024)->nullable();
            $table->tinyInteger('register_type')->default(0);
            $table->string('address')->nullable();
            $table->string('phone_num', 20)->nullable();
            $table->text('bio')->nullable();
            $table->string('job_title', 100)->nullable();
            $table->date('birthday')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->rememberToken();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
