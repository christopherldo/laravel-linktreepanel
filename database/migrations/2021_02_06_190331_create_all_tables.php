<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->string('email', 50)->unique();
            $table->string('password', 255);
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->uuid('id_user');
            $table->string('slug', 16);
            $table->string('op_font_color', 9)->default('#000000');
            $table->string('op_bg_type', 16)->default('color');
            $table->string('op_bg_value', 41)->default('#ffffff');
            $table->string('op_profile_image', 41)->default('default.webp');
            $table->string('op_title', 100)->nullable();
            $table->text('op_description')->nullable();
            $table->string('op_fb_pixel', 15)->nullable();
        });

        Schema::create('links', function(Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->uuid('id_page');
            $table->tinyInteger('status')->default(0);
            $table->integer('order');
            $table->string('title', 100);
            $table->text('href');
            $table->string('op_bg_color', 9)->nullable();
            $table->string('op_text_color', 9)->nullable();
            $table->string('op_border_type', 16)->nullable();
        });

        Schema::create('views', function(Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->uuid('id_page');
            $table->date('view_date');
            $table->integer('total')->default(0);
        });

        Schema::create('clicks', function(Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->integer('id_link');
            $table->date('click_date');
            $table->integer('total')->default(0);
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
        Schema::dropIfExists('pages');
        Schema::dropIfExists('links');
        Schema::dropIfExists('views');
        Schema::dropIfExists('clicks');
    }
}
