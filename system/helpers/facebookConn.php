<?php
/*
 * This is a file that established the facebook connection.
 * It let user log into facebook. Then get the permission
 * to retrieve user's facebook data.
 */

//require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'WordCloud.php';
require_once dirname(dirname(__FILE__)) . '/helpers/facebookConn.php';

//extend php execution time as requesting from Facebook might took a long time depends on number of friends
ini_set('max_execution_time', 3000);

//app id and app secret that is needed to access facebook data.
$appId = '363782597044815';
$appSecret = 'dfa721efc93965202091f3015d43f1f3';


//graph api url
$graphAPI = 'https://graph.facebook.com/';

// Create facebook Application instance
$facebook = new Facebook(array(
		'appId'  => $appId,
		'secret' => $appSecret
));
?>