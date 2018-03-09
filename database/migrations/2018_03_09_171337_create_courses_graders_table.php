<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesGradersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses_graders', function (Blueprint $table) {
            $table->integer('course_id')->unsigned();
            $table->integer('grader_id')->unsigned();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('grader_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses_graders');
    }
}
