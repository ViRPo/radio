<?php include('functions.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mood Radio | by ViRPo</title>
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
						<h1><a href="/">Mood Radio</a></h1>
					</div>
					<div class="col-md-8 text-right">

						<?php

						if (isset($_POST["nickname"]) && isset($_POST["password"]) && $user = check_user(addslashes(strip_tags($_POST["nickname"])), addslashes(strip_tags($_POST["password"])))) {
							$_SESSION['user_id'] = $user['id'];
							$_SESSION['nickname'] = $user['nickname'];
							$_SESSION['email'] = $user['email'];
							$_SESSION['user_group'] = $user['group'];
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

						if(isset($_POST["playsong"])){
							if(isset($_POST["speed1"])){ $speed1 = 1; } else { $speed1 = 0; }
							if(isset($_POST["speed2"])){ $speed2 = 1; } else { $speed2 = 0; }
							if(isset($_POST["speed3"])){ $speed3 = 1; } else { $speed3 = 0; }
							if(isset($_POST["speed4"])){ $speed4 = 1; } else { $speed4 = 0; }
							if(isset($_POST["mood1"])){ $mood1 = 1; } else { $mood1 = 0; }
							if(isset($_POST["mood2"])){ $mood2 = 1; } else { $mood2 = 0; }
							if(isset($_POST["mood3"])){ $mood3 = 1; } else { $mood3 = 0; }
							if(isset($_POST["intensity1"])){ $intensity1 = 1; } else { $intensity1 = 0; }
							if(isset($_POST["intensity2"])){ $intensity2 = 1; } else { $intensity2 = 0; }
							if(isset($_POST["intensity3"])){ $intensity3 = 1; } else { $intensity3 = 0; }
							if(isset($_POST["vocal1"])){ $vocal1 = 1; } else { $vocal1 = 0; }
							if(isset($_POST["vocal2"])){ $vocal2 = 1; } else { $vocal2 = 0; }
							if(isset($_POST["vocal3"])){ $vocal3 = 1; } else { $vocal3 = 0; }
							$songlist = get_songs($speed1, $speed2, $speed3, $speed4, $mood1, $mood2, $mood3, $intensity1, $intensity2, $intensity3, $vocal1, $vocal2, $vocal3);
						} else {
							$songlist = get_songs(0, 1, 1, 0, 0, 1, 1, 0, 1, 1, 1, 1, 1);;
						}

						if(isset($_POST["resolve_comment"])){
							resolve_comment(addslashes(strip_tags($_POST["resolve_comment"])));
						}

						if(isset($_POST["set_user_id"])){
							set_user_group(addslashes(strip_tags($_POST["set_user_id"])), addslashes(strip_tags($_POST["set_user_group"])));
						}

						if(isset($_POST["accept_song_id"])){
							if(isset($_POST["new_instrumental"])){ $new_instrumental = 1; } else { $new_instrumental = 0; }
							if(isset($_POST["new_electro"])){ $new_electro = 1; } else { $new_electro = 0; }
							if(isset($_POST["new_vocal"])){ $new_vocal = 1; } else { $new_vocal = 0; }
							set_song_edit_accept(addslashes(strip_tags($_POST["accept_song_id"])), addslashes(strip_tags($_POST["new_speed"])), addslashes(strip_tags($_POST["new_mood"])), addslashes(strip_tags($_POST["new_intensity"])), $new_instrumental, $new_electro, $new_vocal, 1);
						}

						if(isset($_POST["decline_song_id"])){
							set_song_accept(addslashes(strip_tags($_POST["decline_song_id"])), 2);
						}

						if (isset($_SESSION['nickname'])) {
						?>
						<span class="hi-user">Hi <strong><?php echo $_SESSION['nickname'] ?></strong>! :)</span>

						<form method="post" class="logout-form">
						    <button class="btn btn-trans" name="logout" type="submit" id="logout">
								Logout
							</button>
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

						<a class="btn" type="button" data-toggle="collapse" data-target="#quickAddSongForm" aria-expanded="false" aria-controls="quickAddSongForm">
							<i class="fa fa-plus-circle"></i> Quick add
						</a>

            <form method="post" class="add-form collapse" id="quickAddSongForm">
							<input type="text" name="quick-add-url" value="" placeholder="YouTube URL">
							<br>
							<span class="eg">For example: www.youtube.com/watch?v=SPlQpGeTbIE</span>

							<br>

							<button class="btn btn-default" type="submit" name="quickaddsong" data-toggle="collapse" data-target="#quickAddSongForm" aria-expanded="false" aria-controls="quickAddSongForm"><i class="fa fa-plus-circle"></i> Suggest it to me :)</button>
						</form>

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
								Dark
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
								Cheerful
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
					<div class="flex-video widescreen"><div id="player"></div></div>
					<button id="skip-song" type="button" name="skip-song" class="btn btn-trans btn-skip"><i class="fa fa-step-forward" aria-hidden="true"></i> Skip</button>
				</div>
				<div class="col-md-6 text-center">
					<p><strong>Set <span class="color-red">speed</span>, <span class="color-orange">mood</span>, <span class="color-yellow">intensity</span> and <span class="color-brightyellow">sounds</span>:</strong></p>

					<hr class="ninja">

					<form class="mood-form" action="" method="post">
						<input type="checkbox" name="speed1" id="speed1" value="1" <?php if(isset($_POST["speed1"])){ echo "checked='checked'"; } ?>>
						<label for="speed1" class="redbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Slow
						</label>
						<input type="checkbox" name="speed2" id="speed2" value="2" <?php if(isset($_POST["speed2"])){ echo "checked='checked'"; } else { if(!isset($_POST["playsong"])){ echo "checked='checked'"; } } ?>>
						<label for="speed2" class="redbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Normal
						</label>
						<input type="checkbox" name="speed3" id="speed3" value="3" <?php if(isset($_POST["speed3"])){ echo "checked='checked'"; } else { if(!isset($_POST["playsong"])){ echo "checked='checked'"; } } ?>>
						<label for="speed3" class="redbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Fast
						</label>
						<input type="checkbox" name="speed4" id="speed4" value="4" <?php if(isset($_POST["speed4"])){ echo "checked='checked'"; } ?>>
						<label for="speed4" class="redbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Insane
						</label>

						<hr>

						<input type="checkbox" name="mood1" id="mood1" value="1" <?php if(isset($_POST["mood1"])){ echo "checked='checked'"; } ?>>
						<label for="mood1" class="orangebg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Dark
						</label>
						<input type="checkbox" name="mood2" id="mood2" value="2" <?php if(isset($_POST["mood2"])){ echo "checked='checked'"; } else { if(!isset($_POST["playsong"])){ echo "checked='checked'"; } } ?>>
						<label for="mood2" class="orangebg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Neutral
						</label>
						<input type="checkbox" name="mood3" id="mood3" value="3" <?php if(isset($_POST["mood3"])){ echo "checked='checked'"; } else { if(!isset($_POST["playsong"])){ echo "checked='checked'"; } } ?>>
						<label for="mood3" class="orangebg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i>
							Cheerful
						</label>

						<hr>

						<input type="checkbox" name="intensity1" id="intensity1" value="1" <?php if(isset($_POST["intensity1"])){ echo "checked='checked'"; } ?>>
						<label for="intensity1" class="yellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Calm
						</label>
						<input type="checkbox" name="intensity2" id="intensity2" value="2" <?php if(isset($_POST["intensity2"])){ echo "checked='checked'"; } else { if(!isset($_POST["playsong"])){ echo "checked='checked'"; } } ?>>
						<label for="intensity2" class="yellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Neutral
						</label>
						<input type="checkbox" name="intensity3" id="intensity3" value="3" <?php if(isset($_POST["intensity3"])){ echo "checked='checked'"; } else { if(!isset($_POST["playsong"])){ echo "checked='checked'"; } } ?>>
						<label for="intensity3" class="yellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Intense
						</label>

						<hr>

						<input type="checkbox" name="vocal1" id="vocal1" value="1" <?php if(isset($_POST["vocal1"])){ echo "checked='checked'"; } else { if(!isset($_POST["playsong"])){ echo "checked='checked'"; } } ?>>
						<label for="vocal1" class="brightyellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Instrumental
						</label>
						<input type="checkbox" name="vocal2" id="vocal2" value="2" <?php if(isset($_POST["vocal2"])){ echo "checked='checked'"; } else { if(!isset($_POST["playsong"])){ echo "checked='checked'"; } } ?>>
						<label for="vocal2" class="brightyellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Electro
						</label>
						<input type="checkbox" name="vocal3" id="vocal3" value="3" <?php if(isset($_POST["vocal3"])){ echo "checked='checked'"; } else { if(!isset($_POST["playsong"])){ echo "checked='checked'"; } } ?>>
						<label for="vocal3" class="brightyellowbg">
							<i class="fa fa-check-circle-o"></i>
							<i class="fa fa-circle-o"></i> Vocal
						</label>

						<hr class="ninja">
						<button class="btn" type="submit" name="playsong" value="Submit"><i class="fa fa-play"></i>Apply 'n' play</button>
					</form>
				</div>
			</div>
		</div>

		<?php

			if (isset($_SESSION['user_group'])) {
				if ($_SESSION['user_group']==1) {
					?>

					<div class="row main-row">
						<div class="container">
							<div class="row">
								<div class="col-md-12 text-center">
									<h3 class="text-center">Song queue</h3>
									<?php
									get_songs_edit();
									?>
								</div>
							</div>
						</div>
					</div>

					<div class="row main-row">
						<div class="container">
							<div class="row">
								<div class="col-md-12 text-center">
									<h3 class="text-center">Users</h3>
									<?php
									get_users_edit();
									?>
								</div>
							</div>
						</div>
					</div>

					<div class="row main-row">
						<div class="container">
							<div class="row">
								<div class="col-md-12">
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
							<input type="<?php if(isset($_SESSION['nickname'])) echo "hidden"; else echo"text";?>" name="comment-nickname" value="<?php if(isset($_SESSION['nickname'])) echo $_SESSION['nickname'];?>" placeholder="nickname" <?php if(isset($_SESSION['nickname'])) echo "disabled";?>>
							<?php if(!isset($_SESSION['nickname'])) echo "<br>"; ?>
							<input type="<?php if(isset($_SESSION['nickname'])) echo "hidden"; else echo"text";?>" name="comment-email" value="<?php if(isset($_SESSION['nickname'])) echo $_SESSION['email'];?>" placeholder="email" <?php if(isset($_SESSION['nickname'])) echo "disabled";?>>
							<?php if(!isset($_SESSION['email'])) echo "<br>"; ?>
							<textarea rows="3" name="comment" value="" placeholder="feedback"></textarea><br>
							<input type="text" name="comment-songid" value="" placeholder="song (if any)"><br>
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
					alert('Song was sucesfully submitted. Thank you :)');
				}
			});
		});
		$('form#quickAddSongForm').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				type: 'post',
				url: 'add.php',
				data: $('form#quickAddSongForm').serialize(),
				success: function () {
					alert('Song was sucesfully submitted. Thank you :)');
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
					alert('Song was sucesfully submitted. Thank you :)');
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
          width: '560',
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange,
			      'onError': nextVideo
          }
        });
      }

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
		  nextVideo();
        event.target.playVideo();
      }

      // 5. The API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
      var done = false;
      function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.ENDED) {
        	nextVideo();
        }
      }

      function stopVideo() {
        player.stopVideo();
      }

	  function shuffle(a) {
	      var j, x, i;
	      for (i = a.length; i; i -= 1) {
	          j = Math.floor(Math.random() * i);
	          x = a[i - 1][0];
	          a[i - 1][0] = a[j][0];
	          a[j][0] = x;
	      }
	  }

	  var videos = <?php echo json_encode($songlist) ?>;

	  shuffle(videos);

	  var currentVideo = 0;

	  function nextVideo(){
		  player.cueVideoById(videos[currentVideo][0]);
		  currentVideo++;
		  player.playVideo();
	  }

	  $('#skip-song').on('click', function (e) {
		  e.preventDefault();
		  nextVideo();
	  });
    </script>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-76431545-1', 'auto');
	  ga('send', 'pageview');

	</script>
</body>
</html>
