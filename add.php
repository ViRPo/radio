<?php
	include('functions.php');
	if (isset($_POST['add-url'])) {
		add_song();
	}
	if (isset($_POST['quick-add-url'])) {
		quick_add_song();
	}
	if (isset($_POST['comment'])) {
		add_comment();
	}
?>
