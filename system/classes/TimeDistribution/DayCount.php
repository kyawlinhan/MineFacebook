<?php

/**
 * This is a class that hold counts for a day. (Thu(Day): 4(count), Fri: 6, Mon: 7, etc.)
 * It has an associative array as its data structure.
 * @author kyaw2
 *
 */

require_once 'TimeIntervalCount.php';
class DayCount
{

	private $dayTimeIntervalCount = array();
	private $dayCount;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$temp = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		foreach ($temp as $day)
		{
			$this->dayTimeIntervalCount[$day] = new TimeIntervalCount();
			$this->dayCount[$day] = 0;
		}
	}
	
	/**
	 * add count one to the given day.
	 * @param string $day (strictly 3 letters day only.) 
	 */
	public function addOne($day, $hr)
	{
		$CurrDayTimeIntervalCount = $this->dayTimeIntervalCount[$day];
		$CurrDayTimeIntervalCount->addOne($hr);
		$this->dayCount[$day]++;
	}
	
	/**
	 * return week end counts.
	 * @return number
	 */
	public function getWeekEndCount()
	{
		$temp = array('Sat', 'Sun');
		$reval = 0;
		foreach ($temp as $day)
		{
			$reval = $reval + $this->dayCount[$day];
		}
		return $reval;
	}
	
	/**
	 * return week day counts.
	 * @return number
	 */
	public function getWeekDayCount()
	{
		$temp = array('Mon', 'Tue', 'Wed', 'Thur', 'Fri');
		$reval = 0;
		foreach ($temp as $day)
		{
			$reval = $reval + $this->dayCount[$day];
		}
		return $reval;
	}
	
	/**
	 * return TimeIntervalCount Object of the given day.
	 * @param string $day (strictly 3 letters day only.) 
	 * @return multitype:
	 */
	public function getDayTimeDistribution($day)
	{
		return $this->dayTimeIntervalCount[$day];
	}
	
	public function getDayCounts($day)
	{
		return $this->dayCount[$day];
	}
	
	public function setDayCounts($day, $count)
	{
		$this->dayCount[$day] = $count;
	}

}
?>