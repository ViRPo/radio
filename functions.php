<?php

//Univerzalny link s databazou
function connect_db() {
	if ($link = mysql_connect('localhost', 'root', '')) {
		if (mysql_select_db('radio', $link)) {
			mysql_query("SET CHARACTER SET 'utf8'", $link);
			return $link;
		} else {
			// Nevybralo databázu
			return false;
		}
	} else {
		// Nespojilo sa so servrom
		return false;
	}
}

//Pridavame songu
function add_song() {
	if ($link = connect_db()) {
		$sql = "INSERT INTO `radio`.`songs` (`id`, `youtube_id`, `speed`, `mood`, `intensity`, `instrumental`, `electro`, `vocal`, `admin_id`, `accepted`, `plays`, `skips`) VALUES (NULL, 'ajuIJphqEPY', '3', '2', '3', '1', '1', '1', '0', '0', '0', '0');"
		$sql = "INSERT INTO `songs` (`id`, `name`, `pos`, `day`, `month`, `year`, `injury`, `point`, `assist`) VALUES (NULL, '" . addslashes(strip_tags($_POST['name'])) . "', '". addslashes(strip_tags($_POST['pos'])) ."', '" . addslashes(strip_tags($_POST['day'])) . "', '" . addslashes(strip_tags($_POST['month'])) . "', '" . addslashes(strip_tags($_POST['year'])) . "', '" . addslashes(strip_tags($_POST['injury'])) . "', '" . addslashes(strip_tags($_POST['point'])) . "', '" . addslashes(strip_tags($_POST['assist'])) . "');";
		//$sql = "INSERT INTO players SET name='" . addslashes(strip_tags($_POST['name'])) . "', day='" . addslashes(strip_tags($_POST['day'])) . "', month='" . addslashes(strip_tags($_POST['month'])) . "', year='" . addslashes(strip_tags($_POST['year'])) . "', handler='0', mid='1', long='1', injury='sdafasdf', point='10', assist='20'"; // definuj dopyt
		$result = mysql_query($sql, $link); // vykonaj dopyt
		if ($result) {
			// dopyt sa podarilo vykonať
	    echo '<p>Úspešne pridané</p>'. "\n";
	 	} else {
			// Dopyt sa nepodarilo vykonať
	   	echo '<p class="chyba">Nastala chyba pri pridávaní.</p>' . "\n";
	  }
		mysql_close($link);
	} else {
		// Nepodarilo sa spojit s databazou alebo vybrat databazu
		echo '<p class="chyba">Nepodarilo sa spojiť s databázovým serverom!</p>';
	}
}	// koniec funkcie

?>
