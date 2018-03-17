<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assignment_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->integer('grader_id')->unsigned();
            $table->decimal('points_earned', 6, 2);
            $table->decimal('grade', 6, 5);
            $table->string('letter_grade', 5);
            $table->boolean('approved')->default(0);
            $table->timestamps();

            $table->foreign('assignment_id')->references('id')->on('assignments');
            $table->foreign('student_id')->references('id')->on('users');
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
        Schema::dropIfExists('grades');
    }
}
