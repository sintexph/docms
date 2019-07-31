<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferenceDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->unsigned();
            $table->integer('reference_document_id')->unsigned();
            $table->string('created_by');
            $table->boolean('public')->default(false);
            
            $table->timestamps();

            $table->foreign('document_id')
            ->references('id')
            ->on('documents')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('reference_document_id')
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
        Schema::dropIfExists('reference_documents');
    }
}
