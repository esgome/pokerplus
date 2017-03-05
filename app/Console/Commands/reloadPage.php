<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reloadPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:jet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Postea a interjet';

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


        $array = [['ply'=>1,"mesa"=>1],['ply'=>2,"mesa"=>1],['ply'=>3,"mesa"=>2]];

$array = array_where($array, function ($value, $key) {
    return is_string($value);
});

    //   redirect('http://www.interjet.com');
    }
}
