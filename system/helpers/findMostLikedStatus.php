<?php

/**
 * return the most liked message and like counts.
 * @param status $array
 * @return associative array, keys = message, likes
 */
function getMostLikedStatus ($array)
{
	$remessage;
	$relikeCount = 0;
	foreach ($array as $status)
	{
		$message = $status['message'];
		$likesArray = $status['likes']['data'];
		if ($relikeCount < count($likesArray))
		{
			$relikeCount = count($likesArray);
			$remessage = $message;
		}
	}
	$reval = array();
	$reval['message'] = $remessage;
	$reval['likes'] = $relikeCount;
	return $reval;
}

/* Example of $array that is the input of constructor. Between =================.
 * Query return by : https://graph.facebook.com/me/statuses?limit=500&access_token=[token from index page of the website.]
 * {
   "data": [
   ===============================
      {
         "id": "4918087156963",
         "from": {
            "name": "KyawLin Han",
            "id": "1441779599"
         },
         "message": "Microsoft coming next week with surface, xbox raffles!!!!",
         "updated_time": "2012-11-06T07:10:35+0000",
         "likes": {
            "data": [
               {
                  "id": "100001504718157",
                  "name": "Yang Song"
               },
               {
                  "id": "1348326014",
                  "name": "Siddharth Bhaduri"
               },
               {
                  "id": "100000422257667",
                  "name": "Yoonwoong Kim"
               }
            ],
            "paging": {
               "next": "https://graph.facebook.com/4918087156963/likes?access_token=AAAFK27x46k8BAEBwRw2SPEAatMBhmy6kTv2iqh4SUy1AO1mQfHZCZBDxfmtpO4GMn82AYf0ZBPUkceOuEPbgT3C5AZCpFdKROfNk46BEPwZDZD&limit=25&offset=25&__after_id=100000422257667"
            }
         },
         "comments": {
            "data": [
               {
                  "id": "4918087156963_5608476",
                  "from": {
                     "name": "Yoonwoong Kim",
                     "id": "100000422257667"
                  },
                  "message": "nice",
                  "can_remove": true,
                  "created_time": "2012-11-06T07:51:09+0000",
                  "like_count": 0,
                  "user_likes": false
               }
            ],
            "paging": {
               "next": "https://graph.facebook.com/4918087156963/comments?access_token=AAAFK27x46k8BAEBwRw2SPEAatMBhmy6kTv2iqh4SUy1AO1mQfHZCZBDxfmtpO4GMn82AYf0ZBPUkceOuEPbgT3C5AZCpFdKROfNk46BEPwZDZD&limit=25&offset=25&__after_id=4918087156963_5608476"
            }
         }
      },
      ...
	===============================
      ],
	   "paging": {
	      "previous": "https://graph.facebook.com/1441779599/statuses?limit=500&access_token=AAAFK27x46k8BAEBwRw2SPEAatMBhmy6kTv2iqh4SUy1AO1mQfHZCZBDxfmtpO4GMn82AYf0ZBPUkceOuEPbgT3C5AZCpFdKROfNk46BEPwZDZD&since=1352185835&__paging_token=4918087156963&__previous=1",
	      "next": "https://graph.facebook.com/1441779599/statuses?limit=500&access_token=AAAFK27x46k8BAEBwRw2SPEAatMBhmy6kTv2iqh4SUy1AO1mQfHZCZBDxfmtpO4GMn82AYf0ZBPUkceOuEPbgT3C5AZCpFdKROfNk46BEPwZDZD&until=1319928121&__paging_token=2595739019711"
	   }
	}
	*/
?>