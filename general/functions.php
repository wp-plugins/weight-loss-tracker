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

/*
Delete all from SQL Tables:

 - WP_WS_LS_DATA_TARGETS
 - WP_WS_LS_DATA

 */
function ws_ls_delete_existing_data()
{
    if(is_admin())
    {
        global $wpdb;
        $wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . WE_LS_TARGETS_TABLENAME);
        $wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . WE_LS_TABLENAME);
    }

}


function ws_ls_create_dialog_jquery_code($title, $message, $class_used_to_prompt_confirmation, $js_call = false)
{
	global $wp_scripts;
	$queryui = $wp_scripts->query('jquery-ui-core');
	$url = "//ajax.googleapis.com/ajax/libs/jqueryui/".$queryui->ver."/themes/smoothness/jquery-ui.css";
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_style('jquery-ui-smoothness', $url, false, null);

    $id_hash = md5($title . $message . $class_used_to_prompt_confirmation);

    ?>
    <div id='<?php echo $id_hash; ?>' title='<?php echo $title; ?>'>
      <p><?php echo $message; ?></p>
    </div>
     <script>
          jQuery(function($) {
           
            var $info = $('#<?php echo $id_hash; ?>');
            
            $info.dialog({                   
                'dialogClass'   : 'wp-dialog',           
                'modal'         : true,
                'autoOpen'      : false
            });
           
            $('.<?php echo $class_used_to_prompt_confirmation; ?>').click(function(event) {
                
                event.preventDefault();

                target_url = $(this).attr('href');
             
                var $info = $('#<?php echo $id_hash; ?>');
               
                $info.dialog({                   
                    'dialogClass'   : 'wp-dialog',           
                    'modal'         : true,
                    'autoOpen'      : false, 
                    'closeOnEscape' : true,      
                    'buttons'       : {
                        'Yes': function() {

                            <?php if ($js_call != false): ?>
                                <?php echo $js_call; ?>

                                $(this).dialog('close');
                            <?php else: ?>
                                window.location.href = target_url;
                            <?php endif; ?>
                        },
                         'No': function() {
                            $(this).dialog('close');
                        }
                    }
                });

                 $info.dialog('open');
            });

        });    




      </script>

  <?php
}
?>