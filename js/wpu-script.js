function WPUSC() {

	function addEvent(element, eventName, callback) {
		if (element.addEventListener) {
			element.addEventListener(eventName, callback, false);
		} else {
			element.attachEvent("on" + eventName, callback);
		}
	}

	// init
	function init() {
		var links = document.querySelectorAll('.wpu_shortlinks_sc a');
	    for (var i = 0; i < links.length; i++) {
			WPUSC.addEvent(links[i], 'click', WPUSC.popup)
	    }
	}

	// functions
	function openPopup(e) {
		var top = (screen.availHeight - 500) / 2;
		var left = (screen.availWidth - 500) / 2;
		var e = (e ? e : window.event);
        var target = (e.target ? e.target : e.srcElement);

		var popup = window.open(
			target.href, 
			'social',
			'width=550,height=420,left='+ left +',top='+ top +',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1'
		);

		if(popup) {
			popup.focus();
			e.preventDefault();
			return false;
		}

		return true;
	}

	// public stuff
	return {
		init: init,
		popup: openPopup,
		addEvent: addEvent
	}
}

var WPUSC = new WPUSC();
WPUSC.addEvent(window, 'load', WPUSC.init)