<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demonstração</title>
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
    <span class="navbar-brand mb-0 h1">Demonstração - Gerador de Mensagens</span>
  </nav>
  <div class="container">
    <div style="text-align: center; margin-top:40px">
      <a href="server.php" target="_blank" class="btn btn-primary">Abrir Servidor</a>
    </div>
    <div style="text-align: center">
      <h2>Mensagem a ser Enviada</h2>
      <blockquote style="background-color: #CCC; width: fit-content; margin: auto;text-align: left; padding: 25px; border-radius: 5px;">
        {<br>
          &emsp;"user": "<span id="user"></span>",<br>
          &emsp;"commit": "<span id="commit"></span>"<br>
        }
      </blockquote>
    </div>
     <div style="text-align: center; margin-top:20px">
    <button type="button" id="gerarMensagem" class="btn btn-primary">Gerar Mensagem</button>
    </div>
    <div style="text-align: center; margin-top:10px">
    <button type="button" id="enviarMensagem" class="btn btn-primary">Enviar Mensagem</button>
    </div>
  </div>

  		

<script>
    
    var mensagem = { user:"", commit: ""};
    $(document).ready(function(){
         gerarMensagem();
        $("#gerarMensagem").click(function(){
           gerarMensagem();
        }); 
        $("#enviarMensagem").click(function(){
           send();
        });
    });

      function send(){   
         var json = {"message": JSON.stringify(mensagem)};
         $.ajax({
         url: "put.php",
         type: 'post',
         dataType: 'json',
         contentType: 'application/json',
         data: JSON.stringify(json),
         sucess: function(data){
         console.log(data);
        }
      });
    }

   function gerarMensagem(){
       let commit = stringAleatoria(10);
       sha256(commit).then(function(digest) {
       commit = digest;
       let user = userAleatorio();
       alterarMensagem(user, commit);

    });
   }

   function alterarMensagem(user,commit){
      mensagem.user = user;
      mensagem.commit = commit;
      console.log(mensagem);
      $("#user").html(mensagem.user);
      $("#commit").html(mensagem.commit);

 }

   function userAleatorio(){
     return Math.floor(Math.random()* 10000);
   }

   function stringAleatoria(tamanho){
       let letras = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
       let aleatorio = '';
       for (let i = 0; i < tamanho; i++) {
          let rnum = Math.floor(Math.random() * letras.length);
          aleatorio += letras.substring(rnum, rnum + 1);
    }
       return aleatorio;
   }

  function sha256(str) {
     var buffer = new TextEncoder("utf-8").encode(str);
     return crypto.subtle.digest("SHA-256", buffer).then(function (hash) {
     return hex(hash);
  });
}

function hex(buffer) {
  var hexCodes = [];
  var view = new DataView(buffer);
  for (var i = 0; i < view.byteLength; i += 4) {
    var value = view.getUint32(i)
    var stringValue = value.toString(16)
    var padding = '00000000'
    var paddedValue = (padding + stringValue).slice(-padding.length)
    hexCodes.push(paddedValue);
  }
  return hexCodes.join("");
}

</script>

</body>
</html>