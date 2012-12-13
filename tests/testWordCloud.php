<?php
require_once '../system/classes/WordCloud/WordCloud.php';

$testMessagesArray = array();

//Add "Biggest" 30 times
for ($i=0; $i<30; $i++)
{
	$messageArray = array();
	$messageArray["message"] = "Biggest";
	array_push($testMessagesArray, $messageArray);
}

//Add "Bigger" 25 times
for ($i=0; $i<25; $i++)
{
	$messageArray = array();
	$messageArray["message"] = "Bigger";
	array_push($testMessagesArray, $messageArray);
}

//Add "Big" 20 times
for ($i=0; $i<20; $i++)
{
	$messageArray = array();
	$messageArray["message"] = "Big";
	array_push($testMessagesArray, $messageArray);
}

//Add "Small" 15 times
for ($i=0; $i<15; $i++)
{
	$messageArray = array();
	$messageArray["message"] = "Small";
	array_push($testMessagesArray, $messageArray);
}

//Add "Smaller" 10 times
for ($i=0; $i<10; $i++)
{
	$messageArray = array();
	$messageArray["message"] = "Smaller";
	array_push($testMessagesArray, $messageArray);
}

//Add "Smallest" 5 times
for ($i=0; $i<5; $i++)
{
	$messageArray = array();
	$messageArray["message"] = "Smallest hello";
	array_push($testMessagesArray, $messageArray);
}

$wordCloud = new WordCloud($testMessagesArray);

?>

<head>
	<script src="../javascripts/jquery.js"></script>
	<script src="../javascripts/jquery.awesomeCloud-0.2.min.js"></script>
	<script>
	$(document).ready(function(){
		$("#wordcloud2").awesomeCloud({
			"size" : {
				"grid" : 9,
				"factor" : 5
			},
			"options" : {
				"color" : "random-dark",
				"rotationRatio" : 0.35
			},
			"font" : "'Times New Roman', Times, serif",
			"shape" : "circle"
		});
	});
	</script>
	<style type="text/css">
		.wordcloud {
			border: 1px solid #036;
			height: 7in;
			margin: 0.5in auto;
			padding: 0;
			page-break-after: always;
			page-break-inside: avoid;
			width: 7in;
		}
	</style>
</head>
<body>
	<header>
		<h1>Simple test for word cloud</h1>
	</header>
	<div id="wordcloud2" class="wordcloud">
	<?php
	for ($i=0; $i<$wordCloud->getNumWords(); $i++)
	{
		//echo "Working";
		$wordTag = $wordCloud->getRandomWordTag();
		echo "<span data-weight=".$wordTag->getFrequency().">".$wordTag->getWord()."</span>";
	}?>
	</div>
</body>