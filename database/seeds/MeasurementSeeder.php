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
                'stn' => $station->id,
                'timestamp' => $date->format(DateTime::ATOM),
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
            ];

            $data = [$temp];
            for ($i = 0; $i < $this->seedAmount; $i++) {
                // travel back in tiiime!
                $date->subSecond();
                $temp = [
                    'stn' => $station->id,
                    'timestamp' => $date->format(DateTime::ATOM),
                    'temp' => $this->createRandomValueBasedOn($temp['temp']),
                    'dewp' => $this->createRandomValueBasedOn($temp['dewp']),
                    'stp' => $this->createRandomValueBasedOn($temp['stp']),
                    'slp' => $this->createRandomValueBasedOn($temp['slp']),
                    'visib' => $this->createRandomValueBasedOn($temp['visib']),
                    'prcp' => $this->createRandomValueBasedOn($temp['prcp']),
                    'sndp' => $this->createRandomValueBasedOn($temp['sndp']),
                    'frshtt' => (rand(0, 1) . rand(0, 1)
                        . rand(0, 1) . rand(0, 1) . rand(0, 1)
                        . rand(0, 1)),
                    'ddc' => $this->createRandomValueBasedOn($temp['ddc']),
                    'wnddir' => $this->createRandomValueBasedOn($temp['wnddir']),
                    'wdsp' => $this->createRandomValueBasedOn($temp['wdsp'])
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
