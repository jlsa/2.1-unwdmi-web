<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMeasurementColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('measurements', function ($table) {
            $table->renameColumn('stn', 'station_id');
            $table->renameColumn('timestamp', 'time');
            $table->renameColumn('temp', 'temperature');
            $table->renameColumn('dewp', 'dew_point');
            $table->renameColumn('stp', 'station_pressure');
            $table->renameColumn('slp', 'sea_level_pressure');
            $table->renameColumn('visib', 'visibility');
            $table->renameColumn('prcp', 'precipitation');
            $table->renameColumn('sndp', 'snow_depth');
            $table->renameColumn('frshtt', 'events');
            $table->renameColumn('ddc', 'cloud_cover');
            $table->renameColumn('wnddir', 'wind_direction');
            $table->renameColumn('wdsp', 'wind_speed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('measurements', function ($table) {
            $table->renameColumn('station_id', 'stn');
            $table->renameColumn('time', 'timestamp');
            $table->renameColumn('temperature', 'temp');
            $table->renameColumn('dew_point', 'dewp');
            $table->renameColumn('station_pressure', 'stp');
            $table->renameColumn('sea_level_pressure', 'slp');
            $table->renameColumn('visibility', 'visib');
            $table->renameColumn('precipitation', 'prcp');
            $table->renameColumn('snow_depth', 'sndp');
            $table->renameColumn('events', 'frshtt');
            $table->renameColumn('cloud_cover', 'ddc');
            $table->renameColumn('wind_direction', 'wnddir');
            $table->renameColumn('wind_speed', 'wdsp');
        });
    }
}
