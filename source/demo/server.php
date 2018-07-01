<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servidor Demonstração</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
  
	<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
</head>
<body>

  <nav class="navbar navbar-primary bg-primary">
    <span class="navbar-brand mb-0 h1">Demonstração - Servidor</span>
  </nav>

  <div class="container" style="margin-top:40px">
      <div style="text-align: center; display:none;" id="container-mensagem">
      <h2>Mensagem Recebida</h2>
      <blockquote style="background-color: #CCC; width: fit-content; margin: auto;text-align: left; padding: 25px; border-radius: 5px;">
        {<br>
          &emsp;"user": "<span id="user"></span>",<br>
          &emsp;"commit": "<span id="commit"></span>"<br>
        }
      </blockquote>

    </div>
      <div style="text-align: center;display: none;" id="container-vazio">
        <b style="color:red">Fila Vazia</b>
      </div>
      <div style="text-align: center;display: none;" id="container-erro">
        <b style="color:red">Erro ao conectar no canal</b>
      </div>
      <div style="text-align: center; margin-top:30px">
       <button type="button" id="pegarMensagem" class="btn btn-primary">Pegar Mensagem</button>
     </div>
  </div>

<script>

     $(document).ready(function(){
        $("#pegarMensagem").click(function(){
           get();
        }); 
    });

     function get(){
       $.getJSON("get.php", function(data){
            if(data.response == 'ok'){
                if(data.message == null){
                  $('#container-mensagem').slideUp();
                 $('#container-vazio').slideDown();
                 $('#container-erro').slideUp();
                }else{
                  let mensagem = JSON.parse(data.message); 
                  $("#user").html(mensagem.user);
                  $("#commit").html(mensagem.commit);
                   $('#container-mensagem').slideDown();
                 $('#container-vazio').slideUp();
                 $('#container-erro').slideUp();
                }
                
            }else{
               $('#container-mensagem').slideUp();
               $('#container-vazio').slideUp();
               $('#container-erro').slideDown();
            }
       });           

    }

</script>


</body>
</html>