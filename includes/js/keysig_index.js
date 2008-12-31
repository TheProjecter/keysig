/**
* @package Keysig
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

YAHOO.keysig.prep = function() {	
    return {
        displayCharts : function(status) {
            var chartDiv = YAHOO.util.Dom.get("chart-container");
            YAHOO.util.Dom.setStyle(chartDiv, 'display', status); //none or block
        },
        reset : function () {
            YAHOO.util.Dom.get("keysigKey").value = '';
            YAHOO.keysig.prep.displayCharts('none');
        }
    };
}();

YAHOO.keysig.loadingPanel = function() { 
    var content = YAHOO.util.Dom.get("content");
        content.innerHTML = "";
        
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

//Define our Ajax handler here	
YAHOO.keysig.requestEngine = function() {
    //loading panel
    var content = YAHOO.util.Dom.get("content");
        content.innerHTML = "";
	
	return {
		handleSuccess : function(o) {
			if (o.responseText !== undefined){
				var chartInfo = YAHOO.lang.JSON.parse(o.responseText);
				var chartArea = YAHOO.util.Dom.get("chart-container");
				var chartStrings = '';
				if (chartInfo.status==='ok') {
			        chartStrings = '<h2>Confidence Charts</h2>';
					chartStrings += '<img src="tmp/pattern_chart_graph_'+chartInfo.pattern_graph_key+'.png" alt="chart" /><br />';
					chartStrings += '<img src="tmp/press_time_graph_'+chartInfo.pattern_graph_key+'.png" alt="chart" />';
				} else {
				    chartStrings += '<span class="warning">Values do not match.  Please try again!</span>';
				}
				
				chartArea.innerHTML = chartStrings; //Append the graphs
				YAHOO.keysig.prep.displayCharts('block'); //Show the chart area
                YAHOO.keysig.wait.hide(); //Hide the progress indicator
			} else {
			    alert('Error locating pattern graphs!  Please try again.');
			}
		},
		handleFailure : function(o) {
            YAHOO.keysig.wait.hide(); //Hide the progress indicator
            alert('Error processing request!  Please try again.');
		}
	};
	
}();

var callback = 
{
    success: YAHOO.keysig.requestEngine.handleSuccess, 
    failure: YAHOO.keysig.requestEngine.handleFailure
};

var makeRequest = function() {
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
    var sUrl     = "verify.php?xhr=true";
	var postData = "keysigUser="+user+"&keysigData="+ obj1.jsonString;
	var request  = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback, postData);
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
YAHOO.util.Event.addListener("submit", "click", makeRequest); //Do the ajax pattern check
YAHOO.util.Event.addListener("reset", "click", function(e) {
	//Just clear out the form
	YAHOO.util.Dom.get("keysigUser").value = '';
    YAHOO.util.Dom.get("keysigKey").value  = '';
});
