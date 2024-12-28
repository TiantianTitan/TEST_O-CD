<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id(); // bigint(20) unsigned AUTO_INCREMENT
            $table->unsignedBigInteger('created_by'); // 创建者 ID
            $table->string('first_name', 255)->collation('utf8mb4_unicode_ci'); // 名
            $table->string('last_name', 255)->collation('utf8mb4_unicode_ci'); // 姓
            $table->string('birth_name', 255)->collation('utf8mb4_unicode_ci')->nullable(); // 出生名
            $table->string('middle_names', 255)->collation('utf8mb4_unicode_ci')->nullable(); // 中间名
            $table->date('date_of_birth')->nullable(); // 出生日期
            $table->timestamps(); // created_at 和 updated_at
            $table->index('created_by'); // 为 created_by 创建索引
        });
    }

    public function down()
    {
        Schema::dropIfExists('people');
    }
}
