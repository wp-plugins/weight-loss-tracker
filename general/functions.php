<?php
defined('ABSPATH') or die("Jog on!");

function ws_ls_create_targets_table()
{
	global $wpdb;

	$table_name = $wpdb->prefix . WE_LS_TARGETS_TABLENAME;
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  weight_user_id integer NOT NULL,
	  target_weight_weight decimal NOT NULL,
	  target_weight_stones decimal NOT NULL,
	  target_weight_pounds decimal NOT NULL,
	  target_weight_only_pounds decimal NOT NULL,
	  UNIQUE KEY id (id)
	) $charset_collate;";

    $wpdb->query($sql);

}


?>