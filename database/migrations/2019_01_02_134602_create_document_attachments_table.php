<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->unsigned();
            $table->integer('file_id')->unsigned();

            $table->timestamps();

            $table->foreign('file_id')
            ->references('id')
            ->on('uploads')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('document_id')
            ->references('id')
            ->on('documents')
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
        Schema::dropIfExists('document_attachments');
    }
}
