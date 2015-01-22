=== Plugin Name ===
Contributors: aliakro
Tags: weight, graph, track
Requires at least: 4.0.0
Tested up to: 4.0
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: http://www.yeken.uk

A very simple plugin to allow a logged in user to record their weight (Kg or St) on given dates. This data is then shown in a graph (chart.js) and table.

== Description ==

A very simple plugin to allow a logged in user to record their weight (Kg or St) on given dates. This data is then shown in a graph (chart.js) and table.

Place the tag [weightlosstracker] on a given page and the user is presented with a form to enter a date, weight (Kg or St) and notes for that entry. When the person saves their entry the data table and graph is refreshed.

If data is entered for an existing date, then the previous entry is simply updated. The graph is shown when there are two or more entries.

Also supports the following tags:

	[weightloss_weight_difference] - total weight lost by the logged in member
	[weightloss_weight_start] - start weight of the logged in member
	[weightloss_weight_most_recent] - end weight of the logged in member

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `weight-loss-tracker` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Does it create any custom mySQL tables =

Yes it creates one to store weight information per user

= Does it support the Avada theme =

Yes. In define_globals.php set WE_LS_SUPPORT_AVADA_THEME to "true".

== Screenshots ==

1. A basic view of the plugin displayed to the user (in Avada theme support mode)

== Changelog ==

= 1.5 =

* Replaced chart.js with chartNew.js (https://github.com/FVANCOP/ChartNew.js/) to allow graph axis labels
* Axis labels added to graph
* Bug fix. Imperial measurements are now displayed on the graph in pounds (as opposed to stones / lbs) due to this bug:

	The problem is plotting imperial values on a graph. Say you have the following imperial data (stones / pounds) data:

	'15 7','15 3','15 0','14 12','14 10','14 7'

	I originally added a decimal place to stones / ozs so I can graph it:

	'15.7','15.3','15.0','14.12','14.10','14.7'

	At a glance it looks like this should work.

	However, the 14.7 is treated as 14.70 and therefore comes higher than 14.10.

  I couldn't see a quick way to correct this. So to be safe (and save me time), I've changed it to display in pounds only

= 1.4 =

* Added the following tags:

	[weightloss_weight_difference] - total weight lost by the logged in member
	[weightloss_weight_start] - start weight of the logged in member
	[weightloss_weight_most_recent] - end weight of the logged in member

= 1.2 / 1.3 =
* Various changes made upon feedback from WordPress submission

= 1.1 =
* Support imperial as well as metric weights

= 1.0 =
* Initial Build
