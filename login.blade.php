<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script>
        if (document.location.href == 'http://192.168.10.18/laraveProject/public/log') {
                localStorage.status = "offline";
                }
    </script>
</head>
<body>


<div class="container bg-light">
    <h1 class="text-center">Login Here</h1>
<form action = {{ url('login') }} method ="post">
    @csrf
    <!-- Email input -->
    <div class="form-outline mb-4">
      <input type="email" id="form2Example1" name="email" class="form-control" />
      <label class="form-label" for="form2Example1">Email address</label>
    </div>

    <!-- Password input -->
    <div class="form-outline mb-4">
      <input type="password" id="form2Example2" name="password" class="form-control" />
      <label class="form-label" for="form2Example2">Password</label>
    </div>

    <!-- 2 column grid layout for inline styling --



    <!-- Submit button -->
    <button type="submit"  class="btn btn-primary btn-block mb-4">Sign in</button>
    </div>
  </form>
</div>
</body>
</html>
