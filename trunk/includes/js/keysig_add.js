/**
* @package Keysig
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

var makeAddRequest = function() {
	var user = YAHOO.lang.trim(YAHOO.util.Dom.get("keysigUser").value);
    var bk   = YAHOO.lang.trim(YAHOO.util.Dom.get("keysigKey").value);
    
	if (user==='') {
        alert('You must enter a valid username!');
        return false;
    } else if (bk==='') {
        alert('You must enter a valid password/pattern to test!');
        return false;
    }
    
    YAHOO.keysig.loadingPanel();  //put up the loading panel to indicate background processing
    var sUrl     = "add_user.php?xhr=true";
	var postData = "keysigUser=" + user + "&keysigKey=" + bk + "&keysigData=" + obj1.jsonString;
	var request  = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback, postData);
};

YAHOO.keysig.loadingPanel = function() { 
    if (!YAHOO.keysig.wait) {
        // Initialize the temporary Panel to display while waiting for external content to load
        YAHOO.keysig.wait = 
                new YAHOO.widget.Panel("wait",  
                                            { width: "240px", 
                                              fixedcenter: true, 
                                              close: false, 
                                              draggable: false, 
                                              zindex:4,
                                              modal: true,
                                              visible: false
                                            } 
                                        );

        YAHOO.keysig.wait.setHeader("Loading, please wait...");
        YAHOO.keysig.wait.setBody("<img src=\"includes/images/rel_interstitial_loading.gif\"/>");
        YAHOO.keysig.wait.render(document.body);
    }

    // Show the Panel
    YAHOO.keysig.wait.show();
};

YAHOO.keysig.prep = function() {	
    return {
        reset : function () {
            YAHOO.util.Dom.get("keysigKey").value = '';
        }
    };
}();

//Define our Ajax handler here	
YAHOO.keysig.requestEngine = function() {
	return {
		handleSuccess : function(o) {
			if (o.responseText !== undefined){
				var rt = YAHOO.lang.JSON.parse(o.responseText);
				if (rt.status==='ok') {
			        //Success, send them back to the main page
					window.location = 'index.php';
				} else {
				    YAHOO.keysig.wait.hide(); //Hide the progress indicator
			    	alert('Error adding new user/password!  Please try again.');
				}
			}
		},
		handleFailure : function(o) {
            YAHOO.keysig.wait.hide(); //Hide the progress indicator
			alert('Error adding new user/password!  Please try again.');
		}
	};
}();

var callback = 
{
    success: YAHOO.keysig.requestEngine.handleSuccess, 
    failure: YAHOO.keysig.requestEngine.handleFailure
};

//Define our JavaScript event handlers
var obj1 = new YAHOO.keysig.base.signature();
YAHOO.util.Event.addListener("keysigKey", "focus", YAHOO.keysig.prep.reset);
YAHOO.util.Event.addListener("keysigKey", "focus", YAHOO.keysig.base.signature().init, obj1);
YAHOO.util.Event.addListener("keysigKey", "keydown", YAHOO.keysig.base.signature().capture, obj1);
YAHOO.util.Event.addListener("keysigKey", "keypress", YAHOO.keysig.base.signature().capture, obj1);		
YAHOO.util.Event.addListener("keysigKey", "keyup", YAHOO.keysig.base.signature().capture, obj1);
YAHOO.util.Event.addListener("keysigKey", "blur", YAHOO.keysig.base.signature().end, obj1);
YAHOO.util.Event.addListener("submit", "click", function(e) {
	YAHOO.util.Event.preventDefault(e); //don't submit form/reload page
});
YAHOO.util.Event.addListener("submit", "click", makeAddRequest); //Do the ajax pattern check
YAHOO.util.Event.addListener("reset", "click", function(e) {
	//Just clear out the form
	YAHOO.util.Dom.get("keysigUser").value = '';
    YAHOO.util.Dom.get("keysigKey").value  = '';
});
