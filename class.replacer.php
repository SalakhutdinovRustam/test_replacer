<?php
/**
 * Class Replacer
 *
 * @package Replacer
 * @since 1.0.1
 */

if ( ! class_exists( 'Replacer_Plugin' ) ) :

	class Replacer_Plugin {

		private static $specimen;

	

		private function __construct() {}

		public static function specimen() {
			if ( is_null( self::$specimen ) ) {
				self::$specimen = new Replacer_Plugin;
				self::$specimen->init();
			}

			return self::$specimen;
		}

		protected function init() {
	
			add_action( 'admin_menu', [ $this, 'add_admin_menu_page' ] );
		 	add_action( 'admin_init', [ $this, 'replacer_api_settings_init' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'replacer_front_enqueue' ] );
		
			
			add_filter( 'the_content', [ $this, 'filter_replace_frontline' ] ); 
		}

		
		public function add_admin_menu_page() {
			add_menu_page(
				__( 'Replacer', 'replacer' ),
				__( 'Replacer menu', 'replacer' ),
				'manage_options',
				'replacer',
				[ $this, 'admin_menu_page' ],
				'dashicons-image-filter',
				86
			);
		}

		public function admin_menu_page() {
			?>
            <div class="wrap">
                <h2><?php echo get_admin_page_title(); ?></h2>
            </div>

            <form action="options.php" method="POST" id="replace_option">
				<?php settings_fields('replacer' ); ?>
				<?php do_settings_sections( 'replacer' ); ?>
				<?php submit_button( __( 'Save', 'replacer' ) ); ?>
            </form>
			<?php
		}

		public function replacer_api_settings_init( ) {
				register_setting( 'replacer', 'replacer_api_settings' );
				add_settings_section(
					'fields_section',
					__( 'Fields', 'replacer' ),
					array($this, 'section_callback'),
					'replacer'
			);


				add_settings_field(
					'input_field',
					__( 'Input', 'replacer' ),
					array($this, 'input_field_render'),
					'replacer',
					'fields_section'
			);

			add_settings_field(
					'output_field',
					__( 'Output', 'replacer' ),
					array($this, 'output_field_render'),
					'replacer',
					'fields_section'
			);
		
		}

	public	function input_field_render( ) {
			$options = get_option( 'replacer_api_settings' );
			?>
			<input type='text' id="replacer_input" name='replacer_api_settings[input_field]' value='<?php echo $options['input_field']; ?>'>
			<?php
	}
	
	public	function output_field_render( ) {
				$options = get_option( 'replacer_api_settings' );
				?>
				<input type='text' id="replacer_output" name='replacer_api_settings[output_field]' value='<?php echo $options['output_field']; ?>'>
				<?php
		}
		public function section_callback() {
		
				echo __( 'Enter data separated by comma and space', 'replacer' );
		}
		function filter_replace_frontline( $content ) {
			$options = get_option('replacer_api_settings');	
		
			if ( ! empty( $content )) {
				
				$inputs  = $options['input_field'];
				$outputs = $options['output_field'];


				$input_explode = explode(", ", $inputs);
				$output_explode = explode(", ", $outputs);

			
				shuffle($input_explode);
				shuffle($output_explode);
	
				return	str_ireplace($input_explode, $output_explode, $content);
		

			}
		}	
		
		public function replacer_front_enqueue() {
			wp_enqueue_script( 'replacer-front-js', REPLACER_PLUGIN_URL . 'assets/js/script.js', [ 'jquery' ], false, true );
		
		}

}


endif; 
