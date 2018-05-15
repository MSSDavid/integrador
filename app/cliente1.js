var channel = "canalIntegrador" ;
var client1 = null;

var key = 'qExP4jdAhb8p9GF_yMS6ZjTSTfGErzQR';

function toggleDevice1 (){
    client1 = toggleConnection(client1, "Margaret");
    console.log(client1);
    if(client1){
        $('.avatar-marg').removeClass('avatar-login').addClass('avatar-logout');
        $('.btn-user1').removeClass('btn-login').addClass('btn-logout');
        $('.btn-user1').html('logout');
    }else{
        $('.avatar-marg').removeClass('avatar-logout').addClass('avatar-login');
        $('.btn-user1').removeClass('btn-logout').addClass('btn-login');
        $('.btn-user1').html('login');
    }
}

/**
 * Function that togggles the connection on a particular emitter client.
 */
function toggleConnection(client, name) {
    if(client) {
        // If client is already connected, disconnect it
        client.disconnect();
        return null;
    } else {
        // If client is not yet connected, connect and subscribe to the channel
        client = emitter.connect({ secure: true, username: name });
        client.on('connect', function(){
            client.subscribe({
                key: key,
                channel: channel
            });
        });
        return client;
    }
}