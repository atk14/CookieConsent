window.UTILS = window.UTILS || { };

window.UTILS.cookieConsent = {};

/**
 *
 *	window.UTILS.cookieConsent.accepted( "advertising" ); // true or false
 */
window.UTILS.cookieConsent.accepted = function( category ) {
		var cookieData = window.UTILS.cookieConsent._getCookieData();
		if ( !cookieData ) {
			return false;
		}
		if ( !cookieData.cs[ category ] ) {
			return false;
		}
		return cookieData.cs[ category ].a === "a";
};

window.UTILS.cookieConsent._getCookieData = function() {
	if ( window.UTILS.cookieConsent._cookieData ) {
		return window.UTILS.cookieConsent._cookieData;
	}
	var cookieName = "consent";
	var cookieValue;
	var value = "; " + document.cookie;
	var parts = value.split( "; " + cookieName + "=" );
	if (parts.length === 2) {
		cookieValue = parts.pop().split( ";" ).shift();
		cookieValue = decodeURIComponent( cookieValue ); // "%3D" -> "="
		cookieValue = atob( cookieValue ); // base64 decode
		cookieValue = JSON.parse( cookieValue );
	}
	window.UTILS.cookieConsent._cookieData = cookieValue;
	return cookieValue;
};
