<html>
    <head>
        <title>Timeline</title>
    </head>
    <body >
    <h1>New Users</h1>
    <div  >
    <ul id="tes">
        <li  v-for="user in users">@{{ user.name }}</li>
        <div id="tes2">

        </div>
    </ul>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.2/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>


    <script>
        $.getJSON('/api/attendanceall', function(data){
            var html = '';
            var len = data.length;
            for (var i = 0; i< len; i++) {
                html += '<li>' + data[i].tanggal + '</li>';
            }
            $('#tes2').append(html);
        });
    </script>

    <script>
        var socket=io('http://eabsen.dev:3000');
        new  Vue({
            el:'#tes',

            data:{
                users:[]
            },

            mounted:function () {
                socket.on('test-channel:App\\Events\\UserSignedUp',function(message){
                    this.users.unshift(message.username);
                }.bind(this));

            }
        });
    </script>
    </body>
</html>