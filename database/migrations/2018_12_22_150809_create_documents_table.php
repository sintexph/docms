<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_number')->unique();
            $table->string('title');
            $table->string('system_code');
            $table->string('section_code');
            $table->string('category_code');
            $table->integer('serial');
            $table->text('comment')->nullable();
            $table->json('keywords')->nullable();
            $table->integer('created_by')->unsigned();

            $table->integer('locked')->default(false);
            $table->boolean('obsolete')->default(false);
            $table->integer('access')->default(4)->comment('1= confidential 2=custom 3=public 4=only me');
            $table->integer('version')->comment('Latest version of the document');

            $table->boolean('archived')->default(false);
            $table->string('archived_by')->nullable();
            $table->timestamp('archived_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            
            $table->foreign('created_by')
            ->references('id')
            ->on('users')
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
        Schema::dropIfExists('documents');
    }
}
