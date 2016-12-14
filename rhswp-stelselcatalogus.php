<?php
/*
 * rhswp-stelselcatalogus.php
 *
 * Plugin Name:   ICTU / WP Stelselplaat plugin
 * Plugin URI:    https://wbvb.nl/plugins/rhswp-stelselcatalogus/
 * Description:   De mogelijkheid om een stelselplaat te tonen op een pagina
 * Version:       1.0.4
 * Version desc:  Bug: check op template ingebouwd, op hoger niveau
 * Author:        Paul van Buuren
 * Author URI:    https://wbvb.nl
 * License:       GPL-2.0+
 *
 * Text Domain: rhswp-stelselcatalogus
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

if ( ! class_exists( 'Stelselplaat' ) ) :

  class Stelselplaat {
  
  	/**
  	 * A reference to an instance of this class.
  	 */
  	private static $instance;
  
  	/**
  	 * The array of templates that this plugin tracks.
  	 */
  	protected $templates;

  	/**
  	 * Returns an instance of this class. 
  	 */
  	public static function get_instance() {
  
  		if ( null == self::$instance ) {
  			self::$instance = new Stelselplaat();
  		} 
  
  		return self::$instance;
  
  	} 
  
  	/**
  	 * Initializes the plugin by setting filters and administration functions.
  	 */
  	private function __construct() {
  
  		$this->templates = array();
  		
  		$this->templatefile   		= 'stelselcatalogus-template.php';

  		// add the page templates
  		add_filter( 'theme_page_templates', array( $this, 'rhswp_stelselplaat_add_page_templates' ) );
  		
  		// activate the page filters
  		add_action( 'template_redirect',    array( $this, 'rhswp_stelselplaat_use_page_template' )  );


      // options page 
      if( function_exists('acf_add_options_page') ) {

      	$args = array(
      		'slug' => 'stelselplaatinstellingen',
      		'title' => __( 'Instellingen stelselplaat', 'rhswp-stelselcatalogus' ),
      		'parent' => 'themes.php'
      	); 
      	
      		acf_add_options_page($args);
      
      }

      // register ACF fields
    	require_once plugin_dir_path( __FILE__ ) . 'inc/acf-velden.php';

  	} 

    /**
     * Hides the custom post template for pages on WordPress 4.6 and older
     *
     * @param array $post_templates Array of page templates. Keys are filenames, values are translated names.
     * @return array Expanded array of page templates.
     */
    function rhswp_stelselplaat_add_page_templates( $post_templates ) {

      $post_templates[$this->templatefile]  		= __( 'Stelselplaat', 'rhswp-stelselcatalogus' );    
      return $post_templates;
      
    }

  	/**
  	 * Modify page content if using a specific page template.
  	 */
  	public function rhswp_stelselplaat_use_page_template() {

      global $post;

  		$page_template = get_post_meta( get_the_ID(), '_wp_page_template', true );

      define( 'DO_STELSELPLAAT_FOLDER',     'rhswp-stelselcatalogus' );
      define( 'DO_STELSELPLAAT_BASE_URL',   trailingslashit( plugins_url( DO_STELSELPLAAT_FOLDER ) ) );
      define( 'DO_STELSELPLAAT_PATH',       plugin_dir_path( __FILE__ ) );

    	$page_template  = get_post_meta( get_the_ID(), '_wp_page_template', true );
    
    	if ( $this->templatefile == $page_template ) {
        
        //* Force full-width-content layout
        add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
      
        // write stelselplaat before page content
    		add_action( 'genesis_entry_content',    array( $this, 'rhswp_stelselplaat_pre_post_content' )  );
      
        // add the CSS file and custom CSS
    		add_action( 'wp_enqueue_scripts',    array( $this, 'rhswp_stelselplaat_header_enqueue_js_css' )  );
      
        // Add hook for JS footer
    		add_action( 'wp_footer',    array( $this, 'rhswp_stelselplaat_js_in_footer' )  );
      
      }

  	}
  
  

    //========================================================================================================
    /**
     * Append the CSS and JS to header
     *
     */
    
    function rhswp_stelselplaat_header_enqueue_js_css() {
    
      $custom_css = '';
      
      // general custom CSS
      $stelselplaat_css    = get_field('stelselplaat_css', 'option');
    	if ( $stelselplaat_css ) {
    		$custom_css .= $stelselplaat_css;
    	}
    
      // CSS per bouwsteen
      if( have_rows('stelselplaat_bouwstenen', 'option') ): 
    
        while( have_rows('stelselplaat_bouwstenen', 'option') ): the_row(); 
      
      		// vars
      		$stelselplaat_bouwsteen_css      = get_sub_field('stelselplaat_bouwsteen_css');
      		if ( $stelselplaat_bouwsteen_css ) {
        		$custom_css .= $stelselplaat_bouwsteen_css;
      		}
    
        endwhile; 
    
      endif; 
      
      // enqueue style and the variable CSS
    	wp_enqueue_style( 'stelselplaat-css', DO_STELSELPLAAT_BASE_URL . 'css/stelselplaat.css'	);
      wp_add_inline_style( 'stelselplaat-css', $custom_css );  
    
      // enqueue minified JS
      wp_enqueue_script( 'stelselplaat-min', DO_STELSELPLAAT_BASE_URL . 'js/min/stelselplaat-min.js', array( 'jquery' ) );
    
    }
    
    //========================================================================================================
    /**
     * Append JS to footer
     *
     */
    
    function rhswp_stelselplaat_js_in_footer() {
    
      $stelselplaat_pijlenschema        = get_field('stelselplaat_pijlenschema', 'option');
      $basisplaat                       = $stelselplaat_pijlenschema['url'];
      $stelselplaat_json                = preg_replace('/\s+/', '', strip_tags( get_field('stelselplaat_json', 'option') ) );
      $stelselplaat_begrippenrelaties   = preg_replace('/\s+/', '', strip_tags( get_field('stelselplaat_begrippenrelaties', 'option') ) );
      $stelselplaat_image_location      = get_field('stelselplaat_image_location', 'option');
    
    
      // add css to header  
      echo '<script type="text/javascript">
        var fileref=document.createElement("link");
        fileref.setAttribute("rel", "stylesheet");
        fileref.setAttribute("type", "text/css");
        fileref.setAttribute("media", "screen and (min-width: 900px)");
        fileref.setAttribute("href", "' . DO_STELSELPLAAT_BASE_URL . 'css/stelselplaat-js-enabled.css");
        document.getElementsByTagName("head")[0].appendChild(fileref);var stelselplaat = stelselplaat || {};';
    
      echo "\n jQuery(document).ready(function($) {\n";
    
      
      if ( $stelselplaat_json ) {
    //    echo 'stelselplaat.relations = jQuery.parseJSON(\'' . $stelselplaat_json . '\');';
        echo "\n stelselplaat.relations = jQuery.parseJSON('" . $stelselplaat_json . "');";
      }
        
      if ( $stelselplaat_begrippenrelaties ) {
        echo "\n stelselplaat.begrippen_relations = jQuery.parseJSON('" . $stelselplaat_begrippenrelaties . "');\n";
      }
      
      if ( $stelselplaat_image_location ) {
        echo 'stelselplaat.image_location =  "' . $stelselplaat_image_location . "\";\n";
      }
      else {
        echo 'stelselplaat.image_location =  "' . DO_STELSELPLAAT_BASE_URL . "images/pijlenschemas/\";\n";
      }
      
      if ( $basisplaat ) {
        echo "stelselplaat.basis_plaat =  \"" . $basisplaat . "\";\n";
      }
      else {
        echo "stelselplaat.basis_plaat =  \"" . DO_STELSELPLAAT_BASE_URL . "images/pijlenschemas/pijlen.svg\";\n";
    //    echo "stelselplaat.basis_plaat =  \"" . DO_STELSELPLAAT_BASE_URL . "images/pijlenschemas/pijlen.png\";\n";
      }
      
      if( have_rows('stelselplaat_bouwstenen', 'option') ): 
    
        while( have_rows('stelselplaat_bouwstenen', 'option') ): the_row(); 
      
      		// vars
      		$stelselplaat_bouwsteen_id      = get_sub_field('stelselplaat_bouwsteen_id');
      		$stelselplaat_pijlenschema      = get_sub_field('stelselplaat_bouwsteen_pijlenschema_voor_hover');
          $basisplaat                     = $stelselplaat_pijlenschema['url'];
          
          if ( $basisplaat ) {
            echo 'stelselplaat.hoverimages_' . strtolower($stelselplaat_bouwsteen_id) . ' =  "' . $basisplaat . "\";\n";
          }
      
        endwhile; 
    
      endif; 
    
      echo "});\n</script>";
    
    }
    
    //========================================================================================================
    /**
     * Some text before the stelselplaat and container tags
     *
     */
    
    function rhswp_stelselplaat_pre_post_content() {


        echo '<div id="page" class="stelselplaat">';
      
        $stelselplaat_introductie   = get_field('stelselplaat_introductie', 'option');
        echo $stelselplaat_introductie;
      
        echo '<div id="stelselplaat-container">';
        echo '<div id="adaptoratio">';
      
        $this->rhswp_stelselplaat_write_stelselplaat();
      
        echo '</div>'; // id="adaptoratio";
        echo '</div>'; // id="stelselplaat-container";

    }
    
    //========================================================================================================
    
    function rhswp_stelselplaat_write_stelselplaat() {
    
      $stelselplaat_pijlenschema  = get_field('stelselplaat_pijlenschema',  'option');
      $stelselplaat_veld_basis    = get_field('stelselplaat_veld_basis',    'option');
      $stelselplaat_legenda       = get_field('stelselplaat_legenda',       'option');
    
      $needle                     = '__IMAGE__';
      
      if( $stelselplaat_pijlenschema ) {
        $replacer                   = $stelselplaat_pijlenschema['url'];
      }
      else {
        $replacer                   = DO_STELSELPLAAT_BASE_URL . "images/pijlenschemas/pijlen.svg";
      }
    
      $stelselplaat_veld_basis    = str_replace( $needle, $replacer, $stelselplaat_veld_basis);
    
      echo $stelselplaat_veld_basis;
      echo $stelselplaat_legenda;
    
      // bouwstenen
      if( have_rows('stelselplaat_bouwstenen', 'option') ): 
      
        while( have_rows('stelselplaat_bouwstenen', 'option') ): the_row(); 
      
      		// vars
      		$stelselplaat_bouwsteen_id              = get_sub_field('stelselplaat_bouwsteen_id');
      		$stelselplaat_bouwsteen_heading         = get_sub_field('stelselplaat_bouwsteen_heading');
      		$stelselplaat_bouwsteen_titel_abbr      = get_sub_field('stelselplaat_bouwsteen_titel_abbr');
      		$stelselplaat_bouwsteen_inhoud          = get_sub_field('stelselplaat_bouwsteen_inhoud');
      		$stelselplaat_bouwsteen_ik              = get_sub_field('stelselplaat_bouwsteen_ik');
      		$stelselplaat_bouwsteen_zij_links       = get_sub_field('stelselplaat_bouwsteen_zij_maakt_gebruik_van');
      		$stelselplaat_bouwsteen_zij_rechts      = get_sub_field('stelselplaat_bouwsteen_zij_wordt_gebruikt_door');
      		$stelselplaat_bouwsteen_leverancier     = get_sub_field('stelselplaat_bouwsteen_leverancier');
      		$stelselplaat_bouwsteen_gepubliceerd    = get_sub_field('stelselplaat_bouwsteen_gepubliceerd');
      		if ( get_sub_field('stelselplaat_link_naar_dossier') ) {
        		$stelselplaat_link_naar_dossier        = get_sub_field('stelselplaat_link_naar_dossier');
        		$stelselplaat_link_naar_dossier        = '<p class="dossier-link"><a href="' . get_term_link( $stelselplaat_link_naar_dossier ) . '">' . __( 'Bekijk het dossier', 'rhswp-stelselcatalogus' ) . '</a></p>';
      		}
      		else {
        		$stelselplaat_link_naar_dossier = '';
      		}
        
        
          if ( 'gepubliceerd' == $stelselplaat_bouwsteen_gepubliceerd && $stelselplaat_bouwsteen_id ) {
    
            $needle                     = '__GEOMETRIE__';
            $replacer                   = '<img class="geometrie" src="' . DO_STELSELPLAAT_BASE_URL . 'images/geo.svg" alt="Icon - Bevat geometrie" width="20" height="20" />';
            $stelselplaat_bouwsteen_ik            = str_replace( $needle, $replacer, $stelselplaat_bouwsteen_ik);
            $stelselplaat_bouwsteen_zij_links     = str_replace( $needle, $replacer, $stelselplaat_bouwsteen_zij_links);
            $stelselplaat_bouwsteen_zij_rechts    = str_replace( $needle, $replacer, $stelselplaat_bouwsteen_zij_rechts);
    
            echo '<div id="' . $stelselplaat_bouwsteen_id . '" class="br ' . $stelselplaat_bouwsteen_id . ' hide_js">
                <h2>' . $stelselplaat_bouwsteen_heading . '</h2>
                <div class="statusBasisregistratie">' . $stelselplaat_link_naar_dossier . $stelselplaat_bouwsteen_inhoud . '</div>
                <div class="zij maakt-gebruik-van">
                  <h3>' . $stelselplaat_bouwsteen_titel_abbr . ' ' . __( 'maakt gebruik van', 'rhswp-stelselcatalogus' ) . '</h3>
                  ' . $stelselplaat_bouwsteen_zij_links . '
                </div>
                <div class="ik">
                  <h3>' . $stelselplaat_bouwsteen_titel_abbr . '</h3>
                  ' . $stelselplaat_bouwsteen_ik . '
                </div>
                <div class="zij wordt-gebruikt-door">
                  <h3>' . $stelselplaat_bouwsteen_titel_abbr . ' ' . __( 'wordt gebruikt door', 'rhswp-stelselcatalogus' ) . '</h3>
                  ' . $stelselplaat_bouwsteen_zij_rechts . '
                </div>
                <div class="leverancier">
                  <h3>' . $stelselplaat_bouwsteen_titel_abbr . ' ' . __( 'levert de volgende gegevens door', 'rhswp-stelselcatalogus' ) . '</h3>
                  ' . $stelselplaat_bouwsteen_leverancier . '
                </div>
              </div>
            ';
          }
      
      
        endwhile; 
    
      endif; 
    
    }
    
    //========================================================================================================

  
  } 

add_action( 'plugins_loaded', array( 'Stelselplaat', 'get_instance' ) );

add_filter( 'theme_page_templates', 'rhswp_stelselplaat_add_page_templates' );


    
    /**
     * Hides the custom post template for pages on WordPress 4.6 and older
     *
     * @param array $post_templates Array of page templates. Keys are filenames, values are translated names.
     * @return array Filtered array of page templates.
     */
    function rhswp_stelselplaat_add_page_templates( $post_templates ) {
    
      if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
      }
      else {
        $post_templates['stelselcatalogus-template.php'] = 'Stelselplaat';    
      }

      return $post_templates;
      
    }
  


endif;

//================================================================================================

