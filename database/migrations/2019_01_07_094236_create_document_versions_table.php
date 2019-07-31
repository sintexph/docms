<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->unsigned();
            $table->integer('version');
            $table->json('content')->comment('The content of the version');
            $table->integer('created_by')->unsigned();
            $table->json('description')->comment('The description of the version');
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();

            $table->boolean('for_approval')->default(false)->comment('Flag for sending of for approval to user');
            $table->boolean('for_review')->default(false)->comment('Flag for sending of for review to user');
            
            $table->boolean('reviewed')->default(false);
            $table->boolean('approved')->default(false);
            $table->boolean('released')->default(false);
            $table->timestamp('released_date');


            $table->boolean('active')->comment('Active version or current version')->default(false);
            
            
            $table->timestamps();

            $table->foreign('created_by')
            ->references('id')
            ->on('users')
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
        Schema::dropIfExists('document_versions');
    }
}
