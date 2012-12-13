<?php

/**
 * These functions are to prepare input for the graphing library.
 * Library: http://d3js.org/
 */

/**
 * return indicesArray. The purpose of this function is
 * to change userId to array index from 0 to n.
 * @param $nodeArray
 * @return indicesArray - key: userId, value: index starts from 0
 */
function getIndices ($nodeArray)
{
	$reval = array();
	$i = 0;
	foreach($nodeArray as $node)
	{
		$reval[$node] = $i;
		$i++;
	}
	return $reval;
}

/**
 * This function change $nodeArray to a $nodeArray that has
 * the input format that is ready for d3 library.
 * @param unknown $nodeArray - an array of nodeId
 * @param unknown $indexMap - key: nodeId value: index
 * @param unknown $groupMap - key: nodeId value: groupNumber
 * @param unknown $nameOfId - key: nodeId value: userName
 * @return string
 */
function getD3FriendlyNodesArray ($nodeArray, $indexMap, $groupMap, $nameOfId )
{
	$reval = array();
	foreach($nodeArray as $node)
	{
		$reval[$indexMap[$node]] = array("name"=> $nameOfId[$node], "group" => $groupMap[$node]);
	}
	return json_encode($reval);
}

/**
 * return the linksArray that is compatible with D3 library.
 * @param unknown $linkArray - key: a nodeId, value: array of connecting nodeIds.
 * @param unknown $nodeArray - an array of nodeIds
 * @param unknown $indexMap - key: nodeId, value: index
 * @return string
 */
function getD3FriendlyLinksArray ($linkArray, $nodeArray, $indexMap )
{
	$reval = array();
	foreach($nodeArray as $currNode)
	{
		$currNodeTargets = $linkArray[$currNode];
		foreach($currNodeTargets as $target)
		{
			array_push($reval, array("source"=>$indexMap[$currNode], "target"=>$indexMap[$target], "value"=>1));
		}
	}
	return json_encode($reval);
}
?>