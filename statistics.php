<?php session_start(); 
/**
 * This page shows the statistical view of facebook data.
 */

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
<?php
require_once 'system/facebook/src/facebook.php';
require_once 'system/helpers/facebookConn.php';
require_once 'system/helpers/getGeoCode.php';
require_once 'system/helpers/readUrl.php';
require_once 'system/classes/WordCloud/WordCloud.php';
require_once 'system/helpers/findMostLikedStatus.php';
require_once 'system/classes/TimeDistribution/FriendsStatistics.php';

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
	//get 500 statuses from the user data.
	$url = $graphAPI.'me/statuses?limit=1000&access_token='.$token;
	//echo $url;
	$statusesObject = readUrl($url);
	
	$statusArray = $statusesObject["data"];
	//$statusArray = $statusesObject["data"];
	//var_dump($statusArray);
	$wordCloud = new WordCloud($statusArray);
	$mostLikedStatusArray = getMostLikedStatus($statusArray, $id);
	
	//Get a list of friends using graph api
	$friends = $facebook->api('/me/friends?access_token='.$token);
	$friendIdArray = array();
	$nameOfId = array();
	if ($friends)
	{
		$friends = $friends["data"];
		$friendsStatistics = new FriendsStatistics ();
		
		//loop through all the friends data.
		$i = 0;
		foreach ($friends as $friend)
		{
			$id = $friend["id"];
			$name = $friend["name"];
			
			//get friend statuses and get analyzed data.
			$friendStatus = getFriendStatuses($id);
			
			//filter out people who don't have facebook activities.
			if ($friendStatus)
			{
				//store friend id for later use
				array_push($friendIdArray, $id);
				$nameOfId[$id] = $name;
				$friendsStatistics->addAFriendStatistics($friendStatus, $id);
				$i++;
			}
			if ($i == 10)
				break;
		}
	}
	
	$weekDayTimeDistribution = $friendsStatistics->getGeneralWeekdayDistribution();
	$weekEndTimeDistribution = $friendsStatistics->getGeneralWeekendDistribution();
	$weekDistribution = $friendsStatistics->getWeekDistribution();
}

/**
 * return the results of facebook graph api query, array of friend status.
 * @param unknown $Uid
 * @return mixed
 */
function getFriendStatuses ($Uid)
{
	global $graphAPI, $token;
	$url = $graphAPI.$Uid.'/statuses?limit=50&access_token='.$token;
	return readUrl($url)['data'];
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
<script src="javascripts/jquery.js"></script>
<script src="javascripts/jquery.awesomeCloud-0.2.min.js"></script>
<script src="javascripts/Highcharts-2.3.3/js/highcharts.js"></script>
<script src="javascripts/Highcharts-2.3.3/js/modules/exporting.js"></script>
<script>
var weekdayTimeDistriOf = new Array();
var weekendTimeDistriOf = new Array();
<?php
foreach ($friendIdArray as $friendId)
{?>
	var weekdayTimeDistri = new Array();
	var weekendTimeDistri = new Array();
	<?php 
	$friendTimeDistribution = $friendsStatistics->getAFriendStatistics($friendId);
	$friendWeekdayTimeDistri = $friendTimeDistribution->getWeekdayTimeDistribution();
	$friendWeekendTimeDistri = $friendTimeDistribution->getWeekendTimeDistribution();
	for($i=0; $i<24; $i++)
	{?>
		weekdayTimeDistri.push(Number(<?php echo $friendWeekdayTimeDistri->getHrCounts((string)$i);?>));
		weekendTimeDistri.push(Number(<?php echo $friendWeekendTimeDistri->getHrCounts((string)$i);?>));
	<?php }?>
	weekdayTimeDistriOf['<?php echo $friendId?>'] = weekdayTimeDistri;
	weekendTimeDistriOf['<?php echo $friendId?>'] = weekendTimeDistri;
	<?php
 }
?>
</script>
<script>
	$(document).ready(function(){
		$("#chooser").change(function(){
			var selected = $(this).find('option:selected').attr('value');
			showAFriendStatistics(weekdayTimeDistriOf[selected], weekendTimeDistriOf[selected]);
		});
		function showAFriendStatistics(weekday, weekend)
		{
			//if (weekday == null)
				
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'container4',
					type: 'line',
					marginRight: 130,
					marginBottom: 25
				},
				title: {
					text: 'Friend Online Time Distributions',
					x: -20 //center
				},
				xAxis: {
					title: {
						text: 'Hour of a day'
					},
					categories: ['0', '1', '2', '3', '4', '5', '6',
						'7', '8', '9', '10', '11', '12',
						'13', '14', '15', '16', '17', '18',
						'19', '20', '21', '22', '23']
				},
				yAxis: {
					title: {
						text: 'Number of Friends Activities'
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					verticalAlign: 'top',
					x: -10,
					y: 100,
					borderWidth: 0
				},
				series: [{
					name: 'Weekdays',
					data: weekday
				}, {
					name: 'Weekends',
					data: weekend
					//data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5, -0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
				}]
			});
			//Done chart.
		}
		$("#wordcloud2").awesomeCloud({
			"size" : {
				"grid" : 9,
				"factor" : 10,
				"normalize" : true
			},
			"options" : {
				"color" : "random-dark",
				"rotationRatio" : 0.35
			},
			"font" : "'Times New Roman', Times, serif",
			"shape" : "circle"
		});
		chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container1',
                type: 'line',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Friend Online Time Distributions',
                x: -20 //center
            },
            xAxis: {
            	title: {
                    text: 'Hour of a day'
                },
                categories: ['0', '1', '2', '3', '4', '5', '6',
                    '7', '8', '9', '10', '11', '12',
                    '13', '14', '15', '16', '17', '18',
                    '19', '20', '21', '22', '23']
            },
            yAxis: {
                title: {
                    text: 'Number of Friends Activities'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [{
                name: 'Weekdays',
                //data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
                data: [<?php
                if ($weekDayTimeDistribution)
                {
	                for($i=0; $i<23; $i++)
	                {
	                	$p = (string) $i;
	                	echo $weekDayTimeDistribution->getHrCounts($p);
	                	echo ', ';
	                }
	                echo $weekDayTimeDistribution->getHrCounts('23');
                }
                ?>]
            }, {
                name: 'Weekends',
                data: [<?php
                	if ($weekEndTimeDistribution)
                    {
       	            	for($i=0; $i<23; $i++)
       	            	{
       	                	$p = (string) $i;
       	                	echo $weekEndTimeDistribution->getHrCounts($p);
       	                	echo ', ';
       	                }
       	                echo $weekEndTimeDistribution->getHrCounts('23');
                    }
                 ?>]
                //data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5, -0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }]
        });
        //Done chart.
		chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container2',
                type: 'line',
                width: 500,
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Distribution in Days',
                x: -20 //center
            },
            xAxis: {
            	title: {
                    text: 'Days in a week'
                },
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
            },
            yAxis: {
                title: {
                    text: 'Number of Friends Activities'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [{
                data: [<?php
					if ($weekDistribution)
					{
						$temp = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
						foreach($temp as $day)
						{
							echo $weekDistribution->getDayCounts($day);
							echo ', ';
						}
						echo $weekDistribution->getDayCounts('Sun');
					}
					?>]
            }]
        });
        //Done chart.
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container3',
                type: 'line',
                width: 500,
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Distribution in Quarters',
                x: -20 //center
            },
            xAxis: {
            	title: {
                    text: 'Hours in a day'
                },
                categories: ['0-6', '6-12', '12-18', '18-0']
            },
            yAxis: {
                title: {
                    text: 'Number of Friends Activities'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [{
                data: [<?php
						if ($weekEndTimeDistribution)
						{
							for($i=1; $i<4; $i++)
							{
								echo $weekEndTimeDistribution->getQuarterCounts($i);
								echo ', ';
							}
						echo $weekEndTimeDistribution->getQuarterCounts(4);
						}
						?>]
            }]
        });
        //Done chart.
	});
</script>
</head>
<body>
<div id="wrapper">
	<div id="menu-wrapper">
		<div id="menu" class="container">
			<ul>
				<li><a href="index.php">Homepage</a></li>
				<li><a href="mapfriends.php">Map Friends</a></li>
				<li class="current_page_item"><a href="Statistics.php">Statistics</a></li>
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
				<h2 class="title">Word Cloud</h2>
					<div id="wordcloud2" class="wordcloud">
					<?php
					for ($i=0; $i<$wordCloud->getNumWords(); $i++)
					{
						//echo "Working";
						$wordTag = $wordCloud->getRandomWordTag();
						echo "<span data-weight=".$wordTag->getFrequency().">".$wordTag->getWord()."</span>";
					}?>
					</div>
					
			</div>
			<div class="post">
				<h2 class="title">Most Liked Status</h2>
					<?php echo 'Message: '.$mostLikedStatusArray['message'].'<br/>'.'Likes Count: '.$mostLikedStatusArray['likes']?>
			</div>
			<div class="post">
				<h2 class="title">Best Time to Broadcast</h2>
					<div id="container1" style="height: 400px; margin: 0 auto"></div>
					<div id="container2" style="height: 400px; margin: 0 auto"></div>
					<div id="container3" style="height: 400px; margin: 0 auto"></div>
			</div>
			<div class="post">
				<h2 class="title">LookUp Friend's online time</h2>
				Please choose a user id to see his online time.<br/>
				<select id= "chooser">
					<?php foreach ($friendIdArray as $id)
					{
						echo '<option value="'.$id.'">'.$nameOfId[$id].'</option>';
					}?>
				</select>
				<div id="container4" style="height: 400px; margin: 0 auto"></div>
			</div>
		<!-- end #content -->
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