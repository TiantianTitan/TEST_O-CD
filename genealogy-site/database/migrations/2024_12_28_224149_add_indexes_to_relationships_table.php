<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddIndexesToRelationshipsTable extends Migration
{
    public function up()
    {
        Schema::table('relationships', function (Blueprint $table) {
            if (!$this->indexExists('relationships', 'relationships_parent_id_index')) {
                $table->index('parent_id'); // 添加 parent_id 索引
            }

            if (!$this->indexExists('relationships', 'relationships_child_id_index')) {
                $table->index('child_id'); // 添加 child_id 索引
            }
        });
    }

    public function down()
    {
        Schema::table('relationships', function (Blueprint $table) {
            $table->dropIndex(['parent_id']); // 删除 parent_id 索引
            $table->dropIndex(['child_id']); // 删除 child_id 索引
        });
    }

    // 更新后的 Helper 方法：检查索引是否存在
    private function indexExists($table, $indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return !empty($indexes); // 如果查询结果不为空，表示索引已存在
    }
}

