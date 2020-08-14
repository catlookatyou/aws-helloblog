<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
		        $table->bigIncrements('id');
		        $table->string('title');
                $table->integer('type')->unsigned()->nullable();
                $table->text('content')->nullable();
                $table->string('photo')->nullable();
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('likes')->default(0);
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
