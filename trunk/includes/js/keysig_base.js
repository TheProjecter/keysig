/**
* @package Keysig
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

YAHOO.namespace("keysig");

Array.prototype.inArray = function(valeur) {
    for (var i in this) { 
        if (this[i] === valeur) {
            return i; 
        }
    }
    return false;
};

Array.prototype.clear = function() {
    while (this.length > 0) {
        this.pop();
    }
};
        		
YAHOO.keysig.base = function() {		
	return {    
	    signature : function () {
	        
	        /*
            YAHOO.util.KeyListener.KEY = {
                ALT          : 18,
                BACK_SPACE   : 8,
                CAPS_LOCK    : 20,
                CONTROL      : 17,
                DELETE       : 46,
                DOWN         : 40,
                END          : 35,
                ENTER/RETURN : 13,
                ESCAPE       : 27,
                HOME         : 36,
                LEFT         : 37,
                META         : 224,
                NUM_LOCK     : 144,
                PAGE_DOWN    : 34,
                PAGE_UP      : 33, 
                PAUSE        : 19,
                PRINTSCREEN  : 44,
                RIGHT        : 39,
                SCROLL_LOCK  : 145,
                SHIFT        : 16,
                SPACE        : 32,
                TAB          : 9,
                UP           : 38
            };			        
	        and some others.. i forgot what 145 was....*/
            //var ignoreKeys = new Array (8, 9, 13, 16, 17, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 144, 145, 19, 224);
            var ignoreKeys = [224, 144, 145, 19, 44, 13, 9];
            
            var KeyEvent = function() {
                var kc;//keyCode
                var ch;//char
                var tKD;//timeKeyDown
                var tKU;//timeKeyUp
                var sP;//shift pressed
                var cP;//control pressed
                var aP;//alt pressed
                var mP;//metakey pressed
                return {
                    packed : function() {
                        var obj = {};
                        obj.ch = this.ch;
                        obj.kc = this.kc;
                        obj.sP = this.sP;
                        obj.cP = this.cP;
                        obj.aP = this.aP;
                        obj.mP = this.mP;  
						obj.timeDown = this.tKD;
                        obj.duration = this.tKU - this.tKD;
                        return obj;
                    }
                };
            };
	        
	        var pack = function(obj) {
        	    var jsonObj = {};
        	    jsonObj.whoami = obj.whoami;
        	    jsonObj.sigstart = obj.sigstart;
        	    jsonObj.sigend = obj.sigend;
        	    jsonObj.keys = [];
        	    for(var i = 0; i < obj.keys.length; i++) { 	
        	        var key = obj.keys[i];
    	            jsonObj.keys.push(key.packed());
    	        }
        	    //console.log(YAHOO.lang.JSON.stringify(jsonObj));
				obj.jsonString = YAHOO.lang.JSON.stringify(jsonObj);
        	    
        	    //obj.whoami = don't mess with who am i. I am I;
        	    obj.sigend = 0;
        	    obj.sigstart = 0;
        	    obj.keys.clear();
	        };
	        
	        function registerKeyEvent(e, et, obj) {
	            var code = e.keyCode;
	
                if (ignoreKeys.inArray(code) === false) {
                    if (e.type === "keydown") {
			            //if already here, it's not here. put it in. 
		                var ev = new KeyEvent();
		                ev.kc = code;
		                ev.ch = String.fromCharCode(e.charCode ? e.charCode : e.keyCode);
                        ev.sP = e.shiftKey;//shift pressed
                        ev.cP = e.ctrlKey;//control pressed
                        ev.aP = e.altKey;//alt pressed
                        ev.mP = e.metaKey;//metakey pressed
		                ev.tKD = et.getTime();
		                obj.keys.push(ev); 
                    } else if (e.type === "keyup") {
                        for (var i = obj.keys.length - 1; i >= 0; i--) {
                            ev = obj.keys[i];
                            if (ev.kc === code) {
                                ev.tKU = et.getTime();
                                break;
                            }
                        }
                    } else if (e.type === "keypress") {
                        //ignore for now....  
                    }
	            } else {
	                //console.log ("ignoring " + e.keyCode);
					YAHOO.util.Event.preventDefault(e);
					return false;
	            }
	            return;
	        }
	           
	        return {
	            keys : [],
    			sigstart : null,
    			sigend : null,
    			whoami : null,
    			
	            init : function(e, obj) {
		            obj.sigstart = new Date();
                    var el = YAHOO.util.Event.getTarget(e);
                    if (el !== "undefined") {
                        obj.whoami = el.id;
                    }
                    //console.log("starting:" + obj.whoami);
		        },
        
		        end : function(e, obj) {
                    //console.log("ending: " + obj.whoami);
		            obj.sigend = new Date();
                    var el = YAHOO.util.Event.getTarget(e);
                    //make sure we're dealing with the same thing.
                    if ((el !== "undefined") && el.id === obj.whoami) {
		                pack(obj);
                    }
		        },
		        
	            capture : function(e, obj) {
	                var et = new Date();
                    registerKeyEvent(e, et, obj);
	            }
	        }; //end of signature return
	    }//end of signature
	};	
}();
