<?php
/**
 * This class will hold the time stamps made by a user's friends' status and comments.
 * Future Add on: time stamps made by user's like action, uploading photo or vedios,
 * sharing link, posting on other's wall, commenting other's post.
 */

require_once 'DayCount.php';
require_once 'TimeIntervalCount.php';

class UserTimeStamps
{
	private $userId;
	private $dayStatistics;
	
	/**
	 * Constructor
	 */
	public function __construct($userId)
	{
		$this->userId = $userId;
		$this->dayStatistics = new DayCount();
	}
	
	/**
	 * Add day count to this user's statistics.
	 * @param unknown $day
	 */
	public function addDayCount($day, $hr)
	{
		$this->dayStatistics->addOne($day, $hr);
	}
	
	/**
	 * return userID;
	 */
	public function getUserId()
	{
		return $this->userId;
	}
	
	/**
	 * return TimeIntervalCount Object of the weekends.
	 * @return multitype:
	 */
	public function getWeekendTimeDistribution()
	{
		$Sat = $this->dayStatistics->getDayTimeDistribution('Sat');
		$Sun = $this->dayStatistics->getDayTimeDistribution('Sun');
		$combined = new TimeIntervalCount();
		return $this->combineTimeDistribution($Sat, $Sun, $combined);
	}
	
	/**
	 * return TimeIntervalCount Object of the weekdays.
	 * @return multitype:
	 */
	public function getWeekdayTimeDistribution()
	{
		$combined = new TimeIntervalCount();
		$temp = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri');
		foreach($temp as $day)
		{
			$tempDistribution = $this->dayStatistics->getDayTimeDistribution($day);
			$combined = $this->combineTimeDistribution($tempDistribution, $combined, $combined);
		}
		
		return $combined;
	}
	
	/**
	 * return DayCount object of this user.
	 * @return DayCount
	 */
	public function getDayStatistics()
	{
		return $this->dayStatistics;
	}
	
	/**
	 * combine two time distribution (TimeIntervalCount Object) into one. 
	 * @param unknown $a
	 * @param unknown $b
	 * @param $reval - return variable is a param so that the original data is preserved. 
	 * It also save space if there is multiple calls to this function as JAVA pass object
	 * by reference. (combine weekdays case)
	 * @return TimeIntervalCount
	 */
	private function combineTimeDistribution($a, $b, $reval)
	{
		for($i=0; $i<24; $i++)
		{
			$p = (string) $i;
			$total = $a->getHrCounts($p) + $b->getHrCounts($p);
			$reval->setHrCount($p, $total);
		}
		return $reval;
	}
	
}