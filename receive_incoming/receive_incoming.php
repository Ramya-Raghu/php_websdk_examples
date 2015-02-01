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
            // Answer incoming calls
            function answer() {
                console.log("answering")
                $('#status_txt').text('Answering....');
                Plivo.conn.answer();
                $('#status_txt').text('Answered the Call');
                $('#incoming_callbox').hide();
                $('#btn-container').show();
            }
            // Reject incoming calls
            function reject() {
                Plivo.conn.reject();
                $('#status_txt').text('Rejected the Call');
                $('#btn-container').hide();
                $('#incoming_callbox').hide();
            }
            // Incoming calls
            function onIncomingCall(account_name, extraHeaders) {
                console.log("onIncomingCall:"+account_name);
                console.log("extraHeaders=");
                for (var key in extraHeaders) {
                    console.log("key="+key+".val="+extraHeaders[key]);
                }
                $('#status_txt').text('Incoming Call');
                $('#incoming_callbox').show('slow');
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
            function mute() {
                Plivo.conn.mute();
                console.log("on Mute");
                $('#status_txt').text('on Mute');
                $('#linkUnmute').show('slow');
                $('#linkMute').hide('slow');
            }
            function unmute() {
                Plivo.conn.unmute();
                console.log("Unmuted");
                $('#status_txt').text('Unmuted');
                $('#linkUnmute').hide('slow');
                $('#linkMute').show('slow');
            }
            function hangup() {
                $('#status_txt').text('Hanging up..');
                Plivo.conn.hangup();
                $('#btn-container').hide();
                $('#incoming_callbox').hide();
                console.log("Ready");
                $('#status_txt').text('Ready');
            }
            // Initialization 
            $(document).ready(function() {
                Plivo.onWebrtcNotSupported = webrtcNotSupportedAlert;
                Plivo.onReady = onReady;
                Plivo.onLogin = onLogin;
                Plivo.onLoginFailed = onLoginFailed;
                Plivo.onIncomingCall = onIncomingCall;
                Plivo.onMediaPermission = onMediaPermission;
                Plivo.init();
            });
        </script>
    </head>
    <body>
        <img class="muted" src="assets/logo.png">
        <span class="label" id="status_txt">Waiting Login</span><br/><br/>
        <form id="callcontainer" style="">
            <a href="#" onclick="login();">Login</a>
        </form>
        </form>
            <div id="incoming_callbox" style="display: none;" class="call">
                <a href="#" class="btn main-btn btn-success" onclick="answer()">Answer</a>
                <a href="#" class="btn main-btn btn-danger" onclick="reject()">Reject</a>
            </div>
            <div id="btn-container" style="display: none">
                <a href="#" id="hangup_call" class="btn main-btn btn-danger" onclick="hangup();">Hangup</a>
                <a href="#" id="linkMute" class="badge badge-warning" onclick="mute();">Mute</a>
                <a href="#" id="linkUnmute" class="badge badge-warning" onclick="unmute();" style="display: none">Unmute</a><br/>
            </div>
        </form>
    </body>
</html>