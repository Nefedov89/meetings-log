<?php

use Illuminate\Support\Facades\Schema;
//use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsLogCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_logs', function(Blueprint $table)
        {
//            $table->index('name');
//            $table->addColumn('integer', 'from');
//            $table->index('integer', 'to');
            $table->increments('id');
            $table->string('name');
            $table->string('from');
            $table->string('to');
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
        Schema::drop('meeting_logs');
    }
}
