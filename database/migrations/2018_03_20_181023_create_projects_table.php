<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('supervisor');
            $table->string('lead_member');
            $table->integer('duration');
            $table->text('description');
            $table->enum('status', array('unapproved', 'approved', 'inprogress', 'inreview','published','rejected'));
            $table->string('pdf_link');
            $table->nullableTimestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('projects');

    }

}
