<?php

/*
 * This is the home page for the website. 
 */
require_once 'system/facebook/src/facebook.php';
require_once 'system/helpers/facebookConn.php';

// Get User ID who authorized the application
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
  	
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl(
	  		array(
	  				'scope'         => 'email,user_birthday,user_education_history,user_hometown,
	  				user_likes,user_location,user_relationships,user_relationship_details
	  				user_religion_politics,user_photos,user_status,user_videos,friends_birthday,
	  				friends_education_history,friends_hometown,friends_location,friends_relationships,
	  				friends_relationship_details,friends_religion_politics,friends_photos,friends_status,friends_videos'
	  		)
  		);
}

//getting user name to welcome user on the page.
if ($user)
{
	$user_profile = $facebook->api('/me');
	$user_name = $user_profile['name'];
}
?>
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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Peter's Portal</title>
<link href="http://fonts.googleapis.com/css?family=Dancing+Script|Open+Sans+Condensed:300" rel="stylesheet" type="text/css" />
<link href="stylesheets/style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
	<div id="menu-wrapper">
		<div id="menu" class="container">
			<ul>
				<li class="current_page_item"><a href="index.php">Homepage</a></li>
				<li><a href="mapfriends.php">Map friends</a></li>
				<li><a href="statistics.php">Statistics</a></li>
				<li><a href="socialNetwork.php">Social Network</a></li>
				<!--  no logout as user can always log out from facebook. If the logout is implemented here, user will be logged out from his facebook unintentionally. -->
				<li><a href="<?php if (!$user) /*{ session_destroy(); } else*/ echo $loginUrl; ?>"><?php if (!$user) /*echo $user.'Logout'; else */echo 'Login'?></a></li>
			</ul>
		</div>
	</div>
	<div id="logo" class="container">
		<h1>242 Final Project</h1>
	</div>
	<div class="divider">&nbsp;</div>
	<div id="page" class="container">
				<h2 class="title">Welcome! <?php if ($user) echo $user_name?></h2>
				<div class="entry">
					<ul>
						Menu bar functions:
						<li>Click on "LOGIN" to sign in first. It shows the establishment of facebook connection.</li>
						<li>Click on "MAP FRIENDS" to see mapping of 20 friends on Google Map.</li>
						<li>Click on "Statistics" to see a brief statistical report of your facebook data.</li>
						<li><?php if ($user) echo 'Token: '.($facebook->getAccessToken());?></li>
						<li><b>**WARNINGS: You need to sign in before you see any magic on this website!**</b></li>
					</ul>
				</div>
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
