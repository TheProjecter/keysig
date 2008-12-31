<?php
/**
* @package Keysig
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
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
				<p>Add a new username/password combination below to create a new pattern which can be tested from the index page</p>
			</div>
			<!--Center row 100%-->
			<div class="yui-g">
				<form id="frmIdentity" method="post" action="add.php">
				    <label for="keysigUser">New Username: </label>
				    <input id="keysigUser" name="keysigUser" type="text" size="25" value="" />
					<label for="keysigKey">New Password: </label>
					<input id="keysigKey" name="keysigKey" type="text" size="25" value="" />
					<input id="submit" type="submit" value="Add" />
					<input id="reset" type="button" value="Reset" /><br />
				</form>
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
	<script type="text/javascript" src="includes/js/yui/connection/connection-min.js"></script>
	<script type="text/javascript" src="includes/js/yui/container/container-min.js"></script>
	<script type="text/javascript" src="includes/js/yui/element/element-beta-min.js"></script>
	<script type="text/javascript" src="includes/js/yui/json/json-min.js"></script>
	
	<!-- 
	Combo-handled YUI JS files.  To reload this exact combo configuration for modification use the following link:
	http://developer.yahoo.com/yui/articles/hosting/?connection&container&element&json&yahoo-dom-event&MIN
	-->
	<!--
	<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.6.0/build/yahoo-dom-event/yahoo-dom-event.js&2.6.0/build/connection/connection-min.js&2.6.0/build/container/container-min.js&2.6.0/build/element/element-beta-min.js&2.6.0/build/json/json-min.js"></script>
	-->
	
	<script type="text/javascript" src="includes/js/keysig_base.js"></script>
	<script type="text/javascript" src="includes/js/keysig_add.js"></script>
	<script type="text/javascript" src="includes/js/brilaps_base.js"></script>
</body>
</html>