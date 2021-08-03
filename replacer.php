 <?php 
/*
Plugin Name: Replacer
Description: Changes the selected words to new meanings
Version: 1.0.0
Author: Vasimovich
Text Domain: replacer
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 

define( 'REPLACER_VERSION', '1.0.0' );
define( 'REPLACER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'REPLACER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( REPLACER_PLUGIN_DIR . 'class.replacer.php' );
$Replacer = Replacer_Plugin();


add_action( 'init', 'Replacer_Plugin' );


function Replacer_Plugin() {
	return Replacer_Plugin::specimen();
}

register_activation_hook( __FILE__, [ $Replacer, 'plugin_activation' ] );
register_deactivation_hook( __FILE__, [ $Replacer, 'plugin_deactivation' ] );
register_uninstall_hook( __FILE__, [ 'Replacer', 'plugin_uninstall' ] );


add_action( 'wp_ajax_replacer_valid', 'replacer_valid' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_replacer_valid', 'replacer_valid' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}
// первый хук для авторизованных, второй для не авторизованных пользователей
 
function replacer_valid(){
 
	$replacer_values = array_filter(explode(", ", $_POST['replacer_values']));
	
	$result = array(
		'result_check' => true,
		'result_message' => __('Data entered correctly', 'replacer'),
	);

	foreach ($replacer_values as $replacer_values){
			if(preg_match('/^\p{Latin}+$/', trim($replacer_values)) == 0){
				$result = array(
					'result_check' => false,
					'result_message' => __('Data entered incorrectly', 'replacer'),
				);

				wp_send_json( $result );
			}
	}

	wp_send_json( $result );
}