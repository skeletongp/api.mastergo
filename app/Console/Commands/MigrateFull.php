<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\StreamOutput;

class MigrateFull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migratefull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migración de las dos bases de datos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stream = fopen("php://output", "w");
        Artisan::call(" migrate:fresh --database='moso_master' --path='database/migrations/master'", array(), new StreamOutput($stream));
        Artisan::call(" migrate:fresh --seed", array(), new StreamOutput($stream));
        var_dump($stream);
        return 0;
    }
}
