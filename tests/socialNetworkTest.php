<?php
require_once '../system/classes/SocialNetwork/SocialNetwork.php';

$myNetwork = new SocialNetwork();
$userId = '12345';
$friends = array('123456', '21345', '31245', '41235', '52134');

$mutualFriends0 = array(array("id" => "21345", "name" => "A"));
$mutualFriends1 = array(array("id" => "123456", "name" => "B"));
$mutualFriends2 = array(array("id" => "41235", "name" => "C"), array("id" => "52134", "name" => "D"));
$mutualFriends3 = array(array("id" => "31245", "name" => "E"), array("id" => "52134", "name" => "D"));
$mutualFriends4 = array(array("id" => "31245", "name" => "E"), array("id" => "41235", "name" => "C"));

$myNetwork->addMutualLinks ($useId, $friends[0], $mutualFriends0);
$myNetwork->addMutualLinks ($useId, $friends[1], $mutualFriends1);
$myNetwork->addMutualLinks ($useId, $friends[2], $mutualFriends2);
$myNetwork->addMutualLinks ($useId, $friends[3], $mutualFriends3);
$myNetwork->addMutualLinks ($useId, $friends[4], $mutualFriends4);

$myNetwork->createGroup();
echo "user and A, B are a community. user and C, D, E are a community.<br/>";

$groupOf = $myNetwork->getGroupArray();
$nameOf = $myNetwork->getNameOfMutualId();
$nodes = $myNetwork->getNodeArray();
$neighboursOf = $myNetwork->getAdjacencyList();

$group1 = 2;
$group2 = 3;
foreach ($friends as $nodeId)
{
	echo $nameOf[$nodeId].' : '.$groupOf[$nodeId].'<br/>';
	if ($groupOf[$nodeId] == 2)
	{
		$group1--;
	}
	if ($groupOf[$nodeId] == 3)
	{
		$group2--;
	}
}
$total = $group1 + $group2;
if ($total == 0)
	echo "Success!";
else
	echo "Fail! Pass: ". 5-$total . "/5";
?>