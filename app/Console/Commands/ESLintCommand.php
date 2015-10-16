<?php

namespace Leertaak5\Console\Commands;

use Illuminate\Console\Command;

class ESLintCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cs:js';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks PHP code style';

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
        $eslintPath = './node_modules/.bin/eslint';
        passthru($eslintPath . ' --color ./resources/assets/js ./gulpfile.js');
    }
}
