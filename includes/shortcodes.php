<?php
	defined('ABSPATH') or die("Jog on!");

	function ws_ls_shortcode()
	{
		$output = "";

		if (!is_user_logged_in())
		{
			$output .= "
							<blockquote><p>
					        	" . __("You need to be logged in to record your weight.", WE_LS_SLUG) . "
					    	</p></blockquote>
						";
		}
		else
		{
			if ($_POST)
			{
				if (ws_ls_save_data())
				{
					$output .= "
									<blockquote><p>" . __("Saved!", WE_LS_SLUG) . "</p></blockquote>
							    	
								";

				}
				
			}

			$selected_week_number = -1;
			if ( $_POST && isset($_POST["week_number"]) && is_numeric($_POST["week_number"]))
				$selected_week_number = $_POST["week_number"];

			$week_ranges = ws_ls_get_week_ranges();

			$user_data = ws_ls_get_weights(get_current_user_id(), 100, $selected_week_number);

					

			if (is_array($user_data) && count($user_data) > 1)
			{
				$output .= ws_ls_title(__("In a chart", WE_LS_SLUG));

				$user_data_limited = ws_ls_get_weights(get_current_user_id(), 30);

				$output .= ws_ls_display_chart($user_data_limited);

				//$output .= ws_ls_display_week_filters($week_ranges, $selected_week_number);
			}
			else
			{
					$output .= "
									<blockquote><p>
							        	" . __("A pretty graph shall appear once you have recorded several weights.", WE_LS_SLUG) . "
							    	</p></blockquote>
								";
			}
			
			$output .= ws_ls_display_form();

			if (is_array($user_data) && (count($user_data) > 0 || $selected_week_number != -1))
			{
				$output .= ws_ls_title(__("Weight History", WE_LS_SLUG));

				$output .= ws_ls_display_week_filters($week_ranges, $selected_week_number);

				$output .= ws_ls_display_table($user_data);
			}
		}
		
		return $output;
		
	}

	function ws_ls_display_week_filters($week_ranges, $selected_week_number)
	{
		$output = "";

		if ($week_ranges != false && count($week_ranges > 1))
		{

			$output .=  "<form action=\"" .  get_permalink() . "\" method=\"post\">";
			$output .=  "<input type=\"hidden\" value=\"true\" name=\"week_filter\">";
			$output .=  "<div class=\"ws_ls_week_controls\">";

			$output .= "<select name=\"week_number\" onchange=\"this.form.submit()\">";

				$output .= "<option value=\"-1\" " . (($selected_week_number == -1) ?  " selected=\"selected\"" : "") . ">". __("View all weeks", WE_LS_SLUG) . "</option>";
				

			foreach ($week_ranges as $week) {

				$start_date = new DateTime($week["start"]);
				$start_date = $start_date->format("d/m/Y");

				$end_date = new DateTime($week["end"]);
				$end_date = $end_date->format("d/m/Y");

				$output .= "<option value=\"" . $week["number"] . "\" " . (($selected_week_number == $week["number"]) ? " selected=\"selected\"" : "") . ">

				" . __("View Week", WE_LS_SLUG) . " " . $week["number"] . " - " . $start_date . " " . __("to", WE_LS_SLUG) . " " . $end_date . "</option>";
				 
			}

			$output .= "</select>";


			$output .= "</div>";
			$output .= "</form>";
		}

		return $output;

	}


	function ws_ls_display_form()
	{
		wp_enqueue_script('jquery-ui-datepicker');
		
		wp_enqueue_style('jquery-style', plugins_url( 'css/jquery-ui.css', __FILE__ ));
		
		wp_enqueue_script(
			'jquery-validate',
			plugins_url( 'js/jquery.validate.min.js', __FILE__ )		
		);
		
		$form_class = (WE_LS_SUPPORT_AVADA_THEME) ? "avada-contact-form" :"ws_ls_display_form";
		
		$output = "";

		if (WE_LS_ALLOW_TARGET_WEIGHTS)
		{
			$output .= ws_ls_title(__("Target Weight", WE_LS_SLUG)) ;

			$output .= "<form action=\"" .  get_permalink() . "\" method=\"post\" class=\"" . $form_class .  "\" id=\"weight_form\">";

			$target_weight = ws_ls_get_user_target_for_display(get_current_user_id()) ;

			if ($target_weight != false)
				$output .="<p>" . __("Your current target is", WE_LS_SLUG) . " ". $target_weight . "</p>";
			else
				$output .="<p>" . __("Currently, you haven't specified a target weight.", WE_LS_SLUG) . "</p>";	

			$output .= "<div id=\"comment-input\">";
			
			if(WE_LS_IMPERIAL_WEIGHTS)
			{
				if (WE_LS_DATA_UNITS == "stones_pounds")
					$output .= "<input type=\"text\" name=\"target_weight_stones\" id=\"target_weight_stones\" value=\"\" placeholder=\"" . __("Stones", WE_LS_SLUG) . "\" size=\"11\" tabindex=\"2\" >";

				$output .= "<input type=\"text\" name=\"target_weight_pounds\" id=\"target_weight_pounds\" value=\"\" placeholder=\"" . __("Pounds", WE_LS_SLUG) . "\" size=\"11\" tabindex=\"3\" >";
			}
			else
				$output .= "<input type=\"text\" name=\"target_weight_weight\" id=\"target_weight_weight\" value=\"\" placeholder=\"" . __("Weight", WE_LS_SLUG) . " (" . __("Kg", WE_LS_SLUG) . ")\" size=\"22\" tabindex=\"2\">";
			
			$output .= "
			<div id=\"comment-submit-container\">
				<p>
					<div>
						<input name=\"target_button\" type=\"submit\" id=\"target_button\" tabindex=\"5\" value=\"" . __("Update Target", WE_LS_SLUG) . "\" class=\"comment-submit btn btn-default button default small fusion-button button-small button-default button-round button-flat\">
					</div>
				</p>
			</div></div>
			</form>";

		}

		

		$output .= ws_ls_title(__("Add a new weight", WE_LS_SLUG)) . "

		<form action=\"" .  get_permalink() . "\" method=\"post\" class=\"" . $form_class .  "\" id=\"weight_form\">
			<input type=\"hidden\" value=\"\" name=\"weight_user_id\">
			<div id=\"comment-input\">
				<input type=\"text\" name=\"weight_date\" id=\"weight_date\" value=\"" . date("Y-m-d") . "\" placeholder=\"" . date("Y-m-d") . "\" size=\"22\" tabindex=\"1\">";
			
			if(WE_LS_IMPERIAL_WEIGHTS)
			{
				if (WE_LS_DATA_UNITS == "stones_pounds")
					$output .= "<input type=\"text\" name=\"weight_stones\" id=\"weight_stones\" value=\"\" placeholder=\"" . __("Stones", WE_LS_SLUG) . "\" size=\"11\" tabindex=\"2\" >";

				$output .= "<input type=\"text\" name=\"weight_pounds\" id=\"weight_pounds\" value=\"\" placeholder=\"" . __("Pounds", WE_LS_SLUG) . "\" size=\"11\" tabindex=\"3\" >";
			}
			else
				$output .= "<input type=\"text\" name=\"weight_weight\" id=\"weight_weight\" value=\"\" placeholder=\"" . __("Weight", WE_LS_SLUG) . " (" . __("Kg", WE_LS_SLUG) . ")\" size=\"22\" tabindex=\"2\">";
				
			$output .= "</div>
			<div id=\"comment-textarea\">
				<textarea name=\"weight_notes\" id=\"weight_notes\" cols=\"39\" rows=\"4\" tabindex=\"4\" class=\"textarea-comment\" placeholder=\"" . __("Notes", WE_LS_SLUG) . "\"></textarea>
			</div>
			<div id=\"comment-submit-container\">
				<p>
					<div>
						<input name=\"submit_button\" type=\"submit\" id=\"submit_button\" tabindex=\"5\" value=\"" . __("Save Entry", WE_LS_SLUG) . "\" class=\"comment-submit btn btn-default button default small fusion-button button-small button-default button-round button-flat\">
					</div>
				</p>
			</div>
		</form>
		<script>
			jQuery(document).ready(function() {
		
				jQuery(\"#weight_form\").validate({
                rules: {
                    weight_date:  {
                        required: true,
                        dateISO: true
                    },";

                    if(WE_LS_IMPERIAL_WEIGHTS)
					{
						if (WE_LS_DATA_UNITS == "stones_pounds")
							 $output .= " weight_stones:  {
					                        required: true,
					                        number: true
					                    },";
				        
				        $output .= " weight_pounds:  {
				                        required: true,
				                        number: true
				                    },";

					}
					else
					{
						 $output .= " weight_weight:  {
				                        required: true,
				                        number: true
				                    }";
					}                 
                   
                $output .= "},
                messages: {
                    weight_weight: \"Please enter your weight\",
                  	weight_date: \"Please enter the date\",
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

			   jQuery('#weight_date').datepicker({
	                              changeMonth: true,
	                              changeYear: true,
	                                showButtonPanel: true,
	                                dateFormat: 'yy-mm-dd'
	                            });
		

			});
		</script>

		";

		return $output;

	}
	function ws_ls_get_unit()
	{

		switch (WE_LS_DATA_UNITS) {
			case 'pounds_only':
				$unit = __("lbs", WE_LS_SLUG); 
				break;
			case 'kg':
				$unit = __("Kg", WE_LS_SLUG); 
				break;
			default:
				$unit = __("St", WE_LS_SLUG) . " " . __("lbs", WE_LS_SLUG); 
				break;
		}

		return $unit;
	}
	function ws_ls_display_table($data)
	{
		$output = "";

		if (WE_LS_SUPPORT_AVADA_THEME) 
				$output .= "<div class=\"fusion-table table-2\">";

		$unit = ws_ls_get_unit();

		$output .= "<table  width=\"100%\">
		<thead>
		<tr>
			
			<th>" . __("Date", WE_LS_SLUG) ."</th>
			<th>" . __("Weight", WE_LS_SLUG) . " (" . $unit . ")</th>
			<th>" . __("Notes", WE_LS_SLUG) . "</th>
		</tr>
		</thead>
		<tbody>";
	
		foreach ($data as $row)
		    {

		    	switch (WE_LS_DATA_UNITS) {
					case 'pounds_only':
						$weight = $row->weight_only_pounds; 
						break;
					case 'kg':
						$weight = $row->weight_weight;
						break;
					default:
						$weight = $row->weight_stones . __("st", WE_LS_SLUG) . " " . $row->weight_pounds . __("lbs", WE_LS_SLUG);
						break;
				}

				$output .= "<tr>
						<td>" . $row->standard_date . "</td>
						<td>" . $weight . "</td>
						<td>" .  esc_html($row->weight_notes) . "</td>
					</tr>";

		}

		$output .= "<tbody></table>";

		if (WE_LS_SUPPORT_AVADA_THEME) 
			$output .= "</div>";

		return $output;
	}
	
	function ws_ls_display_chart($data)
	{
		$y_axis_unit = (WE_LS_IMPERIAL_WEIGHTS) ? "(" . __("lbs", WE_LS_SLUG) . ")" : "(" . __("Kg", WE_LS_SLUG) . ")";


 		$output = "<script src=\"". plugins_url( 'js/Chart.min.js', __FILE__ ) . "\" type=\"text/javascript\"></script>";

 		$output .= "<canvas id=\"myChart\" ></canvas>

 		<script>
		var ctx = document.getElementById('myChart').getContext('2d');

 		var data = {
		    labels: [";

	
		    for($i=0; $i<count($data); $i++)
		    {
		    	$output .= "'" . date_i18n( "j M", strtotime( $data[$i]->standard_date ) ) . "'";	

		    	if ($i != count($data) -1)
		    		$output .= ",";

		    }

		$output .= "], 
				datasets: [
		        {
		            label: 'Weight',
		            fillColor: 'rgba(220,220,220,0.2)',
		            strokeColor: 'rgba(220,220,220,1)',
		            pointColor: 'rgba(220,220,220,1)',
		            pointStrokeColor: '#fff',
		            pointHighlightFill: '#fff',
		            pointHighlightStroke: 'rgba(220,220,220,1)',
		            data: [";
   			
   			
		    for($i=0; $i<count($data); $i++)
		    {
		    	if(WE_LS_IMPERIAL_WEIGHTS)
		    	{
		    	
		    		$pounds = ($data[$i]->weight_stones * 14) + $data[$i]->weight_pounds;

		    		$output .= "'" . $pounds . "'";
		    	}
		    	else
		    		$output .= "'" . $data[$i]->weight_weight . "'";

		    	if ($i != count($data) -1)
		    		$output .= ",";

		    }

		$output .= "]
		        }";


		 $target_weight = ws_ls_get_user_target(get_current_user_id());

		 if ( $target_weight != false && WE_LS_ALLOW_TARGET_WEIGHTS)
		 {

		 	$output .= ",
		         {
		            label: 'Target',
		            fillColor: 'rgba(255,255,255,0.2)',
		            strokeColor: 'rgba(153,204,255,1)',
		            pointColor: 'rgba(153,204,255,1)',
		            pointStrokeColor: '#fff',
		            pointHighlightFill: '#fff',
		            pointHighlightStroke: 'rgba(220,220,220,1)',
		            data: [";
   			
   			


			    for($i=0; $i<count($data); $i++)
			    {
			    	if(WE_LS_IMPERIAL_WEIGHTS)
			    	{
			    	
			    		$pounds = ($target_weight->target_weight_stones * 14) + $target_weight->target_weight_pounds;

			    		$output .= "'" . $pounds . "'";
			    	}
			    	else
			    		$output .= "'" . $target_weight->target_weight_weight . "'";

			    	if ($i != count($data) -1)
			    		$output .= ",";

			    }

				$output .= "]
		        }";
		 }
		    $output .= "
		    ]
		};

		var options = 
		{

		    ///Boolean - Whether grid lines are shown across the chart
		    scaleShowGridLines : true,

		    //String - Colour of the grid lines
		    scaleGridLineColor : 'rgba(0,0,0,.05)',

		    //Number - Width of the grid lines
		    scaleGridLineWidth : 1,
			 scaleOverride: false,

			    // ** Required if scaleOverride is true **
			    // Number - The number of steps in a hard coded scale
			    scaleSteps: 14,
			    // Number - The value jump in the hard coded scale
			    scaleStepWidth: 10,
			    // Number - The scale starting value
			    scaleStartValue: 20,
		    //Boolean - Whether the line is curved between points
		    bezierCurve : true,

		    //Number - Tension of the bezier curve between points
		    bezierCurveTension : 0.4,

		    //Boolean - Whether to show a dot for each point
		    pointDot : false,

		    //Number - Radius of each point dot in pixels
		    pointDotRadius : 4,

		    //Number - Pixel width of point dot stroke
		    pointDotStrokeWidth : 1,

		    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
		    pointHitDetectionRadius : 20,

		    //Boolean - Whether to show a stroke for datasets
		    datasetStroke : true,

		    //Number - Pixel width of dataset stroke
		    datasetStrokeWidth : 2,

		    //Boolean - Whether to fill the dataset with a colour
		    datasetFill : true,

		    //String - A legend template
		    legendTemplate : '<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'


			,graphTitle : '',
			graphTitleFontFamily : 'Arial',
			graphTitleFontSize : 24,
			graphTitleFontStyle : 'bold',
			graphTitleFontColor : '#666',

			yAxisLabel : '" . __("Weight", WE_LS_SLUG) . " " . $y_axis_unit . "',
			yAxisFontFamily : 'Arial',
			yAxisFontSize : 16,
			yAxisFontStyle : 'normal',
			yAxisFontColor : '#666',
     		xAxisLabel : '". __("Date", WE_LS_SLUG) ."',
	 	  	xAxisFontFamily : 'Arial',
			xAxisFontSize : 13,
			xAxisFontStyle : 'normal',
			xAxisFontColor : '#666'
		};


 		var width = jQuery('#myChart').parent().width();
		jQuery('#myChart').attr(\"width\",width);
		new Chart(ctx).Line(data,options);
		window.onresize = function(event){
		    var width = jQuery('#myChart').parent().width();
		    jQuery('#myChart').attr(\"width\",width);
		    new Chart(ctx).Line(data,options);
		};

 		</script>


 		";


 		return $output;
	}

	function ws_ls_get_weights($user_id, $limit = 100, $selected_week_number = -1)
	{
		$additional_sql = "";

		if ($selected_week_number != -1)
		{
			$week_ranges = ws_ls_get_week_ranges();

			foreach ($week_ranges as $week) {
				 if ($week["number"] == $selected_week_number)
				 {
				 	$additional_sql = " and (weight_date BETWEEN '" . $week["start"] . "' AND '" . $week["end"] . "')";
				 }
			}
		}

		global $wpdb;

   		$table_name = $wpdb->prefix . WE_LS_TABLENAME;

   		$sql =  $wpdb->prepare("SELECT DATE_FORMAT(weight_date,'%%d %%b') as weight_date_formatted, weight_date, date(weight_date) as standard_date, weight_weight, weight_stones, weight_pounds, weight_only_pounds, weight_notes FROM $table_name where weight_user_id = %d " . $additional_sql. " order by weight_date limit 0, %d", $user_id,  $limit);

		$rows = $wpdb->get_results( $sql );

		return $rows;

	}
	function ws_ls_get_min_max_dates($user_id)
	{
		global $wpdb;

   		$table_name = $wpdb->prefix . WE_LS_TABLENAME;

   		$sql =  $wpdb->prepare("SELECT min(weight_date) as min_date, max(weight_date) as max_date FROM " . $table_name . " WHERE weight_user_id = %d", $user_id);

		$row = $wpdb->get_row($sql);

		if (!is_null($row))
			return $row;

		return false;
	}
	function ws_ls_get_week_ranges()
	{
		
		$entered_date_ranges = ws_ls_get_min_max_dates(get_current_user_id());

		if ($entered_date_ranges != false)
		{

			$start_date = new DateTime($entered_date_ranges->min_date);
			$end_date = new DateTime($entered_date_ranges->max_date);

			$interval = new DateInterval('P1W');
			$daterange = new DatePeriod($start_date, $interval ,$end_date);

			$date_ranges = array();

			$i = 1;

			foreach($daterange as $date){

				$end_of_week = clone $date;
				$end_of_week = date_modify($end_of_week, '+1 week' );
			
			    array_push($date_ranges, array("number" => $i, "start" => $date->format("Y-m-d"), "end" => $end_of_week->format("Y-m-d") ));

			    $i++;
			}

			return $date_ranges;
		}

		return false;
	}

	function ws_ls_get_user_target_for_display($user_id)
	{
		global $wpdb;

   		$table_name = $wpdb->prefix . WE_LS_TARGETS_TABLENAME;

   		$sql = $wpdb->prepare("SELECT target_weight_weight, target_weight_stones, target_weight_pounds, target_weight_only_pounds FROM $table_name where weight_user_id = %d ", $user_id);

		$row = $wpdb->get_row( $sql );

		if (!is_null($row))
		{
			
	    	switch (WE_LS_DATA_UNITS) {
				case 'pounds_only':
					$weight = $row->target_weight_only_pounds .  __("lbs", WE_LS_SLUG); 
					break;
				case 'kg':
					$weight = $row->target_weight_weight .  __("Kg", WE_LS_SLUG);
					break;
				default:
					$weight = $row->target_weight_stones . __("St", WE_LS_SLUG) . " " . $row->target_weight_pounds . __("lbs", WE_LS_SLUG);
					break;
			}
		
			return $weight;
		}

	
		return false;

	}

	function ws_ls_get_user_target($user_id)
	{
		global $wpdb;

   		$table_name = $wpdb->prefix . WE_LS_TARGETS_TABLENAME;

   		$sql = $wpdb->prepare("SELECT target_weight_weight, target_weight_stones, target_weight_pounds, target_weight_only_pounds FROM $table_name where weight_user_id = %d ", $user_id);

		$row = $wpdb->get_row( $sql );

		if (!is_null($row))
			return $row;
		
		return false;
		
	}

	function ws_date_exists($user_id, $date)
	{
		global $wpdb;

   		$table_name = $wpdb->prefix . WE_LS_TABLENAME;

   		$sql =  $wpdb->prepare("SELECT count(*) as iCount FROM " . $table_name . " WHERE weight_date = %s and weight_user_id = %d", $date, $user_id);

		$rows = $wpdb->get_row($sql);

		if ($rows->iCount == 0)
			return false;
		else
			return true;
	}
	function ws_target_exists($user_id)
	{
		global $wpdb;

   		$table_name = $wpdb->prefix . WE_LS_TARGETS_TABLENAME;

   		$sql =  $wpdb->prepare("SELECT count(*) as iCount FROM " . $table_name . " WHERE weight_user_id = %d", $user_id);

		$rows = $wpdb->get_row($sql);

		if ($rows->iCount == 0)
			return false;
		else
			return true;
	}

	function ws_ls_save_data()
	{

		if ( $_POST && !isset($_POST["week_filter"]))
		{ 
			global $wpdb;
		
			$save_target = false;
			$field_prefix = "";

			if(array_key_exists ("target_button", $_POST))
			{
				$save_target = true;
				$field_prefix = "target_";
			}
				
			
			$values = stripslashes_deep($_POST);

			if ($save_target)
				unset($values["target_button"]);
			else
				unset($values["submit_button"]);
	
			$values["weight_user_id"] = get_current_user_id();

			// Convert stones / lbs to Kg
 			if(WE_LS_IMPERIAL_WEIGHTS)
 			{
				if (WE_LS_DATA_UNITS == "stones_pounds")
				{
					$values[$field_prefix . "weight_weight"] =  ws_ls_to_kg($values[$field_prefix . "weight_stones"], $values[$field_prefix . "weight_pounds"] );
					$values[$field_prefix . "weight_only_pounds"] = ($values[$field_prefix . "weight_stones"] * 14) + $values[$field_prefix . "weight_pounds"];
				}
				else
				{
					$values[$field_prefix . "weight_weight"] =  ws_ls_pounds_to_kg($values[$field_prefix . "weight_pounds"]);
					$values[$field_prefix . "weight_only_pounds"] = $values[$field_prefix . "weight_pounds"];

					$weight_data = ws_ls_pounds_to_stone_pounds($values[$field_prefix . "weight_pounds"]);

					$values[$field_prefix . "weight_stones"] = $weight_data["Stones"];
					$values[$field_prefix . "weight_pounds"] =  $weight_data["Pounds"];
				}
			}
			else // Convert Kg to Stones / Lbs
			{
				$weight_data = ws_ls_to_stone_pounds($values[$field_prefix . "weight_weight"]);
				$values[$field_prefix . "weight_stones"] = $weight_data["Stones"];
				$values[$field_prefix . "weight_pounds"] =  $weight_data["Pounds"];
				$values[$field_prefix . "weight_only_pounds"] = ($values[$field_prefix . "weight_stones"] * 14) + $values[$field_prefix . "weight_pounds"];
			}

			if ($save_target)
			{
				$table_name = $wpdb->prefix . WE_LS_TARGETS_TABLENAME;

				if (!ws_target_exists($values["weight_user_id"]))
				{  
					$wpdb->insert( 
						$table_name, 
						$values
					);
				}
				else
				{
					$sql = $wpdb->prepare("Update " . $table_name. " Set target_weight_weight = %f, target_weight_stones = %f, target_weight_pounds = %f, target_weight_only_pounds = %f where weight_user_id = %d", $values["target_weight_weight"], $values["target_weight_stones"], $values["target_weight_pounds"], $values["target_weight_only_pounds"], $values["weight_user_id"]);
			
					$wpdb->query($sql);
				}	
			}
			else
			{	
				$table_name = $wpdb->prefix . WE_LS_TABLENAME;

				if (!ws_date_exists($values["weight_user_id"], $values["weight_date"]))
				{
		
					$wpdb->insert( 
						$table_name, 
						$values
					);

					return true;
				}
				else
				{
					if(!WE_LS_IMPERIAL_WEIGHTS)
						$sql = $wpdb->prepare("Update " . $table_name. " Set weight_notes = %s, weight_weight = %f, weight_stones = %f, weight_pounds = %f, weight_only_pounds = %f where weight_user_id = %d and weight_date = %s", $values['weight_notes'], $values["weight_weight"], $values["weight_stones"], $values["weight_pounds"], $values["weight_only_pounds"], $values["weight_user_id"], $values["weight_date"]);
					else
						$sql = $wpdb->prepare("Update " . $table_name. " Set weight_notes = %s, weight_weight = %f, weight_stones = %f, weight_pounds = %f, weight_only_pounds = %f where weight_user_id = %d and weight_date = %s", $values['weight_notes'], $values["weight_weight"], $values["weight_stones"], $values["weight_pounds"], $values["weight_only_pounds"], $values["weight_user_id"], $values["weight_date"]);
			
					$wpdb->query($sql);
				
					return true;
				}
			}
		}
	
		return false;

	}
	

function ws_ls_register_shortcodes()
{ 	/*
		[weightloss_weight_difference] - total weight lost by the logged in member
		[weightloss_weight_start] - start weight of the logged in member
		[weightloss_weight_most_recent] - end weight of the logged in member
		[weightloss_weight_difference_from_target] - difference between latest and target
	*/

 	add_shortcode( 'weightlosstracker', 'ws_ls_shortcode' );
 	add_shortcode( 'weightloss_weight_difference', 'ws_ls_weight_difference' );
 	add_shortcode( 'weightloss_weight_start', 'ws_ls_weight_start' );
 	add_shortcode( 'weightloss_weight_most_recent', 'ws_ls_weight_recent' );
	add_shortcode( 'weightloss_weight_difference_from_target', 'ws_ls_weight_difference_target' );

}

function ws_ls_weight_start()
{
	if (WE_LS_DATA_UNITS == "pounds_only")
		$weight = ws_ls_get_start_weight_in_pounds();
	else
		$weight = ws_ls_get_weight_extreme(get_current_user_id());

	return we_ls_format_weight_into_correct_string_format($weight);
}
function ws_ls_weight_recent()
{
	if (WE_LS_DATA_UNITS == "pounds_only")
		$weight =  ws_ls_get_recent_weight_in_pounds();
	else
		$weight =  ws_ls_get_weight_extreme(get_current_user_id(), true);

	return we_ls_format_weight_into_correct_string_format($weight);
}
function ws_ls_weight_difference()
{
	if (WE_LS_DATA_UNITS == "pounds_only")
	{
		$start_weight = ws_ls_get_start_weight_in_pounds();
		$recent_weight = ws_ls_get_recent_weight_in_pounds();
	}
	else
	{
		$start_weight = ws_ls_get_start_weight_in_kg();
		$recent_weight = ws_ls_get_weight_extreme(get_current_user_id(), true);
	}
	
	$difference = $recent_weight - $start_weight;

	$display_string = ($difference > 0) ? "+" : ""; 

	$display_string .= we_ls_format_weight_into_correct_string_format($difference);

	return $display_string;
}
function ws_ls_weight_difference_target()
{ 
	if (WE_LS_DATA_UNITS == "pounds_only")
	{
		$target_weight = ws_ls_get_target_weight_in_pounds();
		$recent_weight = ws_ls_get_recent_weight_in_pounds();
	}
	else
	{
		$target_weight = ws_ls_get_target_weight_in_kg();
		$recent_weight = ws_ls_get_weight_extreme(get_current_user_id(), true);
	}
	$difference = $recent_weight - $target_weight;
	
	$display_string = ($difference > 0) ? "+" : ""; 

	$display_string .= we_ls_format_weight_into_correct_string_format($difference);

	return $display_string;
}

function ws_ls_get_start_weight_in_kg()
{
	return ws_ls_get_weight_extreme(get_current_user_id());
}
function ws_ls_get_recent_weight_in_kg()
{
	return ws_ls_get_weight_extreme(get_current_user_id(), true);
}
function ws_ls_get_start_weight_in_pounds()
{
	return ws_ls_get_weight_extreme(get_current_user_id(), false, "weight_only_pounds");
}
function ws_ls_get_recent_weight_in_pounds()
{
	return ws_ls_get_weight_extreme(get_current_user_id(), true, "weight_only_pounds");
}

function ws_ls_get_weight_extreme($user_id, $recent = false, $unit = "weight_weight")
{
	global $wpdb;

	$direction = "asc";

	if ($recent)
		$direction = "desc";

	$table_name = $wpdb->prefix . WE_LS_TABLENAME;

	$sql =  $wpdb->prepare("SELECT " . $unit . " as weight_value FROM $table_name where weight_user_id = %d order by weight_date " . $direction . " limit 0, %d", $user_id, 1);

	$rows = $wpdb->get_row($sql);

	if (count($rows) > 0)
		return $rows->weight_value;
	else
		return false;

}
function ws_ls_get_target_weight_in_kg()
{
	return ws_ls_get_weight_target(get_current_user_id());
}
function ws_ls_get_target_weight_in_pounds()
{
	return ws_ls_get_weight_target(get_current_user_id(), "target_weight_only_pounds");
}
function ws_ls_get_weight_target($user_id, $unit = "target_weight_weight")
{
	global $wpdb;

	$table_name = $wpdb->prefix . WE_LS_TARGETS_TABLENAME;

	$sql =  $wpdb->prepare("SELECT " . $unit . " as weight_value FROM $table_name where weight_user_id = %d", $user_id);

	$rows = $wpdb->get_row($sql);

	if (count($rows) > 0)
		return $rows->weight_value;
	else
		return false;

}
function we_ls_format_weight_into_correct_string_format($weight)
{
	if(WE_LS_IMPERIAL_WEIGHTS)
	{
		if (WE_LS_DATA_UNITS == "pounds_only")
			return $weight . __("lbs", WE_LS_SLUG);
		else
		{
			$weight_data = ws_ls_to_stone_pounds($weight);
			return $weight_data["Stones"] . __("st", WE_LS_SLUG) . " " . (($weight_data["Pounds"] < 0) ? abs($weight_data["Pounds"]) : $weight_data["Pounds"]) . __("lbs", WE_LS_SLUG);
		}
			
	}
	else
		return $weight . __("Kg", WE_LS_SLUG);
}
function ws_ls_title($text)
{
	if(WE_LS_SUPPORT_AVADA_THEME)
		return "<div class=\"fusion-title title\"><h2 class=\"title-heading-left\">" . $text . "</h2><div class=\"title-sep-container\"><div class=\"title-sep sep-single sep-dashed\"></div></div></div>";
	else
		return "<h2 class=\"ws_ls_title\">" . $text . "</h2>";

}

function ws_ls_to_kg($stones, $pounds)
{
	$pounds += $stones * 14;
	
	return round($pounds / 2.20462, 2);
}
function ws_ls_pounds_to_kg($pounds)
{
	return round($pounds / 2.20462, 2);
}

function ws_ls_to_lb($kg)
{
	$pounds = $kg * 2.20462;
	
	return round($pounds, 2);
}

function ws_ls_to_stones($pounds)
{
	$pounds = $pounds / 14;
	
	return round($pounds, 2);
}

function ws_ls_pounds_to_stone_pounds($lb)
{

	$weight = array ("Stones" => 0, "Pounds" => 0);

    $weight["Stones"] = floor($lb / 14);
     $weight["Pounds"] = Round(fmod($lb, 14), 1);

    return $weight;
}

function ws_ls_to_stone_pounds($kg)
{

	$weight = array ("Stones" => 0, "Pounds" => 0);

    $totalPounds = Round($kg * 2.20462, 3);

    $weight["Stones"] = floor($totalPounds / 14);
    $weight["Pounds"] = Round(fmod($totalPounds, 14), 1);

    return $weight;
}


?>