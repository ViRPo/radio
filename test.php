<?php
  include('functions.php');

  //https://developers.google.com/apis-explorer/#p/youtube/v3/youtube.commentThreads.list?part=snippet&maxResults=100&textFormat=plainText&videoId=ajuIJphqEPY&_h=9&

  $initRequest = "https://www.googleapis.com/youtube/v3/commentThreads?part=snippet&maxResults=100&textFormat=plainText&videoId=VzGSeWwNkLI&key=" . $GLOBALS['googleApiKey'];

  echo $initRequest . "<br><br>";

  $request = file_get_contents($initRequest);

  $data = json_decode($request, true);

  $comments = [];

  echo "<h1>Num results: " . $data["pageInfo"]["totalResults"] . "</h1><br>";
  for ($i=0; $i < $data["pageInfo"]["totalResults"]; $i++) {
    $comment = $data["items"][$i]["snippet"]["topLevelComment"]["snippet"]["textOriginal"];
    array_push($comments, $comment);
    echo "<p>" . $comment . "</p>";
  }

  echo "<h1>" . sizeof($comments) . "</h1>";

  echo "<h1>" . is_null($data["nextPageToken"]) . "</h1>";

  while((sizeof($comments) < 500) && (!is_null($data["nextPageToken"]))){
    $curRequest = $initRequest . "&pageToken=" . $data["nextPageToken"];
    echo $curRequest . "<br><br>";
    $request = file_get_contents($curRequest);
    $data = json_decode($request, true);

    //print_r($data);

    echo "<h1>Num results: " . $data["pageInfo"]["totalResults"] . "</h1><br>";
    for ($i=0; $i < $data["pageInfo"]["totalResults"]; $i++) {
      $comment = $data["items"][$i]["snippet"]["topLevelComment"]["snippet"]["textOriginal"];
      array_push($comments, $comment);
      echo "<p>" . $comment . "</p>";
    }

    echo "<h1>Size is now: " . sizeof($comments) . "</h1>";
  }

//print_r($data["items"][i]["snippet"]["topLevelComment"]["snippet"]["textOriginal"]);
?>
