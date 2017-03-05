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

<div>idenr {{$id}}</div>




<div id="formulario">




 <input type="button" value="Torneo1" class="btn btn-danger" id="torneo1">

 <input type="button" value="Ready" class="btn btn-danger" id="hand">


</div>
                <script>

  

var dos = {!! json_encode($id) !!};
console.log('dos '+dos)

        window.Echo.private(`lobby.${dos}`)
    .listen('LobbyMessage', (e) => {

console.log('hello world',e);

       var windowSizeArray = [ "width=200,height=200",
									"width=1200,height=600,scrollbars=yes" ];
  var url = '{!! URL::to("table") !!}/'+dos;
                    var windowName = "popUp";//$(this).attr("name");
                    var windowSize = windowSizeArray[1];

                    window.open(url, windowName, windowSize);

               




    })

                                    $("#torneo1").click(function(e){

        e.preventDefault();




            $.ajax({

                type: "POST",

                url: '{!! URL::to("lobby") !!}',

                dataType: "json",

                data: {'torneo':'torneo1'},

                success:function(data){


                }

            });
        })
    </script>