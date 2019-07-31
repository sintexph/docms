<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentVersionRevisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_version_revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('version_id')->unique()->unsigned();
            $table->longText('content')->comment('Changes in text format')->nullable();

            $table->timestamps();

            $table->foreign('version_id')
            ->references('id')
            ->on('document_versions')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_version_changes');
    }
}
