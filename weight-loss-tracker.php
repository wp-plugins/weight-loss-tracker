<?php

defined('ABSPATH') or die("Jog on!");

/**
 * Plugin Name: Weight Loss Tracker
 * Description: A simple plugin for users to set their initial weight and add subsequent weights. These are then displayed on a graph.
 * Version: 1.11
 * Author: YeKen
 * Author URI: http://www.YeKen.uk
 * License: GPL2
 * Text Domain: weight-loss-tracker
 */
/*  Copyright 2014 YeKen.uk

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('WS_LS_ABSPATH', plugin_dir_path( __FILE__ ));


// -----------------------------------------------------------------------------------------
// AC: Activate / Deactivate / Uninstall Hooks
// -----------------------------------------------------------------------------------------

register_activation_hook(   __FILE__, 'ws_ls_activate');

// -----------------------------------------------------------------------------------------
// AC: Define globals
// -----------------------------------------------------------------------------------------

include WS_LS_ABSPATH . "general/define_globals.php";

// -----------------------------------------------------------------------------------------
// AC: Include all relevant PHP files
// -----------------------------------------------------------------------------------------

include WS_LS_ABSPATH . "general/include_files.php";

// -----------------------------------------------------------------------------------------
// AC: Register hooks
// -----------------------------------------------------------------------------------------

include WS_LS_ABSPATH . "general/register_hooks.php";

// -----------------------------------------------------------------------------------------
// AC: Load relevant language files
// -----------------------------------------------------------------------------------------
load_plugin_textdomain( WE_LS_SLUG, false, dirname( plugin_basename( __FILE__ )  ) . "/languages/" );

// -----------------------------------------------------------------------------------------
// AC: Upgrade Checks
// -----------------------------------------------------------------------------------------
ws_ls_upgrade_check();

// -----------------------------------------------------------------------------------------
// AC: DEV Stuff here (!!!! REMOVE !!!!)
// -----------------------------------------------------------------------------------------




?>