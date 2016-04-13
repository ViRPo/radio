<?php include('functions.php'); ?>
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
						<h1><a href="/radio">Mood Radio</a></h1>
					</div>
					<div class="col-md-8 text-right">

						<?php

						if (isset($_POST["nickname"]) && isset($_POST["password"]) && $pouzivatel = check_user(addslashes(strip_tags($_POST["nickname"])), addslashes(strip_tags($_POST["password"])))) {
							$_SESSION['user_id'] = $pouzivatel['id'];
							$_SESSION['nickname'] = $pouzivatel['nickname'];
							$_SESSION['user_group'] = $pouzivatel['group'];
						} elseif (isset($_POST['logout'])) { // bol odoslany formular s odhlasenim
							session_unset();
						} elseif(!isset($_SESSION['nickname']) && isset($_POST["nickname"])) {
							echo '<span class="hi-user">Invalid login info</span>';
						}

						if(isset($_POST["reg_nickname"]) && isset($_POST["reg_password"]) && isset($_POST["reg_passwordagain"]) && isset($_POST["reg_email"])){
							if (addslashes(strip_tags($_POST["reg_password"]))==addslashes(strip_tags($_POST["reg_passwordagain"]))) {
								add_user();
							} else {
								echo '<span class="hi-user">Passwords didn\'t match</span>';
							}

						}

						if(isset($_POST["resolve_comment"])){
							resolve_comment(addslashes(strip_tags($_POST["resolve_comment"])));
						}

						if(isset($_POST["set_user_id"])){
							set_user_group(addslashes(strip_tags($_POST["set_user_id"])), addslashes(strip_tags($_POST["set_user_group"])));
						}

						if(isset($_POST["accept_song_id"])){
							set_song_accept(addslashes(strip_tags($_POST["accept_song_id"])), addslashes(strip_tags($_POST["accept-song-value"])));
						}

						if (isset($_SESSION['nickname'])) {
						?>
						<span class="hi-user">Hi <strong><?php echo $_SESSION['nickname'] ?></strong>! :)</span>

						<form method="post" class="logout-form">
						    <button class="btn btn-trans" name="logout" type="submit" id="logout">Logout</button>
						</form>
						<?php

						}  else {
						?>
							<a class="btn btn-trans" type="button" data-toggle="collapse" data-target="#loginForm" aria-expanded="false" aria-controls="loginForm">
								Login
							</a>
						<?php
						}
						?>

						<a class="btn" type="button" data-toggle="collapse" data-target="#addSongForm" aria-expanded="false" aria-controls="addSongForm">
							<i class="fa fa-plus-circle"></i> Add new song
						</a>

						<form method="post" class="add-form collapse" id="addSongForm">
							<input type="text" name="add-url" value="" placeholder="YouTube URL">
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

							<input type="checkbox" name="add-instrumental" id="add-instrumental" value="1" checked="checked">
							<label for="add-instrumental" class="brightyellowbg">
								<i class="fa fa-check-circle-o"></i>
								<i class="fa fa-circle-o"></i> Instrumental
							</label>
							<input type="checkbox" name="add-electro" id="add-electro" value="1" checked="checked">
							<label for="add-electro" class="brightyellowbg">
								<i class="fa fa-check-circle-o"></i>
								<i class="fa fa-circle-o"></i> Electro
							</label>
							<input type="checkbox" name="add-vocal" id="add-vocal" value="1" checked="checked">
							<label for="add-vocal" class="brightyellowbg">
								<i class="fa fa-check-circle-o"></i>
								<i class="fa fa-circle-o"></i> Vocal
							</label>
							<br>
							<button class="btn btn-default" type="submit" name="addsong" data-toggle="collapse" data-target="#addSongForm" aria-expanded="false" aria-controls="addSongForm"><i class="fa fa-plus-circle"></i> Suggest it to me :)</button>
						</form>

						<form class="add-form collapse" action="index.php" method="post" id="loginForm">
							<input type="text" name="nickname" value="" placeholder="nickname"><br>
							<input type="password" name="password" value="" placeholder="p4$$w0rd"><br>
							<button class="btn btn-default" type="submit" name="button" data-toggle="collapse" data-target="#loginForm" aria-expanded="false" aria-controls="loginForm">Log me in!</button><br>
							<a class="btn btn-trans wannasignup" type="button" data-toggle="collapse" data-target="#registerForm" aria-expanded="false" aria-controls="registerForm">
								Sign up?
							</a>
						</form>

						<form class="add-form collapse" action="index.php" method="post" id="registerForm">
							<input type="text" name="reg_nickname" value="" placeholder="nickname"><br>
							<input type="email" name="reg_email" value="" placeholder="email"><br>
							<input type="password" name="reg_password" value="" placeholder="p4$$w0rd"><br>
							<input type="password" name="reg_passwordagain" value="" placeholder="p4$$w0rd again"><br>
							<button class="btn btn-default" type="submit" name="button">Register me!</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row main-row">
			<div class="container">
				<div class="col-md-6 text-center">
					<div id="player"></div>
				</div>
				<div class="col-md-6 text-center">
					<p><strong>Set <span class="color-red">speed</span>, <span class="color-orange">mood</span>, <span class="color-yellow">intensity</span> and <span class="color-brightyellow">sounds</span>:</strong></p>

					<hr class="ninja">

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
						<button class="btn" type="submit" value="Submit"><i class="fa fa-play"></i> Play</button>
					</form>
				</div>
			</div>
		</div>

		<?php

			if (isset($_SESSION['user_group'])) {
				if ($_SESSION['user_group']==1) {
					?>

					<div class="row main-row admin-row">
						<div class="container">
							<div class="row">
								<div class="col-md-12 text-center">
									<h2 class="color-white">Section for cool admins only</h2>
								</div>
								<div class="col-md-4 text-center">
									<h3 class="text-center">Songs</h3>
									<?php
									get_songs_edit();
									?>
								</div>
								<div class="col-md-4 text-center">
									<h3 class="text-center">Users</h3>
									<?php
									get_users_edit();
									?>
								</div>
								<div class="col-md-4 text-left">
									<h3 class="text-center">Feedback</h3>
									<?php
									get_feedback_edit();
									?>
								</div>
							</div>
						</div>
					</div>

					<?php
				}
			}

		?>

		<div class="row foot-row">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						By <a href="http://virpo.sk">ViRPo</a>.
						Send me <a role="button" data-toggle="collapse" href="#suggestForm" aria-expanded="false" aria-controls="suggestForm">feedback</a>?
						<form class="add-form collapse" action="#" method="post" id="suggestForm">
							<input type="text" name="comment-nickname" value="" placeholder="nickname"><br>
							<input type="email" name="comment-email" value="" placeholder="email"><br>
							<input type="text" name="comment-songid" value="" placeholder="song ID"><br>
							<textarea rows="3" name="comment" value="" placeholder="feedback"></textarea><br>
							<button class="btn btn-default" type="submit" name="button" data-toggle="collapse" data-target="#suggestForm" aria-expanded="false" aria-controls="suggestForm">Send it to me!</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script>
	$(function () {
		$('form#addSongForm').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				type: 'post',
				url: 'add.php',
				data: $('form#addSongForm').serialize(),
				success: function () {
					alert('form was submitted');
				}
			});
		});
		$('form#suggestForm').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				type: 'post',
				url: 'add.php',
				data: $('form#suggestForm').serialize(),
				success: function () {
					alert('form was submitted');
				}
			});
		});
	});
	</script>
	<script>
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          height: '315',
          width: '530',
          videoId: 'SPlQpGeTbIE',
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
        event.target.playVideo();
      }

      // 5. The API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
      var done = false;
      function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
          setTimeout(stopVideo, 6000);
          done = true;
        }
      }
      function stopVideo() {
        player.stopVideo();
      }
    </script>
</body>
</html>
