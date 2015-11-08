<?php

namespace Leertaak5\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Leertaak5\Measurement;

class GenerateDownloadFiles extends Command
{
    /**
     * max rows allowed by excel
     */
    const MAX_ROWS = 99999;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'genraw:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate raw file with all data.';

    /**
     * The folder where the files are stored
     *
     * @var string
     */
    private $folder;

    /**
     * The root folder where all the files are stored
     */
    private $rootFolder;

    private $current;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function getNext($value)
    {
        if ($value==3) {
            return 1;
        } else {
            return $value+1;
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->rootFolder = base_path().'/resources/measurements/';
        $this->folder = $this->rootFolder;
        $finished = File::get($this->rootFolder.'finished.txt');
        $this->current = $this->getNext($finished);
        $delete = $this->getNext($this->current);

        if (!File::cleanDirectory($this->rootFolder.'session '.$delete)) {
            throw new \Exception("Directory not cleaned");
        }
        $this->folder .='session '.$this->current.'/';

        $counter = 1;
        $measurementsCounter = 0;
        $file = fopen($this->folder.'file'.$counter.'.csv', "w");
        $maxRows = SELF::MAX_ROWS-500;
        Measurement::with('station')->orderBy('time', 'asc')
            ->chunk(500, function ($measurements) use (&$file, &$measurementsCounter, &$counter, $maxRows) {
                if ($measurementsCounter>$maxRows) {
                    echo "measurements: ".$measurementsCounter."\n";
                    echo "file created: ".$counter.".csv\n";
                    $measurementsCounter=0;
                    if (is_resource($file)) {
                        fclose($file);
                    }
                    $counter++;
                    $file = fopen($this->folder.'file'.$counter.'.csv', "w");

                }
                foreach ($measurements as $measurement) {
                    $array = $measurement->toArray();
                    unset($array['station_id'], $array['station']['id']);
                    fputcsv($file, array_flatten($array));
                }
                $measurementsCounter+=500;
            });
        File::put($this->rootFolder.'finished.txt', $this->current);
    }
}
