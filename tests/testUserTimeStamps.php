<?php
require_once '../system/classes/TimeDistribution/UserTimeStamps.php';
$p = new UserTimeStamps('123123');

//Monday has 10 activities. 5 activities at 5pm. 5 activities at 7pm.
for ($i=0; $i<5; $i++)
{
	$p->addDayCount('Mon', '17');
}
for ($i=0; $i<5; $i++)
{
	$p->addDayCount('Mon', '19');
}
//Sunday has 10 activities. 5 activities at 5pm. 5 activities at 7pm.
for ($i=0; $i<5; $i++)
{
	$p->addDayCount('Sun', '17');
}
for ($i=0; $i<5; $i++)
{
	$p->addDayCount('Sun', '19');
}
//Tuesday has 20 activities. 10 activities at 6pm. 5 activities at 7pm. 5 activities at 8pm.
for ($i=0; $i<10; $i++)
{
	$p->addDayCount('Tue', '18');
}
for ($i=0; $i<5; $i++)
{
	$p->addDayCount('Tue', '19');
}
for ($i=0; $i<5; $i++)
{
	$p->addDayCount('Tue', '20');
}

echo "Check against:<br/>";
echo "Monday has 10 activities. 5 activities at Hr 17. 5 activities at Hr 19.<br/>";
echo "Sunday has 10 activities. 5 activities at Hr 17. 5 activities at Hr 19.<br/>";
echo "Tuesday has 20 activities. 10 activities at Hr 18. 5 activities at Hr 19. 5 activities at Hr 20.<br/>";
echo '==============================================<br/>';
$dayCount = $p->getDayStatistics();
$weekendDistribution = $p->getWeekendTimeDistribution();
$weekdayDistribution = $p->getWeekdayTimeDistribution();
$uId = $p->getUserId();

echo '(10 expected) Monday Count: '.$dayCount->getDayCounts("Mon").'<br/>';
echo '(20 expected) Tuesday Count: '.$dayCount->getDayCounts("Tue").'<br/>';
echo 'Wednesday Count: '.$dayCount->getDayCounts("Wed").'<br/>';
echo 'Thursday Count: '.$dayCount->getDayCounts("Thu").'<br/>';
echo 'Friday Count: '.$dayCount->getDayCounts("Fri").'<br/>';
echo 'Satursday Count: '.$dayCount->getDayCounts("Sat").'<br/>';
echo '(10 expected) Sunday Count: '.$dayCount->getDayCounts("Sun").'<br/>';
echo '==============================================<br/>';
echo '(30 expected) Weekday Count: '.$dayCount->getWeekDayCount().'<br/>';
echo '(10 expected) Weekend Count: '.$dayCount->getWeekEndCount().'<br/>';
echo '==============================================<br/>';
echo 'Weekdays expected: Hr 17: 5, Hr 18: 10, Hr 19: 10, Hr 20: 5<br/>';
for ($i=0; $i<24; $i++)
{
	echo 'Weekdays Hr '.$i.' Count: '.$weekdayDistribution->getHrCounts((string)$i).'<br/>';
}
echo '<br/>0 - 6 Count: '.$weekdayDistribution->getQuarterCounts(1).'<br/>';
echo '6 - 12 Count: '.$weekdayDistribution->getQuarterCounts(2).'<br/>';
echo '(5 expected) 12 - 18 Count: '.$weekdayDistribution->getQuarterCounts(3).'<br/>';
echo '(25 expected) 18 - 0 Count: '.$weekdayDistribution->getQuarterCounts(4).'<br/>';
echo '==============================================<br/>';
echo 'Weekends expected: Hr 17: 5, Hr 19: 5<br/>';
for ($i=0; $i<24; $i++)
{
	echo 'Weekends Hr '.$i.' Count: '.$weekendDistribution->getHrCounts((string)$i).'<br/>';
}
echo '<br/>0 - 6 Count: '.$weekendDistribution->getQuarterCounts(1).'<br/>';
echo '6 - 12 Count: '.$weekendDistribution->getQuarterCounts(2).'<br/>';
echo '(5 expected)12 - 18 Count: '.$weekendDistribution->getQuarterCounts(3).'<br/>';
echo '(5 expected)18 - 0 Count: '.$weekendDistribution->getQuarterCounts(4).'<br/>';
?>