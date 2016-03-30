<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mood Radio</title>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700,900,900italic,700italic,500italic,500,400italic,300italic,100italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href='https://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>
<body>
	<div class="container-fluid">
		<div class="row nav-row">
			<div class="container">
				<div class="row">
					<div class="col-md-4 text-center">
						<h1><a href="#">Mood Radio</a></h1>
					</div>
					<div class="col-md-8 text-right">
						<button class="btn btn-trans" type="button" data-toggle="collapse" data-target="#registerForm" aria-expanded="false" aria-controls="registerForm">
							Register
						</button>

						<button class="btn btn-trans" type="button" data-toggle="collapse" data-target="#loginForm" aria-expanded="false" aria-controls="loginForm">
							Login
						</button>

						<button class="btn" type="button" data-toggle="collapse" data-target="#addSongForm" aria-expanded="false" aria-controls="addSongForm">
							<i class="fa fa-plus-circle"></i> Add new song
						</button>

						<form class="add-form collapse" action="index.html" method="post" id="addSongForm">
							<input type="text" name="add-name" value="" placeholder="YouTube URL">
							<br>
							<span class="eg">For example: www.youtube.com/watch?v=SPlQpGeTbIE</span>
							<br>
							<input type="radio" name="add-speed" id="add-speed1" value="1">
							<label for="add-speed1" class="redbg">
								<i class="fa fa-dot-circle-o"></i>
								<i class="fa fa-circle-o"></i>
								Slow
							</label>
							<input type="radio" name="add-speed" id="add-speed2" value="2" checked="checked">
							<label for="add-speed2" class="redbg">
								<i class="fa fa-dot-circle-o"></i>
								<i class="fa fa-circle-o"></i>
								Normal
							</label>
							<input type="radio" name="add-speed" id="add-speed3" value="3">
							<label for="add-speed3" class="redbg">
								<i class="fa fa-dot-circle-o"></i>
								<i class="fa fa-circle-o"></i>
								Fast
							</label>
							<input type="radio" name="add-speed" id="add-speed4" value="4">
							<label for="add-speed4" class="redbg">
								<i class="fa fa-dot-circle-o"></i>
								<i class="fa fa-circle-o"></i>
								Insane
							</label>

							<br>

							<input type="radio" name="add-mood" id="add-mood1" value="1">
							<label for="add-mood1" class="orangebg">
								<i class="fa fa-dot-circle-o"></i>
								<i class="fa fa-circle-o"></i>
								Sad
							</label>
							<input type="radio" name="add-mood" id="add-mood2" value="2" checked="checked">
							<label for="add-mood2" class="orangebg">
								<i class="fa fa-dot-circle-o"></i>
								<i class="fa fa-circle-o"></i>
								Neutral
							</label>
							<input type="radio" name="add-mood" id="add-mood3" value="3">
							<label for="add-mood3" class="orangebg">
								<i class="fa fa-dot-circle-o"></i>
								<i class="fa fa-circle-o"></i>
								Happy
							</label>

							<br>

							<input type="radio" name="add-intensity" id="add-intensity1" value="1">
							<label for="add-intensity1" class="yellowbg">
								<i class="fa fa-dot-circle-o"></i>
								<i class="fa fa-circle-o"></i>
								Calm
							</label>
							<input type="radio" name="add-intensity" id="add-intensity2" value="2" checked="checked">
							<label for="add-intensity2" class="yellowbg">
								<i class="fa fa-dot-circle-o"></i>
								<i class="fa fa-circle-o"></i>
								Neutral
							</label>
							<input type="radio" name="add-intensity" id="add-intensity3" value="3">
							<label for="add-intensity3" class="yellowbg">
								<i class="fa fa-dot-circle-o"></i>
								<i class="fa fa-circle-o"></i>
								Intense
							</label>

							<br>

							<input type="checkbox" name="add-vocal" id="add-vocal1" value="1" checked="checked">
							<label for="add-vocal1" class="brightyellowbg">
								<i class="fa fa-check-circle-o"></i>
								<i class="fa fa-circle-o"></i> Instrumental
							</label>
							<input type="checkbox" name="add-vocal" id="add-vocal2" value="2" checked="checked">
							<label for="add-vocal2" class="brightyellowbg">
								<i class="fa fa-check-circle-o"></i>
								<i class="fa fa-circle-o"></i> Electro
							</label>
							<input type="checkbox" name="add-vocal" id="add-vocal3" value="3" checked="checked">
							<label for="add-vocal3" class="brightyellowbg">
								<i class="fa fa-check-circle-o"></i>
								<i class="fa fa-circle-o"></i> Vocal
							</label>
							<br>
							<button class="btn btn-default" type="button" name="addsong"><i class="fa fa-plus-circle"></i> Suggest it to me :)</button>
						</form>

						<form class="add-form collapse" action="index.html" method="post" id="loginForm">
							<input type="text" name="nickname" value="" placeholder="nickname"><br>
							<input type="password" name="password" value="" placeholder="p4$$w0rd"><br>
							<button class="btn btn-default" type="button" name="button">Log me in!</button>
						</form>

						<form class="add-form collapse" action="index.html" method="post" id="registerForm">
							<input type="text" name="nickname" value="" placeholder="nickname"><br>
							<input type="email" name="email" value="" placeholder="email"><br>
							<input type="password" name="password" value="" placeholder="p4$$w0rd"><br>
							<input type="password" name="passwordagain" value="" placeholder="p4$$w0rd again"><br>
							<button class="btn btn-default" type="button" name="button">Register me!</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row main-row">
			<div class="container">
				<div class="col-md-6 text-center">
					<iframe width="530" height="315" src="https://www.youtube.com/embed/SPlQpGeTbIE" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="col-md-6 text-center">
					<p><strong>Play songs with this mood:</strong></p>
					<form class="mood-form" action="" method="get">
						<input type="checkbox" name="speed" id="speed1" value="1">
						<label for="speed1" class="redbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Slow
						</label>
						<input type="checkbox" name="speed" id="speed2" value="2" checked="checked">
						<label for="speed2" class="redbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Normal
						</label>
						<input type="checkbox" name="speed" id="speed3" value="3" checked="checked">
						<label for="speed3" class="redbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Fast
						</label>
						<input type="checkbox" name="speed" id="speed4" value="4">
						<label for="speed4" class="redbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Insane
						</label>

						<hr>

						<input type="checkbox" name="mood" id="mood1" value="1">
						<label for="mood1" class="orangebg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Sad
						</label>
						<input type="checkbox" name="mood" id="mood2" value="2" checked="checked">
						<label for="mood2" class="orangebg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Neutral
						</label>
						<input type="checkbox" name="mood" id="mood3" value="3" checked="checked">
						<label for="mood3" class="orangebg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Happy
						</label>

						<hr>

						<input type="checkbox" name="intensity" id="intensity1" value="1">
						<label for="intensity1" class="yellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Calm
						</label>
						<input type="checkbox" name="intensity" id="intensity2" value="2" checked="checked">
						<label for="intensity2" class="yellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Neutral
						</label>
						<input type="checkbox" name="intensity" id="intensity3" value="3" checked="checked">
						<label for="intensity3" class="yellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Intense
						</label>

						<hr>

						<input type="checkbox" name="vocal" id="vocal1" value="1" checked="checked">
						<label for="vocal1" class="brightyellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Instrumental
						</label>
						<input type="checkbox" name="vocal" id="vocal2" value="2" checked="checked">
						<label for="vocal2" class="brightyellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Electro
						</label>
						<input type="checkbox" name="vocal" id="vocal3" value="3" checked="checked">
						<label for="vocal3" class="brightyellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Vocal
						</label>

						<hr class="ninja">
						<button class="btn" type="submit" value="Submit"><i class="fa fa-play"></i> Apply 'n' play</button>
						</form>
					</div>
				</div>
			</div>
			<div class="row foot-row">
				<div class="container">
					<div class="row">
						<div class="col-md-12 text-center">
							By <a href="http://virpo.sk">ViRPo</a>.
							Send me <a role="button" data-toggle="collapse" href="#suggestForm" aria-expanded="false" aria-controls="suggestForm">feedback</a>?
							<form class="add-form collapse" action="#" method="post" id="suggestForm">
								<input type="text" name="nickname" value="" placeholder="nickname"><br>
								<input type="email" name="email" value="" placeholder="email"><br>
								<input type="text" name="songid" value="" placeholder="song ID"><br>
								<textarea rows="3" name="comment" value="" placeholder="feedback"></textarea><br>
								<button class="btn btn-default" type="button" name="button">Send it to me!</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	</body>
	</html>
