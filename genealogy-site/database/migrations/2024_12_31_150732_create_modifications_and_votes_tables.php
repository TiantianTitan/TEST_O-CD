<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModificationsAndVotesTables extends Migration
{
    public function up()
    {
        Schema::create('modifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('target_id'); // 被修改的 people ID
            $table->unsignedBigInteger('proposed_by'); // 提案者 ID
            $table->enum('type', ['update_person', 'add_relationship']); // 修改类型
            $table->json('content'); // 修改内容（JSON 格式）
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // 提案状态
            $table->timestamps();

            $table->foreign('target_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('proposed_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modification_id'); // 对应提案 ID
            $table->unsignedBigInteger('user_id'); // 投票者 ID
            $table->enum('vote', ['approve', 'reject']); // 投票结果
            $table->timestamps();

            $table->foreign('modification_id')->references('id')->on('modifications')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
        Schema::dropIfExists('modifications');
    }
}
