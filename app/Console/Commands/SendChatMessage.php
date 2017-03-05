<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;
use App\Http\Controllers\HandController;
use Carbon\Carbon;
class SendChatMessage extends Command
{
    protected $signature = 'chat:message {torneo} {mesa}';

    protected $description = 'Send chat message.';

    public function handle()


    {

$torneo = $this->argument('torneo');
$mesa = $this->argument('mesa');

//$sleep=$next->diffInSeconds($now);

$j=0;
while ( $j<= 200):

  $next=Cache::get("$torneo.$mesa.job");

$now=Carbon::now();
$sleep=$next->diffInSeconds($now);  
echo "dif ",$sleep;
echo "next",$next;
echo "now",$now;
if($next <= $now){
  (new HandController)->bet($torneo,$mesa);
//  $prejob=Carbon::now()->addSeconds(6);
//Cache::put('job',$prejob,10);

$sleep=0;

};

//sleep(5);
sleep($sleep);
$j++;
endwhile;


}
        // Fire off an event, just randomly grabbing the first user for now

    
}