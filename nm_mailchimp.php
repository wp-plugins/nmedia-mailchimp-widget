<?php
/*
Plugin Name: Simple MailChimp Email List Subscriber
Plugin URI: http://www.najeebmedia.com/wordpress-mailchimp-plugin-2-0-released/
Description: Form to capture email subscriptions and send them to your MailChimp account list
Version: 2.6
Author: Najeeb Ahmad
Author URI: http://www.najeebmedia.com/
*/


/*ini_set('display_errors',1);
error_reporting(E_ALL);*/

class nmMailChimp extends WP_Widget {
	
	 
	/*
	** constructor
	*/	
	function nmMailChimp() {
		parent::WP_Widget(  'nmedia_mail_chimp', 
							'MailChimp Widget',
							array('description' => 'MailChimp Widget by najeebmedia.com.'));	
		
	}
	
	
	/*
	** loading js/jquery stuff
	*/
	public function load_js()
	{
		wp_deregister_script( 'jquery' );
    	wp_register_script( 'jquery', plugins_url('js/jquery-1.4.4.min.js', __FILE__));
		wp_enqueue_script( 'jquery' );
	
		wp_register_script( 'nm_mailchimp_custom_script', 
							plugins_url('js/nm_mc_js.js', __FILE__), 
							array('jquery'));
							
							
		wp_enqueue_script('nm_mailchimp_custom_script');
	
	}
	
	/*
	** setting option page in wp admin
	*/  
  	public function set_up_admin_page () {
		
	  	add_submenu_page(   'options-general.php', 
							'MailChimp Widget Options', 
						 	'MailChimp Widget', 
							'activate_plugins', 
							__FILE__, 
							array('nmMailChimp', 'admin_page'));		
	}
	
	
	public function admin_page()
	{
		$file = dirname(__FILE__).'/options.php';
		include($file);
	}
	
  
  	/*
	** display widget
	*/	 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['nm_mc_title']) ? '&nbsp;' : apply_filters('widget_title', $instance['nm_mc_title']);
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		
		/*print_r($args);*/
		nmMailChimp::nm_load_form(	$instance['nm_mc_list_id'], 
									$instance['nm_mc_show_names'],
									$instance['nm_mc_box_title'],
									$args['widget_id']);
		
		echo $after_widget;
	}
	
	
	/*
	** update/save function
	*/	 	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['nm_mc_title'] = strip_tags($new_instance['nm_mc_title']);
		$instance['nm_mc_box_title'] = strip_tags($new_instance['nm_mc_box_title']);
		$instance['nm_mc_list_id'] = strip_tags($new_instance['nm_mc_list_id']);
		$instance['nm_mc_show_names'] = strip_tags($new_instance['nm_mc_show_names']);
		return $instance;
	}
	
	/*
	** admin control form
	*/	 	
	function form($instance) {
		$default = 	array( 'nm_mc_title' 		=> __('MailChimp Widget'),
						   'nm_mc_box_title'	=> __('Your Caption Here'),
						   'nm_mc_list_id' 		=> 0,
						   'nm_mc_show_names' 	=> 0,
						   );
						   
		$instance = wp_parse_args( (array) $instance, $default );
		
		$field_id_title = $this->get_field_id('nm_mc_title');
		$field_name_title = $this->get_field_name('nm_mc_title');
		
		$field_id_box_title = $this->get_field_id('nm_mc_box_title');
		$field_name_box_title = $this->get_field_name('nm_mc_box_title');
		
		$field_id_list = $this->get_field_id('nm_mc_list_id');
		$field_name_list = $this->get_field_name('nm_mc_list_id');
		
				
		$field_id_names = $this->get_field_id('nm_mc_show_names');
		$field_name_names = $this->get_field_name('nm_mc_show_names');
		
		
				
		/*$api_dir = dirname(__FILE__).'/api_mailchimp/mcapi_lists.php';
		include($api_dir);*/
		$arrList = nmMailChimp::getAccountLists();
		
		
		$file = dirname(__FILE__).'/control.php';
		include($file);
		
	}
	
	
	/*
	** Getting Mailchimp account list
	*/
	function getAccountLists()
	{
		$api_dir = dirname(__FILE__).'/api_mailchimp/inc/MCAPI.class.php';
		
		require_once ($api_dir);
		
		$api = new nm_MCAPI(get_option('nm_mc_api_key'));
		
		$retval = $api->lists();
		
		if ($api->errorCode){
		  	_e("You did not enter API Keys please enter your API Keys from Nmedia Mailchimp Setting area");
		 }
		 else
		 {
			 return $retval['data'];
		 }
		 
	}
	
	
	/*
	** Getting Merge vars attached to a list
	*/
	function getMergeVars($list_id)
	{
		$api_dir = dirname(__FILE__).'/api_mailchimp/inc/MCAPI.class.php';
		
		require_once ($api_dir);
		
		$api = new nm_MCAPI(get_option('nm_mc_api_key'));
		
		$retval = $api->listMergeVars($list_id);
		
		if ($api->errorCode){
		  	_e("You did not enter API Keys please enter your API Keys from Nmedia Mailchimp Setting area");
		 }
		 else
		 {
			 return $retval;
		 }
		 
	}
  
  
  /*
  ** this function rendering shortcodes in admin
  */
  
  function renderShortcodes()
  {
	$file = dirname(__FILE__).'/gen-shortcode.php';
	include($file);
  }
  
  
  /*
  ** this function rendering List Manager page
  */
  
  function renderListManager()
  {
	$file = dirname(__FILE__).'/list-manager.php';
	include($file);
  }
 
  /*
  ** this is rendering form in widget area
  */
  function nm_load_form($list_id, $show_names, $boxTitle, $widget_id)
  {
  	
	$file = dirname(__FILE__).'/mc-box.php';
	include($file);
  }
  
  /*
  ** this is rendering form in page/post using shortcode
  */
  function renderForm($atts)
  {
	  extract(shortcode_atts(array(
		  'field'			=> 'email',
		  'list_id'			=> '',
		  'button_text'		=> 'Subscribe',
		  'show_address' 	=> '',
       		), $atts));
  	
	
	$list_id = "{$list_id}";
	$button_text = "{$button_text}";
	$widget_id = time();
	$show_names = ("{$field}" == 'fname') ? true : false;
	$show_address = explode(",", "{$show_address}");
	
	ob_start();
	$file = dirname(__FILE__).'/mc-form.php';
	include($file);
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
	
  }
  
  
  /*
  ** Unistalling the plugin
  */
  
  function nm_mc_unistall()
  {
		global $nm_mc_options;
		
		foreach ($nm_mc_options as $value) {
	    	delete_option( $value['id'] );
    	}
  }
  
  
  /*
  ** listing all countries with ISO standard required by Mailchimp
  */
  
  function listCountries($w_id)
  {
	  global $country_list;
	  $cmb_name = "country-$w_id";
	  echo '<select id="'.$cmb_name.'" class="nm_mc_text">';
	  foreach($country_list as $country => $code)
	  {
		  echo '<option value="'.$code.'">'.$country.'</option>';
	  }
	  echo '</select>';
  }
  
  /*
  ** Enqueue style-file, if it exists.
  */
  function add_nm_stylesheet() {
	$myStyleFile = dirname(__FILE__).'/mc-box.css';	
     if ( file_exists($myStyleFile) ) {
        wp_register_style('nm_mailchimp_stylesheet', plugins_url('mc-box.css', __FILE__));
   		wp_enqueue_style( 'nm_mailchimp_stylesheet');
	 }        
  }
  
  // plugin localization
	function nm_mc_textdomain() {
		load_plugin_textdomain('nm_mailchimp_plugin', false, dirname(plugin_basename( __FILE__ )) . '/locale/');
	}
  
  
}

// activate textdomain for translations
add_action('init', array('nmMailChimp', 'nm_mc_textdomain'));


/* book: register widget when loading the WP core */
add_action('widgets_init', 'nm_mc_register_widgets');

/* hook: loading js stuff */
add_action('wp_enqueue_scripts', array('nmMailChimp', 'load_js'));
/* hook: styilng */
add_action('wp_print_styles', array('nmMailChimp', 'add_nm_stylesheet'));
/* hook deactivating plugin */
register_deactivation_hook(__FILE__, array('nmMailChimp', 'nm_mc_unistall'));

/*shortcode introduced in version 2.6*/
add_shortcode( 'nm-mc-render', array('nmMailChimp', 'renderForm'));

$options_file = dirname(__FILE__).'/plugin-options.php';
include ($options_file);

function nm_mc_register_widgets(){
	// curl need to be installed
	register_widget('nmMailChimp');
}


$country_list = array("AFGHANISTAN" => "AF",
"ÅLAND ISLANDS" => "AX",
"ALBANIA" => "AL",
"ALGERIA" => "DZ",
"AMERICAN SAMOA" => "AS",
"ANDORRA" => "AD",
"ANGOLA" => "AO",
"ANGUILLA" => "AI",
"ANTARCTICA" => "AQ",
"ANTIGUA AND BARBUDA" => "AG",
"ARGENTINA" => "AR",
"ARMENIA" => "AM",
"ARUBA" => "AW",
"AUSTRALIA" => "AU",
"AUSTRIA" => "AT",
"AZERBAIJAN" => "AZ",
"BAHAMAS" => "BS",
"BAHRAIN" => "BH",
"BANGLADESH" => "BD",
"BARBADOS" => "BB",
"BELARUS" => "BY",
"BELGIUM" => "BE",
"BELIZE" => "BZ",
"BENIN" => "BJ",
"BERMUDA" => "BM",
"BHUTAN" => "BT",
"BOLIVIA, PLURINATIONAL STATE OF" => "BO",
"BONAIRE, SINT EUSTATIUS AND SABA" => "BQ",
"BOSNIA AND HERZEGOVINA" => "BA",
"BOTSWANA" => "BW",
"BOUVET ISLAND" => "BV",
"BRAZIL" => "BR",
"BRITISH INDIAN OCEAN TERRITORY" => "IO",
"BRUNEI DARUSSALAM" => "BN",
"BULGARIA" => "BG",
"BURKINA FASO" => "BF",
"BURUNDI" => "BI",
"CAMBODIA" => "KH",
"CAMEROON" => "CM",
"CANADA" => "CA",
"CAPE VERDE" => "CV",
"CAYMAN ISLANDS" => "KY",
"CHAD" => "TD",
"CHILE" => "CL",
"CHINA" => "CN",
"CHRISTMAS ISLAND" => "CX",
"COCOS (KEELING) ISLANDS" => "CC",
"COLOMBIA" => "CO",
"COMOROS" => "KM",
"CONGO" => "CG",
"CONGO, THE DEMOCRATIC REPUBLIC OF THE" => "CD",
"COOK ISLANDS" => "CK",
"COSTA RICA" => "CR",
"CROATIA" => "HR",
"CUBA" => "CU",
"CURAÇAO" => "CW",
"CYPRUS" => "CY",
"CZECH REPUBLIC" => "CZ",
"DENMARK" => "DK",
"DJIBOUTI" => "DJ",
"DOMINICA" => "DM",
"DOMINICAN REPUBLIC" => "DO",
"ECUADOR" => "EC",
"EGYPT" => "EG",
"EL SALVADOR" => "SV",
"EQUATORIAL GUINEA" => "GQ",
"ERITREA" => "ER",
"ESTONIA" => "EE",
"ETHIOPIA" => "ET",
"FALKLAND ISLANDS (MALVINAS)" => "FK",
"FAROE ISLANDS" => "FO",
"FIJI" => "FJ",
"FINLAND" => "FI",
"FRANCE" => "FR",
"FRENCH GUIANA" => "GF",
"FRENCH POLYNESIA" => "PF",
"FRENCH SOUTHERN TERRITORIES" => "TF",
"GABON" => "GA",
"GAMBIA" => "GM",
"GEORGIA" => "GE",
"GERMANY" => "DE",
"GHANA" => "GH",
"GIBRALTAR" => "GI",
"GREECE" => "GR",
"GREENLAND" => "GL",
"GRENADA" => "GD",
"GUADELOUPE" => "GP",
"GUAM" => "GU",
"GUATEMALA" => "GT",
"GUERNSEY" => "GG",
"GUINEA" => "GN",
"GUINEA-BISSAU" => "GW",
"GUYANA" => "GY",
"HAITI" => "HT",
"HEARD ISLAND AND MCDONALD ISLANDS" => "HM",
"HOLY SEE (VATICAN CITY STATE)" => "VA",
"HONDURAS" => "HN",
"HONG KONG" => "HK",
"HUNGARY" => "HU",
"ICELAND" => "IS",
"INDIA" => "IN",
"INDONESIA" => "ID",
"IRAN, ISLAMIC REPUBLIC OF" => "IR",
"IRAQ" => "IQ",
"IRELAND" => "IE",
"ISLE OF MAN" => "IM",
"ISRAEL" => "IL",
"ITALY" => "IT",
"JAMAICA" => "JM",
"JAPAN" => "JP",
"JERSEY" => "JE",
"JORDAN" => "JO",
"KAZAKHSTAN" => "KZ",
"KENYA" => "KE",
"KIRIBATI" => "KI",
"KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF" => "KP",
"KOREA, REPUBLIC OF" => "KR",
"KUWAIT" => "KW",
"KYRGYZSTAN" => "KG",
"LAO PEOPLE'S DEMOCRATIC REPUBLIC" => "LA",
"LATVIA" => "LV",
"LEBANON" => "LB",
"LESOTHO" => "LS",
"LIBERIA" => "LR",
"LIBYA" => "LY",
"LIECHTENSTEIN" => "LI",
"LITHUANIA" => "LT",
"LUXEMBOURG" => "LU",
"MACAO" => "MO",
"MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF" => "MK",
"MADAGASCAR" => "MG",
"MALAWI" => "MW",
"MALAYSIA" => "MY",
"MALDIVES" => "MV",
"MALI" => "ML",
"MALTA" => "MT",
"MARSHALL ISLANDS" => "MH",
"MARTINIQUE" => "MQ",
"MAURITANIA" => "MR",
"MAURITIUS" => "MU",
"MAYOTTE" => "YT",
"MEXICO" => "MX",
"MICRONESIA, FEDERATED STATES OF" => "FM",
"MOLDOVA, REPUBLIC OF" => "MD",
"MONACO" => "MC",
"MONGOLIA" => "MN",
"MONTENEGRO" => "ME",
"MONTSERRAT" => "MS",
"MOROCCO" => "MA",
"MOZAMBIQUE" => "MZ",
"MYANMAR" => "MM",
"NAMIBIA" => "NA",
"NAURU" => "NR",
"NEPAL" => "NP",
"NETHERLANDS" => "NL",
"NEW CALEDONIA" => "NC",
"NEW ZEALAND" => "NZ",
"NICARAGUA" => "NI",
"NIGER" => "NE",
"NIGERIA" => "NG",
"NIUE" => "NU",
"NORFOLK ISLAND" => "NF",
"NORTHERN MARIANA ISLANDS" => "MP",
"NORWAY" => "NO",
"OMAN" => "OM",
"PAKISTAN" => "PK",
"PALAU" => "PW",
"PALESTINIAN TERRITORY, OCCUPIED" => "PS",
"PANAMA" => "PA",
"PAPUA NEW GUINEA" => "PG",
"PARAGUAY" => "PY",
"PERU" => "PE",
"PHILIPPINES" => "PH",
"PITCAIRN" => "PN",
"POLAND" => "PL",
"PORTUGAL" => "PT",
"PUERTO RICO" => "PR",
"QATAR" => "QA",
"RÉUNION" => "RE",
"ROMANIA" => "RO",
"RUSSIAN FEDERATION" => "RU",
"RWANDA" => "RW",
"SAINT BARTHÉLEMY" => "BL",
"SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA" => "SH",
"SAINT KITTS AND NEVIS" => "KN",
"SAINT LUCIA" => "LC",
"SAINT MARTIN (FRENCH PART)" => "MF",
"SAINT PIERRE AND MIQUELON" => "PM",
"SAINT VINCENT AND THE GRENADINES" => "VC",
"SAMOA" => "WS",
"SAN MARINO" => "SM",
"SAO TOME AND PRINCIPE" => "ST",
"SAUDI ARABIA" => "SA",
"SENEGAL" => "SN",
"SERBIA" => "RS",
"SEYCHELLES" => "SC",
"SIERRA LEONE" => "SL",
"SINGAPORE" => "SG",
"SINT MAARTEN (DUTCH PART)" => "SX",
"SLOVAKIA" => "SK",
"SLOVENIA" => "SI",
"SOLOMON ISLANDS" => "SB",
"SOMALIA" => "SO",
"SOUTH AFRICA" => "ZA",
"SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS" => "GS",
"SOUTH SUDAN" => "SS",
"SPAIN" => "ES",
"SRI LANKA" => "LK",
"SUDAN" => "SD",
"SURINAME" => "SR",
"SVALBARD AND JAN MAYEN" => "SJ",
"SWAZILAND" => "SZ",
"SWEDEN" => "SE",
"SWITZERLAND" => "CH",
"SYRIAN ARAB REPUBLIC" => "SY",
"TAIWAN, PROVINCE OF CHINA" => "TW",
"TAJIKISTAN" => "TJ",
"TANZANIA, UNITED REPUBLIC OF" => "TZ",
"THAILAND" => "TH",
"TIMOR-LESTE" => "TL",
"TOGO" => "TG",
"TOKELAU" => "TK",
"TONGA" => "TO",
"TRINIDAD AND TOBAGO" => "TT",
"TUNISIA" => "TN",
"TURKEY" => "TR",
"TURKS AND CAICOS ISLANDS" => "TC",
"TUVALU" => "TV",
"UGANDA" => "UG",
"UKRAINE" => "UA",
"UNITED ARAB EMIRATES" => "AE",
"UNITED KINGDOM" => "GB",
"UNITED STATES" => "US",
"UNITED STATES MINOR OUTLYING ISLANDS" => "UM",
"URUGUAY" => "UY",
"UZBEKISTAN" => "UZ",
"VANUATU" => "VU",
"VENEZUELA, BOLIVARIAN REPUBLIC OF" => "VE",
"VIRGIN ISLANDS, BRITISH" => "VG",
"VIRGIN ISLANDS, U.S." => "VI",
"WALLIS AND FUTUNA" => "WF",
"WESTERN SAHARA" => "EH",
"YEMEN" => "YE",
"ZAMBIA" => "ZM",
"ZIMBABWE" => "ZW");
?>