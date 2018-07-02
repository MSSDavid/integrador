[![Run Status](https://api.shippable.com/projects/5b286fd26104a90700905497/badge?branch=master)](https://app.shippable.com/github/MSSDavid/integrador)
[![Coverage Badge](https://api.shippable.com/projects/5b286fd26104a90700905497/coverageBadge?branch=master)](https://app.shippable.com/github/MSSDavid/integrador)  

# Integrador
Repositório criado para o trabalho onde serão armazenados os artefatos para a criação de um software de integração do GitHub com o Google Drive na disciplina de Integração de Aplicações - 2018.1

## Projeto
O objetivo é fazer com que cada commit feito no GitHub seja enviado ao Google Drive através do método de integração de envio de mensagens.
Isto garantirá uma cópia sobressalente dos arquivos do desenvolvedor que utilizar esta API.

## Dados da Mensagem.
Os dados a serem trocados serão: repositório (nome e link), código do commit (e também data, horário, usuário e mensagem), e metadados da comunicação (para um eventual log de envios de mensagens). O arquivo estará em formato JSON.

## Como testar o sistema

Ao executar o "demo" será possível um exemplo de JSON que pode ser enviado pela API. 
Este JSON será posteriormente transformado numa String, e ao selecionar o botão "Enviar Mensagem", o servidor (que pode ser aberto ao clicar no botão Abrir Servidor).
Com o servidor aberto, ao selecionar o botão: "Receber Mensagem" exibirá a mensagem que foi enviada. 
Caso nenhuma mensagem tenha sido previamente enviada, é esperado uma mensagem de erro.

## Frequência de Comunicação
As aplicações terão mensagens enviadas sempre que houver um novo commit no repositório escolhido.
Porém há um limite de 5000 solititações à API do GitHub por hora.

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
