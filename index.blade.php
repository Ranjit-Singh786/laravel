<!DOCTYPE html>
<html>

<head>
    <title>Pusher Chat</title>
    {{-- <link rel="stylesheet" href="{{ asset('css/chat.css') }}" /> --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        function addInChat(user_name, message, m_key = null) {
            var table = document.getElementById('table');
            var row = table.insertRow(-1);
            var abc = row.insertCell(-1);
            var abcd = row.insertCell(-1);

            if (m_key != null) {
                abcd.id = m_key;
            }

            abc.innerHTML = user_name + ' : ' + message;
            abcd.innerHTML = 'unseen';
        }


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function messageSeen(m_key) {
            // where m_key = jhkj and seen 0  // auth ::id == reciever id
            if(localStorage.logged_id == )

            var mkey = document.getElementById(m_key).value;
            if (mkey == ) {
                mkey.innerHTML = 'seen';
            }
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Pusher.logToConsole = true;
        var pusher = new Pusher('e59164887889b9ca502b', {
            cluster: 'ap2'
        });

        // Get User R Key for listen messages
        const params = new URLSearchParams(window.location.search)
        let r_key = params.get('r_key');

        var channel = pusher.subscribe('pushpa');
        let s_key = localStorage.getItem('s_key');
        let event_name = 'message-' + s_key + '-s-' + r_key + '-r';
        //console.log(event_name);
        channel.bind(event_name, function(data) {
            //console.log(data)
            //alert(data)
            console.log('EVENT RECIEVED', JSON.stringify(data));
            let event_data = data.data;
            console.log(event_data);

            if (event_data.type == 'message') {
                // console.log(event_data.message);
                addInChat(event_data.sender_name, event_data.message)

                // Send Seen Reponse Back
            }

            if (event_data.type == 'seen') {

            }

        });
    </script>
    {{-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    <nav class="navbar navbar-default navbar-inverse" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="">Home</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <!-- <li class="active"><a href="#">Link</a></li> -->
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                            <li class="divider"></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <p class="navbar-text"></p>
                    </li>
                    <li class>
                        <h4><a class="navbar-brand" href="adminselfupdate.php">Admin Update</a></h4>
                    </li>
                    <li class>
                        <h4><a class="navbar-brand">|</a></h4>
                    </li>
                    <li class>
                        <h4><a class="navbar-brand" href="adminchangepass.php">Reset Password</a></h4>
                    </li>
                    <li class>
                        <h4><a class="navbar-brand">|</a></h4>
                    </li>
                    <li class>
                        <h4><a class="navbar-brand" href="http://192.168.10.60/project_laravel/crudapi/public/log">Log
                                Out</a></h4>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="col-md-50" style="margin :50; padding:60px; solid #b0a1a1dd;">
            <h2 class="signup">My App</h2>
            <h3 id="online" disabled>Online :<strong>{{ $reciever_name }}</strong></h3>
        </div>
        <div>
            <table id="table">
            </table>
        </div>
        <form onsubmit="sendMessage(event)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="text" onkeyup="success()" autocomplete="off" name="message" id="messages"
                    placeholder="Enter your message..." class="form-control" />
                {{-- <input type="hidden" value="{{ $reciever_id }}" id="reciever_id"> --}}
                <input type="hidden" name="seen" id="seen">
            </div><br />
            <button type="submit" id="submit" disabled class="btn btn-primary w-100">Send</button>
        </form>
    </div>
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    <script type="text/javascript" language="javascript">
        // reciever_id = document.getElementById('reciever_id').value;
        // localStorage.setItem('reciever_id', reciever_id);


        function dec2hex(dec) {
            return dec.toString(16).padStart(2, "0")
        }

        // generateId :: Integer -> String
        function generateId(len) {
            var arr = new Uint8Array((len || 40) / 2)
            window.crypto.getRandomValues(arr)
            return Array.from(arr, dec2hex).join('')
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  get seen updation
        function get_seen_updation() {
            var token = localStorage.token;
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer ' + token,
                }
            });
            var uri = document.location.href;
            var id = uri.split('/').pop();
            let data = {
                m_key: mkey,
            }

           messageSeen(mkey);
            $.ajax({
                url: "http://192.168.10.18/laraveProject/public/showdata/" + id,
                type: "POST",
                data: data,
                dataType: 'json',
                success: function(data) {
                    //console.log(data);
                    var datas = data.data;
                    console.log(datas);
                    document.getElementById('messages').value = "";
                    success();
                    // getMessage();

                }
            });
        }


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function success() {
            if (document.getElementById("messages").value === "") {
                document.getElementById('submit').disabled = true;
            } else {
                document.getElementById('submit').disabled = false;
            }
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        getMessage();

        function sendMessage(event) {

            var token = localStorage.token;
            event.preventDefault();

            let user_name = localStorage.getItem('logged');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer ' + token,
                }
            });
            let msg = $("input[name=message]").val();
            var uri = document.location.href;
            var id = uri.split('/').pop();
            let data = {
                message: msg,
                m_key: generateId(10),
            }

            addInChat(user_name, msg, data.m_key);
            $.ajax({
                url: "http://192.168.10.18/laraveProject/public/show/" + id,
                type: "POST",
                data: data,
                dataType: 'json',
                success: function(data) {
                    //console.log(data);
                    var datas = data.data;
                    console.log(datas);
                    document.getElementById('messages').value = "";
                    success();
                    // getMessage();

                }
            });
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function getMessage() {

            var uri = document.location.href;
            var id = uri.split('/').pop();
            $.ajax({

                url: "http://192.168.10.18/laraveProject/public/get/" + id,
                type: "GET",
                dataType: 'json',

                success: function(data) {
                    console.log(data);
                    $("#table").empty();
                    html = "";
                    data.message.forEach(element => {
                        html = html +
                            `<tr><td>${element.sender_name} : ${element.message}</td><td>${element.seen}</td></tr>`;
                        var table = document.getElementById('table');
                        table.innerHTML = html;
                    });
                }
            });
        }
    </script>
</body>

</html>
