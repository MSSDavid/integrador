<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demonstração - Integração de Aplicações</title>
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
        <a style="color: white;" class="navbar-brand" href="index.php">Gerador de Mensagens</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a style="color: white;" class="nav-link" href="server.php">Servidor</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container" style="margin-bottom: 60px;">
        <div class="row" style="margin-top: 25px;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="text-align: justify">
                        <h5 class="card-title">Demonstração do Trabalho Final de Integração de Aplicações (2018/1)</h5>
                        <p class="card-text">
                            Alunos:<br>
                            David Matheus Santos Sousa<br>
                            Guilherme Alves Rosa Silva<br>
                            João Pedro Salgado<br>
                            Samuel Rocha Costa<br>
                        </p>
                        <p class="card-text">
                            Esta demonstração web tem o objetivo de demonstrar o funcionamento da classe Queue.php. Nesta primeira página um JSON é <b>gerado aleatoriamente no esquema definido para a aplicação</b>, a cada clique no botão "Gerar Mensagem", outro JSON aleatório é gerado.<br>
                            Ao clicar no botão "Enviar Mensagem", o JSON que estava gerado na tela é enviado via requisição Ajax do tipo POST para o arquivo "put.php", no qual utiliza a função "putMessage" da classe Queue.php para colocar o json na fila do canal "development".
                        </p>
                        <p class="card-text">Abaixo está o link para a simulação de uma instância de um servidor que consome a fila de mensagens. </p>
                        <a style="font-size: 14px;" href="server.php" target="_blank" class="btn btn-primary">Abrir Simulação Servidor</a>
                    </div>
                </div>
            </div>
        </div>
        <div style="text-align: center;margin-top: 20px;width: fit-content;margin-left: auto;margin-right: auto;max-width: 100%;">
            <h6 style="text-align: left;">Mensagem a ser Enviada</h6>
            <blockquote style="background-color: #CCC; margin: auto;text-align: left; padding: 25px; border-radius: 5px;">
                {
                <br>
                &emsp;"user": "<span id="user" style="word-wrap: break-word;"></span>",<br>
                &emsp;"commit": "<span id="commit" style="word-wrap: break-word;"></span>"<br>
                }
            </blockquote>
            <div style="text-align: left; margin-top:20px">
                <button style="font-size: 14px;" type="button" id="gerarMensagem" class="btn btn-primary">Gerar Mensagem</button>
            </div>
            <div style="text-align: left; margin-top:10px">
                <button style="font-size: 14px;" type="button" id="enviarMensagem" class="btn btn-success">Enviar Mensagem</button>
            </div>
            <div id="aguarde" class="text-info" style="margin-top: 10px;text-align: left; display: none;">
                <i class="fa fa-spinner fa-spin fa-fw"></i>
                <b>Enviando Mensagem</b>
            </div>
            <div id="erro" class="text-danger" style="margin-top: 10px;text-align: left; display: none;">
                <i class="fa fa-times"></i>
                <b>Erro ao Enviar a Mensagem!</b>
            </div>
            <div id="sucesso" class="text-success" style="margin-top: 10px;text-align: left; display: none;">
                <i class="fa fa-check"></i>
                <b>Mensagem enviada com sucesso!</b>
            </div>
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
            $('#sucesso').slideUp();
            $('#erro').slideUp();
            $('#aguarde').slideDown();
            $('#gerarMensagem').attr("disabled", true);
            $('#enviarMensagem').attr("disabled", true);
            setTimeout(send, 0);
        });
    });

    function send(){
        let json = {"message": JSON.stringify(mensagem)};
        $.ajax({
            url: "put.php",
            type: 'post',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(json),
            success: function(data){
                $('#aguarde').slideUp('');
                $('#gerarMensagem').attr("disabled", false);
                $('#enviarMensagem').attr("disabled", false);
                if(data.response == 'ok'){
                    $('#erro').slideUp('');
                    $('#sucesso').slideDown('');
                }else{
                    $('#erro').slideDown('');
                    $('#sucesso').slideUp('');
                }
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
        let buffer = new TextEncoder("utf-8").encode(str);
        return crypto.subtle.digest("SHA-256", buffer).then(function (hash) {
            return hex(hash);
        });
    }

    function hex(buffer) {
        let hexCodes = [];
        let view = new DataView(buffer);
        for(let i = 0; i < view.byteLength; i += 4) {
            let value = view.getUint32(i);
            let stringValue = value.toString(16);
            let padding = '00000000';
            let paddedValue = (padding + stringValue).slice(-padding.length);
            hexCodes.push(paddedValue);
        }
        return hexCodes.join("");
    }

</script>

</body>
</html>