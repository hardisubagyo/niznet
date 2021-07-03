<!DOCTYPE html>
<html>
<head>
	<title>Niznet</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('public/login/css/style.css') }}">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/login/img/niznet2.png') }}"/>
</head>
<body>
	<img class="wave" src="{{ asset('public/login/img/wave.png') }}">
	<div class="container">
		<div class="img">
			<img src="{{ asset('public/login/img/bg.svg') }}">
		</div>
		<div class="login-content">
			<form method="POST" method="POST" action="{{ route('signin.attempt') }}" onkeydown="return event.key != 'Enter';">
        @csrf
				<img src="{{ asset('public/login/img/niznet2.png') }}">
				<h2 class="title">Welcome</h2>

        <div class="input-div one">
          <div class="i">
            <i class="fas fa-user"></i>
          </div>
          <div class="div">
           	<h5>Email</h5>
           	<input id="email" type="email" class="input" autocomplete="off" name="email" required>
          </div>
        </div>

     		<div class="input-div pass">
          <div class="i"> 
            <i class="fas fa-lock"></i>
          </div>
          <div class="div">
            <h5>Password</h5>
            <input id="password" type="password" class="input" name="password" required autocomplete="current-password">
          </div>
      	</div>

      	<input type="submit" class="btn" value="Login">

      </form>
    </div>
  </div>
  <script type="text/javascript" src="{{ asset('public/login/js/main.js') }}"></script>
</body>
</html>
