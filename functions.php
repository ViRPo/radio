<?php

include('config.php');
include('stats.php');

session_start();

//Univerzalny link s databazou
function connect_db() {
	if ($link = mysqli_connect($GLOBALS['host'], $GLOBALS['username'], $GLOBALS['passwd'], $GLOBALS['dbname'])) {
			mysqli_query($link, "SET CHARACTER SET 'utf8'");
			return $link;
	} else {
		// Nespojilo sa so servrom
		return false;
	}
}

function contains_count($needles, $haystack) {
  return count(array_intersect($needles, explode(" ", preg_replace("/[^A-Za-z0-9' -]/", "", $haystack))));
}

function contains($needles, $haystack) {
  return array_intersect($needles, explode(" ", preg_replace("/[^A-Za-z0-9' -]/", "", $haystack)));
}

function append_data_to_string($data) {
  $string_result = "";
  for ($i=0; $i < $data["pageInfo"]["totalResults"]; $i++) {
    $comment = $data["items"][$i]["snippet"]["topLevelComment"]["snippet"]["textOriginal"];
    $string_result .= " " . strtolower(preg_replace("/[^a-zA-Z]+/", " ", $comment));
  }
  return $string_result;
}

function get_string_from_yid($youtubeID) {
  $initRequest = "https://www.googleapis.com/youtube/v3/commentThreads?part=snippet&maxResults=100&textFormat=plainText&videoId=" . $youtubeID . "&key=" . $GLOBALS['googleApiKey'];

  $request = file_get_contents($initRequest);
  $data = json_decode($request, true);

  $comments_string = "";
  append_data_to_string($data, $comments_string);

  $count_repeats = 1;
  while(($count_repeats < 7) && array_key_exists("nextPageToken",$data)){
    $count_repeats++;
    $curRequest = $initRequest . "&pageToken=" . $data["nextPageToken"];
    $request = file_get_contents($curRequest);
    $data = json_decode($request, true);

    $comments_string .= " " . append_data_to_string($data);
  }

  return $comments_string;
}

function stat_form_counts($stat_low, $stat_high) {
  if(($stat_low+1)/($stat_high+1) > 2) {
    return 1;
  } else {
    if(($stat_high+1)/($stat_low+1) > 2) {
      return 3;
    } else {
      return 2;
    }
  }
}

function bin_stat_from_counts($stat) {
  if($stat > 1) {
    return 1;
  } else {
    return 0;
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
		$accepted = 0;
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
		if (isset($_SESSION['user_group'])){
			if($_SESSION['user_group']==1){
				$admin_id = $_SESSION['user_id'];
			}
		}
		if (isset($_SESSION['user_group'])){
			if($_SESSION['user_group']<4){
				$accepted = 1;
			}
		}
		$video_parsed_url = "Unable to parse: ".addslashes(strip_tags($_POST['add-url']));
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', addslashes(strip_tags($_POST['add-url'])), $match)) {
		    $video_parsed_url = $match[1];
		}
		//$sql = "INSERT INTO `" . $GLOBALS['dbname'] . "`.`songs` (`id`, `youtube_id`, `speed`, `mood`, `intensity`, `instrumental`, `electro`, `vocal`, `admin_id`, `accepted`, `plays`, `skips`) VALUES (NULL, 'lwPrBchV3ZQ', '3', '2', '2', '1', '0', '1', '0', '0', '0', '0');";
		$sql = "INSERT INTO `" . $GLOBALS['dbname'] . "`.`songs` (`id`, `youtube_id`, `speed`, `mood`, `intensity`, `instrumental`, `electro`, `vocal`, `admin_id`, `user_id`, `accepted`, `plays`, `skips`) VALUES (NULL, '" . $video_parsed_url . "', '" . addslashes(strip_tags($_POST['add-speed'])) . "', '" . addslashes(strip_tags($_POST['add-mood'])) . "', '" . addslashes(strip_tags($_POST['add-intensity'])) . "', '" . $instrumental_val . "', '" . $electro_val . "', '" . $vocal_val . "', '" . $admin_id . "', '" . $user_id . "', '" . $accepted . "', '0', '0');";
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

// Quick Add funkcia
function quick_add_song() {
	if ($link = connect_db()) {
    global $song_stats;

    $speed_val = 0;
    $mood_val = 0;
    $intensity_val = 0;
		$instrumental_val = 0;
		$electro_val = 0;
		$vocal_val = 0;
		$user_id = 0;
		$admin_id = 0;
		$accepted = 0;

		if (isset($_SESSION['user_id'])) {
			$user_id=$_SESSION['user_id'];
		}
		if (isset($_SESSION['user_group'])){
			if($_SESSION['user_group']==1){
				$admin_id = $_SESSION['user_id'];
			}
		}
    $video_id = addslashes(strip_tags($_POST['quick-add-url']));
		$video_parsed_url = "Unable to parse: ".$video_id;
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_id, $match)) {
		    $video_parsed_url = $match[1];
		}
    if (strlen($video_id) == 11) $video_parsed_url = $video_id;

    $result_string = get_string_from_yid($video_parsed_url);

    $speed_val = stat_form_counts(
      contains_count($song_stats["speed_slow"], $result_string),
      contains_count($song_stats["speed_fast"], $result_string)
    );

    $mood_val = stat_form_counts(
      contains_count($song_stats["mood_dark"], $result_string),
      contains_count($song_stats["mood_cheerful"], $result_string)
    );

    $intensity_val = stat_form_counts(
      contains_count($song_stats["intensity_calm"], $result_string),
      contains_count($song_stats["intensity_intense"], $result_string)
    );

		$instrumental_val = bin_stat_from_counts(contains_count($song_stats["instrumental"], $result_string));
		$electro_val = bin_stat_from_counts(contains_count($song_stats["electro"], $result_string));
		$vocal_val = bin_stat_from_counts(contains_count($song_stats["vocal"], $result_string));

		//$sql = "INSERT INTO `" . $GLOBALS['dbname'] . "`.`songs` (`id`, `youtube_id`, `speed`, `mood`, `intensity`, `instrumental`, `electro`, `vocal`, `admin_id`, `accepted`, `plays`, `skips`) VALUES (NULL, 'lwPrBchV3ZQ', '3', '2', '2', '1', '0', '1', '0', '0', '0', '0');";
		$sql = "INSERT INTO `" . $GLOBALS['dbname'] . "`.`songs` (`id`, `youtube_id`, `speed`, `mood`, `intensity`, `instrumental`, `electro`, `vocal`, `admin_id`, `user_id`, `accepted`, `plays`, `skips`) VALUES (NULL, '" . $video_parsed_url . "', '" . $speed_val . "', '" . $mood_val . "', '" . $intensity_val . "', '" . $instrumental_val . "', '" . $electro_val . "', '" . $vocal_val . "', '" . $admin_id . "', '" . $user_id . "', '" . $accepted . "', '0', '0');";
		$result = mysqli_query($link, $sql); // vykonaj dopyt
		if ($result) {
			// dopyt sa podarilo vykonať
	    echo 'Úspešne pridané';
	 	} else {
			// dopyt sa nepodarilo vykonať
	   	echo 'Nastala chyba pri pridávaní';
	  }
		mysqli_close($link);
	} else {
		// nepodarilo sa spojit s databazou alebo vybrat databazu
		echo 'Unable to connect with database server!';
	}
}

function check_if_set($value){
	if(isset($value)){
		echo "=";
	} else {
		echo "!=";
	}
}

function add_comment() {
	if ($link = connect_db()) {
		//$sql = "INSERT INTO `" . $GLOBALS['dbname'] . "`.`songs` (`id`, `youtube_id`, `speed`, `mood`, `intensity`, `instrumental`, `electro`, `vocal`, `admin_id`, `accepted`, `plays`, `skips`) VALUES (NULL, 'lwPrBchV3ZQ', '3', '2', '2', '1', '0', '1', '0', '0', '0', '0');";
		//$sql = "INSERT INTO `" . $GLOBALS['dbname'] . "`.`comments` (`id`, `youtube_id`, `speed`, `mood`, `intensity`, `instrumental`, `electro`, `vocal`, `admin_id`, `accepted`, `plays`, `skips`) VALUES (NULL, '" . $video_parsed_url . "', '" . addslashes(strip_tags($_POST['add-speed'])) . "', '" . addslashes(strip_tags($_POST['add-mood'])) . "', '" . addslashes(strip_tags($_POST['add-intensity'])) . "', '" . $instrumental_val . "', '" . $electro_val . "', '" . $vocal_val . "', '0', '0', '0', '0');";
		$sql = "INSERT INTO `" . $GLOBALS['dbname'] . "`.`comments` (`id`, `nickname`, `email`, `comment`, `song_id`) VALUES (NULL, '" . addslashes(strip_tags($_POST['comment-nickname'])) . "', '" . addslashes(strip_tags($_POST['comment-email'])) . "', '" . addslashes(strip_tags($_POST['comment'])) . "', '" . addslashes(strip_tags($_POST['comment-songid'])) . "');";
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
		$sql = "INSERT INTO `" . $GLOBALS['dbname'] . "`.`users` (`id`, `nickname`, `email`, `password`, `group`) VALUES (NULL, '". $username ."', '". $email ."', '". $pass ."', '". $group ."');";
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
		$sql = "UPDATE `" . $GLOBALS['dbname'] . "`.`users` SET `group` = '".$group."' WHERE `users`.`id` = ".$user.";";
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
		$sql = "UPDATE `" . $GLOBALS['dbname'] . "`.`songs` SET `admin_id` = '".$_SESSION['user_id']."', `accepted` = '".$accept."' WHERE `songs`.`id` = ".$song.";";
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

function set_song_edit_accept($song, $speed, $mood, $intensity, $instrumental, $electro, $vocal, $accept){
	if ($link = connect_db()) {
		$sql = "UPDATE `" . $GLOBALS['dbname'] . "`.`songs` SET `admin_id` = '".$_SESSION['user_id']."', `speed` = '".$speed."', `mood` = '".$mood."', `intensity` = '".$intensity."', `instrumental` = '".$instrumental."', `electro` = '".$electro."', `vocal` = '".$vocal."', `accepted` = '".$accept."' WHERE `songs`.`id` = ".$song.";";
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
		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<div class='comment col-md-4'><span class='color-orange'><a href='mailto:" . $row['email'] ."'>";
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
		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<div class='comment col-md-2'>";
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
		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<div class='comment col-md-4 col-sm-6 col-xs-12'>";
				echo "<iframe width='280' height='190' src='https://www.youtube.com/embed/".$row['youtube_id']."' frameborder='0' allowfullscreen></iframe><br>";
				?>
				<div class="row">
					<div class="col-xs-12 text-center">
						<form class="mini-form accept-song-form" action="index.php" method="post" id="userGroupForm">
							<input type="hidden" name="accept_song_id" value="<?php echo $row['id'] ?>">
							<table class="no-style-table">
								<tr>
									<td class="text-left"><span class='color-red'>Speed&nbsp;</span></td>
									<td class="text-right"><input type="number" name="new_speed" min="1" max="4" value="<?php echo $row['speed'] ?>"></td>
								</tr>
								<tr>
									<td class="text-left"><span class='color-orange'>Mood&nbsp;</span></td>
									<td class="text-right"><input type="number" name="new_mood" min="1" max="3" value="<?php echo $row['mood'] ?>"></td>
								</tr>
								<tr>
									<td class="text-left"><span class='color-yellow'>Intensity&nbsp;</span></td>
									<td class="text-right"><input type="number" name="new_intensity" min="1" max="3" value="<?php echo $row['intensity'] ?>"></td>
								</tr>
							</table>
							<span class='color-brightyellow'>
							<i class='fa fa-music color-white' aria-hidden='true'></i> Instrumental <input type="checkbox" name="new_instrumental" value="1" <?php if($row['instrumental']==1) echo "checked"; ?>><br>
							<i class='fa fa-bolt color-white' aria-hidden='true'></i> Electro <input type="checkbox" name="new_electro" value="1" <?php if($row['electro']==1) echo "checked"; ?>><br>
							<i class='fa fa-microphone color-white' aria-hidden='true'></i> Vocal <input type="checkbox" name="new_vocal" value="1" <?php if($row['vocal']==1) echo "checked"; ?>><br>
							</span>
							<button class="btn btn-default brightyellowbg" type="submit" name="userGroupButton"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Accept</button>
						</form>
					</div>
					<div class="col-xs-12 text-center">
						<form class="mini-form accept-song-form" action="index.php" method="post" id="userGroupForm">
							<input type="hidden" name="decline_song_id" value="<?php echo $row['id'] ?>">
							<button class="btn btn-trans brightyellowbg" type="submit" name="userGroupButton"><i class="fa fa-thumbs-down" aria-hidden="true"></i> Decline</button>
						</form>
					</div>
				</div>
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

function get_all_songs(){
	if ($link = connect_db()) {
		$sql = "SELECT `youtube_id` FROM `songs` WHERE (`instrumental` = 1 OR `electro` = 1 OR `vocal` = 1) AND (`speed` = 1 OR `speed` = 2 OR `speed` = 3 OR `speed` = 4) AND (`mood` = 1 OR `mood` = 2 OR `mood` = 3) AND (`intensity` = 1 OR `intensity` = 2 OR `intensity` = 3) AND `accepted` = 1 ORDER BY `id` ASC";
		$result = mysqli_query($link, $sql);
		$song_list = array();
		$pos = 0;
		if ($result) {
			while ($row = mysqli_fetch_row($result)) {
				$song_list[] = $row;
			}
			return $song_list;
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

function get_all_songs_count(){
	if ($link = connect_db()) {
		$sql = "SELECT COUNT(*) FROM `songs` WHERE `accepted` = 1";
		$result = mysqli_query($link, $sql);
    $answer = mysqli_fetch_row($result);
		if ($result) {
			return $answer[0];
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

function get_songs($speed1, $speed2, $speed3, $speed4, $mood1, $mood2, $mood3, $intensity1, $intensity2, $intensity3, $vocal1, $vocal2, $vocal3){
	if ($link = connect_db()) {
		$sql = "SELECT `youtube_id` FROM `songs` WHERE (";
		if ($speed1==1) {
			$sql = $sql . "`speed` = 1 OR ";
		} else {
			$sql = $sql . "0 OR ";
		}
		if ($speed2==1) {
			$sql = $sql . "`speed` = 2 OR ";
		} else {
			$sql = $sql . "0 OR ";
		}
		if ($speed3==1) {
			$sql = $sql . "`speed` = 3 OR ";
		} else {
			$sql = $sql . "0 OR ";
		}
		if ($speed4==1) {
			$sql = $sql . "`speed` = 4 ";
		} else {
			$sql = $sql . "0 ";
		}
		$sql = $sql .") AND ( ";

		if ($mood1==1) {
			$sql = $sql . "`mood` = 1 OR ";
		} else {
			$sql = $sql . "0 OR ";
		}
		if ($mood2==1) {
			$sql = $sql . "`mood` = 2 OR ";
		} else {
			$sql = $sql . "0 OR ";
		}
		if ($mood3==1) {
			$sql = $sql . "`mood` = 3 ";
		} else {
			$sql = $sql . "0 ";
		}
		$sql = $sql .") AND ( ";

		if ($intensity1==1) {
			$sql = $sql . "`intensity` = 1 OR ";
		} else {
			$sql = $sql . "0 OR ";
		}
		if ($intensity2==1) {
			$sql = $sql . "`intensity` = 2 OR ";
		} else {
			$sql = $sql . "0 OR ";
		}
		if ($intensity3==1) {
			$sql = $sql . "`intensity` = 3 ";
		} else {
			$sql = $sql . "0 ";
		}
		$sql = $sql .") AND ( ";

		if ($vocal1==0) {
			$sql = $sql . "`instrumental` = 0 AND ";
		} else {
			$sql = $sql . "1 AND ";
		}
		if ($vocal2==0) {
			$sql = $sql . "`electro` = 0 AND ";
		} else {
			$sql = $sql . "1 AND ";
		}
		if ($vocal3==0) {
			$sql = $sql . "`vocal` = 0 ";
		} else {
			$sql = $sql . "1 ";
		}
		$sql = $sql .")";

		$sql = $sql . "AND `accepted` = 1 ORDER BY `id` ASC";
		$result = mysqli_query($link, $sql);
		$song_list = array();
		$pos = 0;
		if ($result) {
			while ($row = mysqli_fetch_row($result)) {
				$song_list[] = $row;
			}
			return $song_list;
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
		$sql = "UPDATE `" . $GLOBALS['dbname'] . "`.`comments` SET `resolved` = '1' WHERE `comments`.`id` = '".$id."';";
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
