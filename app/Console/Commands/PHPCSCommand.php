<?php

namespace Leertaak5\Console\Commands;

use Illuminate\Console\Command;
use PHP_CodeSniffer_CLI;

class PHPCSCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cs:php';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks PHP code style.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cli = new PHP_CodeSniffer_CLI();
        // Reset arguments to prevent PHPCS from attempting to parse Artisan
        // parameters
        $_SERVER['argv'] = [''];
        $cli->runphpcs();
    }
}
