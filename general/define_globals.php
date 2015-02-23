<?php

	// -----------------------------------------------------------------------------------------
	// AC: Configurable settings
	// -----------------------------------------------------------------------------------------
		
	define('WE_LS_IMPERIAL_WEIGHTS', false);			// Specifies whether to use Imperial weights e.g. stones / pounds. I
														// If set to false, Metric (Kg) will be used

	define('WE_LS_IMPERIAL_UNITS', 'pounds_only');		// Only applies when WE_LS_IMPERIAL_WEIGHTS is set to true (i.e. using Imperial weights)
														// Specifies which units to use for Imperial measurements:
														// stones_pounds = Stones and Pounds (default)
														// pounds_only = Pounds only

	define('WE_LS_SUPPORT_AVADA_THEME', false);			// If you use the Avada theme (http://theme-fusion.com/avada/)
														// setting this to true will apply their classes for styling
	

	// -----------------------------------------------------------------------------------------
	// AC: It's not recommended to change the following as it may break the plugin.
	// -----------------------------------------------------------------------------------------
	
	define('WE_LS_TITLE', "Weight Loss Tracker");
	define('WE_LS_SLUG', "weight-loss-tracker");
	define('WE_LS_TABLENAME', "WS_LS_DATA"); 

	// Set units to use
	if (WE_LS_IMPERIAL_WEIGHTS)
		define('WE_LS_DATA_UNITS', (WE_LS_IMPERIAL_UNITS == "stones_pounds" || WE_LS_IMPERIAL_UNITS == "pounds_only") ? WE_LS_IMPERIAL_UNITS : "stones_pounds");
	else
		define('WE_LS_DATA_UNITS', 'kg');		
?>