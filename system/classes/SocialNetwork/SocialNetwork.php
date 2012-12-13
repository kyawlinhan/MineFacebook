<?php

/**
 * This class store a connection on a social network.
 * A user and all his connections.
 * @author kyaw2
 *
 */
class SocialNetwork
{
	private $adjacencyList;
	private $nodeArray;
	private $groupArray;
	private $networkOwner;
	private $nameOfMutualId;
	
	/**
	 * Consturctuer
	 */
	public function __construct()
	{
		$this->adjacencyList = array();
		$this->nodeArray = array();
		$this->groupArray = array();
		$this->nameOfMutualId = array();
	}
	
	/**
	 * Add mutual friend link to the social network.
	 * @param $userId
	 * @param $friendId
	 * @param $mutualArray - ids should be accessed by $mutualArray[0]['id']
	 */
	public function addMutualLinks ($userId, $aFriendId, $mutualArray)
	{
		$this->networkOwner = $userId;
		$this->addNode($userId);
		$this->addNode($aFriendId);
		$this->addLink($userId, $aFriendId);
		foreach ($mutualArray as $friendId)
		{
			$this->nameOfMutualId[$friendId['id']] = $friendId['name'];
			$this->addLink($userId, $friendId['id']);
			$this->addLink($aFriendId, $friendId['id']);
			$this->addNode($friendId['id']);
		}
	}
	
	/**
	 * return namOfMutualId array that holds names of ids which
	 * are mutual friends between user and a friend.
	 * @return Ambigous <multitype:, unknown>
	 */
	public function getNameOfMutualId()
	{
		return $this->nameOfMutualId;
	}
	
	/**
	 * This method starts creating groups based on mutual friends.
	 * Simply, it cuts the user off from the social network. Then
	 * create groups based on connected components of the whole network.
	 */
	public function createGroup()
	{
		$visited = array();
		$i=1;
		$this->groupArray[$this->networkOwner] = $i;
		foreach ($this->adjacencyList[$this->networkOwner] as $userNeighbor)
		{
			if (!$visited[$userNeighbor])
			{
				$i++;
				$visited[$userNeighbor] = true;
				$this->groupArray[$userNeighbor] = $i;
				$this->exploreCommunityStartWith($userNeighbor, $visited, $i);
			}
		}
	}
	
	/**
	 * This is a recursive helper function that is called by
	 * createGroups Method.
	 * @param unknown $friend
	 * @param unknown $visited
	 * @param unknown $groupId
	 */
	private function exploreCommunityStartWith($friend, &$visited, $groupId)
	{
		foreach ($this->adjacencyList[$friend] as $friendNeighbor)
		{
			if (!$visited[$friendNeighbor] && $friendNeighbor != $this->networkOwner)
			{
				$visited[$friendNeighbor] = true;
				$this->groupArray[$friendNeighbor] = $groupId;
				$this->exploreCommunityStartWith($friendNeighbor, $visited, $groupId);
			}
		}
	}
	
	/**
	 * return the group Array
	 */
	public function getGroupArray()
	{
		return $this->groupArray;
	}
	
	/**
	 * return the node array
	 */
	public function getNodeArray()
	{
		return $this->nodeArray;
	}
	
	/**
	 * return the adjacencyList
	 */
	public function getAdjacencyList()
	{
		return $this->adjacencyList;
	}
	
	/**
	 * add a node to the graph
	 */
	private function addNode ($node)
	{
		$this->nodeArray[$node] = $node;
	}
	
	/**
	 * Add 2 ways links between two users.
	 * @param unknown $first - user id.
	 * @param unknown $second - user id.
	 */
	private function addLink ($first, $second)
	{
		if($this->adjacencyList[$first] == NULL)
			$this->adjacencyList[$first] = array();
		$this->adjacencyList[$first][$second] = $second;
		if($this->adjacencyList[$second] == NULL)
			$this->adjacencyList[$second] = array();
		$this->adjacencyList[$second][$first] = $first;
	}
}

?>