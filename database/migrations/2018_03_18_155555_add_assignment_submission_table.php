<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssignmentSubmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assignment_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('assignment_id')->references('id')->on('assignments');
            $table->foreign('user_id')->references('id')->on('users');
        });

        // Table for uploaded assignment files.
        Schema::create('assignments_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assignment_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('file', 2048)->nullable();
            $table->string('mime')->nullable();
            $table->string('type');
            $table->integer('submission_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('assignment_id')->references('id')->on('assignments');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('submission_id')->references('id')->on('assignment_submissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments_attachments');
        Schema::dropIfExists('assignment_submissions');
    }
}
