<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('position');
            $table->string('password');
            
            $table->string('created_by');
            $table->string('edited_by')->nullable();

            $table->boolean('perm_administrator')->default(false);
            $table->boolean('perm_reviewer')->default(false);
            $table->boolean('perm_approver')->default(false);

            $table->boolean('notify_changes')->comment('Notify if there are changes with the document')->default(true);
            $table->boolean('notify_followups')->comment('Notify if there are documents to followup')->default(true);
            $table->boolean('notify_comments')->comment('Notify if there are comments with the document')->default(true);
            $table->boolean('notify_reviewed')->comment('Notify if the created document is reviewed')->default(true);
            $table->boolean('notify_approved')->comment('Notify if the created document is approved')->default(true);
            $table->boolean('notify_to_approve')>comment('Notify if there are to be approved')->default(true);
            $table->boolean('notify_to_review')->comment('Notify if there are to be reviewed')->default(true);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
