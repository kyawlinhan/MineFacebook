<?php

/**
 * This is a WordCloud Class. It stores an array of words with
 * their frequency of appearing in user statuses.
 */

require_once 'WordTag.php';

class WordCloud
{
	private $wordArray = array();
	private $numWords;

	/**
	 * Constructor. Input is the array of data returned by querying
	 * user's statuses using graph api. If the api changes one
	 * day, neccessary refactoring should be done here.
	 */
	public function __construct($array)
	{
		$numWords = 0;
		$this->populateCloud($array);
	}
	
	/**
	 * Populate the Cloud with words from given array of statuses.
	 * @param unknown $array
	 */
	private function populateCloud($array)
	{
		foreach ($array as $status)
		{
			$message = $status['message'];
			$words = explode(" ", $message);
			foreach ($words as $word)
			{
				if ($this->validate($word) && !array_key_exists($word, $this->wordArray))
				{
					$this->numWords++;
					$newWordTag = new WordTag($word);
					$this->wordArray[$word] = $newWordTag;
				}
				else if ($this->validate($word) && array_key_exists($word, $this->wordArray))
				{
					$this->wordArray[$word]->addFrequency();
				}
			}
		}
	}
	
	/**
	 * Check whether the word has string length in this range of 3 and 15 both inclusive. (valid)
	 * @param unknown $word
	 * @return boolean
	 */
	private function validate($word)
	{
		return strlen($word)>2 && strlen($word)<16;
	}
	
	/**
	 * return random wordTag and delete it from word cloud.
	 * @return unknown
	 */
	public function getRandomWordTag()
	{
		//$key =  array_rand($this->wordArray, 1);
		//$reval = $this->wordArray[$key];
		//unset($this->wordArray[$key]);
		//print count($this->wordArray);
		//return ($this->wordArray['Microsoft']);
		return array_pop($this->wordArray);
		//return $reval;
	}
	
	/**
	 * return the number of words in the cloud.
	 */
	public function getNumWords()
	{
		return $this->numWords;
	}
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