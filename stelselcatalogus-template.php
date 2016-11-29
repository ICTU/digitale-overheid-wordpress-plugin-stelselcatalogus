<?php


/**
 * ICTU / WP Stelselplaat plugin - stelselcatalogus-template.php
 * ----------------------------------------------------------------------------------
 * Zoekresultaatpagina
 * ----------------------------------------------------------------------------------
 * Description:   De mogelijkheid om een stelselplaat te tonen op een pagina
 * Version:       1.0.1
 * Version desc:  Oplevering. Dossierlink toegevoegd. 
 * Author:        Paul van Buuren
 * Author URI:    https://wbvb.nl
 * License:       GPL-2.0+
 * @link    http://wbvb.nl/themes/wp-rijkshuisstijl/
 */

define( 'DO_STELSELPLAAT_FOLDER',     'rhswp-stelselcatalogus' );
define( 'DO_STELSELPLAAT_BASE_URL',   trailingslashit( plugins_url( DO_STELSELPLAAT_FOLDER ) ) );
define( 'DO_STELSELPLAAT_PATH',       plugin_dir_path( __FILE__ ) );
 
if ( is_page( ) ) {
  
  //* Force full-width-content layout
  add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

  // write stelselplaat before page content
  add_action( 'genesis_entry_content', 'rhswp_stelselplaat_pre_post_content', 9 );

  // add the CSS file and custom CSS
  add_action( 'wp_enqueue_scripts', 'rhswp_stelselplaat_header_enqueue_js_css' );

  // Add hook for JS footer
  add_action('wp_footer', 'rhswp_stelselplaat_js_in_footer');

}


genesis();

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

  rhswp_write_stelselplaat();

  echo '</div>'; // id="adaptoratio";
  echo '</div>'; // id="stelselplaat-container";
//  echo '</div>'; // id="page";

}

//========================================================================================================

function rhswp_write_stelselplaat() {

  $stelselplaat_pijlenschema  = get_field('stelselplaat_pijlenschema', 'option');
  $stelselplaat_veld_basis    = get_field('stelselplaat_veld_basis', 'option');
  $stelselplaat_legenda       = get_field('stelselplaat_legenda', 'option');

  $needle                     = '__IMAGE__';
  
  if( $stelselplaat_pijlenschema ) {
    $replacer                   = $stelselplaat_pijlenschema['url'];
  }
  else {
    $replacer                   = DO_STELSELPLAAT_BASE_URL . "images/pijlenschemas/pijlen.svg";
//    $replacer                   = DO_STELSELPLAAT_BASE_URL . "images/pijlenschemas/pijlen.png";
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

