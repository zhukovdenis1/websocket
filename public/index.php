<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat</title>
    <script
            src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
            crossorigin="anonymous">
    </script>
</head>
<body>
<?php

$email = $_GET['email'] ?? null;

if ($email) {
    ?>
    <section>
        <p>
            Email: <?php echo $email?>
        </p>
        <p>
            <input type="text" placeholder="message" id="message" style="width: 895px;padding: 5px;" />
            <button id="sendButton" style="width: 100px">Отправить</button>
        </p>
        <div id="chat" style="width: 1000px;height: 200px; overflow:scroll;border: 1px solid gray;padding: 5px;">

        </div>
    </section>
    <script>
        let socket = new WebSocket('ws://127.0.0.1:8081');
        let email = '<?=$email?>';

        socket.onopen = function () {
            console.log('onopen');
            // socket.send('email:zd1@list.ru');
            socket.send(JSON.stringify({
                'email': '<?php echo $email?>'
            }));
        }
        socket.onmessage = function (event) {
            let data = JSON.parse(event.data);
            console.log(data);
            $('#chat').append('<p><b>' + data.email + '</b>:' + data.message + '</p>');
        }

        $('#sendButton').click(function() {
            let message = $('#message').val();
            socket.send(JSON.stringify({
                'message': message
            }));
        })
    </script>
    <?php
} else {
    echo '<p>Email was not provided</p>';
}
?>

</body>
</html>