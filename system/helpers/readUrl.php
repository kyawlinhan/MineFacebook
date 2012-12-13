<?php

/**
	Read the content of the given url and return the object representation of the data.
	It can has multiple different attributes based on what you are reading.
	@param url which has the json format string data
 */


function readUrl($url){

	//read the content from the url.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$html_content = curl_exec($ch);
	curl_close($ch);
	
	//As the content supplied is a string that is in json format. Decode the json to object.
	$reval = json_decode($html_content, true);
	
	//$reval = json_decode(utf8_encode($html_content),true);	
	return $reval;
}