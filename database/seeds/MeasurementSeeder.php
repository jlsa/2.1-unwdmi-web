<?php

use Illuminate\Database\Seeder;
use Leertaak5\Measurement;
use Leertaak5\Station;
use Carbon\Carbon;

class MeasurementSeeder extends Seeder
{
    private $seedAmount = 2000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stations = Station::all();
        $stationIds = array();

        foreach ($stations as $station)
        {
            $stationIds[] = $station->id;
        }

        $data = array();

        for ($i = 0; $i < $this->seedAmount; $i++)
        {

            $data[] = array(
                    'stn' => $stationIds[rand(0, sizeof($stationIds) - 1)],
                    'timestamp' => Carbon::now(),
                    'temp' => $this->getRandomDouble(-30, 40, 1),
                    'dewp' => $this->getRandomDouble(-40, 35, 1),
                    'stp' => $this->getRandomDouble(900, 1100, 1),
                    'slp' => $this->getRandomDouble(900, 1100, 1),
                    'visib' => $this->getRandomDouble(0, 165, 1),
                    'prcp' => $this->getRandomDouble(0, 15, 1),
                    'sndp' => $this->getRandomDouble(0, 90, 1),
                    'frshtt' => (rand(0, 1) . rand(0, 1)
                        . rand(0, 1) . rand(0, 1) . rand(0, 1)
                        . rand(0, 1)),
                    'ddc' => $this->getRandomDouble(0, 100, 1),
                    'wnddir' => rand(0, 359),
                    'wdsp' => $this->getRandomDouble(0, 80, 1)
                );
        }

        DB::table('measurements')->insert($data);
    }

    /**
     * Return a random double between the min and max value
     * with the provided amount of decimals.
     */
    public function getRandomDouble($min, $max, $decimals)
    {
        if($decimals < 1 || $decimals > 5)
            return;
        $factor = pow(10, $decimals);
        return rand($min * $factor, $max * $factor) / $factor;
    }
}
