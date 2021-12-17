window.dataLayer = window.dataLayer || [];
var consents = [];

if ( window.UTILS.cookieConsent.accepted( "analytics" ) ) \{
	consents["analytics_storage"] = "granted";
}
if ( window.UTILS.cookieConsent.accepted( "advertising" ) ) \{
	consents["ad_storage"] = "granted";
}
if ( window.UTILS.cookieConsent.accepted( "necessary" ) ) \{
	consents["functionality_storage"] = "granted";
}
if ( typeof dataLayer !== "undefined") \{
	dataLayer.push( \{"event":"consentUpdate", "grantedConsents": consents } );
}
