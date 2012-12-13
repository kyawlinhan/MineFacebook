<?php
echo 'you need a valid token. In index.php, in the middle content, there is a access_token for your session.<br/>';

$url = "https://graph.facebook.com/4301003970269?access_token=AAAFK27x46k8BAEBwRw2SPEAatMBhmy6kTv2iqh4SUy1AO1mQfHZCZBDxfmtpO4GMn82AYf0ZBPUkceOuEPbgT3C5AZCpFdKROfNk46BEPwZDZD";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$html_content = curl_exec($ch);
curl_close($ch);
echo $html_content;

?>