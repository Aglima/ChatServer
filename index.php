<?php
    $colours = array('007AFF', 'FF7000', 'FF7000', '15E25F', 'CFC700', 'CFC700', 'CF1100', 'CF00BE', 'F00');
    $user_colour = array_rand($colours);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Chat</title>
        <meta charset='UTF-8' />
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script language="javascript" type="text/javascript">
            $(document).ready(function() {
                var wsUri = "ws://localhost:9000/chat/server.php";
                websocket = new WebSocket(wsUri);

                websocket.onopen = function() {
                    $('#message_box').append("<div class=\"system_msg\">Verbunden!</div>");
                };

                $('#send-btn').click(function() {
                    var mymessage = $('#message').val();
                    var myname = $('#name').val();

                    if (myname === "") {
                        alert("Du musst einen Namen angeben!");
                        return;
                    }
                    if (mymessage === "") {
                        alert("Du musst eine Nachricht eingeben!");
                        return;
                    }

                    var msg = {
                        message: mymessage,
                        name: myname,
                        color: '<?php echo $colours[$user_colour]; ?>'
                    };

                    websocket.send(JSON.stringify(msg));
                });

                websocket.onmessage = function(ev) {
                    var msg = JSON.parse(ev.data);
                    var type = msg.type;
                    var umsg = msg.message;
                    var uname = msg.name;
                    var ucolor = msg.color;

                    if (type === 'usermsg')
                    {
                        $('#message_box').append("<div><span class=\"user_name\" style=\"color:#" + ucolor + "\">" + uname + "</span> : <span class=\"user_message\">" + umsg + "</span></div>");
                    }
                    if (type === 'system')
                    {
                        $('#message_box').append("<div class=\"system_msg\">" + umsg + "</div>");
                    }
                    $('#message').val('');
                };

                websocket.onerror = function(ev) {
                    $('#message_box').append("<div class=\"system_error\">Fehler - " + ev.data + "</div>");
                };
                websocket.onclose = function() {
                    $('#message_box').append("<div class=\"system_msg\">Verbindung unterbrochen.</div>");
                };
            });
        </script>
    </head>
    <body>
        <div class="chat_wrapper">
            <div class="message_box" id="message_box"></div>
            <div class="panel">
                <input type="text" name="name" id="name" placeholder="Dein Name" maxlength="10" style="width:20%"  />
                <input type="text" name="message" id="message" placeholder="Nachricht" maxlength="80" style="width:60%" />
                <button id="send-btn">Senden</button>
            </div>
        </div>
    </body>
</html>
