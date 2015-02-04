<?php

	defined('ABSPATH') or die("Jog on!");

	// -----------------------------------------------------------------------------------------
	// AC: Functions
	// -----------------------------------------------------------------------------------------
	
	include WS_LS_ABSPATH . "general/functions.php";

	// -----------------------------------------------------------------------------------------
	// AC: Include all PHP files within given folders
	// -----------------------------------------------------------------------------------------
	
	$include_paths = array("installer", "includes");

	foreach ($include_paths as $path) {
	   foreach (glob(WS_LS_ABSPATH . $path . "/*.php") as $filename) {
	        include $filename;
	        //echo $filename . "<br />";
	   }
	}

?>