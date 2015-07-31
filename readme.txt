=== Plugin Name ===
Contributors: aliakro
Tags: weight, graph, track, stones, kg, table, data, plot
Requires at least: 4.0.0
Tested up to: 4.1
Stable tag: 1.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: Paypal: email@YeKen.uk

A simple plugin to allow a logged in user to record their weight on given dates. This data is then shown in a graph (ChartNew.js) and table.

== Description ==

A simple plugin to allow a logged in user to record their weight on given dates. This data is then shown in a graph (ChartNew.js) and table.

Place the tag [weightlosstracker] on a given page and the user is presented with a form to enter a date, weight and notes for that entry. When the person saves their entry the data table and graph is refreshed.

There are options to allow users to enter target weights, split data into tabs and support for the Avada theme.

The following weight formats are supported:

- Metric (Kg)
- Imperial - Stones & Pounds
- Imperial - Pounds only

If data is entered for an existing date, then the previous entry is simply updated. The graph is shown when there are two or more entries.

Also supports the following tags:

	[weightloss_weight_difference] - total weight lost by the logged in member
	[weightloss_weight_start] - start weight of the logged in member
	[weightloss_weight_most_recent] - end weight of the logged in member
	[weightloss_weight_difference_from_target] - end weight of the logged in member

Languages supported:

- English (UK)
- French
- Romanian
- Dutch 
- Need a translation? Email us: email@YeKen.uk

* Developed by YeKen.uk *

Paypal Donate: email@YeKen.uk

== Installation ==

1. Install "Weight Loss Tracker" via the "Plugins" page in WordPress Admin (or download zip and upload).
2. Setup the plugin in WordPress Admin panel by goto to Settings > Weight Loss Tracker
3. Create a page that users will visit. Within the page content add the shortcode [weightlosstracker].
4. Voila

== Frequently Asked Questions ==

= Does it create any custom mySQL tables =

Yes it creates two. One to store weight information per user and another to store target weights:

- WP_WS_LS_DATA_TARGETS - Stores user target data
- WP_WS_LS_DATA - Stores weight history

= Does it support the Avada theme? =

Yes. In WordPress Admin goto Settings > Weight Loss Tracker and change the setting "Enable support for Avada theme?" to Yes.

= How do I switch it from Metric (Kg) to Imperial (Stones / Pounds)? =

In WordPress Admin goto Settings > Weight Loss Tracker and change the setting "Weight Units" to the desried type.

= How do I enable tabs? =

Yes. In WordPress Admin goto Settings > Weight Loss Tracker and change the setting "Display data in tabs?" to Yes.

= Can I change measurement units while the site is live? =

It is only recommended if you first installed the plugin at version 1.6+ (as it stores measurements in Kg / Pound). Before this, you may find data isn't present for previous date entries.

= How do I disable "Target weight" =

Yes. In WordPress Admin goto Settings > Weight Loss Tracker and change the setting "Allow targets?" to No.

== Screenshots ==

1. A basic view of the plugin displayed to the user (in Avada theme support mode)

== Changelog ==

= 1.13 =

- BUG FIX: Some third party plugins / installs insert additional input fields within <form>. Added additional code to remove any non expected ones.
- BUG FIX: Target form appears when tabs enbaled - regardless of whether or not Targets are enabled.

= 1.12 =

- Added a button on admin page to delete all existing user data.

= 1.11 =

- Added an admin page to manage settings for plugin

= 1.10 =

- Weight History data presented on seperate tab

= 1.9 =

- Users can now specify their target weight
- New short code [weightloss_weight_difference_from_target] - end weight of the logged in member to display the difference between target and latest weight
- New filters to filter data by week

= 1.8 =

* Upgraded Datepicker to use jquery (as opposed to HTML5 control) for better browser support

= 1.7 =

* Added Dutch translations

= 1.6 =

* Now supports pounds only. As opposed to just Stones / Pounds
* Translations added for:
  - Romanian
  - French
* Minor tweaks to conversions between stones / pounds
* Corrected ws_ls_to_stone_pounds to calculate pounds correctly
* [weightloss_weight_difference] corrected to display pounds
* Small bug fixes

= 1.5.1 =

Minor bug fix for new tags. Writing out values in the wrong place.

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
