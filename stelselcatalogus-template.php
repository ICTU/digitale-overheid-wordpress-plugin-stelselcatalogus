<?php


/**
 * ICTU / WP Stelselplaat plugin - stelselcatalogus-template.php
 * ----------------------------------------------------------------------------------
 * Zoekresultaatpagina
 * ----------------------------------------------------------------------------------
 * Description:   De mogelijkheid om een stelselplaat te tonen op een pagina
 * Version:       0.0.2
 * Version desc:  eerste versie
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
  
  // Add hook for front-end <head></head>
  add_action('wp_footer', 'rhswp_stelselplaat_js_in_footer');

  add_action( 'genesis_before_loop', 'rhswp_stelselplaat_encapsulate_start', 10 );

  add_action( 'genesis_after_loop', 'rhswp_stelselplaat_add_search_description', 10 );

  add_action( 'genesis_after_loop', 'rhswp_stelselplaat_encapsulate_end', 11 );

  add_action( 'wp_enqueue_scripts', 'rhswp_stelselplaat_template_css' );

}


/** Replace the standard loop with our custom loop */
//remove_action( 'genesis_loop', 'genesis_do_loop' );
//add_action( 'genesis_loop', 'rhswp_stelselplaat_archive_custom_search_loop' );



genesis();

function rhswp_stelselplaat_template_css() {

  $custom_css = '
@charset "UTF-8";
/* CSS Document */

#BRV_li {
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
  width: 225px;
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
.leverancier ul + p {
  margin: 7px -1px 0 !important;
  line-height: 1em;
  font-size: 10px;
}
.gereed .leverancier ul + p {
  background: #C3DBB6;
  border-color: #39870C;
}
.nietgereed .leverancier ul + p {
  background: #FDF6BB;
  border-color: #F9E11E;
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
}
  
  ';
  
	wp_enqueue_style( DO_STELSELPLAAT_FOLDER, DO_STELSELPLAAT_BASE_URL . 'css/stelselplaat.css'	);

//	wp_enqueue_style( DO_STELSELPLAAT_FOLDER, DO_STELSELPLAAT_BASE_URL . 'css/oude-css.css'	);

  wp_add_inline_style( DO_STELSELPLAAT_FOLDER, $custom_css );  

  wp_enqueue_script( 'stelselplaat-lib', DO_STELSELPLAAT_BASE_URL . 'js/jquery.ba-hashchange.min.js', array( 'jquery' ) );
  wp_enqueue_script( 'stelselplaat', DO_STELSELPLAAT_BASE_URL . 'js/stelselplaat.js', array( 'jquery' ) );


  
}


function rhswp_stelselplaat_js_in_footer() {
  echo '<script type="text/javascript">
var fileref=document.createElement("link");
fileref.setAttribute("rel", "stylesheet");
fileref.setAttribute("type", "text/css");
fileref.setAttribute("href", "' . DO_STELSELPLAAT_BASE_URL . 'css/stelselplaat-js-enabled.css?v2");
document.getElementsByTagName("head")[0].appendChild(fileref);var stelselplaat = stelselplaat || {};
jQuery(document).ready(function($) {stelselplaat.relations = jQuery.parseJSON(\'{	"BRV":	["NHR", "BRP-ni", "BRP-i", "BRP"],	"BRI":	["BRP-ni", "BRP-i", "BRP"],	"BLAU":	["NHR", "BRP-ni", "BRP-i", "BRP"],	"BRK": 	["NHR", "BRP-ni", "BRP-i", "BRP", "BRT", "WOZ", "BGT", "BAG"],	"BRT":	["BAG", "BRK", "BGT"],	"BGT":	["BRT", "BAG", "BRK"],	"BRO":	["NHR"],	"BAG":	["BRT", "NHR", "BRK", "WOZ", "NHR", "BRK", "BGT", "BRP-i", "BRP"],	"WOZ":	["BRK", "BRP-i", "BRP-ni", "BRP", "NHR", "BAG"],	"NHR":	["BRO", "WOZ", "BRK", "BLAU", "BRV", "BAG", "BRP-i", "BRP-ni", "BRP"],	"BRP-i":	["WOZ", "NHR", "BLAU", "BRK", "BRV", "BRI", "BAG", "BRP"],	"BRP-ni":	["WOZ", "NHR", "BLAU", "BRK", "BRV", "BRI", "BRP"]}\');
stelselplaat.begrippen_relations = jQuery.parseJSON(\'{	"Adres":	[		["BRP-i"],		["BRP-ni"],                ["BRP"],		["NHR"],		["WOZ"],		["BAG"],	 	["BRK"],		["BRT"],		["BLAU"],		["BRV"]	],	 "Inkomen":	[ 		["BRI"]	 ],	 "Partner":	[		["BRP-i"],                ["BRP-ni"],                ["BRP"],		["BRV"],		["WOZ"]	 ],	"Perceel":	 [		 ["BRK"],		 ["WOZ"]	 ],	 "Natuurlijk persoon":	[		["BRP-i"],		["BRP-ni"],                ["BRP"],		["NHR"],	 	["WOZ"],	 	["BRK"],	 	["BRV"]	 ],	 "Buitenland":	[		["BRP-i"],                ["BRP-ni"],                ["BRP"],		["NHR"],		["BRK"],		["WOZ"],		["BAG"]	],	"Kind":	[		["BRP-i"],                ["BRP-ni"],                ["BRP"],		["NHR"]	],	"Ouder":	[		["BRP-i"],                ["BRP-ni"],                ["BRP"]	],	"Vermogen":	[		["NHR"],		["WOZ"]	],	"Woonland":	[		["BRP-i"],                ["BRP-ni"],                ["BRP"],		["BRK"]	]}\');
stelselplaat.image_location =  \'' . DO_STELSELPLAAT_BASE_URL . 'images/\';});
	</script>';
}

function rhswp_stelselplaat_encapsulate_start() {

  echo '<div id="kolom2">';


}

function rhswp_stelselplaat_encapsulate_end() {

  echo '</div>'; // id="kolom2";
  
//  echo '<hr>';
//  echo file_get_contents(DO_STELSELPLAAT_PATH . 'images/pijlen.svg');
//  echo '<hr>' . DO_STELSELPLAAT_PATH . 'images/pijlen.svg';

}


function rhswp_stelselplaat_add_search_description() {

	
  echo '<div id="page" class="stelselplaat">';
  destelselplaat();
  
}

function destelselplaat() {
echo '<ul class="stelsel">
  <li id="BRP_li" class="gereed"><em>Basisregistratie</em> <strong>Personen</strong></li>
  <li class="relaties"><img src="' . DO_STELSELPLAAT_BASE_URL . 'images/relaties.png" alt="Relaties in het stelsel" /></li>
  <li id="BRV_li" class="gereed"><a href="#BRV"><em>Basisregistratie</em> <strong>Voertuigen</strong></a></li>
  <li id="BRI_li" class="gereed"><a href="#BRI"><em>Basisregistratie</em> <strong>Inkomen</strong></a></li>
  <li id="BLAU_li" class="nietgereed"><a href="#BLAU"><strong><abbr title="Basisregistratie Lonen Arbeidsverhoudingen en Uitkeringen">BLAU</abbr></strong></a></li>
  <li id="BRK_li" class="gereed"><a href="#BRK"><em>Basisregistratie</em> <strong>Kadaster</strong></a></li>
  <li id="BRT_li" class="gereed"><a href="#BRT"><em>Basisregistratie</em> <strong>Topografie</strong></a></li>
  <li id="BGT_li" class="nietgereed"><a href="#BGT"><em>Basisregistratie</em> <strong>Grootschalige topografie</strong></a></li>
  <li id="BRO_li" class="nietgereed"><a href="#BRO"><em>Basisregistratie</em> <strong>Ondergrond</strong></a></li>
  <li id="BAG_li" class="gereed"><a href="#BAG"><em>Basisregistraties</em> <strong>Adressen en Gebouwen</strong> </a></li>
  <li id="WOZ_li" class="gereed"><a href="#WOZ"><em>Basisregistratie</em> <strong><abbr title="Waarde Onroerende Zaken">WOZ</abbr></strong></a></li>
  <li id="NHR_li" class="gereed"><a href="#NHR"><strong>Handelsregister</strong></a></li>
  <li id="BRP-ni_li" class="gereed"><a href="#BRP-ni"> <strong>niet-ingezetenen</strong> </a></li>
  <li id="BRP-i_li" class="gereed"><a href="#BRP-i"> <strong>ingezetenen</strong> </a></li>
</ul>
<ul class="legenda">
  <li class="gereed">Gereed</li>
  <li class="nietgereed">Niet gereed</li>
  <li class="legenda_geo">Bevat geometrie</li>
  <li><a href="/onderwerpen/stelselinformatiepunt/stelselthemas/verbindingen/verbindingen-tussen-basisregistraties/toelichting-interactieve-stelselplaat">Toelichting</a></li>
</ul>
<div id="BAG" class="br BAG hide_js">
  <h2><em>Basisregistraties</em> Adressen en Gebouwen</h2>
  <div class="tab">
    <h2><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is gereed</p>
    </div>
    <p>Verplicht gebruik per 1 juli 2011.</p>
    <div class="zij">
      <h3><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr> maakt gebruik van</h3>
      <p><em>Geen verbindingen</em></p>
    </div>
    <div class="ik">
      <h3><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr></h3>
      <ul>
        <li class="geo"><img class="geometrie" src="' . DO_STELSELPLAAT_BASE_URL . 'images/geo.svg" alt="Icon - Bevat geometrie" width="20" height="20" />Woonplaats</li>
        <li>Openbare ruimte</li>
        <li>Nummeraanduiding</li>
        <li class="geo"><img class="geometrie" src="' . DO_STELSELPLAAT_BASE_URL . 'images/geo.svg" alt="Icon - Bevat geometrie" width="20" height="20" />Pand</li>
        <li>Adresseerbaar object
          <ul>
            <li class="geo"><img class="geometrie" src="' . DO_STELSELPLAAT_BASE_URL . 'images/geo.svg" alt="Icon - Bevat geometrie" width="20" height="20" />Verblijfsobject</li>
            <li class="geo"><img class="geometrie" src="' . DO_STELSELPLAAT_BASE_URL . 'images/geo.svg" alt="Icon - Bevat geometrie" width="20" height="20" />Standplaats</li>
            <li class="geo"><img class="geometrie" src="' . DO_STELSELPLAAT_BASE_URL . 'images/geo.svg" alt="Icon - Bevat geometrie" width="20" height="20" />Ligplaats</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="zij">
      <h3><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr> wordt gebruikt door</h3>
      <ul>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#NHR"><abbr title="Nieuw Handelsregister">NHR</abbr></a></li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#GBA"><abbr title="Basisregistratie Personen ingezetenen">BRP-i</abbr></a></li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#WOZ"><abbr title="Waarde Onroerende Zaken">WOZ</abbr></a>
          <ul>
            <li>01-07-2014</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#BRK"><abbr title="Basisregistratie Kadaster">BRK</abbr></a>
          <ul>
            <li>31-12-2014</li>
          </ul>
        </li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#BRT"><abbr title="Basisregistratie Topografie">BRT</abbr></a></li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#BGT"><abbr title="Basisregistratie grootschalige Topografie">BGT</abbr></a>
          <ul>
            <li>31 dec. 2015</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="leverancier">
      <h3><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr> levert de volgende gegevens door:</h3>
      <p>Levert geen gegevens door vanuit andere registraties.</p>
    </div>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/basisregistraties-adressen-en-gebouwen"><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0025520">Besluit basisregistraties adressen en gebouwen</a> </li>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0025961">Regeling basisregistraties adressen en gebouwen</a> </li>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0030564">Regeling periodieke controle basisregistraties adressen en gebouwen</a> </li>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0023466">Wet basisregistraties adressen en gebouwen</a> </li>
        <li class="external_link">Catalogus basisregistratie: <a href="http://www.kadaster.nl/web/file?uuid=391c0710-c5b4-4f83-8154-9332544e7b8e&amp;owner=23cbe925-35ce-4a72-ac8c-a33a0c19ae1e&amp;contentid=2590"> Catalogus basisregistraties adressen en gebouwen </a> (PDF, 358 KB) </li>
        <li class="external_link">Homepage basisregistratie: <a href="http://www.kadaster.nl/bag"> Bezoek de homepage van <abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr> </a> </li>
      </ul>
    </div>
  </div>
</div>
<div id="BRO" class="br BRO hide_js">
  <h2><em>Basisregistratie</em> Ondergrond</h2>
  <div class="tab">
    <h2><abbr title="Basisregistratie Ondergrond">BRO</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is niet gereed</p>
    </div>
    <p>Verplicht gebruik per: datum onbekend.</p>
    <div class="zij">
      <h3><abbr title="Basisregistratie Ondergrond">BRO</abbr> maakt gebruik van</h3>
      <ul>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#NHR"><abbr title="Nieuw Handelsregister">NHR</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="ik">
      <h3><abbr title="Basisregistratie Ondergrond">BRO</abbr></h3>
      <ul>
        <li class="geo"><img class="geometrie" src="' . DO_STELSELPLAAT_BASE_URL . 'images/geo.svg" alt="Icon - Bevat geometrie" width="20" height="20" /> Ondergrond</li>
      </ul>
    </div>
    <div class="zij">
      <h3><abbr title="Basisregistratie Ondergrond">BRO</abbr> wordt gebruikt door</h3>
      <p><em>Geen verbindingen</em></p>
    </div>
    <div class="leverancier">
      <h3><abbr title="Basisregistratie Ondergrond">BRO</abbr> levert de volgende gegevens door:</h3>
      <p><em>Onbekend</em></p>
    </div>
  </div>
  <div class="tab">
    <h2>Begrippen</h2>
    <p>Onderstaand overzicht toont alle voorkomens van de begrippen in deze Basisregistratie.</p>
    <p><em>Deze informatie is nog niet aangeleverd door de basisregistratie.</em></p>
  </div>
  <div class="tab">
    <h2>Gegevens</h2>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/basisregistratie-ondergrond"><abbr title="Basisregistratie Ondergrond">BRO</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li>Wet: <em>Deze informatie is nog niet aangeleverd door de basisregistratie.</em></li>
        <li>Catalogus basisregistratie: <em>Deze informatie is nog niet aangeleverd door de basisregistratie.</em></li>
        <li class="external_link">Homepage basisregistratie: <a href="http://www.broinfo.nl/"> Bezoek de homepage van <abbr title="Basisregistratie Ondergrond">BRO</abbr> </a> </li>
      </ul>
    </div>
  </div>
</div>
<div id="BLAU" class="br BLAU hide_js">
  <h2><em>Basisregistratie</em> Lonen, Arbeids- en Uitkerings­verhoudingen</h2>
  <div class="tab">
    <h2><abbr title="Basisregistratie Lonen Arbeidsverhoudingen en Uitkeringen">BLAU</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is niet gereed</p>
    </div>
    <p><em>Het besluit tot het ontwikkelen van <abbr title="Basisregistratie Lonen Arbeidsverhoudingen en Uitkeringen">BLAU</abbr> als basisregistratie moet nog worden genomen.</em></p>
    <div class="zij">
      <h3><abbr title="Basisregistratie Lonen Arbeidsverhoudingen en Uitkeringen">BLAU</abbr> maakt gebruik van</h3>
      <ul>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#GBA"><abbr title="Basisregistratie Personen ingezetenen">BRP-i</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#RNI"><abbr title="Basisregistratie Personen niet-ingezetenen">BRP-ni</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#NHR"><abbr title="Nieuw Handelsregister">NHR</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="ik">
      <h3><abbr title="Basisregistratie Lonen Arbeidsverhoudingen en Uitkeringen">BLAU</abbr></h3>
      <ul>
        <li>Loon, Uitkering</li>
        <li>Dienstverband</li>
      </ul>
    </div>
    <div class="zij">
      <h3><abbr title="Basisregistratie Lonen Arbeidsverhoudingen en Uitkeringen">BLAU</abbr> wordt gebruikt door</h3>
      <p><em>Geen verbindingen</em></p>
    </div>
    <div class="leverancier">
      <h3><abbr title="Basisregistratie Lonen Arbeidsverhoudingen en Uitkeringen">BLAU</abbr> levert de volgende gegevens door:</h3>
      <p><em>Onbekend</em></p>
    </div>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/basisregistratie-voor-lonen-arbeidsverhoudingen-en-uitkeringen"><abbr title="Basisregistratie Lonen Arbeidsverhoudingen en Uitkeringen">BLAU</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li>Wet: <em>Deze informatie is nog niet aangeleverd door de basisregistratie.</em></li>
        <li>Catalogus basisregistratie: <em>Deze informatie is nog niet aangeleverd door de basisregistratie.</em></li>
        <li>Homepage basisregistratie: <em>Deze informatie is nog niet aangeleverd door de basisregistratie.</em></li>
      </ul>
    </div>
  </div>
</div>
<div id="BRK" class="br BRK hide_js">
  <h2><em>Basisregistratie</em> Kadaster</h2>
  <div class="tab">
    <h2><abbr title="Basisregistratie Kadaster">BRK</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is gereed</p>
    </div>
    <p>Verplicht gebruik per 1 januari 2008.</p>
    <div class="zij">
      <h3><abbr title="Basisregistratie Kadaster">BRK</abbr> maakt gebruik van</h3>
      <ul>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#BAG"><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr></a>
          <ul>
            <li>2015</li>
          </ul>
        </li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#GBA"><abbr title="Basisregistratie Personen ingezetenen">BRP-i</abbr></a></li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#RNI"><abbr title="Basisregistratie Personen niet-ingezetenen">BRP-ni</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#NHR"><abbr title="Nieuw Handelsregister">NHR</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#BGT"><abbr title="Basisregistratie grootschalige Topografie">BGT</abbr></a>
          <ul>
            <li>1-1-2017</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="ik">
      <h3><abbr title="Basisregistratie Kadaster">BRK</abbr></h3>
      <ul>
        <li>Zakelijk recht</li>
        <li>Onroerende zaak
          <ul>
            <li>Appartementsrecht</li>
            <li class="geo"><img class="geometrie" src="' . DO_STELSELPLAAT_BASE_URL . 'images/geo.svg" alt="Icon - Bevat geometrie" width="20" height="20" /> Perceel</li>
            <li>Leidingnetwerk</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="zij">
      <h3><abbr title="Basisregistratie Kadaster">BRK</abbr> wordt gebruikt door</h3>
      <ul>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#BRT"><abbr title="Basisregistratie Topografie">BRT</abbr></a></li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#WOZ"><abbr title="Waarde Onroerende Zaken">WOZ</abbr></a></li>
      </ul>
    </div>
    <div class="leverancier">
      <h3><abbr title="Basisregistratie Kadaster">BRK</abbr> levert de volgende gegevens door:</h3>
      <ul>
        <li class="nietgereed">Adresseerbaar Object ID
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>01-07-2014</em></li>
        <li class="nietgereed">Adresgegevens
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>01-07-2014</em></li>
        <li class="gereed"><abbr title="Burgerservicenummer">BSN</abbr>
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
        <li class="nietgereed"><abbr title="Kamer van Koophandel">KvK</abbr>-nummer
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>01-07-2014</em></li>
        <li class="nietgereed"><abbr title="Rechtspersonen en Samenwerkingsverbanden Informatie Nummer">RSIN</abbr>
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>01-07-2014</em></li>
      </ul>
      <p><em>Doorlevering is gereed (groen) wanneer het doorgeleverde gegeven in de reguliere producten van de basisregistratie is opgenomen</em></p>
    </div>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/basisregistratie-kadaster"><abbr title="Basisregistratie Kadaster">BRK</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0005259">Kadasterbesluit</a> </li>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0004541&amp;hoofdstuk=3&amp;titeldeel=1&amp;artikel=48">Kadasterwet, artikel 48</a> </li>
        <li class="external_link">Catalogus basisregistratie: <a href="http://brk.kadaster.nl"> Kennisbank Basisregistratie Kadaster </a> (PDF, niet van toepassing) </li>
        <li class="external_link">Homepage basisregistratie: <a href="http://www.kadaster.nl/web/Themas/Registraties/BRK.htm"> Bezoek de homepage van <abbr title="Basisregistratie Kadaster">BRK</abbr> </a> </li>
      </ul>
    </div>
  </div>
</div>
<div id="BRP-i" class="br BRP-i hide_js">
  <h2>Basisregistratie Personen</h2>
  <div class="tab">
    <h2><abbr title="Basisregistratie Personen ingezetenen">BRP-i</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is gereed</p>
    </div>
    <p><abbr title="Basisregistratie Personen">BRP</abbr>, onderdeel ingezetenen</p>
    <p>Sinds 6 januari 2014 spreken we over de Basisregistratie Personen (<abbr title="Basisregistratie Personen">BRP</abbr>), met een onderdeel ingezetenen (voormalig <abbr title="Gemeentelijke Basisadministratie Personen">GBA</abbr>) en niet-ingezetenen (voormalig <abbr title="Registratie Niet Ingezetenen">RNI</abbr>). Dit onderscheid wordt hieronder nog gemaakt vanwege verschil in gereed status van de twee onderdelen.</p>
    <div class="zij">
      <h3><abbr title="Basisregistratie Personen">BRP</abbr> maakt gebruik van</h3>
      <ul>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#BAG"><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr></a></li>
      </ul>
    </div>
    <div class="ik">
      <h3><abbr title="Basisregistratie Personen">BRP</abbr>, onderdeel ingezetenen</h3>
    </div>
    <div class="zij">
      <h3><abbr title="Basisregistratie Personen">BRP</abbr> wordt gebruikt door</h3>
      <ul>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#BRV"><abbr title="Basisregistratie Voertuigen">BRV</abbr></a></li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#BRI"><abbr title="Basisregistratie Inkomen">BRI</abbr></a></li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#BLAU"><abbr title="Basisregistratie Lonen Arbeidsverhoudingen en Uitkeringen">BLAU</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#BRK"><abbr title="Basisregistratie Kadaster">BRK</abbr></a></li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#WOZ"><abbr title="Waarde Onroerende Zaken">WOZ</abbr></a></li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#NHR"><abbr title="Nieuw Handelsregister">NHR</abbr></a></li>
      </ul>
    </div>
    <div class="leverancier">
      <h3><abbr title="Basisregistratie Personen">BRP</abbr> levert de volgende gegevens door:</h3>
      <ul>
        <li class="gereed">Adresseerbaar object-ID
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
        <li class="gereed">Nummer­aanduiding-ID
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
        <li class="gereed">Adresgegevens
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
      </ul>
      <p><em>Doorlevering is gereed (groen) wanneer het doorgeleverde gegeven in de reguliere producten van de basisregistratie is opgenomen</em></p>
    </div>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/basisregistratie-personen"><abbr title="Basisregistratie Personen">BRP</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/BWBR0034306">Besluit basisregistratie personen</a> </li>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/BWBR0034327">Regeling basisregistratie personen</a> </li>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/BWBR0033715">Wet basisregistratie personen</a> </li>
        <li class="external_link">Catalogus basisregistratie: <a href="http://www.bprbzk.nl/dsresource?objectid=40964&amp;type=org"> Logisch ontwerp ingezetenen (voorheen <abbr title="Gemeentelijke Basisadministratie Personen">GBA</abbr>) </a> (PDF, 7,8 MB) </li>
        <li class="external_link">Catalogus basisregistratie: <a href="http://www.bprbzk.nl/dsresource?objectid=45067&amp;type=org"> Logisch ontwerp niet-ingezetenen (voorheen <abbr title="Registratie Niet Ingezetenen">RNI</abbr>) </a> (PDF, 3,3 MB) </li>
        <li class="external_link">Homepage basisregistratie: <a href="http://www.bprbzk.nl/BRP/Basisregistratie_Personen"> Bezoek de homepage van <abbr title="Basisregistratie Personen">BRP</abbr> </a> </li>
      </ul>
    </div>
  </div>
</div>
<div id="BRT" class="br BRT hide_js">
  <h2><em>Basisregistratie</em> Topografie</h2>
  <div class="tab">
    <h2><abbr title="Basisregistratie Topografie">BRT</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is gereed</p>
    </div>
    <p>Verplicht gebruik per 1 januari 2008, met uitzondering van landsdekkende topografische bestanden op schaalniveau kleiner dan 1:10.000. Daarvoor geldt verplicht gebruik en terugmeldplicht per 1-1-2010</p>
    <div class="zij">
      <h3><abbr title="Basisregistratie Topografie">BRT</abbr> maakt gebruik van</h3>
      <ul>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#BAG"><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr></a></li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#BRK"><abbr title="Basisregistratie Kadaster">BRK</abbr></a></li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#BGT"><abbr title="Basisregistratie grootschalige Topografie">BGT</abbr></a>
          <ul>
            <li>1-1-2017</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="ik">
      <h3><abbr title="Basisregistratie Topografie">BRT</abbr></h3>
      <ul>
        <li class="geo"><img class="geometrie" src="' . DO_STELSELPLAAT_BASE_URL . 'images/geo.svg" alt="Icon - Bevat geometrie" width="20" height="20" />Kleinschalig geo-object</li>
      </ul>
    </div>
    <div class="zij">
      <h3><abbr title="Basisregistratie Topografie">BRT</abbr> wordt gebruikt door</h3>
      <p><em>Geen verbindingen</em></p>
    </div>
    <div class="leverancier">
      <h3><abbr title="Basisregistratie Topografie">BRT</abbr> levert de volgende gegevens door:</h3>
      <ul>
        <li class="gereed">Straatnaam
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
        <li class="gereed">Woonplaats­naam
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
        <li class="gereed">Gemeentegrens (cartografisch)
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
      </ul>
      <p><em>Doorlevering is gereed (groen) wanneer het doorgeleverde gegeven in de reguliere producten van de basisregistratie is opgenomen</em></p>
    </div>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/basisregistratie-topografie"><abbr title="Basisregistratie Topografie">BRT</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0021547">Wet basisregistraties kadaster en topografie</a> </li>
        <li class="external_link">Catalogus basisregistratie: <a href="http://www.kadaster.nl/web/file?uuid=f4980d93-77ee-4036-934d-a49d7674a630&amp;owner=23cbe925-35ce-4a72-ac8c-a33a0c19ae1e&amp;contentid=7023"> Catalogus Basisregistratie Topografie </a> (PDF, 761 KB) </li>
        <li class="external_link">Homepage basisregistratie: <a href="http://www.kadaster.nl/BRT"> Bezoek de homepage van <abbr title="Basisregistratie Topografie">BRT</abbr> </a> </li>
      </ul>
    </div>
  </div>
</div>
<div id="BRV" class="br BRV hide_js">
  <h2><em>Basisregistratie</em> Voertuigen</h2>
  <div class="tab">
    <h2><abbr title="Basisregistratie Voertuigen">BRV</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is gereed</p>
    </div>
    <p>Verplicht gebruik per 1 januari 2010.</p>
    <div class="zij">
      <h3><abbr title="Basisregistratie Voertuigen">BRV</abbr> maakt gebruik van</h3>
      <ul>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#RNI"><abbr title="Basisregistratie Personen niet-ingezetenen">BRP-ni</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#GBA"><abbr title="Basisregistratie Personen ingezetenen">BRP-i</abbr></a></li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#NHR"><abbr title="Nieuw Handelsregister">NHR</abbr></a></li>
      </ul>
    </div>
    <div class="ik">
      <h3><abbr title="Basisregistratie Voertuigen">BRV</abbr></h3>
      <ul>
        <li>Gekentekend voertuig</li>
      </ul>
    </div>
    <div class="zij">
      <h3><abbr title="Basisregistratie Voertuigen">BRV</abbr> wordt gebruikt door</h3>
      <p><em>Geen verbindingen</em></p>
    </div>
    <div class="leverancier">
      <h3><abbr title="Basisregistratie Voertuigen">BRV</abbr> levert de volgende gegevens door:</h3>
      <ul>
        <li class="gereed"><abbr title="Burgerservicenummer">BSN</abbr>
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
        <li class="gereed"><abbr title="Kamer van Koophandel">KvK</abbr>-nummer
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
      </ul>
      <p><em>Doorlevering is gereed (groen) wanneer het doorgeleverde gegeven in de reguliere producten van de basisregistratie is opgenomen</em></p>
    </div>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/basisregistratie-voertuigen"><abbr title="Basisregistratie Voertuigen">BRV</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0006622">Wegenverkeerswet</a> </li>
        <li class="external_link">Catalogus basisregistratie: <a href="http://www.rdw.nl/SiteCollectionDocuments/Over%20RDW/Brochures%20en%20folders/Catalogus%20Basisregistratie%20Voertuigen.pdf"> Catalogus Basisregistratie Voertuigen </a> </li>
        <li class="external_link">Homepage basisregistratie: <a href="http://www.rdw.nl/overrdw/Paginas/Kentekenregister-is-basisregistratie-voertuigen.aspx"> Bezoek de homepage van <abbr title="Basisregistratie Voertuigen">BRV</abbr> </a> </li>
      </ul>
    </div>
  </div>
</div>
<div id="WOZ" class="br WOZ hide_js">
  <h2><em>Basisregistratie</em> <abbr title="Waarde Onroerende Zaken">WOZ</abbr></h2>
  <div class="tab">
    <h2><abbr title="Waarde Onroerende Zaken">WOZ</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is gereed</p>
    </div>
    <p>Verplicht gebruik per 1 januari 2009.</p>
    <div class="zij">
      <h3><abbr title="Waarde Onroerende Zaken">WOZ</abbr> maakt gebruik van</h3>
      <ul>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#BAG"><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr></a>
          <ul>
            <li>01-07-2015</li>
          </ul>
        </li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#BRK"><abbr title="Basisregistratie Kadaster">BRK</abbr></a></li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#GBA"><abbr title="Basisregistratie Personen ingezetenen">BRP-i</abbr></a></li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#RNI"><abbr title="Basisregistratie Personen niet-ingezetenen">BRP-ni</abbr></a>
          <ul>
            <li>1-1-2016</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#NHR"><abbr title="Nieuw Handelsregister">NHR</abbr></a>
          <ul>
            <li>1-1-2016</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="ik">
      <h3><abbr title="Waarde Onroerende Zaken">WOZ</abbr></h3>
      <ul>
        <li><abbr title="Waarde Onroerende Zaken">WOZ</abbr> waarde</li>
        <li><abbr title="Waarde Onroerende Zaken">WOZ</abbr> object</li>
        <li>Belang</li>
      </ul>
    </div>
    <div class="zij">
      <h3><abbr title="Waarde Onroerende Zaken">WOZ</abbr> wordt gebruikt door</h3>
      <p><em>Geen verbindingen</em></p>
    </div>
    <div class="leverancier">
      <h3><abbr title="Waarde Onroerende Zaken">WOZ</abbr> levert de volgende gegevens door:</h3>
      <ul>
        <li class="nietgereed">Adresseerbaar object-ID
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>1-7-2015</em></li>
        <li class="nietgereed">Nummer­aanduiding-ID
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>1-7-2015</em></li>
        <li class="nietgereed">Vestigings­nummer
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>1-1-2015</em></li>
        <li class="gereed">Kadastraal object-ID
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
        <li class="nietgereed">Pand-ID
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>1-7-2015</em></li>
        <li class="gereed"><abbr title="Burgerservicenummer">BSN</abbr>
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
        <li class="nietgereed"><abbr title="Kamer van Koophandel">KvK</abbr>-nummer
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>1-1-2015</em></li>
        <li class="gereed"><abbr title="Rechtspersonen en Samenwerkingsverbanden Informatie Nummer">RSIN</abbr>
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
      </ul>
      <p><em>Doorlevering is gereed (groen) wanneer het doorgeleverde gegeven in de reguliere producten van de basisregistratie is opgenomen</em></p>
    </div>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/basisregistratie-waarde-onroerende-zaken"><abbr title="Waarde Onroerende Zaken">WOZ</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0007179">Uitvoeringsbesluit onderbouwing en uitvoering waardebepaling Wet waardering onroerende zaken</a> </li>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0007165">Uitvoeringsregeling instructie waardebepaling Wet waardering onroerende zaken</a> </li>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0007119">Wet waardering onroerende zaken</a> </li>
        <li class="external_link">Catalogus basisregistratie: <a href="http://www.waarderingskamer.nl/documents/catalogus%20basisregistratie%20woz%20versie%201.6.pdf"> Catalogus Basisregistratie <abbr title="Waarde Onroerende Zaken">WOZ</abbr> </a> (PDF, 331 KB) </li>
        <li class="external_link">Homepage basisregistratie: <a href="http://www.kadaster.nl/woz"> Bezoek de homepage van <abbr title="Waarde Onroerende Zaken">WOZ</abbr> </a> </li>
      </ul>
    </div>
  </div>
</div>
<div id="NHR" class="br NHR hide_js">
  <h2>Handelsregister</h2>
  <div class="tab">
    <h2><abbr title="Nieuw Handelsregister">NHR</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is gereed</p>
    </div>
    <p>Verplicht gebruik per: 1-1-2015 (gefaseerd)</p>
    <div class="zij">
      <h3><abbr title="Nieuw Handelsregister">NHR</abbr> maakt gebruik van</h3>
      <ul>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#RNI"><abbr title="Basisregistratie Personen niet-ingezetenen">BRP-ni</abbr></a>
          <ul>
            <li>31-12-2014</li>
          </ul>
        </li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#GBA"><abbr title="Basisregistratie Personen ingezetenen">BRP-i</abbr></a></li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#BAG"><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr></a></li>
      </ul>
    </div>
    <div class="ik">
      <h3><abbr title="Nieuw Handelsregister">NHR</abbr></h3>
      <ul>
        <li>Niet natuurlijk persoon</li>
        <li>Onderneming maatschappelijke activiteit</li>
        <li>Vestiging</li>
      </ul>
    </div>
    <div class="zij">
      <h3><abbr title="Nieuw Handelsregister">NHR</abbr> wordt gebruikt door</h3>
      <ul>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#BRV"><abbr title="Basisregistratie Voertuigen">BRV</abbr></a></li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#BLAU"><abbr title="Basisregistratie Lonen Arbeidsverhoudingen en Uitkeringen">BLAU</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#BRK"><abbr title="Basisregistratie Kadaster">BRK</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#WOZ"><abbr title="Waarde Onroerende Zaken">WOZ</abbr></a>
          <ul>
            <li>1 jan. 2015</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#BRO"><abbr title="Basisregistratie Ondergrond">BRO</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="leverancier">
      <h3><abbr title="Nieuw Handelsregister">NHR</abbr> levert de volgende gegevens door:</h3>
      <ul>
        <li class="gereed"><abbr title="Burgerservicenummer">BSN</abbr>
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
        <li class="nietgereed">Adresseerbaar object-ID
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>Datum onbekend<br />
          </em></li>
        <li class="nietgereed">Nummer­aanduiding-ID
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>Datum onbekend<br />
          </em></li>
        <li class="gereed">Adresgegevens
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
      </ul>
      <p><em>Doorlevering is gereed (groen) wanneer het doorgeleverde gegeven in de reguliere producten van de basisregistratie is opgenomen</em></p>
    </div>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/handelsregister"><abbr title="Nieuw Handelsregister">NHR</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0024097">Handelsregisterregeling</a> </li>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0021777&amp;hoofdstuk=1&amp;artikel=1">Handelsregisterwet, artikel 1</a> </li>
        <li class="external_link">Catalogus basisregistratie: <a href="http://www.kvk.nl/download/Gegevenscatalogus%20v2.3_tcm14-266750.pdf"> Gegevenscatalogus Handelsregister </a> (PDF, 2,6 MB) </li>
        <li class="external_link">Homepage basisregistratie: <a href="http://www.kvk.nl/over-de-kvk/over-het-handelsregister/handelsregisterwet/wat-is-het-nieuw-handelsregister/"> Bezoek de homepage van <abbr title="Nieuw Handelsregister">NHR</abbr> </a> </li>
      </ul>
    </div>
  </div>
</div>
<div id="BRP-ni" class="br BRP-ni hide_js">
  <h2>Basisregistratie Personen</h2>
  <div class="tab">
    <h2><abbr title="Basisregistratie Personen niet-ingezetenen">BRP-ni</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is gereed</p>
    </div>
    <p><abbr title="Basisregistratie Personen">BRP</abbr>, onderdeel niet-ingezetenen</p>
    <p><em>De <abbr title="Basisregistratie Personen">BRP</abbr>, onderdeel niet-ingezetenen kent geen verplicht gebruik</em></p>
    <p>Sinds 6 januari 2014 spreken we over de Basisregistratie Personen (<abbr title="Basisregistratie Personen">BRP</abbr>), met een onderdeel ingezetenen (voormalig <abbr title="Gemeentelijke Basisadministratie Personen">GBA</abbr>) en niet-ingezetenen (voormalig <abbr title="Registratie Niet Ingezetenen">RNI</abbr>). Dit onderscheid wordt hieronder nog gemaakt vanwege verschil in gereed status van de twee onderdelen.</p>
    <div class="zij">
      <h3><abbr title="Basisregistratie Personen">BRP</abbr> maakt gebruik van</h3>
      <p><em>Geen verbindingen.</em></p>
    </div>
    <div class="ik">
      <h3><abbr title="Basisregistratie Personen">BRP</abbr>, onderdeel niet ingezetenen</h3>
    </div>
    <div class="zij">
      <h3><abbr title="Basisregistratie Personen">BRP</abbr> wordt gebruikt door</h3>
      <ul>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#BRV"><abbr title="Basisregistratie Voertuigen">BRV</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#BRI"><abbr title="Basisregistratie Inkomen">BRI</abbr></a></li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="nietgereed" href="#BLAU"><abbr title="Basisregistratie Lonen Arbeidsverhoudingen en Uitkeringen">BLAU</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#BRK"><abbr title="Basisregistratie Kadaster">BRK</abbr></a>
          <ul>
            <li>Datum onbekend</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#WOZ"><abbr title="Waarde Onroerende Zaken">WOZ</abbr></a>
          <ul>
            <li>2015</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#NHR"><abbr title="Nieuw Handelsregister">NHR</abbr></a>
          <ul>
            <li>2015</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="leverancier">
      <h3><abbr title="Basisregistratie Personen">BRP</abbr> levert de volgende gegevens door:</h3>
      <p>Levert geen gegevens door vanuit andere registraties.</p>
    </div>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/basisregistratie-personen"><abbr title="Basisregistratie Personen">BRP</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/BWBR0034306">Besluit basisregistratie personen</a> </li>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/BWBR0034327">Regeling basisregistratie personen</a> </li>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/BWBR0033715">Wet basisregistratie personen</a> </li>
        <li class="external_link">Catalogus basisregistratie: <a href="http://www.bprbzk.nl/dsresource?objectid=40964&amp;type=org"> Logisch ontwerp ingezetenen (voorheen <abbr title="Gemeentelijke Basisadministratie Personen">GBA</abbr>) </a> (PDF, 7,8 MB) </li>
        <li class="external_link">Catalogus basisregistratie: <a href="http://www.bprbzk.nl/dsresource?objectid=45067&amp;type=org"> Logisch ontwerp niet-ingezetenen (voorheen <abbr title="Registratie Niet Ingezetenen">RNI</abbr>) </a> (PDF, 3,3 MB) </li>
        <li class="external_link">Homepage basisregistratie: <a href="http://www.bprbzk.nl/BRP/Basisregistratie_Personen"> Bezoek de homepage van <abbr title="Basisregistratie Personen">BRP</abbr> </a> </li>
      </ul>
    </div>
  </div>
</div>
<div id="BRI" class="br BRI hide_js">
  <h2><em>Basisregistratie</em> Inkomen</h2>
  <div class="tab">
    <h2><abbr title="Basisregistratie Inkomen">BRI</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is gereed</p>
    </div>
    <p>Verplicht gebruik per 1 januari 2009.</p>
    <div class="zij">
      <h3><abbr title="Basisregistratie Inkomen">BRI</abbr> maakt gebruik van</h3>
      <ul>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#RNI"><abbr title="Basisregistratie Personen niet-ingezetenen">BRP-ni</abbr></a></li>
        <li class="gereed">
          <div class="statusVerbinding">(Verbinding gereed)</div>
          <a class="gereed" href="#GBA"><abbr title="Basisregistratie Personen ingezetenen">BRP-i</abbr></a></li>
      </ul>
    </div>
    <div class="ik">
      <h3><abbr title="Basisregistratie Inkomen">BRI</abbr></h3>
      <ul>
        <li>Inkomen</li>
      </ul>
    </div>
    <div class="zij">
      <h3><abbr title="Basisregistratie Inkomen">BRI</abbr> wordt gebruikt door</h3>
      <p><em>Geen verbindingen</em></p>
    </div>
    <div class="leverancier">
      <h3><abbr title="Basisregistratie Inkomen">BRI</abbr> levert de volgende gegevens door:</h3>
      <ul>
        <li class="gereed"><abbr title="Burgerservicenummer">BSN</abbr>
          <div class="statusDoorlevering">(Doorlevering gereed)</div>
        </li>
      </ul>
      <p><em>Doorlevering is gereed (groen) wanneer het doorgeleverde gegeven in de reguliere producten van de basisregistratie is opgenomen</em></p>
    </div>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/basisregistratie-inkomen"><abbr title="Basisregistratie Inkomen">BRI</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/1.0:c:BWBR0002320&amp;hoofdstuk=IVA&amp;artikel=21">Algemene wet inzake rijksbelastingen, artikel 21</a> </li>
        <li>Catalogus basisregistratie: <em>Deze informatie is nog niet aangeleverd door de basisregistratie.</em></li>
        <li class="external_link">Homepage basisregistratie: <a href="http://www.belastingdienst.nl/wps/wcm/connect/bldcontentnl/belastingdienst/prive/werk_en_inkomen/basisregistratie_inkomen/"> Bezoek de homepage van <abbr title="Basisregistratie Inkomen">BRI</abbr> </a> </li>
      </ul>
    </div>
  </div>
</div>
<div id="BGT" class="br BGT hide_js">
  <h2><em>Basisregistratie</em> Grootschalige Topografie</h2>
  <div class="tab">
    <h2><abbr title="Basisregistratie grootschalige Topografie">BGT</abbr></h2>
    <div class="statusBasisregistratie">
      <p>Deze basisregistratie is niet gereed</p>
    </div>
    <p>Verplicht gebruik per 1 januari 2017.</p>
    <div class="zij">
      <h3><abbr title="Basisregistratie grootschalige Topografie">BGT</abbr> maakt gebruik van</h3>
      <ul>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#BAG"><abbr title="Basisregistraties Adressen en Gebouwen">BAG</abbr></a>
          <ul>
            <li>31-12-2015</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="ik">
      <h3><abbr title="Basisregistratie grootschalige Topografie">BGT</abbr></h3>
      <ul>
        <li class="geo"><img class="geometrie" src="' . DO_STELSELPLAAT_BASE_URL . 'images/geo.svg" alt="Icon - Bevat geometrie" width="20" height="20" /> Grootschalig topografisch object</li>
        <li class="geo"><img class="geometrie" src="' . DO_STELSELPLAAT_BASE_URL . 'images/geo.svg" alt="Icon - Bevat geometrie" width="20" height="20" /> Lijnelement</li>
      </ul>
    </div>
    <div class="zij">
      <h3><abbr title="Basisregistratie grootschalige Topografie">BGT</abbr> wordt gebruikt door</h3>
      <ul>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#BRK"><abbr title="Basisregistratie Kadaster">BRK</abbr></a>
          <ul>
            <li>1-1-2017</li>
          </ul>
        </li>
        <li class="nietgereed">
          <div class="statusVerbinding">(Verbinding niet gereed)</div>
          <a class="gereed" href="#BRT"><abbr title="Basisregistratie Topografie">BRT</abbr></a>
          <ul>
            <li>1-1-2017</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="leverancier">
      <h3><abbr title="Basisregistratie grootschalige Topografie">BGT</abbr> levert de volgende gegevens door:</h3>
      <ul>
        <li class="nietgereed">Pand-ID
          <div class="statusDoorlevering">(Doorlevering niet gereed)</div>
          <em>01-01-2016</em></li>
      </ul>
      <p><em>Doorlevering is gereed (groen) wanneer het doorgeleverde gegeven in de reguliere producten van de basisregistratie is opgenomen</em></p>
    </div>
  </div>
  <div class="tab">
    <h2>Begrippen</h2>
    <p>Onderstaand overzicht toont alle voorkomens van de begrippen in deze Basisregistratie.</p>
    <p>Geen van de opgenomen begrippen komen voor in deze basisregistratie.</p>
  </div>
  <div class="tab">
    <h2>Gegevens</h2>
  </div>
  <div class="tab">
    <div class="zie_ook">
      <h2>Zie ook</h2>
      <p>Voor informatie over rollen, producten en diensten en aansluiten, kijk op de pagina <a href="/onderwerpen/stelselinformatiepunt/stelsel-van-basisregistraties/basisregistratie-grootschalige-topografie"><abbr title="Basisregistratie grootschalige Topografie">BGT</abbr> in vogelvlucht</a>.</p>
      <p>Onderstaand overzicht toont de verwijzingen naar de gerelateerde wetgeving, de eigen catalogus en de website van deze Basisregistratie.</p>
      <ul>
        <li class="external_link">Wet: <a href="http://wetten.overheid.nl/BWBR0034026">Wet basisregistratie grootschalige topografie</a> </li>
        <li class="external_link">Catalogus basisregistratie: <a href="http://www.geonovum.nl/sites/default/files/BGTGegevenscatalogus111.pdf"> Gegevenscatalogus <abbr title="Basisregistratie grootschalige Topografie">BGT</abbr> 1.1 </a> (PDF, 3 MB) </li>
        <li class="external_link">Homepage basisregistratie: <a href="https://bgtweb.pleio.nl/"> Bezoek de homepage van <abbr title="Basisregistratie grootschalige Topografie">BGT</abbr> </a> </li>
      </ul>
    </div>
  </div>
</div>
</div>
';

  
}

/** Code for custom loop */
function rhswp_stelselplaat_archive_custom_search_loop() {
  // code for a completely custom loop
  global $post;

  global $post;
  $query  = isset( $_GET['searchwpquery'] ) ? sanitize_text_field( $_GET['searchwpquery'] ) : '';
  $page   = isset( $_GET['swppage'] ) ? absint( $_GET['swppage'] ) : 1;
  the_post();


?>  

        <form action="" method="get">
          <fieldset>
            <legend>Supplemental Search</legend>
            <p>
              <label for="searchwpquery">Search</label>
              <input type="text" id="searchwpquery" name="searchwpquery" value="<?php echo esc_attr( $query ); ?>" />
            </p>
            <p><button type="submit">Search</button></p>
          </fieldset>
        </form>

<?php
  
  if( !empty( $query ) ) :
    $engine                 = SearchWP::instance();     // instatiate SearchWP
    $supplementalEngineName = 'supplemental'; 	        // search engine name
    // perform the search
    $posts = $engine->search( $supplementalEngineName, $query, $page );

    if( !empty( $posts ) ) : 
      
      echo '<div class="block">';
    
      foreach( $posts as $post ) : 

        $permalink    = get_permalink();
        $excerpt      = wp_strip_all_tags( get_the_excerpt( $post ) );
        $postdate     = get_the_date( );
        $doimage      = false;
        $classattr    = genesis_attr( 'entry' );
        $classattr    = str_replace( 'has-post-thumbnail', '', $classattr );
        $contenttype  = get_post_type();
        $theurl       = get_permalink();
        $thetitle     = get_the_title();
        $documenttype = rhswp_stelselplaat_translateposttypes( $contenttype );
        
        if ( 'attachment' == $contenttype ) {
          
          $theurl       = wp_get_attachment_url( $post->ID );
          $parent_id    = $post->post_parent;
          $excerpt      = wp_strip_all_tags( get_the_excerpt( $parent_id ) );


          $mimetype     = get_post_mime_type( $post->ID ); 
          $thetitle     = get_the_title( $parent_id );

          $filesize     = filesize( get_attached_file( $post->ID ) );
          
          if ( $mimetype ) {
            $typeclass = explode('/', $mimetype);

            $classattr = str_replace( 'class="', 'class="attachment ' . $typeclass[1] . ' ', $classattr );

            if ( $filesize ) {
              $documenttype = rhswp_stelselplaat_translatemimetypes( $mimetype ) . ' (' . human_filesize($filesize) . ')';
            }
            else {
              $documenttype = rhswp_stelselplaat_translatemimetypes( $mimetype );
            }

          }
        }
      
      
        if( $post instanceof SearchWPTermResult ) :

          $classattr = str_replace( 'class="', 'class="taxonomy ' . $post->term->taxonomy . ' ', $classattr );

          $theurl       = $post->link;
          $thetitle     = $post->name;
          $excerpt      = $post->description;
          $documenttype = $post->taxonomy;
        
        else : setup_postdata( $post ); 

          if ( 'post' == $contenttype ) {
            $documenttype .= '<span class="post-date">' . get_the_date() . '</span>';          
          }

        endif; 

        printf( '<article %s>', $classattr );
        printf( '<a href="%s"><h3>%s</h3><p>%s</p><p class="meta">%s</p></a>', $theurl, $thetitle, $excerpt, $documenttype );
        echo '</article>';


          
      endforeach; 

      echo '</div>';

    endif; 

  endif; 
      
  
  if ( have_posts() ) {
  
    echo '<div class="block">';
    
    $postcounter = 0;
  
    while (have_posts()) : the_post();
      $postcounter++;
  
      $permalink    = get_permalink();
      $excerpt      = wp_strip_all_tags( get_the_excerpt( $post ) );
      $postdate     = get_the_date( );
      $doimage      = false;
      $classattr    = genesis_attr( 'entry' );
      $contenttype  = get_post_type();

      if ( $postcounter < 3 && has_post_thumbnail( $post->ID ) ) {
        $doimage    = true;
      } 
      else {
        $classattr = str_replace( 'has-post-thumbnail', '', $classattr );
      }
  

      if ( is_search() ) {

        $theurl       = get_permalink();
        $thetitle     = get_the_title();
        $documenttype = rhswp_stelselplaat_translateposttypes( $contenttype );
        
        if ( 'post' == $contenttype ) {
          
          $documenttype .= '<span class="post-date">' . get_the_date() . '</span>';          
        }
        if ( 'attachment' == $contenttype ) {
          
          $theurl       = wp_get_attachment_url( $post->ID );
          $parent_id    = $post->post_parent;
          $excerpt      = wp_strip_all_tags( get_the_excerpt( $parent_id ) );


          $mimetype     = get_post_mime_type( $post->ID ); 
          $thetitle     = get_the_title( $parent_id );

          $filesize     = filesize( get_attached_file( $post->ID ) );
          
          if ( $mimetype ) {
            $typeclass = explode('/', $mimetype);

            $classattr = str_replace( 'class="', 'class="attachment ' . $typeclass[1] . ' ', $classattr );

            if ( $filesize ) {
              $documenttype = rhswp_stelselplaat_translatemimetypes( $mimetype ) . ' (' . human_filesize($filesize) . ')';
            }
            else {
              $documenttype = rhswp_stelselplaat_translatemimetypes( $mimetype );
            }

          }
        }

        printf( '<article %s>', $classattr );
        printf( '<a href="%s"><h3>%s</h3><p>%s</p><p class="meta">%s</p></a>', $theurl, $thetitle, $excerpt, $documenttype );


      } 

      echo '</article>';
      do_action( 'genesis_after_entry' );
  
    endwhile;
  
    echo '</div>';

        genesis_posts_nav();

        wp_reset_query();        
  
  }
}



function rhswp_stelselplaat_archive_custom_search_loop2() {

   ?>


        <form action="" method="get">
          <fieldset>
            <legend>Supplemental Search</legend>
            <p>
              <label for="searchwpquery">Search</label>
              <input type="text" id="searchwpquery" name="searchwpquery" value="<?php echo esc_attr( $query ); ?>" />
            </p>
            <p><button type="submit">Search</button></p>
          </fieldset>
        </form>

      <?php if( !empty( $query ) ) : ?>

        <?php
          $engine = SearchWP::instance();             // instatiate SearchWP
          $supplementalEngineName = 'supplemental'; 	// search engine name
          // perform the search
          $posts = $engine->search( $supplementalEngineName, $query, $page );
        ?>

        <?php if( !empty( $posts ) ) : ?>
          <?php foreach( $posts as $post ) : ?>
            <?php if( $post instanceof SearchWPTermResult ) : ?>
              <article>
                <header class="entry-header">
                  <h1 class="entry-title">
                    <a href="<?php echo $post->link; ?>" rel="bookmark"><?php echo $post->taxonomy; ?>: <?php echo $post->name; ?></a>
                  </h1>
                </header><!-- .entry-header -->
                <div class="entry-summary">
                  <p><?php echo $post->description; ?></p>
                </div><!-- .entry-summary -->
              </article>
            <?php else : setup_postdata( $post ); ?>
              <article>
                <header class="entry-header">
                  <h1 class="entry-title">
                    <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
                  </h1>
                </header><!-- .entry-header -->
                <div class="entry-summary">
                  <?php the_excerpt(); ?>
                </div><!-- .entry-summary -->
              </article>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>

      <?php endif; ?>

    </div><!-- #content -->
  </div><!-- #primary -->

<?php
  wp_reset_postdata();

}
