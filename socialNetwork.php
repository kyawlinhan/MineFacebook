<?php

/**
 * This page show a network of colored nodes. The color represent a connected
 * component. So different colors mean people from one color can only meet
 * another color people (connection) through you only. It also shows people
 * name on the nodes when the mouse hover event is triggered.
**/

require_once 'system/facebook/src/facebook.php';
require_once 'system/helpers/facebookConn.php';
require_once 'system/helpers/getGeoCode.php';
require_once 'system/helpers/readUrl.php';
require_once 'system/helpers/D3CompatiblesMethods.php';
require_once 'system/classes/SocialNetwork/SocialNetwork.php';

// Get User ID who authorized the application
$user = $facebook->getUser();

if ($user) {
	try {
		$user_profile = $facebook->api('/me');
	} catch (FacebookApiException $e) {
		error_log($e);
		$user = null;
	}
}

$user_currLoc = $user_profile['location']['name'];

$token = $facebook->getAccessToken();

if ($user)
{
	$friends = $facebook->api('/me/friends?access_token='.$token);
	$friendIdArray = array();
	$nameOfId = array();
	if ($friends)
	{
		$friends = $friends["data"];

		//loop through all the friends data and get id.
		$i = 0;
		foreach ($friends as $friend)
		{
			$id = $friend["id"];
			array_push($friendIdArray, $id);
			$nameOfId[$id] = $friend["name"];
			$i++;
			if ($i == 15)
				break;
		}
	}
	//======================Getting Social Network data
	$mySocialNetwork = new SocialNetwork();
	foreach($friendIdArray as $friendId)
	{
		$url = $graphAPI.'me/mutualfriends/'.$friendId.'?limit=0&access_token='.$token;
		$Array = readUrl($url);
		$mutualArray = $Array["data"];
		$mySocialNetwork->addMutualLinks($user, $friendId, $mutualArray);
	}
	$nameOfMutualId = $mySocialNetwork->getNameOfMutualId();
	$nameOfId= $nameOfId + $nameOfMutualId;
	$mySocialNetwork->createGroup();
	
	$links = $mySocialNetwork->getAdjacencyList();
	$nodes= $mySocialNetwork->getNodeArray();
	$indexMap = getindices($nodes);
	$groupMap = $mySocialNetwork->getGroupArray();
	$jsNodes = getD3FriendlyNodesArray($nodes, $indexMap, $groupMap, $nameOfId);
	$jsLinks = getD3FriendlyLinksArray ($links, $nodes, $indexMap);
	$random = rand();
	$myFile = 'temp/'.$random.'.json';
	$fh = fopen($myFile, 'w');
	fwrite($fh, '{"nodes":'.$jsNodes.',"links":'.$jsLinks.'}');
	fclose($fh);
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
<style>

.node {
  stroke: #fff;
  stroke-width: 1.5px;
}

.link {
  stroke: #999;
  stroke-opacity: .6;
}

</style>
<script src="javascripts/d3.v2.min.js"></script>
</head>
<body>
<div id="wrapper">
	<div id="menu-wrapper">
		<div id="menu" class="container">
			<ul>
				<li><a href="index.php">Homepage</a></li>
				<li><a href="mapfriends.php">Map friends</a></li>
				<li><a href="statistics.php">Statistics</a></li>
				<li class="current_page_item"><a href="socialNetwork.php">Social Network</a></li>
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
		<div class="post">
		<script>

var width = 960,
    height = 500;

var color = d3.scale.category20();

var force = d3.layout.force()
    .charge(-120)
    .linkDistance(30)
    .size([width, height]);

var svg = d3.select(".post").append("svg")
    .attr("width", width)
    .attr("height", height);

d3.json("temp/<?php echo $random; ?>.json", function(error, graph) {
console.log(graph);
  force
      .nodes(graph.nodes)
      .links(graph.links)
      .start();

  var link = svg.selectAll("line.link")
      .data(graph.links)
    .enter().append("line")
      .attr("class", "link")
      .style("stroke-width", function(d) { return Math.sqrt(d.value); });

  var node = svg.selectAll("circle.node")
      .data(graph.nodes)
    .enter().append("circle")
      .attr("class", "node")
      .attr("r", 5)
      .style("fill", function(d) { return color(d.group); })
      .call(force.drag);

  node.append("title")
      .text(function(d) { return d.name; });

  force.on("tick", function() {
    link.attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node.attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
  });
});

</script>
</div>
	<script>var nodesa = <?php echo '{"nodes":'.$jsNodes.',"links":'.$jsLinks.'}'?>;console.log(nodesa);</script>
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