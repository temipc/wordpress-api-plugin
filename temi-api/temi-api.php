<? header("Access-Control-Allow-Origin: *"); ?>
<?php
/**
 * Plugin name: TEMI Api Database
 * Plugin URI: https://temipc.hu
 * Description: Get information from external APIs in WP and view frontend and backand with shortcode.
 * Author: Laszlo Temesvari
 * Author URI: https://temipc.hu
 * version: 1.0
 * License: GPL+2
 * text-domain: temi-api
 */
defined( 'ABSPATH' ) or die( 'Unauthorized Access' );

add_action('admin_head', 'temi_style'); 
/*plugin style, frontend and backend*/
function temi_style() {
  echo '<style>
   .full_name{
	margin-top: 10px;
	margin-bottom: 10px;
	padding: 5px;
	background-color: #1F52F9;
	color: #fff;
	border-radius: 10px;
	}
	.email_info{
		position:relative;
		padding: 5px;
		background-color: #FF1700;
		color: #fff;
		border-radius: 10px;
	}
	.registred_date{
		margin-top: 10px;
		margin-bottom: 10px;
		padding: 5px;
		background-color: #00B175;
		color: #fff;
		border-radius: 10px;	
	}
	.display_name{
		margin-top: 10px;
		margin-bottom: 10px;
		padding: 5px;
		background-color: #00707E;
		color: #fff;
		border-radius: 10px;
	}
	.description_user{
		margin-top: 10px;
		margin-bottom: 10px;
		padding-top: 10px;
		padding-bottom: 10px !important;
	}
  </style>';
}
/*Backend in admin first menu temi API Data Source*/
function temi_get_send_data() {
	
	echo '<h2>TEMI API Data Source</h2>';
	
	echo '<p><i>The source is my website on with example data: </p><a href="https://temipc.hu/demo/json/" target="_blank">https://temipc.hu/demo/json/</a></i></p>';
    
	$url = 'https://temipc.hu/demo/json/';
    
    $arguments = array(
        'method' => 'GET'
    );

	$response = wp_remote_get( $url, $arguments );
	/*Error Handle*/
	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		return "Something went wrong: $error_message";
	} else {
		echo '<pre>';
		var_dump( wp_remote_retrieve_body( $response ) );
		echo '</pre>';
	}
}	
/*Created admin menu*/
function temi_api_test_menu_page() {
	add_menu_page(__( 'TEMI API Test Settings', 'temi-api' ),'TEMI API Data Source','manage_options','temi-api-source','temi_get_send_data','dashicons-testimonial',16);
	add_submenu_page('temi-api-source', __('TEMI API Table Viewer', 'api-viewer'), __('TEMI API Table Viewer', 'api-viewer'), 'manage_options', 'temi_api_viewer', 'temi_api_viewer');
	add_submenu_page('temi-api-source', __('TEMI API Shortcode', 'api-shortcode'), __('TEMI API Shortcode', 'api-shortcode'), 'manage_options', 'temi_api_shortcode', 'temi_api_shortcode');
	add_submenu_page('temi-api-source', __('TEMI API License', 'api-license'), __('TEMI API License', 'api-license'), 'manage_options', 'temi_api_license', 'temi_api_license');

}
/*shortcode that easy to use*/
add_shortcode('temi_viewer', 'temi_api_viewer');
/*Backend and Frontend table view api data with HTML table*/
function temi_api_viewer(){
	
		/*I was created last day example json datas, that you and i can test and use this plugin.*/
		$response = wp_remote_get( 'https://temipc.hu/demo/json/' );
		$array = json_decode(wp_remote_retrieve_body( $response ), true);
		
		/*Error Handle*/
		if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		return "Something went wrong: $error_message";
		} else {
			
			/*View frontend*/
			if( !is_admin() ) {
				echo '<div><center>';
				foreach( $array as $value ) {
				   echo '<table class="table_shadow"><thead><tr><th  rowspan="4">'.get_avatar( $user->ID, 90 ).'</th><th><h2>ID: ';
				   echo $value['id'].'</h2></th><th colspan="4"><h2 class="full_name">'.
						$value['name']. '</h2></th></tr><tr><th rowspan="3">Custom data:</th><th colspan="4"><p><span class="email_info">' .
						$value['email'] . '</span></p></th></tr><tr><th colspan="4"><p><span class="registred_date">'.
						$value['phone'].'</span></p></th></tr><tr><th colspan="4"><p><span class="display_name"> '.
						$value['username'].'</span><p></th></tr></thead><tbody><tr>';

					echo '<td colspan="5"><p class="description_user">' . 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum' . '</p>';
					echo '<p>'.$value['address']['zipcode'].', '.
							$value['address']['city'].', '.
							$value['address']['street'].' '.
							$value['address']['suite'].'<br/>
							<a href="'.$value['id'].'">Read More</a></p>';
					echo '</td></tr></tbody></table><hr/>';
					
				}
				echo '</center></div>';
			}
			/*View in admin - backend*/
			if( is_admin() ) {
			echo '<h2>TEMI API Viewer</h2>';
			echo '<div><center>';
			   foreach( $array as $value ) {
				   echo '<table style="width:80%; border: 1px solid black;border-collapse: collapse;"><thead><tr><th  rowspan="4">'.get_avatar( $user->ID, 90 ).'</th><th><h2>ID: ';
				   echo $value['id'].'</h2></th><th colspan="4"><h2 class="full_name">'.
						$value['name']. '</h2></th></tr><tr><th rowspan="3">Custom data:</th><th colspan="4"><p><span class="email_info">' .
						$value['email'] . '</span></p></th></tr><tr><th colspan="4"><p><span class="registred_date">'.
						$value['phone'].'</span></p></th></tr><tr><th colspan="4"><p><span class="display_name"> '.
						$value['username'].'</span><p></th></tr></thead><tbody><tr>';
					
					echo '<td colspan="5"><p class="description_user">' . 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum' . '</p>';
					echo '<p>'.$value['address']['zipcode'].', '.
							$value['address']['city'].', '.
							$value['address']['street'].' '.
							$value['address']['suite'].'<br/>
							<a href="' . $value->link . '">Read More</a></p>';
					echo '</td></tr></tbody></table><hr/>';	
				}	
			echo '</center></div>';
			}
		}
	}		 
	add_action( 'admin_menu', 'temi_api_test_menu_page' );
	
	/*In TEMI API Shortcode menu, easy to use copy function.*/
	function temi_api_shortcode(){
		echo '<h2>TEMI API Shortcode</h2>';
		echo 'Short code here: ';
		?>
		<input type="text" value="[temi_viewer]" id="copy_shortcode" disabled>

		<button onclick="copy_shortcode()">Copy Shortcode</button>
		<script>
			function copy_shortcode() {
			  var copyText = document.getElementById("copy_shortcode");
			  copyText.select();
			  copyText.setSelectionRange(0, 99999); /* For mobile devices */
			  navigator.clipboard.writeText(copyText.value);
			  alert("Copied the text: " + copyText.value);
			}
		</script>
		<?php
	}

	function temi_api_license(){
		echo '<h2>TEMI API License</h2>';
		echo '<h3>Use the full version!</h3><p>Please add the own api key:';
		?>
		<input type="text" value="" id="temi-api-license" /></p>
		<button onclick="save_own_api()">Save Api Key</button>
		<?php		
	}
	
?>
