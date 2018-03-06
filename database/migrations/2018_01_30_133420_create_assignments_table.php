<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->dateTime('due_date');
            $table->dateTime('display_date');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
        });

        // Table for uploaded assignment files.
        Schema::create('assignments_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assignment_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('file', 2048);
            $table->string('mime');
            $table->string('type');
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('assignment_id')->references('id')->on('assignments');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('assignments');
    }
}
