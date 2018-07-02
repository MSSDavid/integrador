[![Run Status](https://api.shippable.com/projects/5b286fd26104a90700905497/badge?branch=master)](https://app.shippable.com/github/MSSDavid/integrador)
[![Coverage Badge](https://api.shippable.com/projects/5b286fd26104a90700905497/coverageBadge?branch=master)](https://app.shippable.com/github/MSSDavid/integrador)  

# Integrador
Implementação de uma solução de integração na qual utiliza o método de troca de mensagens da disciplina de Integração de Aplicações - 2018.1.

## Projeto
No contexto da disciplina Integração de Aplicações, de Engenharia de software -
UFG, para o trabalho final foi definido uma aplicação de integração, que utilize o meio da técnica
de troca de mensagens.  
 
 O problema a ser resolvido será a comunicação entre dois microserviços de uma aplicação que oferece o serviço que realiza replicações de segurança de repositórios do GitHub na núvem do Google Drive.  

Esta aplicação deverá possuir suporte para milhares usuários cadastrados, portanto a aplicação deve estar preparada para receber vários commits por segundo. A tarefa de realizar a cópia do GitHub para o Google Drive não é instantânea, pois necessitará de recursos de rede e disco principalmente. Para realizar toda a tarefa demandada, uma fila de processamento deve ser estabelecida, por isso a divisão em dois microserviços.  

O microserviço A tem a responsabilidade de ficar "escutando" os repositórios cadastrados na aplicação, a cada commit identificado pelo microserviço uma mensagem deve ser colocada na fila de processamento.  

O microserviço B tem a responsabilidade de consumir a fila de processamento alimentada pelo microserviço A, quando um servidor de processamento (podem existir vários servidores) tiver disponível para realizar tarefas, uma mensagem é consumida da fila.  

## Dados da Mensagem.
Os dados a serem trocados serão: identificação do usuário (a identificação do usuário na aplicação de integração) e o código do commit. A partir do código do commit é possível fazer requisições na API do github para recuperar as informações das alterações realizadas no respectivo commit. O arquivo utilizará este esquema no formato JSON, conforme o exemplo abaixo:  
![Mensagem exemplo](https://github.com/MSSDavid/integrador/blob/master/docs/images/msg_exemplo.png)

## Fluxo Arquitetural
![Fluxo Arquitetural](https://github.com/MSSDavid/integrador/blob/master/docs/images/arquitetura.png)  

## Como testar o sistema

Ao executar a demonstração será possível ver a aplicação funcionando no esquema JSON definido, no qual é gerado automaticamente e aleatoriamente pela demonstração.   
Para o envio da mensagem, este JSON é convertido para um dado do tipo String, e ao clicar no botão "Enviar Mensagem", o JSON gerado é colocado na fila para ser consumido.  
No botão "Abrir simulação servidor", é aberto uma demonstração de um servidor consumidor da fila, com esta página aberta, basta clicar no botão "Receber Mensagem" para a próxima mensagem da fila seja consumida e mostrada na tela.    
Para maiores detalhes técnicos do funcionamento da demonstração, basta fazer o acesso na mesma.

Para acessar a demonstração online [clique aqui](https://integrador2017.000webhostapp.com/).

## Funcionamento da Classe

![Diagrama UML - Queue.php](https://github.com/MSSDavid/integrador/blob/master/docs/Diagrama%20de%20Classes/Diagrama%20de%20Classes%20UML.jpg)  

Foi implementada uma classe chamada Queue, na qual possui a capacidade de colocar uma mensagem na fila, e ler a próxima mensagem da fila.

Para instanciar a classe é necessário pelo menos o host(URL) do CloudAmqp e o nome do canal a ser utilizado (caso o canal não exista, ele é criado automaticamente), ambos em String.

Para realizar o envio da mensagem basta invocar o método `putMessage($message)`, passando com parâmetro a mensagem desejada em String, a função retornará `true` caso a mensagem tenha sido inserida com sucesso na fila, ou `false` caso ocorra algum problema para realizar a operação. 

Para consumir uma mensagem da fila basta invocar o método `getMessage()`, nele não é necessário passar nenhum parâmetro, o retorno poderá ser `false` caso ocorra algum erro para pegar a mensagem, `null` caso a fila esteja vazia, ou então uma String, na qual é a própria mensagem consumida do canal.

Além dos parâmetros obrigatórios (host e canal) do construtor da classe, existem dois outros parêmetros opcionais que podem ser ou não informados. O primeiro parâmetro é o `$auto_connection`, e o segundo é o `$debug_mode`, ambos são do tipo booleano.

O parâmetro `$auto_connection` por padrão recebe o valor `true`, nele é definido se os métodos `putMessage($message)` e `getMessage()` irão conectar e desconectar automaticamente do servidor de troca de mensagens. Caso seu valor seja `false`, após instanciar a classe e antes de executar os métodos é necessário executar o método `openConnection()`, neste método como o nome diz, é realizada uma conexão com o servidor de troca de mensagens, o que permite executar várias operações reaproveitando uma única conexão, diminuindo o consumo do servidor, após executar as operações com o servidor, também é necessário utilizar o método `closeConnection()`, que irá simplemente fechar a conexão com o servidor. Quando o `$auto_connection` está definido como `true` estas operações de abrir e fechar conexão são feitas automaticamente a cada chamada do método `getMessage()` ou `putMessage($message)`.  

O parâmetro `$debug_mode` por padrão recebe o valor `false`, através deste parâmetro é ativado ou não o modo de Debug da classe, caso esteja definido como `true`, os métodos `getMessage()` e `putMessage($message)` passam a retornar um array, em ambam funções este array sempre tem a chave `response`, seu valor será `true` caso o método tenha sido executado como esperado ou `false` caso contrária. Quando a `response` retorna `false`, existirá a chave `error` com os detalhes do problema ocorrido. No método `getMessage()` quando a chave `response` retorna `true`, também existirá a chave `message` com a mensagem consumida do canal.  

Além dos métodos `getMessage()` e `putMessage($message)` a classe também possui os métodos `getUrl`, `setUrl($url)`, `getChannel` e `setChannel($channel)` nos quais retornam e alteram os parâmetros obrigatórios do construtor da classe. É possível executar os métodos `setUrl($url)` e `setChannel($channel)` retornam `true` caso a conexão com o servidor não esteja aberta, caso contrário irá retornar `false`.  

## Como executar a classe
É necessário a instalação da biblioteca [php-amqplib](https://github.com/cloudamqp/php-amqplib-example) utilizando o [Composer](https://getcomposer.org/). Com a biblioteca instalado é possível instanciar a classe.  
Para maiores detalhes da configuração com Composer, no arquivo [composer.json](https://github.com/MSSDavid/integrador/blob/master/composer.json) está a referências das bibliotecas utilizadas no projeto.

## Frequência de Comunicação
As aplicações terão mensagens enviadas sempre que houver um novo commit no repositório escolhido.
Porém há um limite de 5000 solititações à API do GitHub por hora.

## Tecnologias e soluções para infraestrutura e projeto
* Para armazenar a fila de mensagens é utilizado o [CloudAMQP](https://cloudamqp.com), no qual se instala e utiliza clusters [RabbitMQ](https://rabbitmq.com), atuando como um serviço para troca de mensagens.  
* Liguagem utilizada [php](https://secure.php.net) 7.1
* Biblioteca [php-amqplib](https://github.com/cloudamqp/php-amqplib-example) para fazer as interações com o servidor de troca de mensagens.
* Para documentação do código fonte do projeto, foi utilizado o padrão de escrita PHPDoc e a biblioteca [phpDocumentor](https://www.phpdoc.org/) para gerar a interface web da documentação do projeto.
* O GitHub armazena os artefatos e faz o Gerenciamento de Configuração do Projeto.
* Para realização os testes unitários e métricas de cobertura de código, foi utilizado o [PHPUnit](https://phpunit.de/).
* Para a integração contínua do projeto foi utilizado o [Shippable](https://www.shippable.com/).
* Para o gerenciamento das bibliotecas a serem utilizadas pelo projeto, foi utilizado o [Composer](https://getcomposer.org/)

## Integrantes

| Integrante | Função |
|:-:|:-:|
 David Matheus Santos Sousa | Documentação |
 Guilherme Alves Rosa Silva | Documentação |
 João Pedro Salgado Di Cavalcanti Cunha | Desenvolvedor | 
 Samuel Rocha Costa | Desenvolvedor | 
 
 ## Documentação do Código Fonte
 [Clique aqui](https://mssdavid.github.io/integrador/) para acessar a documentação.
 
 ## Comunicação
 Pessoal e WhatsApp.

## Ferramentas
Visual Studio Code, GitHub, Emitter-io.

## Linguagens Utilizadas

Javascript,
HTML
