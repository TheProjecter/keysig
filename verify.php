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

$userAgent = @$_SERVER['HTTP_USER_AGENT'];
$ip        = @$_SERVER['REMOTE_ADDR'];

/* Typical submissions are done over Ajax and will contain the keysig pattern calculated via
JavaScript.  When this type of submission is not available due to either a less that modern
browser and/or lack of JavaScript support we will simply test the basic value and store the attempt. 

Note: This db code would change greatly when actually implemented in a production system.  
It is just stubbed out/hardcoded/procedural for now */
if (empty($keysigData)) {
    //Fall back on basic submission method
    require('includes/database.php');
    
    //Lets get the original user record for comparison
    $originalSQL = "SELECT id, pattern_value
                    FROM users 
                    WHERE username = '{$username}'
                    LIMIT 1";
                    
    $result = mysql_query($originalSQL);   
    if (!$result || mysql_num_rows($result)!=1) {
        header("Location: index.php?error=invalid_user");
        exit;
    } else {
        $originalUserData = mysql_fetch_assoc($result);
        $userID = $originalUserData['id'];
        $patterValue = $originalUserData['pattern_value'];
        $clnIP = mysql_real_escape_string($ip, $db);
        $clnUserAgent = mysql_real_escape_string($userAgent, $db);
        
        //Store the attempt
        $insertSQL = "INSERT INTO match_attempts (user_id, ip_address, user_agent, ch_array, time_down_array, duration_array, attempt_date_time) 
                        VALUES ({$userID}, '{$clnIP}', '{$clnUserAgent}', 'unknown', 'unknown', 'unknown', now())";

        $result = mysql_query($insertSQL);
        if (!$result) {
            die('Invalid query: '.mysql_error());
        }
        
        if ($keysigKey==$patterValue) {
            header("Location: index.php?error=none");
            exit;
        } else {
            header("Location: index.php?error=invalid_pattern_value");
            exit;
        }
    }
} else {
    //Setup data for graphing
    $whoami          = $keysigData->whoami;
    $sigstart        = $keysigData->sigstart;
    $sigend          = $keysigData->whoami;
    $countOfKeys     = count($keysigData->keys);
    $chArray         = array();
    $timeDownArray   = array();
    $durationArray   = array();
    $patternGraphKey = md5($sigstart.mt_rand());

    for($i=0;$i<$countOfKeys;$i++) {
    	$chArray[] = strtoupper($keysigData->keys[$i]->ch);
    	$timeDownArray[] = $keysigData->keys[$i]->timeDown;
    	$durationArray[] = $keysigData->keys[$i]->duration;
    }

    //Lets store this attempt (this db code would change greatly after the demo...  just stubbed out/hardcoded/procedural for now)
    require('includes/database.php');
    $clnIP            = mysql_real_escape_string($ip, $db);
    $clnUserAgent     = mysql_real_escape_string($userAgent, $db);
    $clnChArray       = mysql_real_escape_string(serialize($chArray), $db);
    $clnTimeDownArray = mysql_real_escape_string(serialize($timeDownArray), $db);
    $clnDurationArray = mysql_real_escape_string(serialize($durationArray), $db);

    //Lets get the original user record for comparison
    $originalSQL = "SELECT id, ch_array, time_down_array, duration_array
                    FROM users 
                    WHERE username = '{$username}'
                    LIMIT 1";

    $result = mysql_query($originalSQL);
    if (!$result || mysql_num_rows($result)!=1) {
        sleep(1); //we want make sure they have time to read the progress indicator
        $returnMessage = json_encode(array('status'=>'error'));
        echo $returnMessage;
        exit;
    } else {
        $originalPatterData = mysql_fetch_assoc($result);
        $userID             = $originalPatterData['id'];
        $org_chArray        = unserialize($originalPatterData['ch_array']);
        $org_timeDownArray  = unserialize($originalPatterData['time_down_array']);
        $org_durationArray  = unserialize($originalPatterData['duration_array']);
    }

    $insertSQL = "INSERT INTO match_attempts (user_id, ip_address, user_agent, ch_array, time_down_array, duration_array, attempt_date_time) 
                    VALUES ({$userID}, '{$clnIP}', '{$clnUserAgent}', '{$clnChArray}', '{$clnTimeDownArray}', '{$clnDurationArray}', now())";

    $result = mysql_query($insertSQL);
    if (!$result) {
        die('Invalid query: ' . mysql_error());
    }

    //Do the pattern values match?  If not don't bother graphing.
    if ($chArray === $org_chArray) { 
        include "includes/libchart/classes/libchart.php";

        /* Graph #1 - Pattern Gap Chart */
        $chart = new VerticalBarChart(650, 325);
        $currentDataSet = new XYDataSet();
        $originalDataSet = new XYDataSet();

        //Calculate time diff between keys (current item)
        for($i=0; $i<count($timeDownArray); $i++) {
            if ($i==0) {
                $currentDataSet->addPoint(new Point($chArray[$i], 0)); //there is no time diff between 0 and -1 ;)
            } else {
                $currentDataSet->addPoint(new Point($chArray[$i], $timeDownArray[$i]-$timeDownArray[$i-1]));
            }
        }

        //Calculate time diff between keys (original item)
        for($i=0;$i<count($org_timeDownArray);$i++) {
            if ($i==0) {
                $originalDataSet->addPoint(new Point($org_chArray[$i], 0)); //there is no time diff between 0 and -1 ;)
            } else {
                $originalDataSet->addPoint(new Point($org_chArray[$i], $org_timeDownArray[$i]-$org_timeDownArray[$i-1]));
            }
        }

        $dataSet = new XYSeriesDataSet();
        $dataSet->addSerie("Current", $currentDataSet);
        $dataSet->addSerie("Original", $originalDataSet);
        $chart->setDataSet($dataSet);
        $chart->setTitle("Pattern Gap Chart");
        $chart->render("tmp/pattern_chart_graph_".$patternGraphKey.".png");
        /* End Graph #1 - Pattern Gap Chart */

        /* Graph #2 - Press Time Graph */
        $chart = new VerticalBarChart(650, 325);
        $currentDataSet = new XYDataSet();
        $originalDataSet = new XYDataSet();

        //Calculate time diff between keys (current item).  Start at 1 since there is no time diff between 0 and -1 ;)
        for($i=0;$i<count($timeDownArray);$i++) {
        	$currentDataSet->addPoint(new Point($chArray[$i], $durationArray[$i]));
        }

        //Calculate time diff between keys (original item).  Start at 1 since there is no time diff between 0 and -1 ;)
        for($i=0;$i<count($org_timeDownArray);$i++) {
        	$originalDataSet->addPoint(new Point($org_chArray[$i], $org_durationArray[$i]));
        }

        $dataSet = new XYSeriesDataSet();
        $dataSet->addSerie("Current", $currentDataSet);
        $dataSet->addSerie("Original", $originalDataSet);
        $chart->setDataSet($dataSet);
        $chart->setTitle("Press Time Chart");
        $chart->render("tmp/press_time_graph_".$patternGraphKey.".png");
        /* End Graph #2 - Press Time Chart */

        sleep(1); //we want make sure they have time to read the progress indicator
        $returnMessage = json_encode(array('status'=>'ok', 'pattern_graph_key'=>$patternGraphKey));
        echo $returnMessage;
    } else {
        sleep(1); //we want make sure they have time to read the progress indicator
        $returnMessage = json_encode(array('status'=>'error'));
        echo $returnMessage;
    }
}
?>
