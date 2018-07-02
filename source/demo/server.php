<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servidor Demonstração - Integração de Aplicações</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <style>
        .card-text{
            font-size: 13px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
    <a style="color: white;" class="navbar-brand" href="index.php">Consumidor de Mensagens</a>
</nav>
    <div class="container" style="margin-bottom: 60px">
        <div class="row" style="margin-top: 25px;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="text-align: justify">
                        <h5 class="card-title">Servidor consumidor de mensagens da fila</h5>
                        <p class="card-text">
                            Nesta página é realizada a simulação de um servidor que consome as mensagens de uma fila utilizando a classe Queue.php.<br>
                            Ao clicar no botão "Pegar Mensagem", é realizada uma requisição Ajax do tipo GET no arquivo "get.php", no qual utiliza a função getMessage() para buscar a próxima mensagem da fila do canal "development", o resultado da função é a resposta da requisição, caso a mensagem seja "null", significa que a fila está vazia, caso contrário a mensagem foi consumida da fila com sucesso.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div style="text-align: center;margin-top: 20px;width: fit-content;margin-left: auto;margin-right: auto;max-width: 100%;min-width: 200px;">
            <div style="display: none;" id="container-mensagem">
                <h6 style="text-align: left;">Mensagem Recebida</h6>
                <blockquote style="background-color: #CCC; margin: auto;text-align: left; padding: 25px; border-radius: 5px;">
                    {
                    <br>
                    &emsp;"user": "<span id="user"></span>",<br>
                    &emsp;"commit": "<span id="commit"></span>"<br>
                    }
                </blockquote>
            </div>
            <div id="aguarde" class="text-info" style="margin-top: 10px;text-align: center; display: none;">
                <i class="fa fa-spinner fa-spin fa-fw"></i>
                <b>Aguarde, buscando mensagem...</b>
            </div>
            <div id="vazio" class="text-warning" style="margin-top: 10px;text-align: center; display: none;">
                <i class="fa fa-times"></i>
                <b>Fila vazia</b>
            </div>
            <div id="vazio" class="text-danger" style="margin-top: 10px;text-align: center; display: none;">
                <i class="fa fa-times"></i>
                <b>Erro ao buscar ao conectar no canal</b>
            </div>
            <div style="text-align: center; margin-top:30px">
                <button style="font-size: 14px;" type="button" id="pegarMensagem" class="btn btn-primary">Pegar Mensagem</button>
            </div>
        </div>
    </div>
<script>

    $(document).ready(function(){
        $("#pegarMensagem").click(function(){
            $(this).attr("disabled", true);
            $("#erro").slideUp();
            $("#container-mensagem").slideUp();
            $("#vazio").slideUp();
            $("#aguarde").slideDown();
            setTimeout(get, 0);
        });
    });

    function get(){
        $.getJSON("get.php", function(data){
            $('#pegarMensagem').attr("disabled", false);
            $("#aguarde").slideUp();
            if(data.response == 'ok'){
                if(data.message == null){
                    $('#container-mensagem').slideUp();
                    $('#vazio').slideDown();
                    $('#erro').slideUp();
                }else{
                    let mensagem = JSON.parse(data.message);
                    $("#user").html(mensagem.user);
                    $("#commit").html(mensagem.commit);
                    $('#container-mensagem').slideDown();
                    $('#vazio').slideUp();
                    $('#erro').slideUp();
                }
            }else{
               $('#container-mensagem').slideUp();
               $('#vazio').slideUp();
               $('#erro').slideDown();
            }
        });
    }
</script>
</body>
</html>