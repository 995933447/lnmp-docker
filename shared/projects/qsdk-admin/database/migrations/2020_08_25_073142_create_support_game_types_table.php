<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportGameTypesTable extends Migration
{
    const TABLE_NAME = 'support_game_types';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(static::TABLE_NAME)) {
            return;
        }

        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->string('name')->comment('游戏名称');
            $table->string('remark')->comment('说明')->nullable();
            $table->tinyInteger('status')->comment('状态：1有效，0无效')->default(1);
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
        Schema::dropIfExists(static::TABLE_NAME);
    }
}
