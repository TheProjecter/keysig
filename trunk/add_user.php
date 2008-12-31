<?php
/**
* @package Keysig
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

//Setup the POST data for processing
if (isset($_POST['keysigUser']) && !empty($_POST['keysigUser'])) {
    $username = $_POST['keysigUser'];
} else {
    $username = '';
}

if (isset($_POST['keysigKey']) && !empty($_POST['keysigKey'])) {
    $keysigKey = $_POST['keysigKey'];
} else {
    $keysigKey = '';
}

if (isset($_POST['keysigData']) && !empty($_POST['keysigData'])) {
    $keysigData = json_decode(stripslashes($_POST['keysigData']));
} else {
    $keysigData = '';
}

$returnMessage = '';
if ($username==='' || $keysigKey==='' || $keysigData==='') {
	$returnMessage = json_encode(array('status'=>'failure'));
    echo $returnMessage;
	exit;
}

require('includes/database.php');

$whoami          = $keysigData->whoami;
$sigstart        = $keysigData->sigstart;
$sigend          = $keysigData->whoami;
$countOfKeys     = count($keysigData->keys);
$chArray         = array();
$timeDownArray   = array();
$durationArray   = array();

for($i=0;$i<$countOfKeys;$i++) {
	$chArray[] = strtoupper($keysigData->keys[$i]->ch);
	$timeDownArray[] = $keysigData->keys[$i]->timeDown;
	$durationArray[] = $keysigData->keys[$i]->duration;
}

//Lets store this new user/pattern
require('includes/database.php');
$clnChArray       = mysql_real_escape_string(serialize($chArray), $db);
$clnTimeDownArray = mysql_real_escape_string(serialize($timeDownArray), $db);
$clnDurationArray = mysql_real_escape_string(serialize($durationArray), $db);
$insertSQL = "INSERT INTO users (id, username, pattern_value, ch_array, time_down_array, duration_array) 
                VALUES (null, '{$username}', '{$keysigKey}', '{$clnChArray}', '{$clnTimeDownArray}', '{$clnDurationArray}')";

$result = mysql_query($insertSQL);
$error_check = mysql_error();
if ($error_check!=='') {
	$returnMessage = json_encode(array('status'=>'failure'));
    echo $returnMessage;
} else {
	$returnMessage = json_encode(array('status'=>'ok'));
    echo $returnMessage;
}

?>
