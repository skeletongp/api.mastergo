<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignUserToStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:user {user} {store}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enlaza un usuario a un negocio';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $user=$this->argument('user');
        $store=$this->argument('store');
        DB::table('store_users')->insert([
            ['user_id' => $user, 'store_id' => $store, 'created_at'=>date(now()), 'updated_at'=>date(now())]
        ]);
        return 0;
    }
}
