<?php

	defined('ABSPATH') or die("Jog on!");


	function ws_ls_activate()
	{
		$debug_values = true;

		global $wpdb;

   		$table_name = $wpdb->prefix . WE_LS_TABLENAME;
		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  weight_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  weight_user_id integer NOT NULL,
		  weight_weight decimal NOT NULL,
		  weight_stones decimal NOT NULL,
		  weight_pounds decimal NOT NULL,
		  weight_only_pounds decimal NOT NULL,
		  weight_notes text null,
		  UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$sql = "ALTER TABLE $table_name ADD COLUMN weight_only_pounds decimal NOT NULL";
		dbDelta( $sql );

		ws_ls_upgrade_check();
	}

?>