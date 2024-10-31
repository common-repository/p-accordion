<?php
/*
Plugin Name: P-accordion
Plugin URI: https://wordpress.org/plugins/p-accordion/ 
Description: Plugin per gestire in modo personalizzato le strutture accordion
Version: 1.1.24
Author: Davide Brunner
Author URI: https://davidebrunner.com
License: GPLv2 or later
Domain Path: /languages
 */
	
	// define jquery lib (min 1.11.3)
	function p_accordion_scripts_load()
	{
	if(wp_script_is('jquery')) {
		// jquery loaded 
	} else {     
		wp_enqueue_script('jquery');
	    }
	}
	add_action( 'wp_enqueue_scripts', 'p_accordion_scripts_load' );
  
   	 function pp_accordion_add_script() {
	 wp_register_script('p_accordion_script', plugins_url( '/js/p_accordion.js', __FILE__ ));
     wp_enqueue_script('p_accordion_script');
     }
     add_action('wp_enqueue_scripts', 'pp_accordion_add_script');
	 
	 // Localization langue
	 function p_accordion_textdomain() {
	    load_plugin_textdomain( 'p_accordion', false, '/p-accordion/languages' );
     }
     add_action( 'init', 'p_accordion_textdomain' );
	 
	 function pp_accordion_add_css() {
	    wp_register_style('accordion_css_pp', plugins_url( '/css/pp-accordion.css', __FILE__ ));
	    wp_enqueue_style('accordion_css_pp');
     }
     //('wp_enqueue_scripts', 'pp_accordion_add_css'); // changed with  dynamic css system
 

    define('DYNAMICSCRIPTVERSION', '0.1.1');
	function dynamic_enqueue_scripts() {
		wp_enqueue_style(
			'dynamic-css', //handle
			admin_url( 'admin-ajax.php' ) . '?action=dynamic_css_action&wpnonce=' . wp_create_nonce( 'dynamic-css-nonce' ), // src
			array(), // dependencies
			DYNAMICSCRIPTVERSION // version number
		);
	}

	function dynamic_css_loader() {
    $nonce = $_REQUEST['wpnonce'];
    if ( ! wp_verify_nonce( $nonce, 'dynamic-css-nonce' ) ) {
        die( 'invalid nonce' );
    } else {
        /**
         * NOTE: Using require or include to call an URL (created by plugins_url() or get_template_directory(), can create the following error:
         *       Warning: require(): http:// wrapper is disabled in the server configuration by allow_url_include=0
         *       Warning: require(http://domain/path/dynamic-javascript.php): failed to open stream: no suitable wrapper could be found
         *       Fatal error: require(): Failed opening required 'http://domain/path/dynamic-javascript.php'
         */
        require_once dirname( __FILE__ ) . '/css/p_accordion_css.php';
    }
    exit;
}

	add_action( 'wp_enqueue_scripts', 'dynamic_enqueue_scripts' );
	add_action( 'wp_ajax_dynamic_css_action', 'dynamic_css_loader' );
	add_action( 'wp_ajax_nopriv_dynamic_css_action', 'dynamic_css_loader' );

 
// wrapper class
if ( !class_exists( "PPaccordion" ) )
{
	class PPaccordion  
	{	
		 function PPaccordion ()//Constructor
		 {
		 // This adds support for a "pp_accordion" shortcode
		 add_shortcode( 'pp_accordion', array( $this, 'pp_accordion_shortcode_fn' ) );
		 add_shortcode( 'pp_accordion_categorie', array( $this, 'pp_accordion_categorie_shortcode_fn' ) );
		 add_filter( 'plugin_row_meta', array($this,'_my_plugin_links'), 10, 2 );
		 }
		 
		 function pp_accordion_categorie_shortcode_fn ( $attributes, $content ) {
			extract( shortcode_atts( array(
				'titolocategoria' => "",
				'idcategoria' => rand(0, 1000000),
				'addclass' => "",
				'panelclass' => "",
				'titlecategorie' => "",
			), $attributes ) );
			
			if( $content <> '' ) {
				$content = do_shortcode( $content );
			}
			if ($titlecategorie <> '') {
			    $titolocategoria = htmlspecialchars( $titlecategorie, ENT_QUOTES);
		    } else {
			    if ($titolocategoria<>'')  $titolocategoria = htmlspecialchars( $titolocategoria, ENT_QUOTES);
		    }
			$current_p_accordion_categorie = $this->style_content_categorie( $titolocategoria, $idcategoria, $addclass, $panelclass, $content);
				
			if( @$current_p_accordion_categorie ) {
				return $current_p_accordion_categorie;
				} 
		 }
		 
		 function pp_accordion_shortcode_fn( $attributes, $content ) {
			extract( shortcode_atts( array(
				'titolo' => "",
				'id' => rand(0, 1000000),
				'addclass' => "",
				'panelclass' => "",
				'title' => "",
			), $attributes ) );

			// wrape content 
			if( $content <> '' ) {
				$content = do_shortcode( $content ); 
            }
			
			if ($title <> '') {
			    $titolo = htmlspecialchars( $title, ENT_QUOTES);
		    } else {
			    if ($titolo<>'') $titolo = htmlspecialchars( $titolo, ENT_QUOTES);
		    }
			$current_p_accordion = $this->style_content_pp( $titolo, $id, $addclass, $panelclass, $content );
								
			if( @$current_p_accordion ) {
				return $current_p_accordion;
				}
			} 
	  
			 // style the accordion
			function style_content_pp ( $titolo, $id, $class, $panelclass, $the_content ) { 
				if ($class<>''){ $class = str_replace(",", " ", $class);}
				if ($panelclass<>''){ $panelclass = str_replace(",", " ", $panelclass);}
				$contents = '<button id="btn'. $id .'" class="accordion ' . $class. '">'. $titolo . '</button>
				<div id="pnl'.  $id  .'" class="panel ' . $panelclass. '">
					 ' .$the_content .'<br>
				</div>';
				return  $contents;
			}
			
			// style the accordion categorie
			function style_content_categorie( $titolocategoria, $idcategoria, $class, $panelclass, $the_content_categorie ) {
				if ($class<>''){ $class = str_replace(",", " ", $class);}
				if ($panelclass<>''){ $panelclass = str_replace(",", " ", $panelclass);}
				$contents = '<button id="cat'. $idcategoria .'" class="accordion-categorie ' . $class. '">'. $titolocategoria . '</button>
				<div id="accat'.  $idcategoria  .'" class="panel-categories ' . $panelclass. '">
					 ' .$the_content_categorie .'
				</div>';
				return  $contents; 					
			}
			public function _my_plugin_links($links, $file) {
		    $plugin = plugin_basename(__FILE__); 
		    if ($file == $plugin) // only for this plugin
		            return array_merge( $links,
				array( '<a href="https://wordpress.org/support/plugin/p-accordion/">' . __('Plugin page' ) . '</a>' ),	
		        array( '<a href="https://wordpress.org/support/plugin/p-accordion/reviews/">' . __('Rating' ) . '</a>' ),				
		        array( '<a href="https://wordpress.org/support/plugin/p-accordion">' . __('Plugin Support') . '</a>' ),
		        array( '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=HTQKZQW6J54R8&lc=US&item_name=link_donate_en&item_number=p%2daccordion&no_note=0&cn=Ajouter%20des%20instructions%20particuli%c3%a8res%20pour%20le%20vendeur%20%3a&no_shipping=2&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank">' . __('Donate') . '</a>' )
		    );
		    return $links;
		}
    } // End Class
	
	
// Instantiating the Class
if (class_exists("PPaccordion")) {
	$PPaccordion = new PPaccordion();
}




//log and debug
if(!function_exists('_log')){
  function _log( $message ) {
    if( WP_DEBUG === true ){
      if( is_array( $message ) || is_object( $message ) ){
        error_log( print_r( $message, true ) );
      } else {
        error_log( $message );
      }
    }
  }
}
	
	
}

 // ---------------  A  D  M  I  N   ----------------------
// ------------- Create option config menu ----------------
function p_accordion_add_option_page()
{
	//add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
    add_options_page('personal_accordion', __( 'Personal Accordion', 'invfr' ), 'administrator', 'p-accordion-options-page', 'p_accordion_update_options_form');
}
add_action('admin_menu', 'p_accordion_add_option_page');

// ----------------------------------- Config Page  -------------------------------------- >>

function p_accordion_update_options_form()
{
		if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'p_accordion' ) );
	}
	echo'<h1>' . __( 'Personal-accordion CSS setting.', 'p_accordion' ).'</h1>';
	echo '<div class="p-accordion-wrap"></div>';
	echo '<p>' . __( 'Configuration parameters plug-in.', 'p_accordion' ) . '</p>';
	echo '<form name="p-accordion-form" method="post" action="options.php">';
	settings_fields( 'p_accordion_option-group' );
	do_settings_sections( 'p_accordion_option-group' );
	?>
	    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php echo __( 'Categorie button color :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_categorie" class='p_accordion_color-field' value="<?php echo esc_attr( get_option('p_accordion_categorie') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php echo __( 'Categorie panel color :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_categorie_panel" class='p_accordion_color-field' value="<?php echo esc_attr( get_option('p_accordion_categorie_panel') ); ?>" /></td>
        </tr>
        <!--
		<tr valign="top">
        <th scope="row"><?php echo __( 'Simbol Categorie  :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_categorie_color" class='' value="<?php echo esc_attr( get_option('p_accordion_categorie_simbol') ); ?>" /></td>
        </tr>
		-->
		<tr valign="top">
        <th scope="row"><?php echo __( 'Title Categorie color :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_categorie_color" class='p_accordion_color-field' value="<?php echo esc_attr( get_option('p_accordion_categorie_color') ); ?>" /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row"><?php echo __( 'Title Categorie font size :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_categorie_font_size" class='' value="<?php echo esc_attr( get_option('p_accordion_categorie_font_size') ); ?>" />px.</td>
        </tr>
		
        <tr valign="top">
        <th scope="row"><?php echo __( 'Section color :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_section" class='p_accordion_color-field' value="<?php echo esc_attr( get_option('p_accordion_section') ); ?>" /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row"><?php echo __( 'Section panel color :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_section_panel" class='p_accordion_color-field' value="<?php echo esc_attr( get_option('p_accordion_section_panel') ); ?>" /></td>
        </tr>
		<!--
		<tr valign="top">
        <th scope="row"><?php echo __( 'Simbol Section :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_categorie_color" class='' value="<?php echo esc_attr( get_option('p_accordion_section_simbol') ); ?>" /></td>
        </tr>
		-->
		<tr valign="top">
        <th scope="row"><?php echo __( 'Title Section color :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_section_color" class='p_accordion_color-field' value="<?php echo esc_attr( get_option('p_accordion_section_color') ); ?>" /></td>
        </tr>
		
		<tr valign="top">
        <th scope="row"><?php echo __( 'Title Section font size :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_section_font_size" class='' value="<?php echo esc_attr( get_option('p_accordion_section_font_size') ); ?>" />px.</td>
        </tr>
		
		<tr valign="top">
        <th scope="row"><?php echo __( 'Radius :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_radius" class='' value="<?php echo esc_attr( get_option('p_accordion_radius') ); ?>" />px.</td>
        </tr>
		
		<!-- <tr valign="top">
        <th scope="row"><?php echo __( 'Time action :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_time_action" class='' value="<?php echo esc_attr( get_option('p_accordion_time_action') ); ?>" /></td>
        </tr> -->
		
		<tr valign="top">
        <th scope="row"><?php echo __( 'Trans color :', 'p_accordion' ); ?></th>
        <td><input type="text" name="p_accordion_trans_color" class='p_accordion_color-field' value="<?php echo esc_attr( get_option('p_accordion_trans_color') ); ?>" /><?php echo __( '(To disable the mouse-over insert only #).', 'p_accordion' ); ?></td>
        </tr>
		
    </table>	
	<?php
	submit_button(__( 'Save', 'p_accordion' ));

   
	echo '<input name="reset_all" type="button" value="Reset to default values" class="button" onclick="submit_form(this)" />'; 
    echo'</form>';
   echo '</div>';	
}

// --------- Create fields plug_in options page  ----------

function p_accordion_register_settings() { 
  register_setting( 'p_accordion_option-group', 'p_accordion_categorie' );
  register_setting( 'p_accordion_option-group', 'p_accordion_categorie_panel' );
  register_setting( 'p_accordion_option-group', 'p_accordion_section' );
  register_setting( 'p_accordion_option-group', 'p_accordion_section_panel' );
  register_setting( 'p_accordion_option-group', 'p_accordion_radius' );
  register_setting( 'p_accordion_option-group', 'p_accordion_time_action' );
  register_setting( 'p_accordion_option-group', 'p_accordion_trans_color' );
  register_setting( 'p_accordion_option-group', 'p_accordion_section_color' );
  register_setting( 'p_accordion_option-group', 'p_accordion_categorie_color' );
  register_setting( 'p_accordion_option-group', 'p_accordion_categorie_font_size' );
  register_setting( 'p_accordion_option-group', 'p_accordion_section_font_size' );
  register_setting( 'p_accordion_option-group', 'p_accordion_section_simbol' );
  register_setting( 'p_accordion_option-group', 'p_accordion_categorie_simbol' );
}

if ( is_admin() ){ // admin actions
  add_action( 'admin_init', 'p_accordion_register_settings' );
} else {
  // non-admin enqueues, actions, and filters
}

add_action( 'admin_enqueue_scripts', 'p_accordion_load_admin_scripts');

function p_accordion_load_admin_scripts( ) {
  wp_enqueue_style('wp-color-picker');
  wp_enqueue_script('p_accordion_plugin-script', plugins_url('js/p-accordion-admin.js', __FILE__), array('wp-color-picker'), false, true );
}

add_action( 'wp_ajax_reset_all', 'reset_p_accordion_action_callback' );
// Callback reset values
function reset_p_accordion_action_callback() {
  $action=$_POST["action"];
  if ( $action ) {
     if($action=="reset_all"){
         update_option( 'p_accordion_categorie', '#ffffff');
		 update_option( 'p_accordion_categorie_panel', '#ffffff');
		 update_option( 'p_accordion_section', '#CCCCCC');
		 update_option( 'p_accordion_section_panel', 'ffffff');
		 update_option( 'p_accordion_radius', '10');
		 update_option( 'p_accordion_time_action', '0');
		 update_option( 'p_accordion_trans_color', '#d67e95');
		 update_option( 'p_accordion_section_color', '#5e5e5e');
		 update_option( 'p_accordion_categorie_color', '#777');
		 update_option( 'p_accordion_categorie_font_size', '20');
		 update_option( 'p_accordion_section_font_size', '15');
		 update_option( 'p_accordion_section_simbol', '');
		 update_option( 'p_accordion_categorie_simbol', '');
		 

		 
		 echo'<div class="notice notice-success is-dismissible"> 
		 <p><strong>';
		 echo __( 'Reset default plugi-in configurations.', 'p_accordion' );
		 echo'</strong></p>
		 <button type="button" class="notice-dismiss">
		 <span class="screen-reader-text">Dismiss this notice.</span>
		 </button>
		 </div>';
     } 
  } else { echo "Not action"; }
  wp_die();
}

register_activation_hook( __FILE__, 'p_accordion_create_config' );

function p_accordion_create_config() {
         update_option( 'p_accordion_categorie', '#ffffff');
		 update_option( 'p_accordion_categorie_panel', '#ffffff');
		 update_option( 'p_accordion_section', '#CCCCCC');
		 update_option( 'p_accordion_section_panel', 'ffffff');
		 update_option( 'p_accordion_radius', '10');
		 update_option( 'p_accordion_time_action', '0');
		 update_option( 'p_accordion_trans_color', '#d67e95');
		 update_option( 'p_accordion_section_color', '#5e5e5e');
		 update_option( 'p_accordion_categorie_color', '#777');
		 update_option( 'p_accordion_categorie_font_size', '20');
		 update_option( 'p_accordion_section_font_size', '15');
		 update_option( 'p_accordion_section_simbol', '');
		 update_option( 'p_accordion_categorie_simbol', '');
}
?>