<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationshipsTable extends Migration
{
    public function up()
    {
        Schema::create('relationships', function (Blueprint $table) {
            $table->id(); // bigint(20) unsigned AUTO_INCREMENT
            $table->unsignedBigInteger('created_by'); // 创建者 ID
            $table->unsignedBigInteger('parent_id'); // 父母 ID
            $table->unsignedBigInteger('child_id'); // 子女 ID
            $table->timestamps(); // created_at 和 updated_at
            $table->index('created_by'); // 索引 created_by
            $table->index('parent_id'); // 索引 parent_id
            $table->index('child_id'); // 索引 child_id
        });
    }

    public function down()
    {
        Schema::dropIfExists('relationships');
    }
}

