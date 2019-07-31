<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_drafts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();

            $table->string('document_number')->nullable();
            $table->string('document_title')->nullable();
            $table->string('document_system_code')->nullable();
            $table->string('document_section_code')->nullable();
            $table->string('document_category_code')->nullable();
            $table->string('document_serial')->nullable();
            $table->text('document_comment')->nullable();
            $table->text('document_keywords')->nullable();

            $table->json('version_approver_ids')->nullable();
            $table->json('version_reviewer_ids')->nullable();
            
            $table->boolean('version_for_approval')->default(false)->comment('Flag for sending of for approval to user');
            $table->boolean('version_for_review')->default(false)->comment('Flag for sending of for review to user');


            $table->json('version_content')->nullable();
            $table->json('version_description')->nullable();
            $table->text('version_effective_date')->nullable();


            $table->boolean('revision_has_revision')->default(false);
            $table->longText('revision_has_content')->nullable();


            $table->timestamps();



            $table->foreign('user_id')
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
        Schema::dropIfExists('document_drafts');
    }
}
