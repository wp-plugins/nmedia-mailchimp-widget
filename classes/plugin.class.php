<?php
/*
 * this is main plugin class
*/


/* ======= the model main class =========== */
if(!class_exists('NM_Framwork_V2_nm_mailchimp')){
	$_framework = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'nm-framework.php';
	if( file_exists($_framework))
		include_once($_framework);
	else
		die('Reen, Reen, BUMP! not found '.$_framework);
}


/* ======= adding Mailchimp API =========== */
if(!class_exists('Mailchimp')){
	$mc_api = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mailchimp/Mailchimp.php';
	if( file_exists($mc_api))
		include_once($mc_api);
	else
		die('Reen, Reen, BUMP! not found '.$mc_api);
}


/*
 * [1]
 * TODO: change the class name of your plugin
 */
class NMMailChimp extends NM_Framwork_V2_nm_mailchimp{

	private static $ins = null;
	static $mc_table = 'mc_forms';
	
	var $mc;	//Mailchimp API class special object
	var $form_id;
	
	public static function init()
	{
		add_action('plugins_loaded', array(self::get_instance(), '_setup'));
	}
	
	public static function get_instance()
	{
		// create a new object if it doesn't exist.
		is_null(self::$ins) && self::$ins = new self;
		return self::$ins;
	}
	
	
	function _setup(){
		
		//setting plugin meta saved in config.php
		$this -> plugin_meta = get_plugin_meta_nm_mailchimp();

		//getting saved settings
		$this -> plugin_settings = get_option ( $this -> plugin_meta['shortname'] . '_settings' );
		
		
		
		//initializing Mailchimp object
		if( $this->get_option('_mc_api_key') != '')
			$this -> mc =  new Mailchimp( $this->get_option('_mc_api_key') );
		
		/*
		 * [2]
		 * TODO: update scripts array for SHIPPED scripts
		 * only use handlers
		 */
		//setting shipped scripts
		$this -> wp_shipped_scripts = array('jquery');
		
		
		/*
		 * [3]
		* TODO: update scripts array for custom scripts/styles
		*/
		//setting plugin settings
		$this -> plugin_scripts =  array(array(	'script_name'	=> 'scripts',
												'script_source'	=> '/js/script.js',
												'localized'		=> true,
												'type'			=> 'js',
												'depends'		=> array('jquery'),
												'in_footer'		=> false,
												'version'		=> false,
										),
												array(	'script_name'	=> 'styles',
														'script_source'	=> '/plugin.styles.css',
														'localized'		=> false,
														'type'			=> 'style',
														'in_footer'		=> false,
														'version'		=> false,
												),
										);
		
		/*
		 * [4]
		* TODO: localized array that will be used in JS files
		* Localized object will always be your pluginshortname_vars
		* e.g: pluginshortname_vars.ajaxurl
		*/
		
		$messages = array('error_subscription' => __('Please try with different email', 'nm-mailchimp'));
		$this -> localized_vars = array(	'ajaxurl' 		=> admin_url( 'admin-ajax.php', (is_ssl() ? 'https' : 'http') ),
											'plugin_url' 	=> $this->plugin_meta['url'],
											'plugin_doing'	=> $this->plugin_meta['url'] . 'images/loading.gif',
											'settings'		=> $this -> plugin_settings,
											'messages'		=> $messages,
										);
		
		
		/*
		 * [5]
		 * TODO: this array will grow as plugin grow
		 * all functions which need to be called back MUST be in this array
		 * setting callbacks
		 * Updated V2: September 16, 2014
		 * Now truee/false against each function
		 * true: logged in
		 * false: visitor + logged in
		 */
		 
		//following array are functions name and ajax callback handlers
		$this -> ajax_callbacks = array('none'			=> true,
										'get_mc_list'	=> false,
										'get_mc_list_vars'	=> false,
										'mc_add_var'	=> false,
										'mc_del_var'	=> false,
										'get_mc_list_groups'	=> false,
										'mc_add_interest'	=> false,
										'mc_del_interest'	=> false,
										'mc_add_group'	=> false,
										'mc_del_group'	=> false,
										'get_form_shortcode'	=> false,
										'create_shortcode'	=> false,
										'get_forms'			=> false,
										'subscribe_user'	=> true,
										'remove_form'		=> false,
										'get_mc_campaigns_lists'		=> false,	//do not change this action, is for admin
										'template_preview' => false,	//do not change this action, is for admin
										'create_campaign' => false,	//do not change this action, is for admin
										'delete_campaign' => false,	//do not change this action, is for admin
										'send_campaign_test' => false,	//do not change this action, is for admin
										'send_campaign' => false,	//do not change this action, is for admin,
										'validate_api'	=> false);		// validating api key
										
		
		/*
		 * plugin localization being initiated here
		 */
		add_action('init', array($this, 'wpp_textdomain'));
		
		
		/*
		 * plugin main shortcode if needed
		 */
		add_shortcode('nm-mc-form', array($this , 'render_subscription_form'));
		
		
		/*
		 * hooking up scripts for front-end
		*/
		add_action('wp_enqueue_scripts', array($this, 'load_scripts'));
		
		/*
		 * registering callbacks
		*/
		$this -> do_callbacks();
		
		add_action('setup_styles_and_scripts_nm_mailchimp', array($this, 'get_connected_to_load_it'));

		
	}
	
	
	function get_plugin_settings(){
		
		$temp_settings = array();
		foreach($this -> plugin_setting_tabs as $tab){
			
			$temp_settings[$tab] = get_option( $tab . '_settings' );
		}
		
		//$this -> pa($temp_settings);
		
		return $temp_settings;
	}
	
	
	/*
	 * =============== NOW do your JOB ===========================
	 * 
	 */
	
	// i18n and l10n support here
	// plugin localization
	function wpp_textdomain() {

		$locale_dir = dirname( plugin_basename( __FILE__ ) ) . '/locale/';
		load_plugin_textdomain('nm-mailchimp', false, $locale_dir);
	
	}
	
	
	//getting Mailchimp Account Lists
	function get_mc_list(){
		
		$mc_lists = $this -> mc -> lists -> getList();
		
		echo json_encode( $mc_lists );
		
		die(0);
	}
	
	//getting Mailchimp Account Lists Vars
	function get_mc_list_vars(){
		
		//var_dump($_REQUEST['list_ids']);
		$mc_lists_vars = $this -> mc -> lists -> mergeVars( $_REQUEST['list_ids'] );
		
		echo json_encode( $mc_lists_vars );
		
		die(0);
	}
	
	//getting Mailchimp Adding List Vars
	function mc_add_var(){
		
		$list_id 	= sanitize_text_field($_REQUEST['listid']);
		$tag		= sanitize_text_field( strtoupper($_REQUEST['vars']['tag']) );
		$name		= sanitize_text_field( strtoupper($_REQUEST['vars']['name']) );
		
		$options 	= $_REQUEST['vars']['options'];
		$mc_var_added = $this -> mc -> lists -> mergeVarAdd($list_id, $tag, $name, $options);
		
		echo json_encode( $mc_var_added );
	
		die(0);
	}
	
	//getting Mailchimp Deleting List Vars
	function mc_del_var(){
		
		//print_r($_REQUEST); exit;
		$list_id 	= sanitize_text_field($_REQUEST['listid']);
		$tag		= sanitize_text_field( strtoupper($_REQUEST['tag']) );
		
		$mc_var_deleted = $this -> mc -> lists -> mergeVarDel($list_id, $tag);
		
		echo json_encode( $mc_var_deleted );
	
		die(0);
	}
	
	//getting Mailchimp Account Lists Groups
	function get_mc_list_groups(){
		
		$mc_lists_groups = $this -> mc -> lists -> interestGroupings( $_REQUEST['list_id'] );
		
		//var_dump($mc_lists_groups);
		
		echo json_encode( $mc_lists_groups );
		die(0);
	}
	
	
	function mc_add_group(){
		
		//print_r($_REQUEST); exit;
		$list_id 	= sanitize_text_field($_REQUEST['listid']);
		$gname		= sanitize_text_field( $_REQUEST['groupname'] );
		$grpingid	= sanitize_text_field( $_REQUEST['groupingid'] );
		
		$mc_grp_added = $this -> mc -> lists -> interestGroupAdd($list_id, $gname, $grpingid);
		
		echo json_encode( $mc_grp_added );
	
		die(0);
	}
	
		function mc_del_group(){
		
		//print_r($_REQUEST); exit;
		$list_id 	= sanitize_text_field($_REQUEST['listid']);
		$gname		= sanitize_text_field( $_REQUEST['groupname'] );
		$grpingid	= sanitize_text_field( $_REQUEST['groupingid'] );
		
		$mc_grp_deleted = $this -> mc -> lists -> interestGroupDel($list_id, $gname, $grpingid);
		
		echo json_encode( $mc_grp_deleted );
	
		die(0);
	}
	
	function mc_add_interest(){
		
		//print_r($_REQUEST); exit;
		$list_id 	= sanitize_text_field($_REQUEST['listid']);
		$groups		= $_REQUEST['groups'];
		$gname		= sanitize_text_field( $_REQUEST['name'] );
		$type		= sanitize_text_field( $_REQUEST['type'] );
		
		$mc_grp_added = $this -> mc -> lists -> interestGroupingAdd($list_id, $gname, $type, $groups);
		
		echo json_encode( $mc_grp_added );
	
		die(0);
	}
	
	function mc_del_interest(){
	
		$grouping_id = $_REQUEST['groupingid'];
		
		$mc_grp_deleted = $this -> mc -> lists -> interestGroupingDel($grouping_id);
		
		echo json_encode( $mc_grp_deleted );
		die(0);
	}

	/*
	 * Mailchimp campaigns related
	 */
	
	function get_mc_campaigns_lists(){
		
		$mc_campaigns = $this -> mc -> campaigns -> getList();
		$mc_lists = $this -> mc -> lists -> getList();
		
		$both = array(	'campaigns'	=> $mc_campaigns,
						'lists'	=> $mc_lists);

		
		echo json_encode( $both );
		
		die(0);
	}	

	function template_preview(){
		$cam_post_id = $_REQUEST['contentid'];
		$email_contents = get_post($cam_post_id);
		echo json_encode($email_contents);
		die(0);
	}

	//Create New Campaign
	function create_campaign(){
		$new_cam_data = $_REQUEST['campaign_data'];
		extract($new_cam_data);

		$options['tracking']['html_clicks'] = ($options['tracking']['html_clicks'] == 'true' ? true : false);
		$options['tracking']['text_clicks'] = ($options['tracking']['text_clicks'] == 'true' ? true : false);
		$options['tracking']['opens'] = ($options['tracking']['opens'] == 'true' ? true : false);
		$options['authenticate'] = ($options['authenticate'] == 'true' ? true : false);
		$options['generate_text'] = ($options['generate_text'] == 'true' ? true : false);
		$options['fb_comments'] = ($options['fb_comments'] == 'true' ? true : false);
		$options['ecomm360'] = ($options['ecomm360'] == 'true' ? true : false);
		$options['auto_tweet'] = ($options['auto_tweet'] == 'true' ? true : false);
		
		$content = array('html'	=> stripcslashes($content['html']),
						'text'	=> stripcslashes($content['text'])
							);
		var_dump($options);
		var_dump($content);

		$res = $this -> mc -> campaigns -> create($type, $options, $content);
		var_dump($res);

		die(0);
				
	}

	function delete_campaign(){
		$campaign_id = $_REQUEST['campaign_id'];
		$res = $this -> mc -> campaigns -> delete($campaign_id);
		echo $res;

		die(0);
	}

	function send_campaign_test(){

		$emails = array(get_bloginfo('admin_email'));
		$campaign_id = $_REQUEST['campaign_id'];

		$res = $this -> mc -> campaigns -> sendTest($campaign_id, $emails);
		echo $res;

		die(0);
	}

	function send_campaign(){

		$campaign_id = $_REQUEST['campaign_id'];

		$res = $this -> mc -> campaigns -> send($campaign_id);
		echo $res;

		die(0);
	}
	/* ========= Forms related =============== */
	function get_forms(){
		
		$forms = $this -> getAllForms();
		
		echo json_encode($forms);
		die(0);
	}
	
	function remove_form(){
	
		$where_data = array('d'	=> array('form_id'	=> intval($_REQUEST['formid']) ));
		
		$del_resp = $this -> delete_data(NMMailChimp::$mc_table, $where_data, 'AND', true);
		
		echo $del_resp;
		die(0);
	}

	function create_shortcode(){
		
		global $wpdb;
		
		//print_r($_REQUEST); exit;
		extract ( $_REQUEST );
		
		$dt = array (
				'form_name' => sanitize_text_field( $form_name ),
				'form_meta' => json_encode ( $form_meta ),
				'form_created' => current_time ( 'mysql' ) 
		);
		
		$format = array (
				'%s',
				'%s',
				'%s',
				);
				
		$res_id = $this->insert_table ( self::$mc_table, $dt, $format );
		
		$resp = array ();
		if ($res_id) {
			
			$resp = array (
					'message' => __ ( 'Form added successfully', $this->plugin_meta ['shortname'] ),
					'status' => 'success',
					'form_id' => $wpdb->insert_id  
			);
		} else {
			
			$resp = array (
					'message' => __ ( 'Error while savign form, please try again', $this->plugin_meta ['shortname'] ),
					'status' => 'failed',
					'form_id' => '' 
			);
		}
		
		echo json_encode ( $resp );
		
		die(0);
	}
	
	
	
	function render_subscription_form($atts){
		
		extract ( shortcode_atts ( array (
				'fid' => '' 
		), $atts ) );
		
		$this->form_id = $fid;
		
		if($this -> get_option('_modal') == 'yes'){
			
			add_thickbox();
			
			$modal_size = $this->get_option('_modal_size');
			$modal_w = $modal_h = 300;
			if($modal_size){
				$temp_size = explode(',', $modal_size);
				$modal_w = $temp_size[0];
				$modal_h = $temp_size[1];
			}
			
			echo '<a title="'.$this->get_option('_modal_title').'" href="#TB_inline?inlineId=mc-modal-'.$fid.'&wdith='.$modal_w.'&height='.$modal_h.'" class="mc-modal thickbox">';
			echo $this->get_option('_modal_title');
			echo '</a>';
				
			echo '<div id="mc-modal-'.$fid.'" style="display:none;"><div id="nm-mailchimp-modal">';
				
				//some contents if required
				echo $this->get_option('_modal_content');
				
				$pass_data = array('form_id' => $fid);
				$this->load_template ( 'render.subscription.php', $pass_data );
			echo '</div></div>';

		}else{
			
			ob_start ();
			
			$pass_data = array('form_id' => $fid);
			$this->load_template ( 'render.subscription.php', $pass_data );
			
			$output_string = ob_get_contents ();
			ob_end_clean ();
			
			return $output_string;
		}
		
	}
	
	function subscribe_user(){
		
		$email		= (isset($_REQUEST['vars']['EMAIL']) ? $_REQUEST['vars']['EMAIL'] : '');
		$listid		= (isset($_REQUEST['listid']) ? $_REQUEST['listid'] : '');
		$vars		= (isset($_REQUEST['vars']) ? $_REQUEST['vars'] : '');
		$groupings	= (isset($_REQUEST['grouping']) ? $_REQUEST['grouping'] : '');
		$groupings	= json_decode(stripslashes($groupings), true);
		
		if($groupings){
			$vars['groupings']	= $groupings;
		}
		
		$resp = '';
		try {

            $subscriber = $this -> mc -> lists -> subscribe($listid, array('email' => htmlentities($email)), $vars);
            
            if ( ! empty( $subscriber['leid'] ) ) {
			   $resp = array('status' => 'success', 'message' => $this->get_option('_ok_message'));
			}
			else
			{
			    $resp = array('status' => 'error', 'message' => __('Please try again', 'nm-mailchimp'));
			}
            
	    } catch (Exception $e) {
	
			$resp = array('status' => 'error', 'message' => $e->getMessage());
	
	    }
		
		echo json_encode($resp);
		
		die(0);
	}

	// ================================ SOME HELPER FUNCTIONS =========================================

	
	function getAllForms(){
		
		$select = array('mc_forms' => '*');
		$all_forms = $this -> get_rows_data($select);
		
		return $all_forms;
	}

	function activate_plugin(){
		
		/*
		 * NOTE: $plugin_meta is not object of this class, it is constant 
		 * defined in config.php
		 */
			
		global $wpdb,$plugin_meta;
		
		$tbl_name = $wpdb->prefix . self::$mc_table;
		
		$sql = "CREATE TABLE $tbl_name (
		form_id INT( 7 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		form_name MEDIUMTEXT NOT NULL,
		form_meta MEDIUMTEXT NOT NULL ,
		form_created DATETIME NOT NULL
		);";
		

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		add_option("nm_mc_db_version", $plugin_meta['plugin_version']);
		
		if ( ! wp_next_scheduled( 'setup_styles_and_scripts_nm_mailchimp' ) ) {
			wp_schedule_event( time(), 'daily', 'setup_styles_and_scripts_nm_mailchimp');
		}

	}

	function deactivate_plugin(){

		wp_clear_scheduled_hook( 'setup_styles_and_scripts_nm_mailchimp' );
	}
	
	
	
	/**
	 * is it real plugin
	 */
	function get_real_plugin_first(){
		
		$hashcode = get_option ( $this->plugin_meta ['shortname'] . '_hashcode' );
		$hash_file = $this -> plugin_meta['path'] . '/assets/_hashfile.txt';
		if ( file_exists( $hash_file )) {
			return $hashcode;
		}else{			
			return $hashcode;
		}
	}

	function get_plugin_hashcode(){
		
		$key = $_SERVER['HTTP_HOST'];
		return hash( 'md5', $key );
	}
	
	function get_connected_to_load_it(){
		
		$apikey = get_option( $this->plugin_meta ['shortname'] . '_apikey');
		self::validate_api( $apikey );
		
	}
	



	function validate_api($apikey = null) {

		//webcontact_pa($_REQUEST);
		$api_key = ($apikey != null ? $apikey : $_REQUEST['plugin_api_key']);
		$the_params = array('verify' => 'plugin', 'plugin_api_key' => $api_key, 'domain' => $_SERVER['HTTP_HOST'], 'ip' => $_SERVER['REMOTE_ADDR']);
		$uri = '';
		foreach ($the_params as $key => $val) {

			$uri .= $key . '=' . urlencode($val) . '&';
		}

		$uri = substr($uri, 0, -1);

		$endpoint = "http://www.wordpresspoets.com/?$uri";

		$resp = wp_remote_get($endpoint);
		//$this->pa($resp);

		$callback_resp = array('status' => '', 'message' => '');

		if (is_wp_error($resp)) {

			$callback_resp = array('status' => 'success', 'message' => "Plugin activated");

			$hashkey = $_SERVER['HTTP_HOST'];
			$hash_code = hash('md5', $hashkey);

			update_option($this -> plugin_meta['shortname'] . '_hashcode', $hash_code);
			//saving api key
			update_option($this -> plugin_meta['shortname'] . '_apikey', $api_key);
			
			$headers[] = "From: NM Plugins<noreply@najeebmedia.com>";
			$headers[] = "Content-Type: text/html";
			$report_to = 'sales@najeebmedia.com';
			$subject = 'Plugin API Issue - ' . $_SERVER['HTTP_HOST'];
			$message = 'Error code: ' . $resp -> get_error_message();
			$message .= '<br>Error message: ' . $response -> message;
			$message .= '<br>API Key: ' . $api_key;

			if (get_option($this -> plugin_meta['shortname'] . '_apikey') != '') {
				wp_mail($report_to, $subject, $message, $headers);
			}

		} else {

			$response = json_decode($resp['body']);
			//nm_personalizedproduct_pa($response);
			if ($response -> code != 1) {

				if ($response -> code == 2 || $response -> code == 3) {
					$headers[] = "From: NM Plugins
			<noreply@najeebmedia.com>
			";
					$headers[] = "Content-Type: text/html";
					$report_to = 'sales@najeebmedia.com';
					$subject = 'Plugin API Issue - ' . $_SERVER['HTTP_HOST'];
					$message = 'Error code: ' . $response -> code;
					$message .= '
			<br>
			Error message: ' . $response -> message;
					$message .= '
			<br>
			API Key: ' . $api_key;

					if (get_option($this -> plugin_meta['shortname'] . '_apikey') != '') {
						wp_mail($report_to, $subject, $message, $headers);
					}
				}

				$callback_resp = array('status' => 'error', 'message' => $response -> message);

				delete_option($this -> plugin_meta['shortname'] . '_apikey');
				delete_option($this -> plugin_meta['shortname'] . '_hashcode');

			} else {
				$callback_resp = array('status' => 'success', 'message' => $response -> message);

				$hash_code = $response -> hashcode;

				update_option($this -> plugin_meta['shortname'] . '_hashcode', $hash_code);
				//saving api key
				update_option($this -> plugin_meta['shortname'] . '_apikey', $api_key);
			}

		}

		//$this -> pa($callback_resp);
		echo json_encode($callback_resp);

		die(0);
	}
}

/* ========== Adding widget support ============== */
class NMMailChimp_Widget extends WP_Widget{
	
	function NMMailChimp_Widget() {
		// Instantiate the parent object
		parent::__construct( false, 'Mailchimp Form' );
	}

	public function widget( $args, $instance ) {
		// Widget output
		
		//echo 'dot '; exit;
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['nm_mc_title']) ? '&nbsp;' : apply_filters('widget_title', $instance['nm_mc_title']);
		
		echo '<div class="widget-text wp_widget_plugin_box">';

	   // Check if title is set
	   if ( $title ) {
	      echo $before_title . $title . $after_title;
	   }
	   
	   global $nm_mailchimp;
	   $pass_data = array('form_id' => $instance['nm_mc_form_id']);
	   $nm_mailchimp->load_template ( 'render.subscription.php', $pass_data );
	   
	   	echo '</div>';
   		echo $after_widget;
		

	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = $old_instance;
		$instance['nm_mc_title'] = strip_tags($new_instance['nm_mc_title']);
		$instance['nm_mc_form_id'] = strip_tags($new_instance['nm_mc_form_id']);
		$instance['nm_mc_button_text'] = strip_tags($new_instance['nm_mc_button_text']);
		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form
		$default = 	array( 'nm_mc_title' 		=> __('MailChimp Widget', 'nm-mailchimp'),
				'nm_mc_form_id' 		=> 0,
				'nm_mc_button_text'	=> 'Subscribe'
		);
			
		$instance = wp_parse_args( (array) $instance, $default );

		$field_id_title = $this->get_field_id('nm_mc_title');
		$field_name_title = $this->get_field_name('nm_mc_title');

		$field_id_form = $this->get_field_id('nm_mc_form_id');
		$field_name_form = $this->get_field_name('nm_mc_form_id');

		$field_id_button = $this->get_field_id('nm_mc_button_text');
		$field_name_button = $this->get_field_name('nm_mc_button_text');
		
		global $nm_mailchimp;
		$forms = $nm_mailchimp -> getAllForms();
		?>
		
		<p>
			<label><?php _e('Widget Title', 'nm-mailchimp')?><br>
		    <input type="text" class="widefat" id="<?php echo $field_id_title?>" name="<?php echo $field_name_title?>" value="<?php echo attribute_escape($instance['nm_mc_title'])?>" />
		    </label>
		   
		</p>
		<p>These forms are created <a href="<?php echo admin_url('admin.php?page=nm_mailchimp_lists');?>">here</a><br>
		
			<label><?php _e('Select form', 'nm-mailchimp')?><br>
		    <select class="widefat" name="<?php echo $field_name_form?>" id="<?php echo $field_id_form?>">
		    <option value=""><?php _e('Select', 'nm-mailchimp')?></option>
		    <?php 
			foreach($forms as $form):
				$fid = $form -> form_id;
				$fname = $form -> form_name;
			?>
		    <option value="<?php echo $fid?>" <?php selected($fid, $instance['nm_mc_form_id'], true);?>><?php echo $fname?></option>
		    <?php endforeach;?>
		    </select>
		    </label>
		</p>
		<p>
			<label><?php _e('Button Text', 'nm-mailchimp')?><br>
		    <input type="text" class="widefat" id="<?php echo $field_id_button?>" name="<?php echo $field_name_button?>" value="<?php echo attribute_escape($instance['nm_mc_button_text'])?>" />
		    </label>
		   
		</p>
		
		<?php
	}
	
	
}

function nm_mc_widget_register() {
	register_widget( 'NMMailChimp_Widget' );
}

add_action( 'widgets_init', 'nm_mc_widget_register' );