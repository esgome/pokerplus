<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Event;
use Request;
use Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\HandController;
use Symfony\Component\Process\Process;
use App\Jobs\AutoPost;
class LobbyController extends Controller
{


	    public function __construct()
    {
        $this->middleware('auth');
    }


  private function filter_by_value ($array, $index, $value){
  	$newarray=[];
        if(is_array($array) && count($array)>0) 
        {
            foreach(array_keys($array) as $key){
                $temp[$key] = $array[$key][$index];
                
                if ($temp[$key] == $value){
                    $newarray[$key] = $array[$key];
                }
            }
          }
      return $newarray;
    } 


    public function register()
    {

$user=Auth::user();
$torneo=Request::input('torneo');
$plys=4;//num players

if(Cache::has($torneo)){



$datos=Cache::get($torneo);



if(count($datos)<$plys){
$update=['id'=>$user->id,'user'=>$user->name, 'mesa'=>1,'ply'=>count($datos),'status'=>true];

array_push($datos, $update);
}
}else{

$datos=[['id'=>$user->id,'user'=>$user->name, 'mesa'=>1,'ply'=>1,'status'=>true]];

};

Cache::put($torneo,$datos,10);


if (count($datos)===$plys){



shuffle($datos);

$l=0;

$temp=[];
while($l<=$plys-1):





$datos[$l]['mesa']=intval($l/4)+1;
$mesaT=intval($l/4)+1;
$datos[$l]['ply']=($l%4)+1;
$k=($l%4)+1;
array_push($temp, $datos[$l]);

if($k===4){
Cache::put("$torneo.$mesaT.setup",$temp,10);//ojo poco tiempo

(new HandController)->game($torneo,$mesaT);

   $process = new Process("php artisan chat:message ".$torneo." ".$mesaT." > /dev/null 2>&1 &", '/var/www/html/submit/pluspoker/'); 

$process->start();
$p=0; // Numero de jugadores


	     	 $job = (new AutoPost($torneo,$mesaT))
                 ->delay(Carbon::now()->addSeconds(10));
 dispatch($job);
while ( $p<= 3): 


	         Event::fire(new \App\Events\LobbyMessage($temp,$temp[$p]['id']));
	     if ($p=== 3){



	     }

$p++;
	endwhile;




 



$temp=[];


}
$l++;



endwhile;

	    // 	(new HandController)->hand($torneo,1);
	    // 		     	(new HandController)->hand($torneo,2);



}
	return;

    }



    public function index()
    {

$id=Auth::user()->id;
	return view('lobby', compact('id'));

    }
    //
}
