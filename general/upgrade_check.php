<?php

defined('ABSPATH') or die("Jog on!");

function ws_ls_upgrade_check()
{
	if(is_admin())
	{
		switch (get_option(WE_LS_VERSION)) {
			case '1.9':
				//Do nothing.
				break;
			default: // Not around yet so create relevant tables
				ws_ls_create_targets_table();
				update_option(WE_LS_VERSION, WE_LS_CURRENT_VERSION);
				break;
		}

		
	}
}


?>