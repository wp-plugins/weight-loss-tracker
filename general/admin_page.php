<?php

defined('ABSPATH') or die('Jog on!');

function ws_ls_admin_menu() {
	add_options_page( WE_LS_TITLE . ' Options', WE_LS_TITLE, 'manage_options', 'ws-ls-admin-page', 'ws_ls_admin_page' );
}
function ws_ls_admin_page() {
	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

		?>
		<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	
	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">
						<h3 class="hndle"><span><?php _e( WE_LS_TITLE . ' Instructions'); ?> </span></h3>
						<div style="padding: 0px 15px 0px 15px">
							<p><?php _e( 'Place the tag [weightlosstracker] on a given page and the user is presented with a form to enter a date, weight and notes for that entry. When the person saves their entry the data table and graph is refreshed.' ); ?></p>
						</div>
					</div>

					<div class="postbox">

					
						<h3 class="hndle"><span><?php _e( WE_LS_TITLE . ' Settings'); ?></span></h3>

						<div class="inside">
						
							<form method="post" action="options.php"> 
								<?php

									settings_fields( 'we-ls-options-group' );
									do_settings_sections( 'we-ls-options-group' ); 

								?>
							
									<table class="form-table">
										<tr>
											<th scope="row"><?php _e( 'Weight Units' ); ?></th>
											<td>
												<select id="ws-ls-units" name="ws-ls-units">
													<option value="kg" <?php selected( get_option('ws-ls-units'), 'kg' ); ?>><?php _e('Kg'); ?></option>
													<option value="stones_pounds" <?php selected( get_option('ws-ls-units'), 'stones_pounds' ); ?>><?php _e('Stones & Pounds'); ?></option>
													<option value="pounds_only" <?php selected( get_option('ws-ls-units'), 'pounds_only' ); ?>><?php _e('Pounds'); ?></option>
												</select>
												<p><?php _e('You can specify whether to display weights in Kg, Stones & Pounds or just Pounds. Please note: The graph will be displayed in Pounds if "Stones & Pounds" is selected.');?></p>
											</td>
										</tr>
										<tr>
											<th scope="row"><?php _e( 'Allow target weights?' ); ?></th>
											<td>
												<select id="ws-ls-allow-targets" name="ws-ls-allow-targets">
													<option value="no" <?php selected( get_option('ws-ls-allow-targets'), 'no' ); ?>><?php _e('No'); ?></option>
													<option value="yes" <?php selected( get_option('ws-ls-allow-targets'), 'yes' ); ?>><?php _e('Yes'); ?></option>
												</select>
												<p><?php _e('If enabled, a user is allowed to enter a target weight. This will be displayed as a horizontal bar on the line chart.'); ?></p>
											</td>
										</tr>
										<tr>
											<th scope="row"><?php _e( 'Display data in tabs?' ); ?></th>
											<td>
												<select id="ws-ls-use-tabs" name="ws-ls-use-tabs">
													<option value="no" <?php selected( get_option('ws-ls-use-tabs'), 'no' ); ?>><?php _e('No')?></option>
													<option value="yes" <?php selected( get_option('ws-ls-use-tabs'), 'yes' ); ?>><?php _e('Yes')?></option>
												</select>
												<p><?php _e('If enabled, "Weight History" and "Target Weight" will be displayed on sepearate tabs.')?></p>
											</td>
										</tr>
										<tr>
											<th scope="row"><?php _e( 'Enable support for Avada theme?' ) ?>:</th>
											<td>
												<select id="ws-ls-support-avada" name="ws-ls-support-avada">
													<option value="no" <?php selected( get_option('ws-ls-support-avada'), 'no' ); ?>><?php _e('No')?></option>
													<option value="yes" <?php selected( get_option('ws-ls-support-avada'), 'yes' ); ?>><?php _e('Yes')?></option>
												</select>
												<p><?php _e('Enables additional styling to support the <a href="http://theme-fusion.com/avada/" target="_blank">Avada theme</a>.')?></p>
											</td>
										</tr>
									</table>
									
								
								<?php submit_button(); ?>



							</form>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->		

	



	<?php
}

function ws_ls_register_settings()
{
	register_setting( 'we-ls-options-group', 'ws-ls-units' );
  	register_setting( 'we-ls-options-group', 'ws-ls-allow-targets' );
  	register_setting( 'we-ls-options-group', 'ws-ls-use-tabs' );
  	register_setting( 'we-ls-options-group', 'ws-ls-support-avada' );
}

if ( is_admin() )
{
	add_action( 'admin_menu', 'ws_ls_admin_menu' );
	add_action( 'admin_init', 'ws_ls_register_settings' );
}