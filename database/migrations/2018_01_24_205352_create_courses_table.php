<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject', 5);
            $table->integer('course_number');
            $table->tinyInteger('section')->unsigned();
            $table->string('slug')->unique();
            $table->integer('crn')->unsigned();
            $table->string('title', 255);
            $table->tinyInteger('capacity')->unsigned();
            $table->string('campus', 10);
            $table->tinyInteger('credits')->unsigned();
            $table->string('semester', 10);
            $table->integer('year')->unsigned();
            $table->timestamps();
        });

        Schema::create('courses_students', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('course_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses_instructor');
        Schema::dropIfExists('coruses_students');
        Schema::dropIfExists('courses');
    }
}
