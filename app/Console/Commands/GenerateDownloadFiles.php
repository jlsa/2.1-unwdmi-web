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
        list($rootFolder, $current, $folder) = $this->setFolder();

        $counter = 1;
        $measurementsCounter = 0;
        $file = fopen($folder.'file'.$counter.'.csv', "w");
        $maxRows = self::MAX_ROWS-500;
        Measurement::with('station')
            ->where('time', '>', Carbon::now()->subMonths(3))
            ->orderBy('time', 'asc')
            ->chunk(500, function ($measurements) use ($folder, &$file, &$measurementsCounter, &$counter, $maxRows) {
                if ($measurementsCounter>$maxRows) {
                    echo "measurements: ".$measurementsCounter."\n";
                    echo "file created: ".$counter.".csv\n";
                    $measurementsCounter=0;
                    if (is_resource($file)) {
                        fclose($file);
                    }
                    $counter++;
                    $file = fopen($folder.'file'.$counter.'.csv', "w");
                }
                foreach ($measurements as $measurement) {
                    $array = $measurement->toArray();
                    unset($array['station_id'], $array['station']['id']);
                    fputcsv($file, array_flatten($array));
                }
                $measurementsCounter+=500;
            });
        File::put($rootFolder.'finished.txt', $current);
    }

    /**
     * prepares the folder if not ready
     * @return array [rootfolder, current, folder]
     * @throws \Exception directory not cleaned
     */
    private function setFolder()
    {
        $rootFolder = base_path() . '/resources/measurements/';
        $finished = File::get($rootFolder . 'finished.txt');
        $current = $this->getNext($finished);
        $folder = $rootFolder . 'session ' . $current . '/';
        if (!File::exists($folder)) {
            if (File::makeDirectory($folder)) {
                throw new \Exception("Directory not created");
            }
            return array($rootFolder, $current, $folder);
        } else {
            if (!File::cleanDirectory($folder)) {
                throw new \Exception("Directory not cleaned");
            }
            return array($rootFolder, $current, $folder);
        }
    }
}
