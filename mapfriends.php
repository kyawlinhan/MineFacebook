<?php 
/**
 * This page will map a number of your friends on the google map.
 */

session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : TrendyBiz
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20120818

-->
<?php
require_once 'system/facebook/src/facebook.php';
require_once 'system/helpers/facebookConn.php';
require_once 'system/helpers/getGeoCode.php';
require_once 'system/helpers/readUrl.php';

// Get User ID who authorized the application
$user = $facebook->getUser();

if ($user) {
	try {
		 
		// Proceed knowing you have a logged in user who's authenticated.
		$user_profile = $facebook->api('/me');
	} catch (FacebookApiException $e) {
		error_log($e);
		$user = null;
	}
}

$user_currLoc = $user_profile['location']['name'];

//$token is a key to the authorized data
$token = $facebook->getAccessToken();

//If user is logged in,
if ($user)
{
	//Get a list of friends using graph api
	$friends = $facebook->api('/me/friends?access_token='.$token);
	$id_hometownlist = array();
	if ($friends)
	{
		$friends = $friends["data"];
		//loop through all the friends data.
		$i = 0;
		foreach ($friends as $friend)
		{
			$id = $friend["id"];
				
			$fql = "SELECT uid, hometown_location FROM user "
					. "WHERE uid = $id";
			$path = 'https://graph.facebook.com/'.$id.'?access_token='.$token;
				
			//query facebook data using FQL (SQL like facebook language)
			$friend_id_h = $facebook->api(array(
					'method'       => 'fql.query',
					'access_token' => $token,
					'query'        => $fql,
			));
			
			//print_r($friend_id_h);
			//printed in this format
			//Array ( [0] => Array ( [uid] => 1507902 [hometown_location] => Array ( [city] =>
			//Richmond [state] => Virginia [country] => United States [zip] => [id] => 103727876332781
			//[name] => Richmond, Virginia ) ) ) v   nm bmmnnb 
			
			
			array_push ($id_hometownlist, array($friend_id_h[0]['uid'], $friend_id_h[0]['hometown_location']['name']));
			$i++;
			if ($i == 10)
				break;
		}
	}
}

/*$apiKey = 'AIzaSyDVqEgfsnRZsCktgUDyYrgN62ohFJBcceI';
$geo = new GoogleGeocode( $apiKey );

//This api doesn't work for this string. Strange error
$result = $geo->geocode("Singapore, Singapore");*/

$result = lookup($user_currLoc);
$user_coor = array($result['latitude'], $result['longitude']);

$friends_coor = array();
foreach ($id_hometownlist as $id)
{
	//var_dump($id[1]);
	if ($id[1] != null)
	{
		$result = lookup((string)$id[1]);
		array_push ($friends_coor, array($id[0], $result['name'], $result['latitude'], $result['longitude']));
	}
}


?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Peter's Portal</title>
<link href="http://fonts.googleapis.com/css?family=Dancing+Script|Open+Sans+Condensed:300" rel="stylesheet" type="text/css" />
<link href="stylesheets/style.css" rel="stylesheet" type="text/css" media="screen" />
<script
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDVqEgfsnRZsCktgUDyYrgN62ohFJBcceI&sensor=false">
</script>
<script>
var myCenter=new google.maps.LatLng(<?php echo $user_coor[0];?>,<?php echo $user_coor[1];?>);

function initialize()
{
	var mapProp = {
	  center:myCenter,
	  zoom:2,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };
	
	var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
	
	var marker=new google.maps.Marker({
	  position:myCenter,
	  animation:google.maps.Animation.BOUNCE
	  });
	
	marker.setMap(map);
	
	var infowindow = new google.maps.InfoWindow({
	  content:"Me!"
	  });
	
	google.maps.event.addListener(marker, 'click', function() {
	  infowindow.open(map,marker);
	  });
	<?php 
	foreach ($friends_coor as $friend)
	{
		?>
		
		var point=new google.maps.LatLng(<?php echo $friend[2];?>,<?php echo $friend[3];?>);
		var marker=new google.maps.Marker({
			position:point,
		});
		
		marker.setMap(map);
		/*var infowindow = new google.maps.InfoWindow({
			  content:'<?php echo $friend[0].' from '.$friend[1];?>'
			  });
		google.maps.event.addListener(marker, 'click', function() {
			  infowindow.open(map,marker);
			  });*/
		
		<?php
	}
	?>
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>
<body>
<div id="wrapper">
	<div id="menu-wrapper">
		<div id="menu" class="container">
			<ul>
				<li><a href="index.php">Homepage</a></li>
				<li class="current_page_item"><a href="mapfriends.php">Map Friends</a></li>
				<li><a href="statistics.php">Statistics</a></li>
				<li><a href="socialNetwork.php">Social Network</a></li>
			</ul>
		</div>
	</div>
	<div id="logo" class="container">
		<h1>242 Final Project</h1>
		<p>Welcome!</p>
	</div>
	<div class="divider">&nbsp;</div>
	<div id="page" class="container">
			<div class="post">
				<h2 class="title">The map of your friends.</h2>
					<div id="googleMap" style="width:100%;height:380px;"></div>
											
					user loc:<br/>
					<pre><?php print_r($user_coor)?></pre>
					friends:<br/>
					<pre><?php print_r($friends_coor)?></pre>
			</div>
		<!-- end #content -->
		<!-- end #sidebar -->
		<div style="clear: both;"></div>
	</div>
	<!-- end #page -->
	<div class="divider">&nbsp;</div>
	<div id="three-column" class="container"></div>
</div>
<div id="footer-content" class="container">
	<div id="footer-bg"></div>
</div>
<div id="footer">
	<p>© 2012 Untitled Inc. All rights reserved. Lorem ipsum dolor sit amet nullam blandit consequat phasellus etiam lorem. Design by <a href="http://www.freecsstemplates.org">FCT</a>.  Photos by <a href="http://fotogrph.com/">Fotogrph</a>.</p>
</div>
<!-- end #footer -->
</body>
</html>