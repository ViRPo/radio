<?php
  include('functions.php');

  $video_id = "VgXOPeobPcI";

  $result_string = get_string_from_yid($video_id);
  echo "https://www.youtube.com/watch?v=" . $video_id . "<br><br>";

  $count_cheer = contains_count($song_stats["mood_cheerful"], $result_string);
  echo "Mood dark: ";
  print_r(contains($song_stats["mood_dark"], $result_string));
  echo "<br>";
  $count_dark = contains_count($song_stats["mood_dark"], $result_string);
  echo "Mood cheer: ";
  print_r(contains($song_stats["mood_cheerful"], $result_string));
  echo "<br>";
  echo "<strong>Mood: " . stat_form_counts($count_dark, $count_cheer) . "</strong><br>";

  echo "<br>";

  $count_slow = contains_count($song_stats["speed_slow"], $result_string);
  echo "Speed slow: ";
  print_r(contains($song_stats["speed_slow"], $result_string));
  echo "<br>";
  $count_fast = contains_count($song_stats["speed_fast"], $result_string);
  echo "Speed fast: ";
  print_r(contains($song_stats["speed_fast"], $result_string));
  echo "<br>";
  $speed_val = stat_form_counts($count_slow, $count_fast);
  echo "<strong>Speed: " . stat_form_counts($count_slow, $count_fast) . "</strong><br>";

  echo "<br>";

  $count_calm = contains_count($song_stats["intensity_calm"], $result_string);
  echo "Intensity calm: ";
  print_r(contains($song_stats["intensity_calm"], $result_string));
  echo "<br>";
  $count_intense = contains_count($song_stats["intensity_intense"], $result_string);
  echo "Intensity intense: ";
  print_r(contains($song_stats["intensity_intense"], $result_string));
  echo "<br>";
  $intensity_val = stat_form_counts($count_calm, $count_intense);
  echo "<strong>Intensity: " . stat_form_counts($count_calm, $count_intense) . "</strong><br>";

  echo "<br>";

  $count_vocal = contains_count($song_stats["vocal"], $result_string);
  echo "<strong>Vocal: " . bin_stat_from_counts($count_vocal) . "</strong><br>";
  print_r(contains($song_stats["vocal"], $result_string));
  echo "<br>";
  $count_electro = contains_count($song_stats["electro"], $result_string);
  echo "<strong>Electro: " . bin_stat_from_counts($count_electro) . "</strong><br>";
  print_r(contains($song_stats["electro"], $result_string));
  echo "<br>";
  $count_instrumental = contains_count($song_stats["instrumental"], $result_string);
  echo "<strong>Instrumental: " . bin_stat_from_counts($count_instrumental) . "</strong><br>";
  print_r(contains($song_stats["instrumental"], $result_string));
  echo "<br>";
  echo "<br>";

  echo sizeof($song_stats["mood_cheerful"]) . "<br>";

  print_r($song_stats["mood_cheerful"][0]);

  echo "<h1>This is the string:</h1>" . $result_string;

?>
