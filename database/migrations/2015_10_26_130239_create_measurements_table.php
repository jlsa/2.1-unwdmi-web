<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMeasurementsTable
 * The class which handles the
 */
class CreateMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stn');
            $table->timestamp('timestamp');
            $table->double('temp');
            $table->double('dewp');
            $table->double('stp');
            $table->double('slp');
            $table->double('visib');
            $table->double('prcp');
            $table->double('sndp');
            $table->string('frshtt');
            $table->double('ddc');
            $table->integer('wnddir');
            $table->double('wdsp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('measurements');
    }
}