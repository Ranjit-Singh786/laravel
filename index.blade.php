<!DOCTYPE html>

<head>
    <title>Pusher Test</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        //Pusher.logToConsole = true;


        var pusher = new Pusher('e59164887889b9ca502b', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('pushpa');
        channel.bind('chat', function(data) {

            if (localStorage.skey == data.s_key && localStorage.logged_id == data.user_id && localStorage.user_id ==
                data.auth_id) {
                get_message();
                alert(JSON.stringify(data));

            }
        });
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    <div class="container">
        <div class="col-md-50" style="margin :50; padding:60px; solid #b0a1a1dd;">
            <h2 class="signup">My App</h2>
            <h3> :<strong>{{ $reciever_name }}</strong></h3>
            <p id="status"></p>
        </div>

        <div class="container">
            <div class="col-md-50" style="margin :50; padding:60px; solid #b0a1a1dd;">
                <table class="w-1">
                    <th>name</th>
                  <th>messages</th>


                </table>
                <table class="table" >
                    <tbody id="table">

                    </tbody>

                </table>

            </div>
            <form onsubmit="sendMessage(event)" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <textarea type="input" onkeyup="success()" autocomplete="off" name="message" id="messages"
                        placeholder="Enter your message..." class="form-control"></textarea>
                    <input type="hidden" value="{{ $s_key }}" id="skey">
                    <input type="hidden" value="{{ $user_id }}" id="user_id">
                    {{-- <input type="hidden" value="{{ $seen }}" id="seen"> --}}


                </div><br />
                <button type="submit" id="submit" disabled class="btn btn-primary w-100">Send</button>
            </form>
        </div>
    </div>
    </div>

    <script>
        var skey = document.getElementById('skey').value;
        localStorage.setItem('skey', skey);
        var user_id = document.getElementById('user_id').value;
        localStorage.setItem('user_id', user_id);
    </script>
</body>

</html>




<script>
    function success() {

        if (document.getElementById("messages").value === "") {
            document.getElementById('submit').disabled = true;
        } else {
            document.getElementById('submit').disabled = false;
        }
    }
    get_message();


    function sendMessage(event) {
        var token = localStorage.token;


        event.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + token,
            }
        });
        let msg = $("textarea[name=message]").val();
        //alert(msg)
        var url = document.location.href;
        var id = url.split('/').pop();
        //alert(url);


        let data = {

            message: msg
        }

        $.ajax({
            url: "http://192.168.10.18/laraveProject/public/show/" + id,
            type: "POST",
            data: data,
            dataType: 'json',

            success: function(data) {
                console.log(data);
                get_message();
            }
        });
    }


    function get_message() {

        var uri = document.location.href;
        var id = uri.split('/').pop();
        $.ajax({
            url: "http://192.168.10.18/laraveProject/public/get/" + id,
            type: "get",
            dataType: 'json',

            success: function(data) {
                $("#table").empty();
                data.forEach(element => {
                    // console.log(element.name);
                    var table = document.getElementById('table');
                    var row = table.insertRow(-1);
                    var abc = row.insertCell(-1);
                    message = document.getElementById('messages').value;
                    abc.innerHTML = element.sender_name + ' : ' + element.message + ' : ' + element
                        .seen;

                });
                if (window.location.href == 'http://192.168.10.18/laraveProject/public/showdata/' +
                    localStorage.user_id) {
                    get_message();
                }




            }

        })
    }
</script>

<style>
    h3 {
        text-align: end;
        color: brown;
    }

    .signup {
        color: green;
        text-align: center;
    }

    label {
        color: #888;
        font-weight: bold;
        font-size: 18px;
        padding: 0px;
        margin-bottom: 0px;
    }

    input {
        border: 2px solid #ccc;
        padding: 5px;
        border-radius: 5px;
    }

</style>
