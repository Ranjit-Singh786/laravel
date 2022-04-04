
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div>

    <h1>{{ $status}}:{{ $logged_name }}</h1>
    <h3></h3>
    @foreach ($users as $user)

     <button type='button' class="btn btn-danger" style="" ><a href="http://192.168.10.18/laraveProject/public/showdata/{{ $user['id']}}">{{$user['name']}}</a> <p>{{$user['status']}}</p></button>



    @endforeach
    <br><br><br><br><br><br><br>
   <input id="token" type="hidden" value="{{$token}}">
   <input id="logged" type="hidden" value="{{$logged_name}}">
   <input id="logged_id" type="hidden" value="{{$logged_id}}">
   {{-- <input id="status" type="hidden" value="{{$status}}"> --}}
</div>


<a href="http://192.168.10.18/laraveProject/public/UserLogOut/" class="btn btn-primary">log out</a>
<script>
//console.log(localStorage.status);


var token = document.getElementById('token').value;
var logged= document.getElementById('logged').value;
var logged_id= document.getElementById('logged_id').value;
// var status= document.getElementById('status').value;

localStorage.setItem('token',token);
localStorage.setItem('logged',logged);
localStorage.setItem('logged_id',logged_id);
// localStorage.setItem('status',status);




</script>



</body>
</html>


