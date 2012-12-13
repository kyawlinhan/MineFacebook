<?php

/**
 * This is a class that hold counts for a time interval. (1(hr): 4(count), 2: 6, 22: 7, etc.)
 * It has an associative array as its data structure.
 * @author kyaw2
 *
 */
class TimeIntervalCount
{
	//associative array stores count of every hour.
	private $intervalCount = array();
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		for ($i=0; $i<24; $i++)
			$this->intervalCount[(string)$i] = 0;
	}
	
	/**
	 * add count one to the given hr.
	 * @param string $hr (strictly number only. no alphabet)
	 */
	public function addOne($hr)
	{
		$old = $this->intervalCount[$hr];
		$this->intervalCount[$hr] = $old + 1;
	}
	
	/**
	 * set count of the given hr.
	 * @param string $hr (strictly number only. no alphabet)
	 */
	public function setHrCount($hr, $count)
	{
		$this->intervalCount[$hr] = $count;
	}
	
	/**
	 * return an associative array of quarter counts. keys = firstQtr, secondQtr, thirdQtr, forthQtr
	 * @return multitype:number
	 */
	public function getQuarterCounts($qtrNum)
	{
		$reval = 0;
		for ($i=(($qtrNum-1)*6); $i<($qtrNum*6); $i++)
		{
			$reval = $reval + $this->intervalCount[(string)$i];
		}
		return $reval;
	}
	
	/**
	 * return the counts of the given hour.
	 * @param string $hr (strictly number only. no alphabet)
	 * @return multitype:
	 */
	public function getHrCounts($hr)
	{
		return $this->intervalCount[$hr];
	}

}
?>