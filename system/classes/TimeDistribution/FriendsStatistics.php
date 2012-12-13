<?php

/**
 * This class attached a user's status created times and comment created time to
 * that user's UserTimeStamps data structure. 
 */
require_once 'UserTimeStamps.php';
require_once 'TimeIntervalCount.php';
require_once 'DayCount.php';

class FriendsStatistics
{
	private $statisticsOf;
	
	/**
	 * Return an associative array that store the online time statistics of all friends.
	 * @param unknown $UidArray
	 * @return associative array. key = fbUserId (string).
	 */
	public function __construct()
	{
		$this->statisticsOf = array();
	}
	
	/**
	 * Add a friend's statistics to the data structure
	 */
	public function addAFriendStatistics($StatusesArray, $Uid)
	{
		//create userTimeStamps object for this user.
		$userTimeStamps = new UserTimeStamps($Uid);
		
		//analyze status one by one.
		foreach ($StatusesArray as $status)
		{
			$userTimeStamps = $this->getUserTimestamps($status, $userTimeStamps);
			$this->statisticsOf[$Uid] = $userTimeStamps;
		}
	}
	
	/**
	 * return a friend's statistics from the data structure
	 */
	public function getAFriendStatistics($Uid)
	{
		return $this->statisticsOf[$Uid];
	}
	
	/**
	 * combine all friends' statistics and return a weekday statistics
	 */
	public function getGeneralWeekdayDistribution()
	{
		$reval = new TimeIntervalCount();
		foreach ($this->statisticsOf as $userStatistics)
		{
			$temp = $userStatistics->getWeekdayTimeDistribution();
			$reval = $this->combineTimeDistribution($reval, $temp, $reval);
		}
		return $reval;
	}
	
	/**
	 * combine all friends' statistics and return a weekend statistics
	 */
	public function getGeneralWeekendDistribution()
	{
		$reval = new TimeIntervalCount();
		foreach ($this->statisticsOf as $userStatistics)
		{
			$temp = $userStatistics->getWeekendTimeDistribution();
			$reval = $this->combineTimeDistribution($reval, $temp, $reval);
		}
		return $reval;
	}
	
	/**
	 * return a DayCount object that record week distribution
	 */
	public function getWeekDistribution()
	{
		$reval = new DayCount();
		foreach ($this->statisticsOf as $userStatistics)
		{
			$temp = $userStatistics->getDayStatistics();
			$reval = $this->combineDayDistribution($reval, $temp, $reval);
		}
		return $reval;
	}
	
	/**
	 * Combine two time distribution into one. Return the result.
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
	
	/**
	 * combine two day distribution into one. Return the result.
	 */
	private function combineDayDistribution($a, $b, $reval)
	{
		$temp = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		foreach ($temp as $p)
		{
			$total = $a->getDayCounts($p) + $b->getDayCounts($p);
			$reval->setDayCounts($p, $total);
		}
		return $reval;
	}
	/**
	 * return UserTimeStamps object that store processed statistical data of user's online time
	 * @param unknown $Uid
	 * @param unknown $status
	 * @return UserTimeStamps
	 */
	private function getUserTimestamps($status, $userTimeStamps)
	{
		$Uid = $userTimeStamps->getUserId();
		$postTime = $status['updated_time'];
		$statusHr = $this->getHour($postTime);
		$statusDay = $this->getDay($postTime);
		
		//add status post date and hour to the user's statistics.
		$userTimeStamps->addDayCount($statusDay, $statusHr);
		
		//get comments related to user's status
		$commentsArray = $status['comments']['data'];
		if ($commentsArray)
		{
			$timesArray = $this->getTimesFromComments($Uid, $commentsArray);
		
			//loop every timeStamp created by the user's comment on his own status post.
			foreach($timesArray as $timeStamp)
			{
				$statusHr = $this->getHour($timeStamp);
				$statusDay = $this->getDay($timeStamp);
				
				//add comment post date and time to the user's statics.
				$userTimeStamps->addDayCount($statusDay, $statusHr);
			}
			//echo "<html><body>";
			//var_dump($userTimeStamps);
			//echo "</body></html>";
		}
		return $userTimeStamps;
	}
	
	/**
	 * return an array of timestamps created by the user's comment on his status.
	 * @param unknown $Uid
	 * @param unknown $commentsArray
	 * @return multitype:
	 */
	private function getTimesFromComments($Uid, $commentsArray)
	{
		$reval = array();
		foreach ($commentsArray as $comment)
		{
			$authorID = $comment['from']['id'];
			if ($authorID == $Uid)
			{
				array_push($reval, $comment['created_time']);
			}
		}
		return $reval;
	}
	
	/**
	 * get hour from a time string.
	 * @param unknown $postTime
	 * @return string
	 */
	private function getHour ($postTime)
	{
		date_default_timezone_set($this->getLocalTimezone());
		$postTime = strtotime($postTime);
		return date('G', $postTime);
	}
	
	/**
	 * get day from a time string.
	 * @param unknown $postTime
	 * @return string
	 */
	private function getDay ($postTime)
	{
		date_default_timezone_set($this->getLocalTimezone());
		$postTime = strtotime($postTime);
		return date('D', $postTime);
	}
	
	/**
	 * source: http://php.net/manual/en/function.date-default-timezone-set.php
	 * @return boolean|Ambigous <>
	 */
	private function getLocalTimezone()
	{
	    $iTime = time();
	    $arr = localtime($iTime);
	    $arr[5] += 1900;
	    $arr[4]++;
	    $iTztime = gmmktime($arr[2], $arr[1], $arr[0], $arr[4], $arr[3], $arr[5], $arr[8]);
	    $offset = doubleval(($iTztime-$iTime)/(60*60));
	    $zonelist =
	    array
	    (
	        'Kwajalein' => -12.00,
	        'Pacific/Midway' => -11.00,
	        'Pacific/Honolulu' => -10.00,
	        'America/Anchorage' => -9.00,
	        'America/Los_Angeles' => -8.00,
	        'America/Denver' => -7.00,
	        'America/Tegucigalpa' => -6.00,
	        'America/New_York' => -5.00,
	        'America/Caracas' => -4.30,
	        'America/Halifax' => -4.00,
	        'America/St_Johns' => -3.30,
	        'America/Argentina/Buenos_Aires' => -3.00,
	        'America/Sao_Paulo' => -3.00,
	        'Atlantic/South_Georgia' => -2.00,
	        'Atlantic/Azores' => -1.00,
	        'Europe/Dublin' => 0,
	        'Europe/Belgrade' => 1.00,
	        'Europe/Minsk' => 2.00,
	        'Asia/Kuwait' => 3.00,
	        'Asia/Tehran' => 3.30,
	        'Asia/Muscat' => 4.00,
	        'Asia/Yekaterinburg' => 5.00,
	        'Asia/Kolkata' => 5.30,
	        'Asia/Katmandu' => 5.45,
	        'Asia/Dhaka' => 6.00,
	        'Asia/Rangoon' => 6.30,
	        'Asia/Krasnoyarsk' => 7.00,
	        'Asia/Brunei' => 8.00,
	        'Asia/Seoul' => 9.00,
	        'Australia/Darwin' => 9.30,
	        'Australia/Canberra' => 10.00,
	        'Asia/Magadan' => 11.00,
	        'Pacific/Fiji' => 12.00,
	        'Pacific/Tongatapu' => 13.00
	    );
	    $index = array_keys($zonelist, $offset);
	    if(sizeof($index)!=1)
	        return false;
	    return $index[0];
	} 
}

/* Example of $array that is the input of constructor. Between =================.
 * Query return by : https://graph.facebook.com/me/statuses?limit=500&access_token=[token from index page of the website.]
 * {
   "data": [
      {
         "id": "508639245821831",
         "from": {
            "name": "Ye Min Thant",
            "id": "100000272791864"
         },
         "message": "\u101c\u1088\u1015\u1039\u103b\u1015\u1014\u1039\u103b\u1015\u102e",
         "updated_time": "2012-11-11T18:31:33+0000",
         "likes": {
            "data": [
               {
                  "id": "100000274942279",
                  "name": "Aung Kyaw Min"
               },
               {
                  "id": "100000407996329",
                  "name": "Khine Phu Wai Moe"
               },
               {
                  "id": "100002966337726",
                  "name": "Pinkgirl Lay"
               },
               {
                  "id": "100000418700730",
                  "name": "Penny Mai"
               },
            ],
            "paging": {
               "next": "https://graph.facebook.com/508639245821831/likes?access_token=AAAFK27x46k8BAEBwRw2SPEAatMBhmy6kTv2iqh4SUy1AO1mQfHZCZBDxfmtpO4GMn82AYf0ZBPUkceOuEPbgT3C5AZCpFdKROfNk46BEPwZDZD&limit=25&offset=25&__after_id=100003272964244"
            }
            "comments": {
            "data": [
            {
            	"id": "501883253164097_5969106",
            	"from": {
            	"name": "Zin Thu Aung",
            	"id": "100002883428837"
            	},
            	"message": "so tar paw",
            	"can_remove": false,
            	"created_time": "2012-10-27T09:13:08+0000",
            	"like_count": 0,
            	"user_likes": false
            },
            {
            	"id": "501883253164097_5970113",
            	"from": {
            	"name": "Ye Min Thant",
            	"id": "100000272791864"
            	},
            	"message": "So. So pl.*-*",
            	"can_remove": false,
            	"created_time": "2012-10-27T15:03:11+0000",
            	"like_count": 0,
            	"user_likes": false
            }
            ],
            "paging": {
            "next": "https://graph.facebook.com/501883253164097/comments?access_token=AAAFK27x46k8BAEBwRw2SPEAatMBhmy6kTv2iqh4SUy1AO1mQfHZCZBDxfmtpO4GMn82AYf0ZBPUkceOuEPbgT3C5AZCpFdKROfNk46BEPwZDZD&limit=25&offset=25&__after_id=501883253164097_5970113"
            }
            }
         }
      },...
   ]
}
*/
?>