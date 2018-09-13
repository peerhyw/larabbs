<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            //图片类型 avatar 和 topic 分别用于用户头像以及话题中的图片。记录图片类型是因为不同类型的图片有不同的尺寸，以及不同的文件目录，修改个人头像所使用的 image 必须为 avatar 类型。
            //enum枚举类型  只能输入avatar和topic这两种
            $table->enum('type',['avatar','topic'])->index();
            $table->string('path')->index();
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
        Schema::dropIfExists('images');
    }
}
