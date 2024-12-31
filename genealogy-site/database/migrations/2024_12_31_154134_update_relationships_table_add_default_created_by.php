<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRelationshipsTableAddDefaultCreatedBy extends Migration
{
    public function up()
    {
        Schema::table('relationships', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('relationships', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable(false)->change();
        });
    }
}
