<?php
/**
 * Main One Click Demo Import plugin class/file.
 * 
 * Modified by ThemeSphere.
 *
 * @package ocdi
 */

namespace OCDI;

/**
 * One Click Demo Import class, so we don't have to worry about namespaces.
 */
class OneClickDemoImport {
	/**
	 * The instance *Singleton* of this class
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * The instance of the OCDI\Importer class.
	 *
	 * @var object
	 */
	public $importer;

	/**
	 * The resulting page's hook_suffix, or false if the user does not have the capability required.
	 *
	 * @var boolean or string
	 */
	private $plugin_page;

	/**
	 * Holds the verified import files.
	 *
	 * @var array
	 */
	public $import_files;

	/**
	 * The path of the log file.
	 *
	 * @var string
	 */
	public $log_file_path;

	/**
	 * The index of the `import_files` array (which import files was selected).
	 *
	 * @var int
	 */
	private $selected_index;

	/**
	 * The paths of the actual import files to be used in the import.
	 *
	 * @var array
	 */
	private $selected_import_files;

	/**
	 * Holds any error messages, that should be printed out at the end of the import.
	 *
	 * @var string
	 */
	public $frontend_error_messages = array();

	/**
	 * Was the before content import already triggered?
	 *
	 * @var boolean
	 */
	private $before_import_executed = false;

  /**
   * Make plugin page options available to other methods.
   *
   * @var array
   */
  private $plugin_page_setup = array();

  /**
   * Bunyad Import Type. Full or partial.
   *
   * @var string
   */
  public $import_type;
  protected $admin_page;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return OneClickDemoImport the *Singleton* instance.
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	/**
	 * Class construct function, to initiate the plugin.
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct() {
		// Actions
		add_action( 'after_setup_theme', array( $this, 'setup_plugin_with_filter_data' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		
		// +EDIT: Bunyad
		
		// Import AJAX handler
		add_action('wp_ajax_bunyad_import_demo', array($this, 'import'));
		
		// Register scripts
		add_action('admin_enqueue_scripts', array( $this, 'register_assets'));
		
		// Add Menu page
		add_action('admin_menu', array($this, 'menu_setup'));
	}


	/**
	 * Private clone method to prevent cloning of the instance of the *Singleton* instance.
	 *
	 * @return void
	 */
	private function __clone() {}


	/**
	 * Private unserialize method to prevent unserializing of the *Singleton* instance.
	 *
	 * @return void
	 */
	public function __wakeup() {}

	/**
	 * Enqueue admin scripts (JS and CSS)
	 *
	 * @param string $hook holds info on which admin page you are currently loading.
	 */
	public function register_assets($hook) {

		// Enqueue the scripts only on the plugin page.
		if ($this->admin_page === $hook) {
			wp_enqueue_script('bunyad-import', PT_OCDI_URL . 'assets/js/main.js', array('jquery'), PT_OCDI_VERSION);

			wp_localize_script('bunyad-import', 'Bunyad_Import',
				array(
					'ajax_url'     => admin_url('admin-ajax.php'),
					'ajax_nonce'   => wp_create_nonce('ocdi-ajax-verification'),
				)
			);
			
			wp_enqueue_style('bunyad-import-css', PT_OCDI_URL . 'assets/css/main.css', array(), PT_OCDI_VERSION);
		}
	}

	/**
	 * Add the menu option
	 */
	public function menu_setup()
	{
		$this->admin_page = add_submenu_page(
			'themes.php', esc_html__('Demo Import', 'pt-ocdi'), esc_html__('Import Demos', 'pt-ocdi'), 'import', 'bunyad-demo-import', array($this, 'admin_page')
		);
	}

	/**
	 * Admin page output - can be overridden by the theme using bunyad_import_admin_page hook
	 */
	public function admin_page()
	{
		ob_start();
		do_action('bunyad_import_admin_page');
		$content = ob_get_clean();
		
		if (!empty($content)) {
			echo $content;
			return;
		}

		// Should be in admin_head ideally.
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_style('wp-jquery-ui-dialog');

		require_once PT_OCDI_PATH . 'views/plugin-page.php';
	}

	/**
	 * Main AJAX callback function for:
	 * 1). prepare import files (uploaded or predefined via filters)
	 * 2). execute 'before content import' actions (before import WP action)
	 * 3). import content
	 * 4). execute 'after content import' actions (before widget import WP action, widget import, customizer import, after import WP action)
	 */
	public function import() 
	{
		// This may run multiple times due to AJAX. Use 'bunyad_import_begin' filter for single
		// run only at beginning.
		do_action('bunyad_import_pre_import');

		// Try to update PHP memory limit (so that it does not run out of it).
		@ini_set( 'memory_limit', apply_filters( 'pt-ocdi/import_memory_limit', '350M' ) );

		// DEBUG INFO: To debug and log proper messages in the log file, such as "DOM support is not enabled", make sure to enable showing errors:
		// ini_set('display_errors', 'on');

		// Check for XMLReader availability or a FATAL error maybe caused.
		if (!class_exists('XMLReader')) {
			ob_start();
			?>
			<div class="notice error">
				<p>  
				<?php 
					echo esc_html('Missing XMLReader. Installed PHP is missing an essential feature for any importer to work. Please contact your webhost to enable xmlreader in PHP.', 'pt-ocdi'); ?>
				</p>
			</div>
			<?php

			wp_send_json(array('message' => ob_get_clean()));
			return;
		}

		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();

		// Is this a new AJAX call to continue the previous import?
		$use_existing_importer_data = $this->use_existing_importer_data();

		if (!$use_existing_importer_data) {

			// Will only fire on the first AJAX call.
			do_action('bunyad_import_begin', $this->selected_index, $this->importer, $this);

			// Create a date and time string to use for demo and log file names.
			Helpers::set_demo_import_start_time();

			// Define log file path.
			$this->log_file_path = Helpers::get_log_path();

			// Get selected file index or set it to 0.
			$this->selected_index = empty($_POST['demo_id']) ? 0 : $_POST['demo_id'];
			$this->import_type = $_POST['import_type'];

			// Download the import files (content, widgets and customizer files).
			$this->selected_import_files = Helpers::download_import_files( $this->import_files[ $this->selected_index ] );

			// Check Errors.
			if ( is_wp_error( $this->selected_import_files ) ) {
				// Write error to log file and send an AJAX response with the error.
				Helpers::log_error_and_send_ajax_response(
					$this->selected_import_files->get_error_message(),
					$this->log_file_path,
					esc_html__( 'Downloaded files', 'pt-ocdi' )
				);
			}

			// Add this message to log file.
			$log_added = Helpers::append_to_file(
				sprintf(
					__( 'The import files for: %s were successfully downloaded!', 'pt-ocdi' ),
					$this->import_files[ $this->selected_index ]['demo_name']
				) . Helpers::import_file_info( $this->selected_import_files ),
				$this->log_file_path,
				esc_html__( 'Downloaded files' , 'pt-ocdi' )
			);
		}

		// Save the initial import data as a transient, so other import parts (in new AJAX calls) can use that data.
		Helpers::set_ocdi_import_data_transient( $this->get_current_importer_data() );

		if ( ! $this->before_import_executed ) {
			$this->before_import_executed = true;

			/**
			 * 2). Execute the actions hooked to the 'pt-ocdi/before_content_import_execution' action:
			 *
			 * Default actions:
			 * 1 - Before content import WP action (with priority 10).
			 */
			do_action( 'pt-ocdi/before_content_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index );
		}

		/**
		 * Import content
		 */
		if ($this->import_type == 'full') {
			
			// Import content
			$this->append_to_frontend_error_messages( 
				$this->importer->import_content($this->selected_import_files['content'])
			);
		}
		else {
			unset(
				$this->selected_import_files['widgets'],
				$this->selected_import_files['content']
			);
		}

		/**
		 * Import customizer settings. Will call the hooks set in ImportActions.php
		 */
		do_action('pt-ocdi/customizer_import_execution', $this->selected_import_files);

		/**
		 * Execute the actions hooked to the 'pt-ocdi/after_content_import_execution' action:
		 *
		 * Default actions:
		 * 1 - Before widgets import setup (with priority 10).
		 * 2 - Import widgets (with priority 20).
		 * 3 - Import Redux data (with priority 30).
		 */
		do_action('pt-ocdi/after_content_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index);

		// Save the import data as a transient, so other import parts (in new AJAX calls) can use that data.
		Helpers::set_ocdi_import_data_transient( $this->get_current_importer_data() );

		// Display final messages (success or error messages).

		$response['message'] = '<div class="notice notice-success"><p>All done! Please deactivate and delete the "Bunyad Demo Import" plugin now.</p></div>';
		if ($this->import_type === 'full') {
			
			ob_start();
			?>
			
			<div class="notice notice-success">
				<p><?php echo esc_html__('Import is successful! Just two more steps:', 'pt-ocdi'); ?></p>
				<ol>
					<li><a href="<?php echo admin_url('tools.php?page=regenerate-thumbnails'); ?>" target="_blank"><?php echo esc_html__('Run Re-generate Thumbnails.', 'pt-ocdi'); ?></a></li>
					<li>Once all thumbnails are regenerated, "Bunyad Demo Import" and "Regenerate Thumbnails" plugins aren't needed anymore. De-activate and remove them.</li>
				</ol>
			</div>
			
			<?php
			$response['message'] = apply_filters('bunyad_import_success_message', ob_get_clean());
		}

		if (!empty($this->frontend_error_messages)) {
			$response['message'] .= sprintf(
				__( '%1$sIf you wish to inspect the import log, you can find it in this %2$slog file%3$s %4$s', 'pt-ocdi' ),
				'<div class="notice"><p>',
				'<a href="' . Helpers::get_log_url( $this->log_file_path ) .'" target="_blank">',
				'</a>',
				'</p></div>'
			);
		}

		do_action('bunyad_import_done', $this->selected_index, $this->importer, $this);
		
		// Delete importer data transient for current import.
		delete_transient( 'ocdi_importer_data' );

		wp_send_json($response);
	}

	/**
	 * Get content importer data, so we can continue the import with this new AJAX request.
	 *
	 * @return boolean
	 */
	private function use_existing_importer_data() {
		if ( $data = get_transient( 'ocdi_importer_data' ) ) {
			$this->frontend_error_messages = empty( $data['frontend_error_messages'] ) ? array() : $data['frontend_error_messages'];
			$this->log_file_path           = empty( $data['log_file_path'] ) ? '' : $data['log_file_path'];
			$this->selected_index          = empty( $data['selected_index'] ) ? 0 : $data['selected_index'];
			$this->selected_import_files   = empty( $data['selected_import_files'] ) ? array() : $data['selected_import_files'];
			$this->import_files            = empty( $data['import_files'] ) ? array() : $data['import_files'];
			$this->before_import_executed  = empty( $data['before_import_executed'] ) ? false : $data['before_import_executed'];

			$this->import_type             = empty( $data['import_type'] ) ? '' : $data['import_type'];
			
			$this->importer->set_importer_data( $data );

			return true;
		}
		return false;
	}


	/**
	 * Get the current state of selected data.
	 *
	 * @return array
	 */
	public function get_current_importer_data() {
		return array(
			'frontend_error_messages' => $this->frontend_error_messages,
			'log_file_path'           => $this->log_file_path,
			'selected_index'          => $this->selected_index,
			'selected_import_files'   => $this->selected_import_files,
			'import_files'            => $this->import_files,
			'before_import_executed'  => $this->before_import_executed,

			'import_type'             => $this->import_type,
		);
	}


	/**
	 * Getter function to retrieve the private log_file_path value.
	 *
	 * @return string The log_file_path value.
	 */
	public function get_log_file_path() {
		return $this->log_file_path;
	}


	/**
	 * Setter function to append additional value to the private frontend_error_messages value.
	 *
	 * @param string $additional_value The additional value that will be appended to the existing frontend_error_messages.
	 */
	public function append_to_frontend_error_messages( $text ) {
		$lines = array();

		if ( ! empty( $text ) ) {
			$text = str_replace( '<br>', PHP_EOL, $text );
			$lines = explode( PHP_EOL, $text );
		}

		foreach ( $lines as $line ) {
			if ( ! empty( $line ) && ! in_array( $line , $this->frontend_error_messages ) ) {
				$this->frontend_error_messages[] = $line;
			}
		}
	}


	/**
	 * Display the frontend error messages.
	 *
	 * @return string Text with HTML markup.
	 */
	public function frontend_error_messages_display() {
		$output = '';

		if ( ! empty( $this->frontend_error_messages ) ) {
			foreach ( $this->frontend_error_messages as $line ) {
				$output .= esc_html( $line );
				$output .= '<br>';
			}
		}

		return $output;
	}


	/**
	 * Load the plugin textdomain, so that translations can be made.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'pt-ocdi', false, plugin_basename( dirname( dirname( __FILE__ ) ) ) . '/languages' );
	}


	/**
	 * Get data from filters, after the theme has loaded and instantiate the importer.
	 */
	public function setup_plugin_with_filter_data() {
		if ( ! ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) ) {
			return;
		}

		// +EDIT: Get info of import data files and filter it.
		$demos = apply_filters('pt-ocdi/import_files', array());
		$this->import_files = apply_filters('bunyad_import_demos', $demos);

		/**
		 * Register all default actions (before content import, widget, customizer import and other actions)
		 * to the 'before_content_import_execution' and the 'pt-ocdi/after_content_import_execution' action hook.
		 */
		$import_actions = new ImportActions();
		$import_actions->register_hooks();

		// Importer options array.
		$importer_options = apply_filters( 'pt-ocdi/importer_options', array(
			'fetch_attachments' => true,
		) );

		// Logger options for the logger used in the importer.
		// Note: Doesn't really work right now as logger just prints non-errors and inc/Importer.php 
		// just discards the output. 
		$logger_options = apply_filters( 'pt-ocdi/logger_options', array(
			'logger_min_level' => 'warning',
		) );

		// Configure logger instance and set it to the importer.
		$logger            = new Logger();
		$logger->min_level = $logger_options['logger_min_level'];

		// Create importer instance with proper parameters.
		$this->importer = new Importer( $importer_options, $logger );
	}
}
