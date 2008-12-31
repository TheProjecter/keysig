<?php
/**
* @package Keysig
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

//Your database connection information
$dbHost     = '127.0.0.1';
$dbUsername = 'root';
$dbPassword = '';

//Do not modify the code below this line
$db = @mysql_connect($dbHost, $dbUsername, $dbPassword) or die('Unable to connect to database!');
mysql_select_db('keysig', $db) or die('Unable to select database!');

?>