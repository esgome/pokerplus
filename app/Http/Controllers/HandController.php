<?php

namespace App\Http\Controllers;

use Event;
use Request;
use Illuminate\Support\Facades\Cache;
use Form;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use \App\Jobs\AutoPost;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\Process\Process;


class HandController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }



    
	private function send($datos){




$mask=$datos['cartas'];
$jugadores=Cache::get($datos['torneo'].".".$datos['mesat'].".setup");
$no_ply=count($jugadores);

$datos['jugadores']=$jugadores;

	$j=1;
while ($j <= 4):
	# code...
	$temp=['ply1'=>['red_joker','red_joker'],'ply2'=>['red_joker','red_joker'],'ply3'=>['red_joker','red_joker'],'ply4'=>['red_joker','red_joker']];
$temp["ply{$j}"]=$datos['cartas']["ply{$j}"];

//$datos->cartas=$temp;
$datos['cartas']=$temp;



         $aja=Event::fire(new \App\Events\ChatMessageWasReceived($datos,$jugadores[$j-1]['id']));
$datos['cartas']=$mask;

$j++;

endwhile;



    $job= Carbon::now()->addSeconds(12);
    Cache::put($datos['torneo'].".".$datos['mesat'].".job",$job,5);
   //         $jobnew = (new Autopost())
   //                 ->delay(Carbon::now()->addSeconds(5));

   //     dispatch($jobnew);

return $aja;
}
	private function plusturn($x,$max){


$plus=$x+1;
if($plus > $max){

$plus=1;
}
return $plus;
}

 
 	private function minusturn($x,$max){


$minus=$x-1;
if($minus < 1){

$minus=$max;
}

return $minus;
	}

public function fire() {


        return view('poker');
    }
    




    public function bet($torneo=0,$mesat=0)
    {


    	$allin=false;
$bet=Request::input('bet',-1);




    	if ($bet === -1){


$bet=0;

$table=Cache::get("$torneo.$mesat.table");


    	}else{

$torneo=Request::input('torneo');
$mesat=Request::input('mesat');
$table=Cache::get("$torneo.$mesat.table");


$players=Cache::get("$torneo.$mesat.setup");
$pos=$table['position']['turn'];

if($players[$table['position']['turn']-1]['id'] !== Auth::user()->id){
	return;
}

if($table['money']["ply$pos"]<$bet)
	{
		$bet=$table['money']["ply$pos"];

$allin=true;
$table['allin'][$pos-1]=true;

if(array_sum($table['players']) > 1){
$table['players'][$pos-1]=0;
}

}



    	}


$datos=Cache::get("$torneo.$mesat.cartas");

$ply=$table['position']['ply'];
$turn=$table['position']['turn'];
$lasttemp=$table['position']['last'];
echo 'table' ,json_encode($table),'--S';
$j=1;
while ($j <= 4):
 

$lasttemp=$this->minusturn($lasttemp,4);
if($table["players"][$lasttemp-1]=== 0){

} else {
$j=5;

}
$j++;
endwhile;



$flag= false;

if ($table['position']['turn'] === $lasttemp){
echo 'lasttemp', $lasttemp;
$flag = true;


}

echo 'turn',$table['position']['turn'],'y bets=',$table["bets"][$table['position']['turn']-1]+$bet,'-'; //,'y',$table["bets"][$table['position']['turn']];



if (($table["bets"][$table['position']['turn']-1])+$bet < max($table["bets"]) && !$allin){
echo 'foldie';
$temp2=$turn;
	$i = 1;

	if($table['position']['last']===$temp2){
while ($i <= 4):
 

$temp2=$this->plusturn($temp2,$ply);
if($table["players"][$temp2-1]=== 0){





} else {
$table['position']['last']=$temp2;
$i=5;

}
$i++;
endwhile;


}


	$table['position']['ply']=$table['position']['ply']-1;
	$table['players'][$turn-1]=0;
	$table['bets'][$turn-1]=0;
	$chat="player ".$table['position']['turn']." las tira";

} elseif ($table["bets"][$table['position']['turn']-1]+$bet > max($table['bets'])) {
	echo 'subi',$table["bets"][$table['position']['turn']-1];
	$table['money']["ply{$turn}"]=$table['money']["ply{$turn}"]-$bet;
	$table['position']['last']=$table["position"]["turn"];
	$table['bets'][$turn-1]=$table['bets'][$turn-1]+$bet;
	$table['pot']=$table['pot']+$bet;
	$chat="player". $table['position']['turn']." sube";

} elseif (($table["bets"][$table['position']['turn']-1]+$bet === max($table['bets'])) || $allin) {
	echo 'llame';
	$table['money']["ply{$turn}"]=$table['money']["ply{$turn}"]-$bet;
	$table['bets'][$turn-1]=$table['bets'][$turn-1]+$bet;
	$table['pot']=$table['pot']+$bet;

	$chat="player".$table['position']['turn']." llama";
 }





 $temp2=$turn;
	$i = 1;
while ($i <= 4):




    echo ' antes turn ', $temp2;

$temp2=$this->plusturn($temp2,4);
if($table["players"][$temp2-1]=== 0){
echo 'tempo-turn',$table['position']['turn'];
} else {
$table['position']['turn']=$temp2;
echo ' turn: ',$table['position']['turn'];
$i=5;

}
$i++;
endwhile;


echo 'tabla',json_encode($table),'suma',array_sum($table["players"]);


$hh=0;
$flagallin=false;
while ($hh <= 3): //num plys
	


if($table['allin'][$hh]){

$flagallin=true;

}
$hh++;
endwhile;
if ((array_sum($table["players"]) === 1) && !$flagallin){


	
$k = array_search(1, $table["players"])+1;
echo 'si entro',$k;
if($flag && $table['position']['step'] === 'vistas'){
} else {
	$table['money']["ply{$k}"]=$table['money']["ply{$k}"]+$table['pot'];
}


Cache::put("$torneo.$mesat.table",$table,10);
//$datos=['tabla'=>$table,'cartas'=>$cartas,'mesa'=>[],'chat'=>'foldio'];

$this->hand($torneo,$mesat);

return;
}
Cache::put("$torneo.$mesat.table",$table,10);

if ($flag && $turn === $table['position']['last'] ){





} elseif ($flag)

{




	$cartas=Cache::get("$torneo.$mesat.cartas");

if ($flagallin && (array_sum($table['players']) === 1)){

$table['position']['step']='vistas';


}


switch ($table['position']['step']) {
	case 'flop':
		$this->flop($torneo,$mesat);
		break;
	
		case 'turn':
		$this->turn($torneo,$mesat);
		break;
		
		case 'river':
		$this->river($torneo,$mesat);
		break;
		
		case 'vistas':
		$this->vistas($torneo,$mesat);
		break;

}

return;

}

$cartas=Cache::get("$torneo.$mesat.cartas");
$mesa=Cache::get("$torneo.$mesat.mesavi");
$datos=['tabla'=>$table,'cartas'=>$cartas,'mesa'=>$mesa,'chat'=>$chat,'torneo'=>$torneo,'mesat'=>$mesat];
$this->send($datos);


        //blockio init
}

    public function test()
    {

                 $job = (new Autopost())->delay(Carbon::now()->addSeconds(10));

        dispatch($job);
        return;
    
    }

    public function game($torneo,$mesat)
    {
$dealer=rand ( 1 , 4 );
$turn=$dealer +1;

$money = ['ply1'=>1500,'ply2'=>1500,'ply3'=>1500,'ply4'=>1500];
$table=['money' => ['ply1'=>1500,'ply2'=>1500,'ply3'=>1500,'ply4'=>1500],
		'position'=>['dealer'=>$dealer,'last'=> 1, 'turn'=>1, 'step'=>'flop','ply'=>4],
		'bets'=>[0,0,0,0],
		'players'=>[1,1,1,1],'pot'=>0,'allin'=>[false,false,false,false]];

$prejob=Carbon::now()->addSeconds(20);
Cache::put("$torneo.$mesat.job",$prejob,10);

Cache::put("$torneo.$mesat.money",$money,10);
Cache::put("$torneo.$mesat.table",$table,10);
$datos=[];
        Log::info("Request Cycle with Queues Begins");




//$this->hand();
//exec("echo php artisan chat-message | at now");

 //dispatch(new AutoPost());
//call_in_background('chat:message');
        return "$torneo.$mesat.job";
     
}

	  public function hand($torneo,$mesat)

    {

$table=Cache::get("$torneo.$mesat.table");

//dealer'


//	$url = 'https://api.random.org/json-rpc/1/invoke';
// $predata=array('apiKey'=>'ae675036-317b-4221-8e43-9ebb690887fc','n'=>52,'min'=>1,'max'=>52,'replacement'=>false,'base'=>10);
//$datos = array('jsonrpc' => '2.0', 'method' => 'generateSignedIntegers','params'=>$predata,'id'=>17877);

// use key 'http' even if you send the request to https://...
//$ch = curl_init($url);
//curl_setopt_array($ch, array(
//    CURLOPT_POST => TRUE,
//    CURLOPT_RETURNTRANSFER => TRUE,
//    CURLOPT_HTTPHEADER => array(
//        'Content-Type: application/json'
//    ),
//    CURLOPT_POSTFIELDS => json_encode($datos)
// ));

// Send the request
// $response = curl_exec($ch);

$deck=array('2h','3h','4h','5h','6h','7h','8h','9h','Th','Jh','Qh','Kh','Ah',
	'2s','3s','4s','5s','6s','7s','8s','9s','Ts','Js','Qs','Ks','As',
	'2c','3c','4c','5c','6c','7c','8c','9c','Tc','Jc','Qc','Kc','Ac',
	'2d','3d','4d','5d','6d','7d','8d','9d','Td','Jd','Qd','Kd','Ad');

//$response = json_decode($response);


//$datos=$response->result->random->data;



//foreach ( $datos as &$valor){


//$valor=$deck[$valor-1];


//}
$datos=$deck;
shuffle($datos);
   
$u=1;
$j=1;
$pos=0;
$blind=50;
$lasttemp=$table["position"]["dealer"];
$table["players"]=[1,1,1,1];
$table["bets"]=[0,0,0,0];
  while ($u<= 4):
 

if($table["money"]["ply{$u}"]<=0){

$table["players"][$u-1]=0;
	
} 
$u++;
endwhile;
$suma=array_sum($table["players"]);

$cartas=[];
   while ($j <= 8):
 

$lasttemp=$this->plusturn($lasttemp,4);
if($table["money"]["ply{$lasttemp}"]<=0){

$cartas["ply{$lasttemp}"]=['black_joker','black_joker'];
	echo 'no hay',$lasttemp,' j ',$j;
} else {


if ($pos ===3){

	if($suma > 2){
				$cartas["ply{$lasttemp}"]=[$datos[2],$datos[$suma+2]];


	}

$table["position"]["turn"]=$lasttemp;
$j=9;
}


	if ($pos === 2){
		$cartas["ply{$lasttemp}"]=[$datos[1],$datos[$suma+1]];
$table["money"]["ply{$lasttemp}"] = $table["money"]["ply{$lasttemp}"]-100;
$table["bets"][$lasttemp-1]=100;
$blind=100;
$pos++;
}	if ($pos === 1){

$cartas["ply{$lasttemp}"]=[$datos[0],$datos[$suma]];

$table["money"]["ply{$lasttemp}"] = $table["money"]["ply{$lasttemp}"]-50;

$table["bets"][$lasttemp-1]=50;
$pos++;
}
	if ($pos === 0 ){


			if($suma > 3){
				$cartas["ply{$lasttemp}"]=[$datos[3],$datos[$suma+3]];


	}

$table["position"]["dealer"]=$lasttemp;
$pos++;
}
}
$j++;
endwhile; 	



$cucui=$suma*2;

$mesa=[$datos[$cucui+1],$datos[$cucui+2],$datos[$cucui+3],$datos[$cucui+5],$datos[$cucui+7]];

$table["position"]["ply"]=array_sum($table['players']);

$table["position"]["last"]=$table["position"]["turn"];

$table["pot"]=150;
$table['position']['step']='flop';



Cache::put("$torneo.$mesat.cartas",$cartas,10);





$mesavi=[];


Cache::put("$torneo.$mesat.table",$table,10);
Cache::put("$torneo.$mesat.mesa",$mesa,10);
Cache::put("$torneo.$mesat.mesavi",$mesavi,10);



$datos=['tabla'=>$table,'cartas'=>$cartas,'mesa'=>$mesavi,'chat'=>'hand','torneo'=>$torneo,'mesat'=>$mesat];
 //        Event::fire(new \App\Events\ChatMessageWasReceived($datos,1));
$this->send($datos);
       return $datos; 
    }
    //



public function flop($torneo,$mesat)
{

$table=Cache::get("$torneo.$mesat.table");

 $mesa=Cache::get("$torneo.$mesat.mesa");
 $cartas=Cache::get("$torneo.$mesat.cartas");





$table['position']['step']='turn';

 $lasttemp=$table['position']['dealer'];


$j=1;

while ($j <= 4):
 

$lasttemp=$this->plusturn($lasttemp,4);
if($table["players"][$lasttemp-1]=== 0){


	echo 'no hay',$lasttemp,' j ',$j;
} else {


echo 'si hay',$lasttemp,' j ',$j;
$j=5;
}
$j++;
endwhile;
$j=1;


$table['position']['turn']=$lasttemp;
$table['position']['last']=$lasttemp;

$temp=[$mesa[0],$mesa[1],$mesa[2]];

	Cache::put("$torneo.$mesat.mesavi",$temp,10);
Cache::put("$torneo.$mesat.table",$table,10);
$datos=['tabla'=>$table,'cartas'=>$cartas,'mesa'=>$temp,'chat'=>'flop','torneo'=>$torneo,'mesat'=>$mesat];
$this->send($datos);

 
return;

}

public function turn($torneo,$mesat)
{
	  

$table=Cache::get("$torneo.$mesat.table");

 $mesa=Cache::get("$torneo.$mesat.mesa");
 $cartas=Cache::get("$torneo.$mesat.cartas");





$table['position']['step']='river';

 $lasttemp=$table['position']['dealer'];


$j=1;

while ($j <= 4):
 

$lasttemp=$this->plusturn($lasttemp,4);
if($table["players"][$lasttemp-1]=== 0){


	echo 'no hay',$lasttemp,' j ',$j;
} else {


echo 'si hay',$lasttemp,' j ',$j;
$j=5;
}
$j++;
endwhile;
$j=1;


$table['position']['turn']=$lasttemp;
$table['position']['last']=$lasttemp;




$temp=[$mesa[0],$mesa[1],$mesa[2],$mesa[3]];



	Cache::put("$torneo.$mesat.mesavi",$temp,10);
Cache::put("$torneo.$mesat.table",$table,10);
$datos=['tabla'=>$table,'cartas'=>$cartas,'mesa'=>$temp,'chat'=>'turn','torneo'=>$torneo,'mesat'=>$mesat];
$this->send($datos);

        return;


}

public function river($torneo,$mesat)
{
		 
$table=Cache::get("$torneo.$mesat.table");

 $mesa=Cache::get("$torneo.$mesat.mesa");
 $cartas=Cache::get("$torneo.$mesat.cartas");



$temp=[$mesa[0],$mesa[1],$mesa[2],$mesa[3],$mesa[4]];
$table['position']['step']='vistas';

Cache::put("$torneo.$mesat.mesavi",$temp,10);

Cache::put("$torneo.$mesat.table",$table,10);
$datos=['tabla'=>$table,'cartas'=>$cartas,'mesa'=>$temp,'chat'=>'river','torneo'=>$torneo,'mesat'=>$mesat];
$this->send($datos);


        return ;


}


public function vistas($torneo="torneo1",$mesat=1)
{
		 
$table=Cache::get("$torneo.$mesat.table");

 $cartas=Cache::get("$torneo.$mesat.cartas");

 $mesa=Cache::get("$torneo.$mesat.mesa");


$mesaR=$mesa[0].' '.$mesa[1].' '.$mesa[2].' '.$mesa[3].' '.$mesa[4];
$j=1;
$lasttemp=[];


$mano='';
$l=4;

$super=[10000,10000,10000,10000];
$rank=['','','',''];
while ($j <= 4):
 


if($table["players"][$j-1]=== 0 && !$table['allin'][$j-1]){


} else {

	$mano1=$cartas["ply{$j}"][0];
	$mano2=$cartas["ply{$j}"][1];

$total1=$mesaR.' '.$mano1.' '.$mano2;
exec("/var/www/html/submit/poker.py $total1",$outputhand);
exec("/var/www/html/submit/poker-nice.py $outputhand[0]",$outputrank);

$super[$j-1]=(int)$outputhand[0];
$rank[$j-1]=$outputrank;
$outputhand=[];
$outputrank=[];



}
$j++;
endwhile;
$u=true;
$a=true;
while ($u):

	$winner = array_search(min($super), $super)+1;
		$super[$winner-1]=10000;

		if($table['bets'][$winner-1] < max($table['bets']))
		{
		$j=0;
			if ($a){
			$output= 'el ganador es: Jugador '.$winner.'con: '.json_encode($rank[$winner-1]);
			$a=false;
			}
		while ($j <= 3): // num plys

		if(($winner-1) !== $j){

			if($table['bets'][$j] < $table['bets'][$winner-1] ) 
			{
			$table['money']["ply{$winner}"]=$table['money']["ply{$winner}"]+$table['bets'][$j];

			$table['pot']=$table['pot']-$table['bets'][$j];
			$table['bets'][$j]=0;
	
			} else {

			$table['money']["ply{$winner}"]=$table['money']["ply{$winner}"]+$table['bets'][$winner-1];

			$table['bets'][$j]=$table['bets'][$j]-$table['bets'][$winner-1];
			$table['pot']=$table['pot']-$table['bets'][$winner-1];

			}

		}else{
		$table['money']["ply{$winner}"]=$table['money']["ply{$winner}"]+$table['bets'][$winner-1];
					$table['pot']=$table['pot']-$table['bets'][$winner-1];
		$table['bets'][$winner-1]=0;

		}
		$j++;
		endwhile;
		} else{



		$table['money']["ply{$winner}"]=$table['money']["ply{$winner}"]+$table['pot'];
		$output= 'el ganador es: Jugador '.$winner.'con: '.json_encode($rank[$winner-1]);

		$u=false;
		}

		if(min($super)=== 10000){



			$u=false;
		}

	endwhile;




//echo 'mano',$mano,' total ',$total;
//exec("/var/www/html/submit/poker.py $total",$output, $ret_code);


//echo json_encode($output),'dealer',$table['position']['dealer'];





	
Cache::put("$torneo.$mesat.table",$table,10);
$datos=['tabla'=>$table,'cartas'=>$cartas,'mesa'=>$mesa,'chat'=>$output,'torneo'=>$torneo,'mesat'=>$mesat];
    $job= Carbon::now()->addSeconds(12);
    Cache::put($datos['torneo'].".".$datos['mesat'].".job",$job,5);
$this->send($datos);
	     	 $job = (new AutoPost($torneo,$mesat))
                 ->delay(Carbon::now()->addSeconds(12));
 dispatch($job);


return;
}

}
