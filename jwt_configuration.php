<?php
// set your default time-zone
	date_default_timezone_set('Europe/Belgrade');
	 
	// variables used for jwt
	$key = "lb12345";
	$issued_at = time();
	//$expiration_time = $issued_at+(60*60);
	$issuer = "http://localhost/";
?>