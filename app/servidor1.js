var channel = "canalIntegrador" ;
var client0 = emitter.connect({ secure: true });

var key = 'AGp07nyrQfR1FNDtzqQKU9L34R5btw_e';
var usuarios = [];
var occupancy = 0;

function geraUsers(){
    $("#users-container").html("");
    for(var i = 0; i < usuarios.length; i++){
        $("#users-container").append('<li>' + usuarios[i].username + '</li>');
    }
}


client0.on('connect', function(){
    // once we're connected, subscribe to the 'chat' channel
    console.log('emitter: connected');

    // Query the presence state
    client0.presence({
        key: key,
        channel: channel
    })
});

// on every presence event, print it out
client0.on('presence', function(msg){
    console.log(msg);
    var users = usuarios;
    switch(msg.event){
        // Occurs when we've received a full response with a complete list of clients
        // that are currently subscribed to this channel.
        case 'status':
            for(var i=0; i<msg.who.length;++i){
                usuarios.push(msg.who[i]);
            }
            break;

        // Occurs when a user subscribes to a channel.
        case 'subscribe':
            usuarios.push(msg.who);
            geraUsers();
            break;

        // Occurs when a user unsubscribes or disconnects from a channel.
        case 'unsubscribe':
            usuarios = users.filter(function( obj ) {
                return obj.id !== msg.who.id;
            });
            geraUsers();
            break;
    }

    // Also, set the occupancy
    occupancy = usuarios.length;
    $('#online-now').html(occupancy);
});