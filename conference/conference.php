<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Plivo Click2Call Demo</title>
        <meta name="description" content="">
        <meta name="author" content="">

        <link href="./assets/bootstrap-combined.min.css" rel="stylesheet">
        <link href="./assets/bubble.css" rel="stylesheet">

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
            .btn-large {
                padding: 22px 33px;
                font-size: 44.5px;
                -webkit-border-radius: 6px;
                -moz-border-radius: 6px;
                border-radius: 6px;
            }
        </style>

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <script type="text/javascript" src="./assets/jquery.js"></script>
        <script type="text/javascript" src="https://s3.amazonaws.com/plivosdk/web/plivo.min.js"></script>

        <script type="text/javascript">
            function webrtcNotSupportedAlert() {
                $('#txtStatus').text("");
                alert("Your browser doesn't support WebRTC. You need Chrome 25 to use this demo");
            }
            function getURLParameter(name) {
                return decodeURI((RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]);
            }
            function callUI() {
                //show outbound call UI
                ringbacktone.pause();
                dialpadHide();
                $('#callcontainer').show();
                $('#status_txt').text('Ready');
                $('#make_call').text('Conference');
            }
            function callAnsweredUI() {
                ringbacktone.pause();
                $('#status_txt').text('Call Answered....');
                $('#callcontainer').hide('slow');
                dialpadShow();
            }
            function onReady() {
                console.log("onReady...");
                login();
            }
            function login() {
                Plivo.conn.login("Your SIP Endpoint Username", "Your SIP Endpoint password");
            }
            function logout() {
                Plivo.conn.logout();
            }
            function onLogin() {
                callUI()
            }
            function onLoginFailed() {
                $('#status_txt').text("Auth Failed");
            }
            function onCalling() {
                $('#allownotice').hide();
                console.log("onCalling");
                $('#status_txt').text('Connecting....');
            }
            function onCallRemoteRinging() {
                $('#status_txt').text('Ringing..');
            }
            function onCallAnswered() {
                console.log('onCallAnswered');
                callAnsweredUI()
            }
            function onCallTerminated() {
                console.log("onCallTerminated");
                callUI()
            }
            function call() {
                if ($('#make_call').text() == "Conference") {
                    $('#status_txt').text('Calling..');
                    var r = getURLParameter("vr");
                    Plivo.conn.call(r);
                    $('#make_call').text('End');
                }
                else if($('#make_call').text() == "End") {
                    $('#status_txt').text('Ending..');
                    Plivo.conn.hangup();
                    $('#make_call').text('Conference');
                    $('#status_txt').text('Ready');
                }
            }
            function hangup() {
                $('#status_txt').text('Hanging up..');
                Plivo.conn.hangup();
                callUI()
            }
            function dtmf(digit) {
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
            $(document).ready(function() {
                Plivo.onWebrtcNotSupported = webrtcNotSupportedAlert;
                Plivo.onReady = onReady;
                Plivo.onLogin = onLogin;
                Plivo.onLoginFailed = onLoginFailed;
                Plivo.onCalling = onCalling;
                Plivo.onCallRemoteRinging = onCallRemoteRinging;
                Plivo.onCallAnswered = onCallAnswered;
                Plivo.onCallTerminated = onCallTerminated;
                console.log('Initializing Plivo SDK');
                Plivo.init();
            });
        </script>

    </head>

    <body>

        <div class="container-narrow">

            <div class="masthead">

                <img class="muted" src="./assets/plivo.jpg">
            </div>

            <div class="row-fluid marketing">
                <div class="span12 offset3">
                    <br><br><br>
                    <form id="callcontainer">
                        <a href="#" id="make_call" class="btn main-btn btn-success btn-large" onclick="call();">Conference</a><a>

                            <br><br><span class="label" id="status_txt">Loading....</span><br>

                            </a></form><div id="btn-container" style="display: none"><a>
                        </a><a href="#" id="hangup_call" class="btn main-btn btn-danger" onclick="hangup();">Hangup</a>
                        <a href="#" id="linkMute" class="badge badge-warning" onclick="mute();">Mute</a>
                        <a href="#" id="linkUnmute" class="badge badge-warning" onclick="unmute();" style="display: none">Unmute</a><br>

                        <a class="btn btn-info" href="#" onclick="dtmf('1');">1</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('2');">2</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('3');">3</a><br>

                        <a class="btn btn-info" href="#" onclick="dtmf('4');">4</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('5');">5</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('6');">6</a><br>

                        <a class="btn btn-info" href="#" onclick="dtmf('7');">7</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('8');">8</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('9');">9</a><br>

                        <a class="btn btn-info" href="#" onclick="dtmf('*');">*</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('0');">0</a>
                        <a class="btn btn-info" href="#" onclick="dtmf('#');"> #</a><br>
                    </div>
                </div>
            </div>
            <audio id="ringbacktone" loop="" src="http://s3.amazonaws.com/plivowebrtc/audio/ringtone/ringbacktone.wav"/>

            <hr>

            <div class="footer">
                <p><a href="http://www.plivo.com/">www.plivo.com</a>
                </p><div class="offset4">
                <p>© Plivo Inc. 2011-2012</p>
            </div>
        </div>

    </div>

    <embed id="WebRtc4npapi" type="application/w4a" height="1px" width="1px">
    </body>
</html>
