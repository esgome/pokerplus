<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\HandController;
use Illuminate\Support\Facades\Queue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AutoPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */


protected $torneo;
protected $mesaT;

    public function __construct($torneo,$mesaT)
    {
      

$this->torneo=$torneo;
$this->mesaT=$mesaT;


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

$uno=(new HandController);

$uno->hand($this->torneo,$this->mesaT);


    }
}
