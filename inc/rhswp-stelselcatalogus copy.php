<?php
/*
 * owmsvelden. 
 *
 * Plugin Name:   ICTU / Plugin voor interactieve stelselplaat
 * Plugin URI:    https://wbvb.nl/plugins/rhswp-stelselcatalogus/
 * Description:   De mogelijkheid om een stelselplaat te tonen op een pagina
 * Version:       0.0.1
 * Version desc:  eerste versie
 * Author:        Paul van Buuren
 * Author URI:    https://wbvb.nl
 * License:       GPL-2.0+
 *
 * Text Domain: owmsvelden-translate
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

if ( ! class_exists( 'Stelselplaat' ) ) :

/**
 * Register the plugin.
 *
 * Display the administration panel, insert JavaScript etc.
 */
class Stelselplaat {

    /**
     * @var string
     */
    public $version = '0.0.1';


    /**
     * @var owmsvelden
     */
    public $stelselplaatvelden = null;
          


    /**
     * Init
     */
    public static function init() {

        $DO_STELSELPLAAT_this = new self();

    }


    /**
     * Constructor
     */
    public function __construct() {

        $this->define_constants();
        $this->includes();
        $this->setup_actions();
        if ( DO_STELSELPLAAT_DO_DEBUG ) {
          $this->setup_debug_filters();
        }
        $this->append_comboboxes();


    }


    /**
     * Define owmsvelden constants
     */
    private function define_constants() {

      $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';

      define( 'DO_STELSELPLAAT_VERSION',    $this->version );
      define( 'DO_STELSELPLAAT_FOLDER',     'rhswp-stelselcatalogus' );
      define( 'DO_STELSELPLAAT_BASE_URL',   trailingslashit( plugins_url( DO_STELSELPLAAT_FOLDER ) ) );
      define( 'DO_STELSELPLAAT_ASSETS_URL', trailingslashit( DO_STELSELPLAAT_BASE_URL . 'assets' ) );
      define( 'DO_STELSELPLAAT_MEDIAELEMENT_URL', trailingslashit( DO_STELSELPLAAT_BASE_URL . 'mediaelement' ) );
      define( 'DO_STELSELPLAAT_PATH',       plugin_dir_path( __FILE__ ) );
      define( 'DO_STELSELPLAAT_FIELD',      'stelselplaat_pf_' ); // prefix for owmsvelden metadata fields
      define( 'DO_STELSELPLAAT_DO_DEBUG',   false );
      define( 'DO_STELSELPLAAT_USE_CMB2',   true ); 

      
    }


    /**
     * All owmsvelden classes
     */
    private function plugin_classes() {

        return array(
            'stelselplaatSystemCheck'  => DO_STELSELPLAAT_PATH . 'inc/rhswp-stelselcatalogus.systemcheck.class.php',
        );

    }




    /**
     * Load required classes
     */
    private function includes() {
    
      if ( DO_STELSELPLAAT_USE_CMB2 ) {
        // load CMB2 functionality
        if ( ! defined( 'CMB2_LOADED' ) ) {
          // cmb2 NOT loaded
          if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
            require_once dirname( __FILE__ ) . '/cmb2/init.php';
          }
          elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
            require_once dirname( __FILE__ ) . '/CMB2/init.php';
          }
        }
      }
      
      
      
      $autoload_is_disabled = defined( 'DO_STELSELPLAAT_AUTOLOAD_CLASSES' ) && DO_STELSELPLAAT_AUTOLOAD_CLASSES === false;
      
      if ( function_exists( "spl_autoload_register" ) && ! ( $autoload_is_disabled ) ) {
        
        // >= PHP 5.2 - Use auto loading
        if ( function_exists( "__autoload" ) ) {
          spl_autoload_register( "__autoload" );
        }
        spl_autoload_register( array( $this, 'autoload' ) );
        
      } 
      else {
        // < PHP5.2 - Require all classes
        foreach ( $this->plugin_classes() as $id => $path ) {
          if ( is_readable( $path ) && ! class_exists( $id ) ) {
            require_once( $path );
          }
        }
        
      }
    
    }







    /**
     * filter for when the CPT is previewed
     */
    public function owms_debug_info($content = '') {

      global $post;


      $output = $this->get_header_data( $post->ID );
      $output = str_replace( '>', "&gt;", $output );
      $output = str_replace( '<', "&lt;", $output );


      if ( DO_STELSELPLAAT_DO_DEBUG && WP_DEBUG ) {
        return '<pre>' . $output . '</pre>' . $content;
      }
      else {
        return $content; 
      }
      
      
    }


  	public function is_posts_page() {
  		return ( is_home() && 'page' == get_option( 'show_on_front' ) );
  	}


    /**
     * filter for when the CPT is previewed
     */
    public function owms_debug_info_title(  $title, $id = null ) {

      global $post;

      
      $output = $this->get_header_data( $post->ID );
      $output = str_replace( '>', "&gt;", $output );
      $output = str_replace( '<', "&lt;", $output );


      if ( DO_STELSELPLAAT_DO_DEBUG && WP_DEBUG ) {
    
        $currenturl = get_page_uri( $post->ID  );
        $postpage   = get_permalink( get_option( 'page_for_posts' ) );

        echo '<div style="border: 1px solid black; white-space: pre-wrap; font-size: 80%; padding: 1em; margin: 1em;"><p>OWMS debug info:</p><pre>' . $output . '</pre></div>';
        

      }
      
      
    }


    /**
     * Autoload owmsvelden classes to reduce memory consumption
     */
    public function autoload( $class ) {

        $classes = $this->plugin_classes();

        $class_name = strtolower( $class );

        if ( isset( $classes[$class_name] ) && is_readable( $classes[$class_name] ) ) {
            require_once( $classes[$class_name] );
        }

    }



    /**
     * Hook owmsvelden into WordPress
     */
    private function setup_actions() {

      add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
      
      add_action( 'wp_head', array( $this, 'write_header_data' ), 4 );

    }



    /**
     * Hook owmsvelden into WordPress
     */
    private function setup_debug_filters() {

      	// content filter
      	

        global $wp_query;
        $loop = 'notfound';

      	if ( $wp_query->is_single ) {
        	
          add_filter( 'the_content', array( $this, 'owms_debug_info' ) );
          
      	}
      	else {

          // Find Genesis Theme Data
          $checkgenesis = wp_get_theme( 'genesis' );
          
          if ( $checkgenesis ) {
            // genesis is available.
  
            $theme_info = wp_get_theme();
            
            $genesis_flavors = array(
              'genesis',
              'genesis-trunk',
            );
            
            if ( in_array( $theme_info->Template, $genesis_flavors ) ) {
  
              //* Add some debug info to the genesis_before action.
              add_action( 'genesis_before', array( $this, 'owms_debug_info_title' ), 10, 2 );            
  
            }
            else {
              // genesis exists, but is not activated
              add_filter( 'the_content', array( $this, 'owms_debug_info' ) );
              
            }
          }
          else {
            // genesis does not exist in this environment
            add_filter( 'the_content', array( $this, 'owms_debug_info' ) );

          }
  
      	}



    }




    /**
     * Write out the header data
     */
    public function write_header_data() {

      global $post;

      echo $this->get_header_data( $post->ID );

    }


    /**
     * Initialise translations
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain( "owmsvelden-translate", false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


    }

    


    /**
     * Register admin-side styles
     */
    public function register_admin_styles() {

        wp_enqueue_style( 'owmsvelden-admin-styles', DO_STELSELPLAAT_ASSETS_URL . 'css/admin.css', false, DO_STELSELPLAAT_VERSION );

        do_action( 'DO_STELSELPLAAT_register_admin_styles' );

    }


    /**
     * Register admin JavaScript
     */
    public function register_admin_scripts() {

        // media library dependencies
        wp_enqueue_media();

        // plugin dependencies
        wp_enqueue_script( 'jquery-ui-core', array( 'jquery' ) );

        wp_dequeue_script( 'link' ); // WP Posts Filter Fix (Advanced Settings not toggling)
        wp_dequeue_script( 'ai1ec_requirejs' ); // All In One Events Calendar Fix (Advanced Settings not toggling)


        do_action( 'DO_STELSELPLAAT_register_admin_scripts' );

    }


    //========================================================================================================
    /**
     * Output the HTML
     */
    public function get_header_data( $postid ) {
      
      global $post;
      global $wp_query;
      
      if ( $postid ) {
        $postid = $postid;
      }
      else {
        $postid = $post->ID;
      }

      if ( $this->is_posts_page() ) {
        $postid = get_option( 'page_for_posts' );
      }


      $returnstring         = '';      
      $owms_title           = '';
      $owms_type            = 'webpagina';
      $owms_identifier       = get_permalink( $postid );
      $owms_language        = $this->get_stored_values( $postid, DO_STELSELPLAAT_FIELD . 'language', '' );
      $owms_rights          = $this->get_stored_values( $postid, DO_STELSELPLAAT_FIELD . 'rights', '' );
      $currentposttype      = get_post_type( $postid );

      $owms_authority       = $this->get_stored_values( $postid, DO_STELSELPLAAT_FIELD . 'authority', '' );
      $owms_creator         = $this->get_stored_values( $postid, DO_STELSELPLAAT_FIELD . 'creator', '' );
      $owms_spatial         = $this->get_stored_values( $postid, DO_STELSELPLAAT_FIELD . 'spatial', '' );
      
      
      $owms_date_modified   = get_the_modified_time( 'Y-m-d\TH:i:s' );
      $owms_date_published  = get_the_date( get_option( 'date_format' ), $postid );
      

      if ( $currentposttype === 'post' ) {
        $owms_type      = 'nieuwsbericht';
      }
      


      return $returnstring;

    }

    //========================================================================================================

    private function get_stored_values( $postid, $postkey, $defaultvalue = '' ) {

      if ( DO_STELSELPLAAT_DO_DEBUG ) {
        $returnstring = $defaultvalue;
      }
      else {
        $returnstring = '';
      }

      $temp = get_post_meta( $postid, $postkey, true );
      if ( $temp ) {
        $returnstring = $temp;
      }
      
      return $returnstring;
    }

    //========================================================================================================
    
    
    
    public function append_comboboxes() {

    
    if ( DO_STELSELPLAAT_USE_CMB2 ) {
      
      if ( ! defined( 'CMB2_LOADED' ) ) {
        die( ' CMB2_LOADED not loaded ' );
        return false;
        // cmb2 NOT loaded
      }
      else {
        // okidokie!
      }
    
      add_action( 'cmb2_admin_init', 'rhswp_register_metabox_stelselvelden' );
    
      /**
       * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
       */
      function rhswp_register_metabox_stelselvelden() {

      	/**
      	 * Metabox with fields for the video
      	 */
      	$cmb2_metafields = new_cmb2_box( array(
          'id'            => DO_STELSELPLAAT_FIELD . 'metabox',
          'title'         => __( 'OWMS velden', "owmsvelden-translate" ),
          'object_types'  => array( 'page', 'post' ), // Post type
      	) );
    
      	/**
      	 * The fields
      	 */


        $cmb2_metafields->add_field( array(
        'name'             => __( 'Taal van deze pagina', "owmsvelden-translate" ),
        'desc'             => __( 'Taal van deze pagina', "owmsvelden-translate" ),
        'id'            => DO_STELSELPLAAT_FIELD . 'language',
        'type'             => 'select',
        'show_option_none' => false,
        'options'          => array(
          'nl-NL'   => __( 'Nederlands (nl-NL)', "owmsvelden-translate" ),
          'en-GB'   => __( 'Engels (en-GB)', "owmsvelden-translate" ),
          'en-US'   => __( 'Engels (n-US)', "owmsvelden-translate" ),
          'pap-AW'  => __( 'Papiamento (pap-AW)', "owmsvelden-translate" ),
          'pap-CW'  => __( 'Papiamentu (pap-CW)', "owmsvelden-translate" ),
        ),
        ) );

        $cmb2_metafields->add_field( array(
        'name'             => __( 'Gebruiksrechten voor de pagina', "owmsvelden-translate" ),
        'desc'             => __( '(auteursrechtelijke licentie)', "owmsvelden-translate" ),
        'id'            => DO_STELSELPLAAT_FIELD . 'rights',
        'type'             => 'select',
        'show_option_none' => false,
        'options'          => array(
          ''   => __( 'Niet van toepassing', "owmsvelden-translate" ),
          'CC0 1.0 Universal'   => __( 'CC0 1.0 Universal', "owmsvelden-translate" ),
        ),
        ) );

        $cmb2_metafields->add_field( array(
        'name'             => __( 'authority', "owmsvelden-translate" ),
        'desc'             => __( 'authority', "owmsvelden-translate" ),
        'id'            => DO_STELSELPLAAT_FIELD . 'authority',
        'type'             => 'select',
        'show_option_none' => false,
        'options'          => $activeministeries,
        ) );

        $cmb2_metafields->add_field( array(
        'name'             => __( 'creator', "owmsvelden-translate" ),
        'desc'             => __( 'creator', "owmsvelden-translate" ),
        'id'            => DO_STELSELPLAAT_FIELD . 'creator',
        'type'             => 'select',
        'show_option_none' => false,
        'options'          => $activeministeries,
        ) );


        require_once dirname( __FILE__ ) . '/inc/cmb2-check-required-fields.php';

      }
    
    
    }  // DO_STELSELPLAAT_USE_CMB2
    
}    

    //========================================================================================================
    



    /**
     * Check our WordPress installation is compatible with owmsvelden
     */
    public function do_system_check() {

        $systemCheck = new stelselplaatSystemCheck();
        $systemCheck->check();

    }

}

endif;

add_action( 'plugins_loaded', array( 'Stelselplaat', 'init' ), 10 );