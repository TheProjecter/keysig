/**
* @package Keysig
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

YAHOO.namespace("brilaps");
YAHOO.brilaps.browserGrade = function() {
    //This function utilizes the YUI Environment Info (http://developer.yahoo.com/yui/docs/Env.js.html) to
    //create a browser grade based on the Yahoo Graded Browser Support Table (http://developer.yahoo.com/yui/articles/gbs/)
    //its better to use feature detection and this lacks OS info, but if you are here then you know that ;)
    var envInfo = YAHOO.env.ua;
    
    //Not gonna be exact without solid OS info , but we'll get it close
    var grade  = 'X';
    if (envInfo.ie >= 6) {
        //6 not not A on Vista & 7 not A on 2000
        grade  = 'A';
    } else if (envInfo.opera >= 9.5) {
        //only an A on XP and Mac OS X 10.5 technically
        grade  = 'A';
    } else if (envInfo.gecko >= 1.8) {
	    //v2 is only A on XP and Mac OS X 10.5 technically & v3 is A everywhere
	    //we don't get the FF version, but rather the gecko engine number which makes things a bit
	    //more difficult since some of 1.5 and 2 report the same gecko engine (i.e.) Firefox 1.5.0.9 thru Firefox 2.0.0.3 <-- Report 1.8
	    grade  = 'A';
	} else if (envInfo.webkit >= 523.12) {
	    grade  = 'A';
	}
    
    //What is the final verdict?
	if (grade!=='A') {
	    alert('WARNING: This is not a supported browser!  Try upgrading.');
	}
};

YAHOO.util.Event.onDOMReady(YAHOO.brilaps.browserGrade); //Warning folks if they are not using a grade A browser
YAHOO.util.Event.onDOMReady(function() {
	YAHOO.brilaps.browserGrade(); //Warning folks if they are not using a grade A browser
	//We need to disable autocomplete for these sensitive fields, but without blowing XHTML validation
	YAHOO.util.Dom.get("keysigUser").setAttribute("autocomplete","off");
	YAHOO.util.Dom.get("keysigKey").setAttribute("autocomplete","off");
});