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
        Schema::create('entity_labels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('target_type', ['portfolio', 'tech', 'blog']);
            $table->unsignedBigInteger('target_id');
            $table->enum('kind', ['category', 'tag']);
            $table->string('slug', 120);
            $table->string('name', 120);
            $table->integer('weight')->default(0);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();

            $table->index(['slug', 'kind'], 'idx_entity_label_slug_kind');
            $table->index(['target_type', 'target_id'], 'idx_entity_label_target');
            $table->unique(['target_type', 'target_id', 'kind', 'slug'], 'uk_entity_label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_labels');
    }
};
