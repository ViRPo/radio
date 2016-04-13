<?php

session_start();

//Univerzalny link s databazou
function connect_db() {
	if ($link = mysqli_connect('localhost', 'root', '', 'radio')) {
			mysqli_query($link, "SET CHARACTER SET 'utf8'");
			return $link;
	} else {
		// Nespojilo sa so servrom
		return false;
	}
}

//Pridavame songu
function add_song() {
	if ($link = connect_db()) {
		$instrumental_val = 0;
		$electro_val = 0;
		$vocal_val = 0;
		$user_id = 0;
		$admin_id = 0;
		if (isset($_POST['add-instrumental'])) {
			$instrumental_val=1;
		}
		if (isset($_POST['add-electro'])) {
			$electro_val=1;
		}
		if (isset($_POST['add-vocal'])) {
			$vocal_val=1;
		}
		if (isset($_SESSION['user_id'])) {
			$user_id=$_SESSION['user_id'];
		}
		$video_parsed_url = "Unable to parse: ".addslashes(strip_tags($_POST['add-url']));
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', addslashes(strip_tags($_POST['add-url'])), $match)) {
		    $video_parsed_url = $match[1];
		}
		//$sql = "INSERT INTO `radio`.`songs` (`id`, `youtube_id`, `speed`, `mood`, `intensity`, `instrumental`, `electro`, `vocal`, `admin_id`, `accepted`, `plays`, `skips`) VALUES (NULL, 'lwPrBchV3ZQ', '3', '2', '2', '1', '0', '1', '0', '0', '0', '0');";
		$sql = "INSERT INTO `radio`.`songs` (`id`, `youtube_id`, `speed`, `mood`, `intensity`, `instrumental`, `electro`, `vocal`, `admin_id`, `user_id`, `accepted`, `plays`, `skips`) VALUES (NULL, '" . $video_parsed_url . "', '" . addslashes(strip_tags($_POST['add-speed'])) . "', '" . addslashes(strip_tags($_POST['add-mood'])) . "', '" . addslashes(strip_tags($_POST['add-intensity'])) . "', '" . $instrumental_val . "', '" . $electro_val . "', '" . $vocal_val . "', '" . $admin_id . "', '" . $user_id . "', '0', '0', '0');";
		//$sql = "INSERT INTO `songs` (`id`, `name`, `pos`, `day`, `month`, `year`, `injury`, `point`, `assist`) VALUES (NULL, '" . addslashes(strip_tags($_POST['name'])) . "', '". addslashes(strip_tags($_POST['pos'])) ."', '" . addslashes(strip_tags($_POST['day'])) . "', '" . addslashes(strip_tags($_POST['month'])) . "', '" . addslashes(strip_tags($_POST['year'])) . "', '" . addslashes(strip_tags($_POST['injury'])) . "', '" . addslashes(strip_tags($_POST['point'])) . "', '" . addslashes(strip_tags($_POST['assist'])) . "');";
		$result = mysqli_query($link, $sql); // vykonaj dopyt
		if ($result) {
			// dopyt sa podarilo vykonať
	    echo 'Úspešne pridané';
	 	} else {
			// Dopyt sa nepodarilo vykonať
	   	echo 'Nastala chyba pri pridávaní';
	  }
		mysqli_close($link);
	} else {
		// Nepodarilo sa spojit s databazou alebo vybrat databazu
		echo 'Unable to connect with database server!';
	}
}	// koniec funkcie

function add_comment() {
	if ($link = connect_db()) {
		//$sql = "INSERT INTO `radio`.`songs` (`id`, `youtube_id`, `speed`, `mood`, `intensity`, `instrumental`, `electro`, `vocal`, `admin_id`, `accepted`, `plays`, `skips`) VALUES (NULL, 'lwPrBchV3ZQ', '3', '2', '2', '1', '0', '1', '0', '0', '0', '0');";
		//$sql = "INSERT INTO `radio`.`comments` (`id`, `youtube_id`, `speed`, `mood`, `intensity`, `instrumental`, `electro`, `vocal`, `admin_id`, `accepted`, `plays`, `skips`) VALUES (NULL, '" . $video_parsed_url . "', '" . addslashes(strip_tags($_POST['add-speed'])) . "', '" . addslashes(strip_tags($_POST['add-mood'])) . "', '" . addslashes(strip_tags($_POST['add-intensity'])) . "', '" . $instrumental_val . "', '" . $electro_val . "', '" . $vocal_val . "', '0', '0', '0', '0');";
		$sql = "INSERT INTO `radio`.`comments` (`id`, `nickname`, `email`, `comment`, `song_id`) VALUES (NULL, '" . addslashes(strip_tags($_POST['comment-nickname'])) . "', '" . addslashes(strip_tags($_POST['comment-email'])) . "', '" . addslashes(strip_tags($_POST['comment'])) . "', '" . addslashes(strip_tags($_POST['comment-songid'])) . "');";
		$result = mysqli_query($link, $sql); // vykonaj dopyt
		if ($result) {
			// dopyt sa podarilo vykonať
	    echo 'Úspešne pridané';
	 	} else {
			// Dopyt sa nepodarilo vykonať
	   	echo 'Nastala chyba pri pridávaní';
	  }
		mysqli_close($link);
	} else {
		// Nepodarilo sa spojit s databazou alebo vybrat databazu
		echo 'Unable to connect with database server!';
	}
}	// koniec funkcie

function check_user($username, $pass) {
	if ($link = connect_db()) {
		$sql = "SELECT * FROM users WHERE nickname='$username'";  // definuj dopyt
//		echo "sql = $sql <br/>";
		$result = mysqli_query($link, $sql); // vykonaj dopyt
		// dopyt sa podarilo vykonať
		if ($result && (mysqli_num_rows($result) > 0) ) {
			$row = mysqli_fetch_assoc($result);
			mysqli_free_result($result);
			if(hash("sha512", $pass.$row['email'])==$row['password']){
				return $row;
			} else {
				return false;
			}
		} else {
			// Unable to finish query/such parameters dont exist in our database
			return false;
		}
	} else {
		// Unable to connect with database server!
		return false;
	}
}

function add_user(){
	reg_user(addslashes(strip_tags($_POST['reg_nickname'])), hash("sha512", addslashes(strip_tags($_POST['reg_password'])).addslashes(strip_tags($_POST['reg_email']))), addslashes(strip_tags($_POST['reg_email'])), 4);
}

function reg_user($username, $pass, $email, $group){
	if ($link = connect_db()) {
		$sql = "INSERT INTO `radio`.`users` (`id`, `nickname`, `email`, `password`, `group`) VALUES (NULL, '". $username ."', '". $email ."', '". $pass ."', '". $group ."');";
		$result = mysqli_query($link, $sql);
		if ($result) {
	    echo 'You can login now :)';
	 	} else {
	   	echo 'Nickname already exists :(';
	  }
		mysqli_close($link);
	} else {
		echo 'Unable to connect with database server!';
	}
}

function get_user_group($user_id){
	if ($link = connect_db()) {
		$sql = "SELECT * FROM users WHERE id='$user_id'";
		$result = mysqli_query($link, $sql); // vykonaj dopyt
		// dopyt sa podarilo vykonať
		if ($result && (mysqli_num_rows($result) > 0) ) {
			$row = mysqli_fetch_assoc($result);
			mysqli_free_result($result);
				return $row['group'];
		} else {
			// Unable to finish query/such parameters dont exist in our database
			return false;
		}
	} else {
		// Unable to connect with database server!
		return false;
	}
}

function set_user_group($user, $group){
	if ($link = connect_db()) {
		$sql = "UPDATE `radio`.`users` SET `group` = '".$group."' WHERE `users`.`id` = ".$user.";";
		$result = mysqli_query($link, $sql);
		if ($result) {
	    echo 'User group set sucesfully!';
	 	} else {
	   	echo 'Unable to find that user';
	  }
		mysqli_close($link);
	} else {
		echo 'Unable to connect with database server!';
	}
}

function set_song_accept($song, $accept){
	if ($link = connect_db()) {
		$sql = "UPDATE `radio`.`songs` SET `accepted` = '".$accept."' WHERE `songs`.`id` = ".$song.";";
		$result = mysqli_query($link, $sql);
		if ($result) {
	    echo "Song's 'accepted' set sucesfully!";
	 	} else {
	   	echo 'Unable to find that song';
	  }
		mysqli_close($link);
	} else {
		echo 'Unable to connect with database server!';
	}
}

function get_feedback_edit(){
	if ($link = connect_db()) {
		$sql = "SELECT * FROM `comments` WHERE resolved='0'";
		$result = mysqli_query($link, $sql);
		$pocet = 0;
		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				if ($pocet==0) {
					$pocet++;
				} else {
					echo "<hr>";
				}
				echo "<div class='comment'><span class='color-orange'><a href='mailto:" . $row['email'] ."'>";
				echo $row['nickname'];
				echo "</strong></span></a> says";
				echo "<blockquote>";
				echo $row['comment'];
				echo "</blockquote>";
				if($row['id']!=0){
					echo "Song: <span class='color-yellow'>" . $row['song_id'] . "</span><br>";
				}
				echo "Feedback ID: <span class='color-brightyellow'>" . $row['id'] . "</span><br>";
				?>
				<form class="mini-form" action="index.php" method="post" id="resolveForm">
					<input type="hidden" name="resolve_comment" value="<?php echo $row['id'] ?>">
					<button class="btn btn-trans btn-resolved" type="submit" name="resolvedButton">Mark resolved!</button>
				</form>
				<?php
				echo "</div>";
			}
			mysqli_free_result($result);
		} else {
			// dopyt sa NEpodarilo vykonať!
			return false;
		}
	} else {
		// Unable to connect with database server!
		return false;
	}
}

function get_users_edit(){
	if ($link = connect_db()) {
		$sql = "SELECT * FROM `users`";
		$result = mysqli_query($link, $sql);
		$pocet = 0;
		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				if ($pocet==0) {
					$pocet++;
				} else {
					echo "<hr>";
				}
				echo "<div class='comment'>";
				echo "<span class='color-orange'>".$row['nickname']."</span><br>";
				echo $row['email']."<br>";
				?>
				<form class="mini-form" action="index.php" method="post" id="userGroupForm">
					<input type="hidden" name="set_user_id" value="<?php echo $row['id'] ?>">
					<select name="set_user_group">
						<option <?php if($row['group']==4) echo "selected"; ?> value="4">User</option>
						<option <?php if($row['group']==3) echo "selected"; ?> value="3">Editor</option>
						<option <?php if($row['group']==2) echo "selected"; ?> value="2">Moderator</option>
						<option <?php if($row['group']==1) echo "selected"; ?> value="1">Admin</option>
					</select>
					<br>
					<button class="btn btn-trans" type="submit" name="userGroupButton">Set group</button>
				</form>
				<?php
				echo "</div>";
			}
			mysqli_free_result($result);
		} else {
			// dopyt sa NEpodarilo vykonať!
			return false;
		}
	} else {
		// Unable to connect with database server!
		return false;
	}
}



function get_songs_edit(){
	if ($link = connect_db()) {
		$sql = "SELECT * FROM `songs` WHERE `accepted` = 0 ORDER BY `id` ASC";
		$result = mysqli_query($link, $sql);
		$pocet = 0;
		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				if ($pocet==0) {
					$pocet++;
				} else {
					echo "<hr>";
				}
				echo "<div class='comment'>";
				echo "<iframe width='300' height='200' src='https://www.youtube.com/embed/".$row['youtube_id']."' frameborder='0'></iframe><br>";
				echo "<span class='color-red'>Speed</span> ".$row['speed']."<br>";
				echo "<span class='color-orange'>Mood</span> ".$row['mood']."<br>";
				echo "<span class='color-yellow'>Intensity</span> ".$row['intensity']."<br>";
				echo "<span class='color-brightyellow'>";
				if ($row['instrumental']==1) echo "Instrumental ";
				if ($row['electro']==1) echo "Electro ";
				if ($row['vocal']==1) echo "Vocal ";
				echo "</span>";
				if (($row['instrumental'] ==1) || ($row['electro'] ==1) || ($row['vocal'] ==1)) echo "<br>";
				?>
				<form class="mini-form accept-song-form" action="index.php" method="post" id="userGroupForm">
					<input type="hidden" name="accept_song_id" value="<?php echo $row['id'] ?>">
					<br>

					<input type="radio" name="accept-song-value" id="accept-song1" value="1" checked="checked">
					<label for="accept-song1" class="brightyellowbg">
						<i class="fa fa-dot-circle-o"></i>
						<i class="fa fa-circle-o"></i>
						Accept
					</label>
					<input type="radio" name="accept-song-value" id="accept-song2" value="2">
					<label for="accept-song2" class="redbg">
						<i class="fa fa-dot-circle-o"></i>
						<i class="fa fa-circle-o"></i>
						Decline
					</label>

					<br>

					<button class="btn btn-trans" type="submit" name="userGroupButton">Do it!</button>
				</form>
				<?php
				echo "</div>";
			}
			mysqli_free_result($result);
		} else {
			// dopyt sa NEpodarilo vykonať!
			return false;
		}
	} else {
		// Unable to connect with database server!
		return false;
	}
}

function resolve_comment($id){
	echo "resolved ".$id;

	if ($link = connect_db()) {
		$sql = "UPDATE `radio`.`comments` SET `resolved` = '1' WHERE `comments`.`id` = '".$id."';";
		$result = mysqli_query($link, $sql);
		if ($result) {
	    echo 'Comment marked resolved';
	 	} else {
	   	echo 'Not able to find the comment :(';
	  }
		mysqli_close($link);
	} else {
		echo 'Unable to connect with database server!';
	}
}

?>
