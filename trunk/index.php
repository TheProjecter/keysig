<?php
/**
* @package Keysig
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

//Setup database connection
require('includes/database.php');

//Test tmp directory permissions and GD support for graphing
if (is_writable('tmp')===false) {
    die('Error: The keysig tmp directory must be writable! See installation instructions.');
}
if (function_exists('imagecreatetruecolor')===false) {
	die('Error: Keysig requires that GD support be enabled within PHP!');
}

/* Some logic to handle returns process from a less than 
   modern browser and/or one without JavaScript */
if (isset($_GET['error']) && !empty($_GET['error'])) {
    if ($_GET['error']=='invalid_user') {
        $error = 'Invalid user! Please try again.';
    } else if ($_GET['error']=='invalid_pattern_value') {
        $error = 'Invalid pattern value! Please try again.';
    } else if ($_GET['error']=='none') {
        $error = 'The user and pattern matched, but we could not calculate a keysig pattern.'; //The user & pattern value matched
    } else {
        $error = 'Unknown Error. Please try again.';
    }
} else {
    $error = false;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Keysig</title>
	<link rel="stylesheet" type="text/css" href="includes/js/yui/reset-fonts-grids/reset-fonts-grids.css" />
	<link rel="stylesheet" type="text/css" href="includes/js/yui/container/assets/skins/sam/container.css" />
	<link rel="stylesheet" type="text/css" href="includes/css/main.css" />
</head>
<body class="yui-skin-sam">
	<div id="doc3" class="yui-t7">
		<div id="hd">
			<h1>Keysig</h1>
		</div> 
		<div id="bd">
			<!--Top row 100%-->
			<div class="yui-g">
				<p>Keysig is a base framework/example for doing keyboard keystroke pattern capture and unique signature creation/analysis in a cross-browser manner (i.e.) grade "A" browsers.  This<br/>
				type of functionality could have many real world implementations, but probably the most likely candiate would be a browser based biometric login system of some sort.</p>
				<p>This example form demonstrates creation, storage, and analysis of user entered keyboard keystroke patterns. It also demonstrates pattern graphing for simple visual comparison.<br />
				This example uses the common username/password test.  Unlike traditional username/password tests not only are the username/password values tested, but the keystroke pattern is <br />
				also examined.</p>
				<p>Feel free to experiment with any of the existing username/password combinations shown in the table below and/or create your own combinations and try to match them. You should<br />
				be able to match your own creations within a reasonable standard divation, but not those created by others.  The username is a plain text match, but the password field also compares<br />
				the keystroke pattern.</p>
				<h2>Explanations:</h2>
				<ul>
					<li><span class="explanation-title">Patter Gap Chart</span>: This chart displays the time between keystrokes.  It compares the current test against the original submission.</li>
					<li><span class="explanation-title">Press Time Chart</span>: This chart displays the time of each physical keypress.  It compares the current test against the original submission.</li>
				</ul>
				<br />
			</div>
			<!--Center row 100%-->
			<div class="yui-g">
			    <?php
			    if (!empty($error)) {
			        echo '<p id="submission_error">'.$error.'</p>';
			    }
			    ?>
				<form id="frmIdentity" method="post" action="verify.php">
				    <label for="keysigUser">Username: </label>
				    <input id="keysigUser" name="keysigUser" type="text" size="25" value="" />
					<label for="keysigKey">Password: </label>
					<input id="keysigKey" name="keysigKey" type="text" size="25" value="" />
					<input id="submit" type="submit" value="Submit" />
					<input id="reset" type="button" value="Reset" /><br />
				</form>
				<noscript>
				    <p id="noscript">Your browser does not support JavaScript!  The pattern test will be limited as a result.</p>
				</noscript>
			</div>
			<div id="content"></div>
			<div class="yui-gb">
				<div id="existing-users">
					<p>Existing username/password combinations for testing:</p>
					<?php
					//Find existing patterns to show for testing
					$existingUsers = "SELECT username, pattern_value FROM users";         
					$result = mysql_query($existingUsers);
					?>
					<a href="add.php">Add new</a>
					<table width="500" border="1">
						<tr>
							<th>Username</th>
							<th>Password</th>
						</tr>
						<?php
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
							echo '<tr>';
							echo '<td>' . $row["username"] . '</td>';
							echo '<td>' . $row["pattern_value"] . '</td>';
							echo '</tr>';
						}
						?>
					</table>
				</div>
	            <div id="chart-container">
					<!-- Dynamic charts go here -->
				</div>
		    </div>
		    <div id="ft">Copyright &copy; 2008 <a href="http://brilaps.com" target="blank">Brilaps, LLC</a>. All rights reserved.</div>
	    </div> <!-- end bd -->
    </div> <!-- end doc3 -->
    <!-- 
    The YUI library files can be loaded individually via the locally included files or as a single combo
	file served from Yahoo! or Google for enhanced performance and standard CDN benefits.  The default is
	to serve locally, but to change this simply comment out the individual YUI includes below and uncomment
	the single combo include.  Make sure to read the hosting TOS first.
	
	More on the YUI combo option here:
	http://developer.yahoo.com/yui/articles/hosting/#combo
	-->
	<script type="text/javascript" src="includes/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="includes/js/yui/animation/animation-min.js"></script>
	<script type="text/javascript" src="includes/js/yui/connection/connection-min.js"></script>
	<script type="text/javascript" src="includes/js/yui/container/container-min.js"></script>
	<script type="text/javascript" src="includes/js/yui/element/element-beta-min.js"></script>
	<script type="text/javascript" src="includes/js/yui/json/json-min.js"></script>
	
	<!-- 
	Combo-handled YUI JS files.  To reload this exact combo configuration for modification use the following link:
	http://developer.yahoo.com/yui/articles/hosting/?animation&connection&container&element&json&yahoo-dom-event&MIN
	-->
	<!--
	<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.6.0/build/yahoo-dom-event/yahoo-dom-event.js&2.6.0/build/animation/animation-min.js&2.6.0/build/connection/connection-min.js&2.6.0/build/container/container-min.js&2.6.0/build/element/element-beta-min.js&2.6.0/build/json/json-min.js"></script>
	-->
	
    <script type="text/javascript" src="includes/js/keysig_base.js"></script>
	<script type="text/javascript" src="includes/js/keysig_index.js"></script>
	<script type="text/javascript" src="includes/js/brilaps_base.js"></script>
</body>
</html>