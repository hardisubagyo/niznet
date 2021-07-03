<!DOCTYPE html>
<html>
<head>
	<title>HappySelling.id</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('public/login/css/style.css') }}">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<img class="wave" src="{{ asset('public/login/img/wave.png') }}">
	<div class="container">
		<div class="img">
			<img src="{{ asset('public/login/img/bg.svg') }}">
		</div>
		<div class="login-content">
			<form method="POST" action="{{ route('login') }}">
        @csrf
				<img src="{{ asset('public/login/img/avatar.svg') }}">
				<h2 class="title">Welcome</h2>

        <div class="input-div one">
          <div class="i">
            <i class="fas fa-user"></i>
          </div>
          <div class="div">
           	<h5>Email</h5>
           	<input id="email" type="email" class="input @error('email') is-invalid @enderror" autocomplete="off" name="email" value="{{ old('email') }}" required>
            @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

     		<div class="input-div pass">
          <div class="i"> 
            <i class="fas fa-lock"></i>
          </div>
          <div class="div">
            <h5>Password</h5>
            <input id="password" type="password" class="input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @error('password')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
      	</div>

      	<input type="submit" class="btn" value="Login">

      </form>
    </div>
  </div>
  <script type="text/javascript" src="{{ asset('public/login/js/main.js') }}"></script>
</body>
</html>
