<?php
/*
 * rhswp-stelselcatalogus.php
 *
 * Plugin Name:   ICTU / WP Stelselplaat plugin
 * Plugin URI:    https://wbvb.nl/plugins/rhswp-stelselcatalogus/
 * Description:   De mogelijkheid om een stelselplaat te tonen op een pagina
 * Version:       0.0.6
 * Version desc:  Fallbacks voor pijlenschema. 
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

      // register ACF fields

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_582d863b1a66b',
	'title' => 'Instellingen voor de stelselplaat',
	'fields' => array (
		array (
			'key' => 'field_582d88796e173',
			'label' => 'Introductie',
			'name' => 'stelselplaat_introductie',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
		),
		array (
			'key' => 'field_582dc6c3a2f9f',
			'label' => 'Achtergrondplaatje voor pijlenschema',
			'name' => 'stelselplaat_pijlenschema',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'preview_size' => 'medium',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array (
			'key' => 'field_582d88dd6e174',
			'label' => 'Veld basis',
			'name' => 'stelselplaat_veld_basis',
			'type' => 'wysiwyg',
			'instructions' => '<p>Bevat:<br>
de list items voor de elementen in de kaart (ul.stelsel) </p>
<p>Deze regel moet als tweede list-item worden toegevoegd:<br>
&lt;li class="relaties"&gt;&lt;img src="__IMAGE__" alt="Relaties in het stelsel" /&gt;&lt;/li&gt;</p>

<p>__IMAGE__ wordt hier gebruikt als placeholder. Het door jou onder \'Achtergrondplaatje voor pijlenschema\' geüploade plaatje wordt hier ingevoegd.</p>',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'VELD BASIS
<ul class="stelsel">
 	<li id="BRP_li" class="gereed"><em>Basisregistratie</em> <strong>Personen</strong></li>
 	<li class="relaties"><img src="__IMAGE__" alt="Relaties in het stelsel" /></li>
 	<li id="BRV_li" class="gereed"><a href="#BRV"><em>Basisregistratie</em> <strong>Voertuigen</strong></a></li>
 	<li id="BRI_li" class="gereed"><a href="#BRI"><em>Basisregistratie</em> <strong>Inkomen</strong></a></li>
 	<li id="BLAU_li" class="nietgereed"><a href="#BLAU"><strong>BLAU</strong></a></li>
 	<li id="BRK_li" class="gereed"><a href="#BRK"><em>Basisregistratie</em> <strong>Kadaster</strong></a></li>
 	<li id="BRT_li" class="gereed"><a href="#BRT"><em>Basisregistratie</em> <strong>Topografie</strong></a></li>
 	<li id="BGT_li" class="nietgereed"><a href="#BGT"><em>Basisregistratie</em> <strong>Grootschalige topografie</strong></a></li>
 	<li id="BRO_li" class="nietgereed"><a href="#BRO"><em>Basisregistratie</em> <strong>Ondergrond</strong></a></li>
 	<li id="BAG_li" class="gereed"><a href="#BAG"><em>Basisregistraties</em> <strong>Adressen en Gebouwen</strong> </a></li>
 	<li id="WOZ_li" class="gereed"><a href="#WOZ"><em>Basisregistratie</em> <strong>WOZ</strong></a></li>
 	<li id="NHR_li" class="gereed"><a href="#NHR"><strong>Handelsregister</strong></a></li>
 	<li id="BRP-ni_li" class="gereed"><a href="#BRP-ni"> <strong>niet-ingezetenen</strong> </a></li>
 	<li id="BRP-i_li" class="gereed"><a href="#BRP-i"> <strong>ingezetenen</strong> </a></li>
</ul>',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
		),
		array (
			'key' => 'field_582dd00097151',
			'label' => 'Legenda',
			'name' => 'stelselplaat_legenda',
			'type' => 'wysiwyg',
			'instructions' => 'de items voor de legenda (ul.legenda)',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '<ul class="legenda">
	<li class="legenda_geo">Bevat geometrie</li>
	<li><a href="<URL>">Toelichting</a></li>
</ul>',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
		),
		array (
			'key' => 'field_582dd40ce7348',
			'label' => 'JSON',
			'name' => 'stelselplaat_json',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '{
	 "BRV":[
			"NHR",
			"BRP-ni",
			"BRP-i",
			"BRP"
	 ],
	 "BRI":[
			"BRP-ni",
			"BRP-i",
			"BRP"
	 ],
	 "BLAU":[
			"NHR",
			"BRP-ni",
			"BRP-i",
			"BRP"
	 ],
	 "BRK":[
			"NHR",
			"BRP-ni",
			"BRP-i",
			"BRP",
			"BRT",
			"WOZ",
			"BGT",
			"BAG"
	 ],
	 "BRT":[
			"BAG",
			"BRK",
			"BGT"
	 ],
	 "BGT":[
			"BRT",
			"BAG",
			"BRK"
	 ],
	 "BRO":[
			"NHR"
	 ],
	 "BAG":[
			"BRT",
			"NHR",
			"BRK",
			"WOZ",
			"NHR",
			"BRK",
			"BGT",
			"BRP-i",
			"BRP"
	 ],
	 "WOZ":[
			"BRK",
			"BRP-i",
			"BRP-ni",
			"BRP",
			"NHR",
			"BAG"
	 ],
	 "NHR":[
			"BRO",
			"WOZ",
			"BRK",
			"BLAU",
			"BRV",
			"BAG",
			"BRP-i",
			"BRP-ni",
			"BRP"
	 ],
	 "BRP-i":[
			"WOZ",
			"NHR",
			"BLAU",
			"BRK",
			"BRV",
			"BRI",
			"BAG",
			"BRP"
	 ],
	 "BRP-ni":[
			"WOZ",
			"NHR",
			"BLAU",
			"BRK",
			"BRV",
			"BRI",
			"BRP"
	 ]
}',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
		),
		array (
			'key' => 'field_582dd4acf40d6',
			'label' => 'Begrippen relaties',
			'name' => 'stelselplaat_begrippenrelaties',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '{
	 "Adres":[
			[
				 "BRP-i"
			],
			[
				 "BRP-ni"
			],
			[
				 "BRP"
			],
			[
				 "NHR"
			],
			[
				 "WOZ"
			],
			[
				 "BAG"
			],
			[
				 "BRK"
			],
			[
				 "BRT"
			],
			[
				 "BLAU"
			],
			[
				 "BRV"
			]
	 ],
	 "Inkomen":[
			[
				 "BRI"
			]
	 ],
	 "Partner":[
			[
				 "BRP-i"
			],
			[
				 "BRP-ni"
			],
			[
				 "BRP"
			],
			[
				 "BRV"
			],
			[
				 "WOZ"
			]
	 ],
	 "Perceel":[
			[
				 "BRK"
			],
			[
				 "WOZ"
			]
	 ],
	 "Natuurlijk persoon":[
			[
				 "BRP-i"
			],
			[
				 "BRP-ni"
			],
			[
				 "BRP"
			],
			[
				 "NHR"
			],
			[
				 "WOZ"
			],
			[
				 "BRK"
			],
			[
				 "BRV"
			]
	 ],
	 "Buitenland":[
			[
				 "BRP-i"
			],
			[
				 "BRP-ni"
			],
			[
				 "BRP"
			],
			[
				 "NHR"
			],
			[
				 "BRK"
			],
			[
				 "WOZ"
			],
			[
				 "BAG"
			]
	 ],
	 "Kind":[
			[
				 "BRP-i"
			],
			[
				 "BRP-ni"
			],
			[
				 "BRP"
			],
			[
				 "NHR"
			]
	 ],
	 "Ouder":[
			[
				 "BRP-i"
			],
			[
				 "BRP-ni"
			],
			[
				 "BRP"
			]
	 ],
	 "Vermogen":[
			[
				 "NHR"
			],
			[
				 "WOZ"
			]
	 ],
	 "Woonland":[
			[
				 "BRP-i"
			],
			[
				 "BRP-ni"
			],
			[
				 "BRP"
			],
			[
				 "BRK"
			]
	 ]
}',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
		),
		array (
			'key' => 'field_582f27ff502d9',
			'label' => 'CSS',
			'name' => 'stelselplaat_css',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '#BRV_li {
	top: 15px;
	left: 20px;
	width: 110px;
}
#BRI_li {
	top: 15px;
	left: 150px;
	width: 110px;
}
#BLAU_li {
	top: 15px;
	left: 280px;
	width: 110px;
}
#BRK_li {
	top: 15px;
	left: 440px;
	width: 225px; /* 17%; */
}
#BRT_li {
	top: 15px;
	left: 725px;
}
#BGT_li {
	top: 160px;
	left: 725px;
	height: 75px;
}
#BRO_li {
	top: 255px;
	left: 725px;
	height: 75px;
}
#WOZ_li {
	top: 175px;
	left: 505px;
	width: 125px;
}
#BAG_li {
	top: 355px;
	left: 625px;
	width: 245px;
}
#NHR_li {
	top: 355px;
	left: 345px;
	width: 120px;
}
#BRP_li {
	top: 355px;
	left: 20px;
	width: 245px;
}
#BRP-ni_li {
	top: 395px;
	left: 30px;
	width: 110px;
	border: 1px solid #fff;
	height: 60px;
}
#BRP-i_li {
	top: 395px;
	left: 145px;
	width: 110px;
	border: 1px solid #fff;
	height: 60px;
}
#NHR_li .object {
	top: -105px;
}
#BRP-ni_li .object {
	top: -7px;
}
#BRP-i_li .object {
	top: -7px;
}
.mod.box .zij > ul > li {
	width: 63px;
}',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
		),
		array (
			'key' => 'field_582ec6bafa46e',
			'label' => 'Bouwstenen',
			'name' => 'stelselplaat_bouwstenen',
			'type' => 'repeater',
			'instructions' => 'Schema waar de velden uit de bouwsteen gebruikt worden: <br><img src="/wp-content/plugins/rhswp-stelselcatalogus/images/legenda-invoervelden.png" alt="Schema voor bouwsteen">',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => 'field_582ec6dafa46f',
			'min' => 1,
			'max' => '',
			'layout' => 'row',
			'button_label' => 'Nieuwe bouwsteen',
			'sub_fields' => array (
				array (
					'key' => 'field_582ec6dafa46f',
					'label' => 'ID	(1)',
					'name' => 'stelselplaat_bouwsteen_id',
					'type' => 'text',
					'instructions' => 'De titel is de exacte afkorting die gebruikt wordt voor elk element in de stelselplaat.',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array (
					'key' => 'field_582ec770fa471',
					'label' => 'Heading (2)',
					'name' => 'stelselplaat_bouwsteen_heading',
					'type' => 'text',
					'instructions' => 'De heading mag (beperkt) HTML bevatting. Toegestane tags: &lt;em&gt;',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array (
					'key' => 'field_582ee9c1cc00a',
					'label' => 'Titel met afkorting (3)',
					'name' => 'stelselplaat_bouwsteen_titel_abbr',
					'type' => 'text',
					'instructions' => 'Voorbeeld: &lt;abbr title="Basisregistraties Adressen en Gebouwen"&gt;BAG&lt;/abbr&gt;',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array (
					'key' => 'field_582ec893fa472',
					'label' => 'Inhoud (4)',
					'name' => 'stelselplaat_bouwsteen_inhoud',
					'type' => 'wysiwyg',
					'instructions' => 'Hier kun je tekst plaatsen over de status van deze bouwsteen.',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
				),
				array (
					'key' => 'field_582ecc19d3344',
					'label' => 'Maakt gebruik van (5)',
					'name' => 'stelselplaat_bouwsteen_zij_maakt_gebruik_van',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
				),
				array (
					'key' => 'field_582ec8c5fa473',
					'label' => 'Elementen in deze bouwsteen (6)',
					'name' => 'stelselplaat_bouwsteen_ik',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
				),
				array (
					'key' => 'field_582ecc41d3345',
					'label' => 'Wordt gebruikt door (7)',
					'name' => 'stelselplaat_bouwsteen_zij_wordt_gebruikt_door',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
				),
				array (
					'key' => 'field_582ecf40ee1df',
					'label' => 'Levert gegevens door (8)',
					'name' => 'stelselplaat_bouwsteen_leverancier',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
				),
				array (
					'key' => 'field_582ec726fa470',
					'label' => 'Status',
					'name' => 'stelselplaat_bouwsteen_gepubliceerd',
					'type' => 'radio',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array (
						'gepubliceerd' => 'Gepubliceerd',
						'gedepubliceerd' => 'Gedepubliceerd',
					),
					'allow_null' => 0,
					'other_choice' => 0,
					'save_other_choice' => 0,
					'default_value' => 'gepubliceerd',
					'layout' => 'horizontal',
					'return_format' => 'value',
				),
				array (
					'key' => 'field_582ed19e0fcfa',
					'label' => 'CSS',
					'name' => 'stelselplaat_bouwsteen_css',
					'type' => 'textarea',
					'instructions' => 'Extra CSS voor deze bouwsteen',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'maxlength' => '',
					'rows' => '',
					'new_lines' => '',
				),
				array (
					'key' => 'field_582ede1165ca1',
					'label' => 'Pijlenschema voor hover',
					'name' => 'stelselplaat_bouwsteen_pijlenschema_voor_hover',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'array',
					'preview_size' => 'thumbnail',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
			),
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'stelselplaatinstellingen',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'left',
	'instruction_placement' => 'field',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;

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