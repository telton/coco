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
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->dateTime('due_date');
            $table->dateTime('display_date');
            $table->timestamps();
        });

        // Table for uploaded assignment documents.
        Schema::create('assignments_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assignment_id')->unsigned();
            $table->string('name');
            $table->string('file', 2048);
            $table->string('mime');
            $table->timestamps();

            $table->foreign('assignment_id')->references('id')->on('assignments');
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
