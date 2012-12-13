<?php

/**
 * Source: http://www.andrew-kirkpatrick.com/2011/10/google-geocoding-api-with-php/
 * This function return latitude and longitude of a string address.
 * @param the address
 * @return associative array. keys are latitude, longitude, name
 */

//Tried printing out and the result is shown below.
//print_r(lookup("Singapore, Singapore"));
//http://maps.googleapis.com/maps/api/geocode/json?address=Singapore,+Singapore&sensor=false

function lookup($string){
 
   $string = str_replace (" ", "+", urlencode($string));
   $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$string."&sensor=false";
 
   //opened up the webpage and read the content from it.
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $details_url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $response = json_decode(curl_exec($ch), true);
   curl_close($ch);
 
   // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
   if ($response['status'] != 'OK') {
    return null;
   }
 
   //Preparing the return array.
    $array = array(
        'latitude' => $response['results'][0]['geometry']['location']['lat'],
        'longitude' => $response['results'][0]['geometry']['location']['lng'],
    	'name' => $response['results'][0]['formatted_address']
    );
 
    return $array;

}
 
?>