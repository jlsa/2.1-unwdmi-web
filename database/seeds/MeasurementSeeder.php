<?php

use Illuminate\Database\Seeder;
use Leertaak5\Measurement;
use Leertaak5\Station;
use Carbon\Carbon;

class MeasurementSeeder extends Seeder
{
    // only generate lots of data for stations close to Kyoto
    private $between = [134, 136];
    private $seedAmount = 2000;
    private $randomValueWindow = 0.003;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stations = Station::where('longitude', '>', $this->between[0])
                           ->where('longitude', '<', $this->between[1]);
        foreach ($stations->get() as $station) {
            $date = Carbon::now();
            $temp = [
                'station_id' => $station->id,
                'time' => $date->format(DateTime::ATOM),
                'temperature' => $this->getRandomDouble(-30, 40, 1),
                'dew_point' => $this->getRandomDouble(-40, 35, 1),
                'station_pressure' => $this->getRandomDouble(900, 1100, 1),
                'sea_level_pressure' => $this->getRandomDouble(900, 1100, 1),
                'visibility' => $this->getRandomDouble(0, 165, 1),
                'precipitation' => $this->getRandomDouble(0, 15, 1),
                'snow_depth' => $this->getRandomDouble(0, 90, 1),
                'events' => (rand(0, 1) . rand(0, 1)
                    . rand(0, 1) . rand(0, 1) . rand(0, 1)
                    . rand(0, 1)),
                'cloud_cover' => $this->getRandomDouble(0, 100, 1),
                'wind_direction' => rand(0, 359),
                'wind_speed' => $this->getRandomDouble(0, 80, 1)
            ];

            $data = [$temp];
            for ($i = 0; $i < $this->seedAmount; $i++) {
                // travel back in tiiime!
                $date->subSecond();
                $temp = [
                    'station_id' => $station->id,
                    'time' => $date->format(DateTime::ATOM),
                    'temperature' => $this->createRandomValueBasedOn($temp['temperature']),
                    'dew_point' => $this->createRandomValueBasedOn($temp['dew_point']),
                    'station_pressure' => $this->createRandomValueBasedOn($temp['station_pressure']),
                    'sea_level_pressure' => $this->createRandomValueBasedOn($temp['sea_level_pressure']),
                    'visibility' => $this->createRandomValueBasedOn($temp['visibility']),
                    'precipitation' => $this->createRandomValueBasedOn($temp['precipitation']),
                    'snow_depth' => $this->createRandomValueBasedOn($temp['snow_depth']),
                    'events' => (rand(0, 1) . rand(0, 1)
                        . rand(0, 1) . rand(0, 1) . rand(0, 1)
                        . rand(0, 1)),
                    'cloud_cover' => $this->createRandomValueBasedOn($temp['cloud_cover']),
                    'wind_direction' => $this->createRandomValueBasedOn($temp['wind_direction']),
                    'wind_speed' => $this->createRandomValueBasedOn($temp['wind_speed'])
                ];
                $data[] = $temp;
            }
            DB::table('measurements')->insert($data);
        }
    }

    /**
     * Return a random double between the min and max value
     * with the provided amount of decimals.
     */
    public function getRandomDouble($min, $max, $decimals)
    {
        if ($decimals < 0 || $decimals > 5) {
            return;
        }
        $factor = $decimals == 0 ? 1 : pow(10, $decimals);
        return rand($min * $factor, $max * $factor) / $factor;
    }

    /**
     * Creates a random value based on a base value and copies
     * its amount of decimals.
     */
    public function createRandomValueBasedOn($value)
    {
        $decimals = strlen(substr(strrchr($value, "."), 1));
        return $this->getRandomDouble(
            $value * (1 - ($this->randomValueWindow / 2)),
            $value * (1 + ($this->randomValueWindow / 2)),
            $decimals
        );
    }
}
