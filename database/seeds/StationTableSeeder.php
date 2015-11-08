<?php

use Illuminate\Database\Seeder;

class StationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('TRUNCATE stations CASCADE');
        $insertArray = array();
        $stations = file_get_contents(base_path().'/resources/stations.tsv');
        $stationArray = explode(PHP_EOL, $stations);
        foreach ($stationArray as $station) {
            $stationParams = explode("\t", $station);
            $insertArray[] = array(
                'id'         =>  $stationParams[0],
                'name'       =>  $stationParams[1],
                'country'    =>  $stationParams[2],
                'latitude'   =>  $stationParams[3],
                'longitude'  =>  $stationParams[4],
                'elevation'  =>  $stationParams[5],
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' =>  date('Y-m-d H:i:s'),
            );
        }
        DB::table('stations')->insert($insertArray);
    }
}
