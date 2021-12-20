var ev = new Event( "consentupdate" );
ev.grantedConsents = {$settings->getGtmGrantedConsents()|json_encode nofilter};
document.dispatchEvent( ev );
