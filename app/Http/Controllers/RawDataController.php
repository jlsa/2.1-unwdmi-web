<?php
namespace Leertaak5\Http\Controllers;

use Carbon\Carbon;
use Leertaak5\Http\Requests;
use Leertaak5\Measurement;

class RawDataController extends Controller
{
    const MAX_ROWS = 1048576;


    public function __construct()
    {
        set_time_limit(0);
    }
    public function raw()
    {
//        $this->printHeaders();
        Measurement::where('timestamp', '>', $this->getStartDate())
            ->chunk(1000, function ($measurements) {
                foreach ($measurements as $measurement) {
                    echo implode(',', $measurement->toArray()).PHP_EOL;
                }
            });
    }



    public function country($country)
    {


        $GLOBALS['country'] = $country;
//        $this->printHeaders();
        $measurements = Measurement::where('timestamp', '>', $this->getStartDate())
            ->whereHas('station', function ($query) {
                $query->where('country', $GLOBALS['country']);
            });
        $measurements->chunk(1000, function ($measurements) {
            foreach ($measurements as $measurement) {
                echo implode(',', $measurement->toArray()) . PHP_EOL;
            }
        });
    }

    private function printHeaders()
    {
        header('Content-type: text/tab-separated-values');
        header("Content-Disposition: attachment; filename=rawText.tsv");
    }

    private function getStartDate()
    {
        return Carbon::now()->subMonth(3);
    }
}
