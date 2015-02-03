<?php

    require_once 'plivo.php';
    $auth_id = "Your AUTH_ID";
    $auth_token = "Your AUTH_TOKEN";
    
    $length = 6;

    $username = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
    $password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
    //echo "<br>$username";
    //echo "<br>$password";

    $p = new RestAPI($auth_id, $auth_token);
    $params = array(
            'username' => $username,
            'password' => $password,
            'alias' => $username
        );
    $response = $p->create_endpoint($params);
    //echo "<br>Response";
    //print_r ($response['response']);
    $uname = $response['response']['username'];
    $end_id = $response['response']['endpoint_id'];
    //echo "<br> Username : $uname";
    //echo "<br> Endpoint ID :$end_id";

?>

<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Plivo Webphone Demo</title>
        <meta name="description" content="">
        <meta name="author" content="">

        <link href="./assets/bootstrap-combined.min.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 20px;
                padding-bottom: 40px;
            }
            .container-narrow {
                margin: 0 auto;
                max-width: 700px;
            }
            .container-narrow > hr {
                margin: 30px 0;
            }
            #btn-container a{
                margin-top:7px;
                min-width:7px;
            }
        </style>

        <script language="javascript" content-type="text/javascript" src="./assets/jquery.js"></script>
        <script type="text/javascript" src="http://s3.amazonaws.com/plivosdk/web/plivo.min.js"></script>

        <script type="text/javascript">
            function webrtcNotSupportedAlert() {
                $('#txtStatus').text("");
                alert("Your browser doesn't support WebRTC. You need Chrome 25 to use this demo");
            }
            function isNotEmpty(n) {
                return n.length > 0;
            }
            function formatUSNumber(n) {
                var dest = n.replace(/-/g, '');
                dest = dest.replace(/ /g, '');
                dest = dest.replace(/\+/g, '');
                dest = dest.replace(/\(/g, '');
                dest = dest.replace(/\)/g, '');
                if (!isNaN(dest)) {
                    n = dest
                    if (n.length == 10 && n.substr(0, 1) != "1") {
                        n = "1" + n;
                    }
                }
                return n;
            }
            function replaceAll(txt, replace, with_this) {
                return txt.replace(new RegExp(replace, 'g'),with_this);
            }
            function initUI() {
                //callbox
                $('#callcontainer').hide();
                $('#btn-container').hide();
                $('#status_txt').text('Waiting login');
                $('#login_box').show();
                $('#logout_box').hide();
            }
            function callUI() {
                //show outbound call UI
                dialpadHide();
                $('#incoming_callbox').hide('slow');
                $('#callcontainer').show();
                $('#status_txt').text('Ready');
                $('#make_call').text('Call');
            }
            function IncomingCallUI() {
                //show incoming call UI
                $('#status_txt').text('Incoming Call');
                $('#callcontainer').hide();
                $('#incoming_callbox').show('slow');
            }
            function callAnsweredUI() {
                $('#incoming_callbox').hide('slow');
                $('#callcontainer').hide();
                dialpadShow();
            }
            function onReady() {
                console.log("onReady...");
                $('#status_txt').text('Login');
                $('#login_box').show();
            }
            function login() {
                var username = "<?php echo $uname; ?>";
                var password = "<?php echo $password; ?>";
                console.log(username);
                console.log(password);
                Plivo.conn.login(username, password);
            }
            function logout() {
                var end_id = "<?php echo $end_id; ?>";
                console.log(end_id)
                $.get( "/~ramya/delete_endpoint.php?endpoint_id=" + end_id, function( data ) { console.log("delete endpoint status: " + data) })
                Plivo.conn.logout();
            }
            function onLogin() {
                $('#status_txt').text('Logged in');
                $('#login_box').hide();
                $('#logout_box').show();
                $('#callcontainer').show();
            }
            function onLoginFailed() {
                $('#status_txt').text("Login Failed");
            }
            function onLogout() {
                initUI();
            }
            function onCalling() {
                console.log("onCalling");
                $('#status_txt').text('Connecting....');
            }
            function onCallRemoteRinging() {
                $('#status_txt').text('Ringing..');
            }
            function onCallAnswered() {
                console.log('onCallAnswered');
                callAnsweredUI();
                $('#status_txt').text('Call Answered');
            }
            function onCallTerminated() {
                console.log("onCallTerminated");
                callUI();
            }
            function onCallFailed(cause) {
                console.log("onCallFailed:"+cause);
                callUI();
                $('#status_txt').text("Call Failed:"+cause);
            }
            function call() {
                if ($('#make_call').text() == "Call") {
                    var dest = $("#to").val();
                    if (isNotEmpty(dest)) {
                        $('#status_txt').text('Calling..');
                        Plivo.conn.call(dest);
                        $('#make_call').text('End');
                    }
                    else{
                        $('#status_txt').text('Invalid Destination');
                    }
                }
                else if($('#make_call').text() == "End") {
                    $('#status_txt').text('Ending..');
                    Plivo.conn.hangup();
                    $('#make_call').text('Call');
                    $('#status_txt').text('Ready');
                }
            }
            function hangup() {
                $('#status_txt').text('Hanging up..');
                Plivo.conn.hangup();
                callUI()
            }
            function dtmf(digit) {
                console.log("send dtmf="+digit);
                Plivo.conn.send_dtmf(digit);
            }
            function dialpadShow() {
                $('#btn-container').show();
            }
            function dialpadHide() {
                $('#btn-container').hide();
            }
            function mute() {
                Plivo.conn.mute();
                $('#linkUnmute').show('slow');
                $('#linkMute').hide('slow');
            }
            function unmute() {
                Plivo.conn.unmute();
                $('#linkUnmute').hide('slow');
                $('#linkMute').show('slow');
            }
            function onIncomingCall(account_name, extraHeaders) {
                console.log("onIncomingCall:"+account_name);
                console.log("extraHeaders=");
                for (var key in extraHeaders) {
                    console.log("key="+key+".val="+extraHeaders[key]);
                }
                IncomingCallUI();
            }
            function onIncomingCallCanceled() {
                callUI();
            }
            function  onMediaPermission (result) {
                if (result) {
                    console.log("get media permission");
                } else {
                    alert("you don't allow media permission, you will can't make a call until you allow it");
                }
            }
            function answer() {
                console.log("answering")
                $('#status_txt').text('Answering....');
                Plivo.conn.answer();
                callAnsweredUI()
            }
            function reject() {
                callUI()
                Plivo.conn.reject();
            }
            $(document).ready(function() {
                Plivo.onWebrtcNotSupported = webrtcNotSupportedAlert;
                Plivo.onReady = onReady;
                Plivo.onLogin = onLogin;
                Plivo.onLoginFailed = onLoginFailed;
                Plivo.onLogout = onLogout;
                Plivo.onCalling = onCalling;
                Plivo.onCallRemoteRinging = onCallRemoteRinging;
                Plivo.onCallAnswered = onCallAnswered;
                Plivo.onCallTerminated = onCallTerminated;
                Plivo.onCallFailed = onCallFailed;
                Plivo.onMediaPermission = onMediaPermission;
                Plivo.onIncomingCall = onIncomingCall;
                Plivo.onIncomingCallCanceled = onIncomingCallCanceled;
                Plivo.init();
            });
        </script>

    </head>

    <body>
        <div class="container-narrow">
            <div class="masthead">
                <img class="muted" src="./assets/logo.png">
            </div>
            <hr/>
            <div class="row-fluid marketing">
                <div class="span12">

                    <h4>Plivo WebSDK Webphone Demo</h4>
                    <p>Login to the webphone using your Plivo Endpoint credentials and make calls right out of your web browser!!</p>

                    <hr/>

                    <div id="logout_box" style="display: none">
                        <input class="btn" type="button" id="btn_logout" onclick="logout()" value="Logout">
                        <br/>
                    </div>

                    <span class="label" id="status_txt">Login</span><br/><br/>


                    <form id="login_box" title="Login" style="">
                        <label>Username</label>
                        <input type="text" name="username" value="" id="username">

                        <br/>

                        <label>Password</label>
                        <input type="password" name="password" value="" id="password">

                        <br/>

                        <input class="btn" type="button" id="btn_login" onclick="login()" value="Login">
                    </form>

                    <form id="callcontainer" style="display: none">
                        <input type="text" name="to" value="" id="to" placeholder="Phone number or a SIP URI">
                        <br/>
                        <a href="#" id="make_call" class="btn main-btn btn-success" onclick="call();">Call</a>

                    </form>
                    <div id="incoming_callbox" style="display: none;" class="call">
                        <a href="#" class="btn main-btn btn-success" onclick="answer()">Answer</a>
                        <a href="#" class="btn main-btn btn-danger" onclick="reject()">Reject</a>
                    </div>

                    <div id="btn-container" style="display: none">
                        <a href="#" id="hangup_call" class="btn main-btn btn-danger" onclick="hangup();">Hangup</a>
                        <a href="#" id="linkMute" class="badge badge-warning" onclick="mute();">Mute</a>
                        <a href="#" id="linkUnmute" class="badge badge-warning" onclick="unmute();" style="display: none">Unmute</a><br/>

                        <a class="btn btn-info" href="#" onclick="dtmf('1');">1</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('2');">2</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('3');">3</a>

                        <br/>

                        <a class="btn btn-info" href="#" onclick="dtmf('4');">4</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('5');">5</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('6');">6</a>

                        <br/>

                        <a class="btn btn-info" href="#" onclick="dtmf('7');">7</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('8');">8</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('9');">9</a>

                        <br/>

                        <a class="btn btn-info" href="#" onclick="dtmf('*');">*</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('0');">0</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('#');"> #</a>

                        <br/>
                    </div>
                </div>
                <hr/>

                <div class="footer">
                    <p>© Plivo Inc. 2011-2012</p>
                </div>
            </div>
        </div>
    </body>
</html>
