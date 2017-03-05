
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>

<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
 <script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>
</script>
    <script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    <script src="/js/app.js"></script>
<link rel="stylesheet" href="{{ elixir('css/app.css') }}">
     <script type="text/javascript">

    </script>
<div class="row-md-16">
<div class="col-md-3" >
<label id="ply1"></label><br>
<img id="Aply1" src="../pokerimage/{{isset($datos[0]) ? $datos[0] :'red_joker'}}.png" width="100" height="150">
<img id="Bply1" src="../pokerimage/{{isset($datos[4]) ? $datos[4] :'red_joker'}}.png" width="100" height="150">
</div>
<div class="col-md-3">
<label id="ply2"></label><br>
<img id="Aply2" src="../pokerimage/{{isset($datos[1]) ? $datos[1] :'red_joker'}}.png" width="100" height="150">
<img id="Bply2" src="../pokerimage/{{isset($datos[5]) ? $datos[5] :'red_joker'}}.png" width="100" height="150">
</div>

<div class="col-md-3">
<label id="ply3"></label><br>
<img id="Aply3" src="../pokerimage/{{isset($datos[2]) ? $datos[2] :'red_joker'}}.png" width="100" height="150">
<img id="Bply3" src="../pokerimage/{{isset($datos[6]) ? $datos[6] :'red_joker'}}.png" width="100" height="150">
</div>
<div class="col-md-3">
<label id="ply4"></label><br>
<img id="Aply4" src="../pokerimage/{{isset($datos[3]) ? $datos[3] :'red_joker'}}.png" width="100" height="150">
<img id="Bply4" src="../pokerimage/{{isset($datos[7]) ? $datos[7] :'red_joker'}}.png" width="100" height="150">
</div>
</div>
<br>
<div class="row-md-16">
<div class="col-md-6" >
<label>Mesa</label><br>
<img src="../pokerimage/red_joker.png" width="100" height="150" id="mesa1">
<img src="../pokerimage/red_joker.png" width="100" height="150" id="mesa2">
<img src="../pokerimage/red_joker.png" width="100" height="150" id="mesa3">
<img src="../pokerimage/red_joker.png" width="100" height="150" id="mesa4">
<img src="../pokerimage/red_joker.png" width="100" height="150" id="mesa5">
</div>
<div class="col-md-6" >
<label id="bote">Pot </label><br>
<textarea rows="4" cols="30" id="textarea">
At w3schools.com you will learn how to make a website. We offer free tutorials in all web development technologies.
</textarea>
</div>

</div>
<br>
<div id="formulario">

                            <form action="bet" method="POST">
                                <input type="number" name="bet" value=0 min=200 id="raise">
                                <input type="hidden" name="prebet" value=0 id="prebet">
                                <input type="hidden" name="torneo" value='' id="torneo">
                                <input type="hidden" name="mesat" value='' id="mesat">


                                

                                <input type="button" value="Send" class="btn btn-success send-msg">
 <button value="0" class="btn btn-danger" id="call">Call  <span id="calltext"></span></button>
 <button value="0" class="btn btn-danger" id="fold">Fold</button>

                            </form>


 <input type="button" value="New Game" class="btn btn-danger" id="game">

 <input type="button" value="Ready" class="btn btn-danger" id="hand">
                                    <label id="turn"></label><br>


</div>

                <script>

    var one='1';

var dos = {!! json_encode($ply) !!};
console.log('dos '+dos)

        window.Echo.private(`chat-room.${dos}`)
    .listen('ChatMessageWasReceived', (e) => {
        

console.log('evento '+e.data);
dealer1='';
dealer2='';
dealer3='';
dealer4='';

switch (e.data.tabla.position.dealer) {



    case 1:
        dealer1 = "Dealer";
        break;
    case 2:
        dealer2 = "Dealer";
        break;
    case 3:
        dealer3 = "Dealer";
        break;
    case 4:
        dealer4 = "Dealer";
        break;

} 


$("#turn").text("Turno:"+e.data.tabla.position.turn+' Last '+e.data.tabla.position.last+' '+e.data.tabla.position.step);
$("#ply1").text(e.data.jugadores[0]['user']+": "+e.data.tabla.money.ply1+' '+dealer1);
$("#ply2").text(e.data.jugadores[1]['user']+": "+e.data.tabla.money.ply2+' '+dealer2);
$("#ply3").text(e.data.jugadores[2]['user']+": "+e.data.tabla.money.ply3+' '+dealer3);
$("#ply4").text(e.data.jugadores[3]['user']+": "+e.data.tabla.money.ply4+' '+dealer4);
$("#bote").text("bote: "+e.data.tabla.pot);

$("#textarea").prepend(e.data.chat+'\n');

$("#Aply1").attr('src', '../pokerimage/'+e.data.cartas.ply1[0]+'.png');
$("#Bply1").attr('src', '../pokerimage/'+e.data.cartas.ply1[1]+'.png');
$("#Aply2").attr('src', '../pokerimage/'+e.data.cartas.ply2[0]+'.png');
$("#Bply2").attr('src', '../pokerimage/'+e.data.cartas.ply2[1]+'.png');
$("#Aply3").attr('src', '../pokerimage/'+e.data.cartas.ply3[0]+'.png');
$("#Bply3").attr('src', '../pokerimage/'+e.data.cartas.ply3[1]+'.png');
$("#Aply4").attr('src', '../pokerimage/'+e.data.cartas.ply4[0]+'.png');
$("#Bply4").attr('src', '../pokerimage/'+e.data.cartas.ply4[1]+'.png');


if (typeof e.data.mesa[0] !== 'undefined'){
var m1='../pokerimage/'+e.data.mesa[0]+'.png';
} else {
    m1='../pokerimage/red_joker.png'
}
if (typeof e.data.mesa[1] !== 'undefined'){
var m2='../pokerimage/'+e.data.mesa[1]+'.png';
} else {
    m2='../pokerimage/red_joker.png'
}
if (typeof e.data.mesa[2] !== 'undefined'){
var m3='../pokerimage/'+e.data.mesa[2]+'.png';
} else {
    m3='../pokerimage/red_joker.png'
}
if (typeof e.data.mesa[3] !== 'undefined'){
var m4='../pokerimage/'+e.data.mesa[3]+'.png';
} else {
    m4='../pokerimage/red_joker.png'
}
if (typeof e.data.mesa[4] !== 'undefined'){
var m5='../pokerimage/'+e.data.mesa[4]+'.png';
} else {
    m5='../pokerimage/red_joker.png'
}


$("#torneo").attr('value',e.data.torneo);
$("#mesat").attr('value',e.data.mesat);


console.log('esto pasa: '+'e.data.jugadores ' +e.data.jugadores[(e.data.tabla.position.turn)-1]['id']+' dos: '+dos)
if (e.data.jugadores[(e.data.tabla.position.turn)-1]['id'] == dos){

$("#formulario").show()

} else {
   $("#formulario").hide() 
}
$("#mesa1").attr('src', m1);
$("#mesa2").attr('src', m2);
$("#mesa3").attr('src', m3);
$("#mesa4").attr('src', m4);
$("#mesa5").attr('src', m5);

var llama =Math.max.apply(Math, e.data.tabla.bets);




var money = e.data.tabla.bets[e.data.tabla.position.turn-1];
console.log('llama'+money)

$("#call").attr('value', llama-money);
$("#calltext").text(llama-money);
$("#prebet").attr('value',money);
$("#raise").attr('min', (llama*2)-money);
$("#raise").attr('value', (llama*2)-money);
});


$("#call").click(function(e){

        e.preventDefault();

        var bet = Number($(this).val())
        var torneo = $("input[name='torneo']").val();
        var mesat = $("input[name='mesat']").val();


            $.ajax({

                type: "POST",

                url: '{!! URL::to("bet") !!}',

                dataType: "json",

                data: {'bet':bet,'torneo':torneo,'mesat':mesat},

                success:function(data){

                    console.log(data);

                    $("bet").val('');

                }

            });

        

    })
$("#fold").click(function(e){

        e.preventDefault();

        var bet = 0;

        var torneo = $("input[name='torneo']").val();
        var mesat = $("input[name='mesat']").val();


            $.ajax({

                type: "POST",

                url: '{!! URL::to("bet") !!}',

                dataType: "json",

                data: {'bet':bet,'torneo':torneo,'mesat':mesat},

                success:function(data){

                    console.log(data);

                    $("bet").val('');

                }

            });

        

    })
    $(".send-msg").click(function(e){

        e.preventDefault();

        var bet = $("input[name='bet']").val();
        var torneo = $("input[name='torneo']").val();
        var mesat = $("input[name='mesat']").val();



        if(bet != ''){

            $.ajax({

                type: "POST",

                url: '{!! URL::to("bet") !!}',

                dataType: "json",

                data: {'bet':bet,'torneo':torneo,'mesat':mesat},

                success:function(data){

                    console.log(data);

                    $("bet").val('');

                }

            });

        }else{

            alert("Please Add Message.");

        }

    })




        $("#hand").click(function(e){

        e.preventDefault();




  

            $.ajax({

                type: "GET",

                url: '{!! URL::to("hand") !!}',

                success:function(data){

                    console.log(data);

                   

                }

            });

     

    })
                $("#game").click(function(e){

        e.preventDefault();




  

            $.ajax({

                type: "GET",

                url: '{!! URL::to("game") !!}',

                success:function(data){

                  

                   

                }

            });

     

    })
                                $("#game").click(function(e){

        e.preventDefault();




  

            $.ajax({

                type: "GET",

                url: '{!! URL::to("game") !!}',

                success:function(data){

                  

                   

                }

            });
})
     

</script>
