<?php
/*
 * rhswp-stelselcatalogus.php
 *
 * Plugin Name:   ICTU / WP Stelselplaat plugin
 * Plugin URI:    https://wbvb.nl/plugins/rhswp-stelselcatalogus/
 * Description:   De mogelijkheid om een stelselplaat te tonen op een pagina
 * Version:       0.0.3
 * Version desc:  Blokken donkerblauw_vol gemaakt. Velden via ACF.
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
  
  
  		// Add a filter to the attributes metabox to inject template into the cache.
  		add_filter(
  			'page_attributes_dropdown_pages_args',
  			array( $this, 'register_project_templates' ) 
  		);
  
  
  		// Add a filter to the save post to inject out template into the page cache
  		add_filter(
  			'wp_insert_post_data', 
  			array( $this, 'register_project_templates' ) 
  		);
  
  
  		// Add a filter to the template include to determine if the page has our 
  		// template assigned and return it's path
  		add_filter(
  			'template_include', 
  			array( $this, 'view_project_template') 
  		);
  
  
  		// Add your templates to this array.
  		$this->templates = array(
  			'stelselcatalogus-template.php' => 'Stelselplaat',
  		);


      // options page 
      if( function_exists('acf_add_options_page') ):
      
      	$args = array(
      		'slug' => 'stelselplaatinstellingen',
      		'title' => __( 'Instellingen stelselplaat', 'wp-rijkshuisstijl' ),
      		'parent' => 'themes.php'
      	); 
      	
      		acf_add_options_page($args);
      
      endif;

//      add_filter('upload_mimes', 'cc_mime_types');
			
  	} 
  


  	public function cc_mime_types($mimes) {
      $mimes['svg'] = 'image/svg+xml';
      return $mimes;
    }

  
  	/**
  	 * Adds our template to the pages cache in order to trick WordPress
  	 * into thinking the template file exists where it doens't really exist.
  	 *
  	 */
  
  	public function register_project_templates( $atts ) {
  
  		// Create the key used for the themes cache
  		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
  
  		// Retrieve the cache list. 
  		// If it doesn't exist, or it's empty prepare an array
  		$templates = wp_get_theme()->get_page_templates();
  		if ( empty( $templates ) ) {
  			$templates = array();
  		} 
  
  		// New cache, therefore remove the old one
  		wp_cache_delete( $cache_key , 'themes');
  
  		// Now add our template to the list of templates by merging our templates
  		// with the existing templates array from the cache.
  		$templates = array_merge( $templates, $this->templates );
  
  		// Add the modified cache to allow WordPress to pick it up for listing
  		// available templates
  		wp_cache_add( $cache_key, $templates, 'themes', 1800 );
  
  		return $atts;
  
  	} 
  
  	/**
  	 * Checks if the template is assigned to the page
  	 */
  	public function view_project_template( $template ) {
  		
  		// Get global post
  		global $post;
  
  		// Return template if post is empty
  		if ( ! $post ) {
  			return $template;
  		}
  
  		// Return default template if we don't have a custom one defined
  		if ( !isset( $this->templates[get_post_meta( 
  			$post->ID, '_wp_page_template', true 
  		)] ) ) {
  			return $template;
  		} 
  
  		$file = plugin_dir_path(__FILE__). get_post_meta( 
  			$post->ID, '_wp_page_template', true
  		);
  
  		// Just to be safe, we check if the file exist first
  		if ( file_exists( $file ) ) {
  			return $file;
  		} else {
  			echo $file;
  		}
  
  		// Return template
  		return $template;
  
  	}
  
  } 

add_action( 'plugins_loaded', array( 'Stelselplaat', 'get_instance' ) );

endif;

//add_action( 'plugins_loaded', array( 'Stelselplaat', 'init' ), 10 );