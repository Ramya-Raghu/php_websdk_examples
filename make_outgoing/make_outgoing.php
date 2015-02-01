<?php

$username = "Your SIP Endpoint Username";
$password = "Your SIP Endpoint Password";

?>

<html lang="en">
    <head>
        <script language="javascript" content-type="text/javascript" src="assets/jquery.js"></script>
        <script type="text/javascript" src="http://s3.amazonaws.com/plivosdk/web/plivo.min.js"></script>
        <link href="assets/bootstrap-combined.min.css" rel="stylesheet">
        <script type="text/javascript">
            // Make outgoing calls
            function call() {
                if ($('#make_call').text() == "Call") {
                    // The destination phone number to connect the call to
                    var dest = $("#to").val();
                    if (isNotEmpty(dest)) {
                        $('#status_txt').text('Calling..');
                        // Conect the call
                        Plivo.conn.call(dest);
                        $('#make_call').text('End');
                    }
                    else{
                        $('#status_txt').text('Invalid Destination');
                    }
                }
                else if($('#make_call').text() == "End") {
                    $('#status_txt').text('Ending..');
                    // Hang up the call
                    Plivo.conn.hangup();
                    $('#make_call').text('Call');
                    $('#status_txt').text('Ready');
                }
            }
            // Login with SIP Endpoint
            function login() {
                // SIP Endpoint username and password
                var username = "<?php echo $username; ?>";
                var password = "<?php echo $password; ?>";
                
                // Login
                Plivo.conn.login(username, password);
            }
            function isNotEmpty(n) {
                return n.length > 0;
            }
            function onCalling() {
                console.log("onCalling");
                $('#status_txt').text('Connecting....');
            }
            function  onMediaPermission (result) {
                if (result) {
                    console.log("get media permission");
                } else {
                    alert("you don't allow media permission, you will can't make a call until you allow it");
                }
            }
            function webrtcNotSupportedAlert() {
                $('#txtStatus').text("");
                alert("Your browser doesn't support WebRTC. You need Chrome 25 to use this demo");
            }
            function onLogin() {
                $('#status_txt').text('Logged in');
            }
            function onLoginFailed() {
                $('#status_txt').text("Login Failed");
            }
            function onReady() {
                console.log("onReady...");
            }
            // Initialization 
            $(document).ready(function() {
                Plivo.onWebrtcNotSupported = webrtcNotSupportedAlert;
                Plivo.onReady = onReady;
                Plivo.onLogin = onLogin;
                Plivo.onLoginFailed = onLoginFailed;
                Plivo.onCalling = onCalling;
                Plivo.onMediaPermission = onMediaPermission;
                Plivo.init();
            });
        </script>
    </head>
    <body>
        <img class="muted" src="assets/logo.png">
        <span class="label" id="status_txt">Call</span><br/><br/>
        <form id="callcontainer" style="">
            <input type="text" name="to" value="" id="to" placeholder="Phone number or a SIP URI">
            <br/>
            <a href="#" onclick="login();">Login</a>
            <a href="#" id="make_call" class="btn main-btn btn-success" onclick="call();">Call</a>
        </form>
    </body>
</html>