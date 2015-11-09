<?php
namespace Leertaak5\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Validation\Validator;
use Leertaak5\Helpers\ZipStream;
use Leertaak5\Http\Requests;
use Leertaak5\Measurement;
use Leertaak5\Station;
use Maatwebsite\Excel\Facades\Excel;

class DownloadController extends Controller
{
    /** @var $filter array - The filters which are applied to the filter*/
    private $filter = [];

    /** @const MAX_ROWS - Maximum rows for export to excel*/
    const MAX_EXCEL_ROWS = 10000;

    /** @const ROWS_PER_SHEET - prefered rows fitting on a sheet */
    const ROWS_PER_SHEET = 100;

    /** @const FIELDS - all the fields which are accessible for the client */
    public static $FIELDS = [
        'measurements' => [
            'numberFields' => [
                'time',
                'temperature',
                'dew_point',
                'station_pressure',
                'sea_level_pressure',
                'visibility',
                'precipitation',
                'snow_depth',
                'cloud_cover',
                'wind_direction',
                'wind_speed'
            ],
            'showOnlyFields' => [
                'events'
            ]
        ],
        'stations' => [
            'numberFields' => [
                'longitude',
                'latitude',
                'elevation'
            ],
            'nameFields' => [
                'name',
                'country'
            ]
        ]
    ];


    public function index()
    {
        $field['numberFields'] = array_merge(
            self::$FIELDS['measurements']['numberFields'],
            self::$FIELDS['stations']['numberFields']
        );
        $field['nameFields'] = self::$FIELDS['stations']['nameFields'];
        $field['showOnlyFields'] = ['events'];

        foreach (self::$FIELDS['measurements']['numberFields'] as $numberField) {
            $fieldValues[$numberField]['min'] = Measurement::min($numberField);
            $fieldValues[$numberField]['max'] = Measurement::max($numberField);
        }

        foreach (self::$FIELDS['stations']['numberFields'] as $numberField) {
            $fieldValues[$numberField]['min'] = Station::min($numberField);
            $fieldValues[$numberField]['max'] = Station::max($numberField);
        }

        return view('weather.export', [
            'fields' => $field,
            'stations' => Station::all()->sortBy('name'),
            'countries' => Station::distinct('country')->lists('country')->sort(),
            'fieldValues' => $fieldValues
            ]);
    }
    /**
     * Index: The main function in this class
     * This is the function where the data is received
     * @param Request $request
     */
    public function download(Request $request)
    {
<<<<<<< HEAD
        $this->validate($request, [
            'stationSelect' => 'exists:stations,id',
            'countrySelect' => 'exists:stations,country',
            'filter.temperature.min' => 'required_with:filter.temperature.max|numeric',
            'filter.temperature.max' => 'required_with:filter.temperature.min|numeric',
            'filter.dew_point.min' => 'required_with:filter.dew_point.max|numeric',
            'filter.dew_point.max' => 'required_with:filter.dew_point.min|numeric',
            'filter.station_pressure.min' => 'required_with:filter.station_pressure.max|numeric',
            'filter.station_pressure.max' => 'required_with:filter.station_pressure.min|numeric',
            'filter.sea_level_pressure.min' => 'required_with:filter.sea_level_pressure.max|numeric',
            'filter.sea_level_pressure.max' => 'required_with:filter.sea_level_pressure.min|numeric',
            'filter.visibility.min' => 'required_with:filter.visibility.max|numeric',
            'filter.visibility.max' => 'required_with:filter.visibility.min|numeric',
            'filter.precipitation.min' => 'required_with:filter.precipitation.max|numeric',
            'filter.precipitation.max' => 'required_with:filter.precipitation.min|numeric',
            'filter.snow_depth.min' => 'required_with:filter.snow_depth.max|numeric',
            'filter.snow_depth.max' => 'required_with:filter.snow_depth.min|numeric',
            'filter.longitude.min' => 'required_with:filter.longitude.max|numeric',
            'filter.longitude.max' => 'required_with:filter.longitude.min|numeric',
            'filter.latitude.min' => 'required_with:filter.latitude.max|numeric',
            'filter.latitude.max' => 'required_with:filter.latitude.min|numeric',
            'filter.elevation.min' => 'required_with:filter.elevation.max|numeric',
            'filter.elevation.max' => 'required_with:filter.elevation.min|numeric',
            'filter.time.min' => 'required_with:filter.time.max|date_format:Y-m-d',
            'filter.time.max' => 'required_with:filter.time.min|date_format:Y-m-d',
            ]);

        $startValue = $this->start($request);
        $size = $startValue[0];
        $query = $startValue[1];
=======
        list($size,$query) = $this->start($request);
>>>>>>> adds time constraint minimum 3 months iff more

        if ($size == -1) {
            $this->sendAllInZip();
        }

        if ($size <= self::MAX_EXCEL_ROWS) {
            $this->downLoadExcel($query);
        } else {
            $this->downloadCSV($query);
        }
    }

    public function count(Request $request)
    {
        return $this->start($request)[0];
    }


    /**
     * Counts the amount of rows for the query
     * In case of the cron job: returns -1
     * @param Request $request - request received from the browser
     * @return array [count, query]
     */
    private function start(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        if ($request->has('show')) {
            $show = $request->input('show');
        } else {
            $show = array_flatten(self::$FIELDS);
        }
        if ($request->has('filter')) {
            $this->filter = $request->input('filter');
            $this->checkTime();
        }

        if ($show == array_flatten(self::$FIELDS) && $this->filter == [Carbon::now()->subMonths(3)]) {
            return [-1, null];
        }

        $show  = $this->splitShow($show);
        $query = $this->setShow($show);
        $query = $this->numberFieldsMeasurement($query);
        $query = $this->progressStationQuery($query);
        return [$query->count(), $query];
    }

    /**
     * This method splits the attributes being shown
     * in station and measurement properties which
     * Which is needed to build the query
     * @param $show
     * @return mixed
     */
    private function splitShow($show)
    {
        $select['measurements'] = array_intersect(
            array_flatten(self::$FIELDS['measurements']),
            $show
        );
        $select['stations'] = array_intersect(
            array_flatten(self::$FIELDS['stations']),
            $show
        );
        return $select;
    }


    /**
     * Generate a query in which the showings are stored
     * @param $show - which fields need to be shown
     * @return mixed $query - the generated query
     */
    private function setShow($show)
    {
        $query = Measurement::with([
            'station' => function ($query) use ($show) {
                $query->select($show['stations'])
                    ->addSelect('id');
            }
        ])->select($show['measurements'])
            ->addSelect('station_id');
        return $query;
    }

    /**
     * The processor of the numerical field for measurements
     * @param $query - query where the filters needs to be applied on
     * @return mixed $query - query with the filters applied on it
     */
    private function numberFieldsMeasurement($query)
    {
        foreach ($this->filter as $property => $settings) {
            if (in_array($property, self::$FIELDS['measurements']['numberFields'])) {
                if (!$this->isEmpty($settings['min'])) {
                    $query = $query->where($property, '>=', $settings['min']);
                }
                if (!$this->isEmpty($settings['max'])) {
                    $query = $query->where($property, '<=', $settings['max']);
                }
            }
        }
        return $query;
    }

    /**
     * The processor of the numerical field for stations
     * @param $query - query where the filters needs to be applied on
     * @return mixed $query - query with the filters applied on it
     */
    private function numberFieldsStation($query)
    {
        foreach ($this->filter as $property => $settings) {
            if (in_array($property, self::$FIELDS['stations']['numberFields'])) {
                if (!$this->isEmpty($settings['min'])) {
                    $query = $query->where($property, '>=', $settings['min']);
                }
                if (!empty($settings['max'])) {
                    $query = $query->where([$property, '<=', $settings['max']]);
                }
            }
        }
        return $query;
    }

    /**
     * the processor of the name fields for ths stations
     * @param $query - query where the filters needs to be applied on
     * @return mixed $query - query with the filters applied on it
     */
    private function nameFieldsStation($query)
    {
        foreach ($this->filter as $property => $settings) {
            if(in_array($this->filter, self::$FIELDS['stations']['nameFields'])) {
                if (!$this->isEmpty($settings['in'])) {
                    $query = $query->whereIn($property, $settings['in']);
                } elseif (!$this->isEmpty($settings['notIn'])) {
                    $query = $query->whereNotIn($property, $settings['notIn']);
                }
            }
        }
        return $query;
    }


    /**
     * Adds the station and his requirements
     * @param $query - query where the station needs to be added to
     * @return mixed $query - query with the station
     */
    private function progressStationQuery($query)
    {
        $query->whereHas('station', function ($query) {
            $this->numberFieldsStation($query);
            $this->nameFieldsStation($query);
        });
        return $query;
    }

    /**
     * downloads the results of the query in a CSV file
     * @param $queryComplete = The completed query
     * @return void
     */
    private function downloadCSV($queryComplete)
    {
        $this->printHeaders();
        $queryComplete->chunk(10000, function ($measurements) {
            foreach ($measurements as $measurement) {
                $measurement = $measurement->toArray();
                $station = $measurement['station'];
                unset($measurement['station']);
                unset($measurement['station_id']);
                unset($station['id']);
                $merged = array_merge($measurement, $station);
                echo implode(', ', $merged).PHP_EOL;
            }
        });
        die();
    }


    /**
     * Dpwnloads the results of the query in an excel file
     * @param $queryComplete - The completed query
     * @return void
     */
    private function downloadExcel($queryComplete)
    {
        $data = [];

        $queryComplete->chunk(200, function ($measurements) use (&$data) {
            $data = array_merge($data, $measurements->toArray());
        });
        //dd($data);
        Excel::create(Carbon::today()->toDateString(), function ($excel) use ($data) {
            $chunks = array_chunk($data, self::ROWS_PER_SHEET);
            foreach ($chunks as $chunk) {
                $excel->sheet('sheet', function ($sheet) use ($chunk) {
                    $sheet->fromArray($chunk);
                });
            }
        })->download('xlsx');
        die();
    }


    /**
     * Prints the headers to let the browser know he should download
     * @return void
     */
    private function printHeaders()
    {
        header('Content-type: text/tab-separated-values');
        header("Content-Disposition: attachment; filename=rawText.tsv");
    }

    /**
     * This function returns if the parameter is meant to be empty
     * @param $value mixed
     * @return boolean meantEmpty
     *
     */
    public function isEmpty(&$value)
    {
        return ( (empty($value) || $value=="null") && (!is_numeric($value)) );
    }

    /**
     * Send all the raw data - generated by the cron job - as a zip
     * @return void
     */
    private function sendAllInZip()
    {
        $root = base_path() . '/resources/measurements/';
        $dir = File::get($root . 'finished.txt');
        $files = File::files($root . 'session ' . $dir . '/');
        $zip = new ZipStream(Carbon::today()->toDateString() . '.zip');
        foreach ($files as $file) {
            $zip->addFileFromPath(basename($file), $file);
        }
        $zip->finish();
    }

    /**
     * Checks if the time is bigger than 3 months and fixes it if it's not
     */
    private function checkTime()
    {
        $start = Carbon::now()->subMonths(3);
        if ($this->isEmpty($this->filter) || Carbon::parse($this->filter['time']['min']) <= $start) {
            $this->filter['time']['min'] = $start;
        }
    }
}
