<?php 
	session_start();
	$link = mysqli_connect("localhost", "", "", "users");
	if (mysqli_connect_errno()){
		print_r(mysqli_connect_error());
		exit();
	}
	if ($_GET["function"] == "logout"){
		session_unset();
	}


	function time_since($since) {
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'min'),
        array(1 , 's')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    return $print;
}

	function displayTweets($type){
		global $link;
		if ($type == "public"){
			$whereClause = "";
		} else if($type == "isFollowing") {
			$query = "SELECT * FROM isFollowing follower = ".mysqli_real_escape_string($link, $_SESSION["id"]);
			$result = mysqli_query($link, $query);
			$whereClause = "";
			while($row = mysqli_fetch_assoc($result)){
				if ($whereClause == "") $whereClause = "WHERE";
				else $whereClause.=" OR";
				$whereClause.=  " userid".$row['isFollowing'];
			} else if (is_numeric($type)){
				$userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $type)."LIMIT 1";
				$userQueryResult = mysqli_query($link, $userQuery);
				$user = mysqli_fetch_assoc($userQueryResult);
				echo "<h2>".mysqli_real_escape_string($link, $user["email"])."s Tweets</h2>"

				$whereClause = "WHERE userid = ".mysqli_real_escape_string($link, $type);
			}

		} else if ($type == "youtweets"){
			$whereClause = "WHERE userid = ".mysqli_real_escape_string($link, $_SESSION['id']);
		} else if ($type == "search"){
			echo "<p>Showing results for ".mysqli_real_escape_string($link, $_GET['q'],"':'</p>";
			$whereClause = "WHERE tweet LIKE '% ".mysqli_real_escape_string($link, $_GET['q'])."%'";
		}

		$query = "SELECT * FROM tweets ".$whereClause." ORDER BY `datetime` DESC LIMIT 10";

		$result = mysqli_query($link, $query);
		if (mysqli_num_rows($result) == 0){
			echo "There are no tweets to display";
		} else {
			while ($row = mysqli_fetch_assoc($result)) {
				$userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $row["userid"])."LIMIT 1";
				$userQueryResult = mysqli_query($link, $userQuery);
				$user = mysqli_fetch_assoc($userQueryResult);

				echo "<div class=tweet><p><a href='?page=publicprofiles&userid=".$user['id']."'>".user["email"]."</a><span class='time'>".time_since(time()-strtotime($row["datatime"]))." ago</span:</p>";

				echo "<p>".$row["tweet"]."</p>";

				echo "<p><a class='toggleFollow' data-userId=".$row['userid']."></p>";

				$isFollowingQuery = "SELECT * FROM isFollowing follower = ".mysqli_real_escape_string($link, $_SESSION["id"])." AND isFollowing =".mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
				$isFollowingQuery = mysqli_query($link, $isFollowingQuery);
				if (mysqli_num_rows($isFollowingQuery)>0){
					echo "Unfollow";
				} else {
					echo "Follow";
				}

				echo "</a></div>";
			}
		}
	}
	function displaySearch(){
		echo '<div class="form-inline">

  <div class="form-group mx-sm-3 mb-2">
  <input type="hidden" name="page" value="search">
    <input type="password" name="q" class="form-control" id="search" placeholder="Search">
  </div>
  <button type="submit" class="btn btn-primary mb-2">Search Tweets</button>
</div>';
	}

	function displayTweetBox(){
		if ($_SESSION["id"] > 0){
			echo '<div id="tweetSuccess" class="alert alert-success">Your twet was posted successfully</div><div id="tweetFail" class="alert alert-danger"></div>
			<div class="form">
  <div class="form-group mx-sm-3 mb-2">
    <textarea class="form-control" id="tweetContent" placeholder="Search"></textarea>
  </div>
  <button id="postTweetButton" class="btn btn-primary mb-2">Post Tweet</button>
</div>';
		}
	}

	function displayUsers(){
		global $link;

		$query = "SELECT * FROM users LIMIT 10";

		$result = mysqli_query($link, $query);
		
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<p><a href='?page=pagepublicprofiles$userid=".$row['id']."'>".$row['email']."</a></p>";
		}
	}
?>