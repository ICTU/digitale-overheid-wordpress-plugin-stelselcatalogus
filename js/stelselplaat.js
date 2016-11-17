
/**
 * ICTU / WP Stelselplaat plugin - stelselcatalogus-template.php
 * ----------------------------------------------------------------------------------
 * Zoekresultaatpagina
 * ----------------------------------------------------------------------------------
 * Description:   De mogelijkheid om een stelselplaat te tonen op een pagina
 * Version:       0.0.3
 * Version desc:  Blokken donkerblauw_vol gemaakt. Velden via ACF.
 * Author:        Paul van Buuren
 * Author URI:    https://wbvb.nl
 * License:       GPL-2.0+
 * @link    http://wbvb.nl/themes/wp-rijkshuisstijl/
 */


jQuery(document).ready(function ($) {

  $('.br').hide();

  /**
   * Open popup based on hash
   */
  function openPopup() {
    // close filters
    $('.begrippen-filter h2').siblings().slideUp();

    // reset
    clearTimeout(timer);
    timer = null;
    $('.popup').fadeOut(function () {
      $(this).remove();
    });
    $(this).parent().animate({ opacity: 1}, 'fast');
    $(this).parent().siblings().each(function () {
      $(this).animate({
        top: $.data(this, 'top'),
        left: $.data(this, 'left'),
        height: $.data(this, 'height'),
        width: $.data(this, 'width')
      }, 150).css('z-index', 'auto');
    });

    // set shield
    if ($('.shield').length === 0) {
      $(this).parents('.stelsel').append('<li class="shield" />');
    }

    // remember data
    $(this).parent().each(function () {
      $.data(this, 'top', $(this).css('top'));
      $.data(this, 'left', $(this).css('left'));
      $.data(this, 'height', $(this).css('height'));
      $.data(this, 'width', $(this).css('width'));
    });

    // animate this
    $(this).parent().css('z-index', 9).css('overflow', 'visible').animate({
      top: 10,
      left: 150,
      height: 600,
      width: 600
    }, 200, 'linear', function () {
      // extra content
      $(this).append('<div class="popup mod box closed" />');
      $(this).find('.popup').hide().fadeIn().prepend('<a class="close" href="#">X</a>');
      $('#' + $(this).prop('id').slice(0, -3) + '>*').clone().appendTo('.popup');

      // Trigger first (default tab) or specific tab from hash
      var hash = window.location.hash.split('~');
      var index = hash.length > 1 ? hash[1] : 0;
      $(this).find('.tabs li').eq(index).find('a').data('time', 0).each(openTab);
    });
  };

  var timer, filter_active;

  $('.stelsel li>a').hover(function () {
    if (filter_active) {
      return;
    }
    var obj = $(this);
    if (timer) {
      clearTimeout(timer);
      timer = null;
    }
    timer = setTimeout(function () {
      $selector = obj.parent().prop('id').slice(0, -3);
      $relaties = stelselplaat.relations[$selector];
      obj.parent().siblings().each(function () {
        $rel = $(this).prop('id').slice(0, -3);
        if ($.inArray($rel, $relaties) === -1) {
          $('#' + $rel + '_li').stop().animate({
            opacity: 0.1
          });
        }
      });
      obj.parent().css('z-index', 9);

      // Linux server is case sensitive, uploading within Joomla convert file name to lowercase.
      var image_lowercase = $selector.toLowerCase();
      $('.relaties img').attr('src', stelselplaat.image_location + image_lowercase + '.png');
    }, 250);
  });

  $('.stelsel li>a').mouseleave(function () {
    if (filter_active) {
      return;
    }
    clearTimeout(timer);
    timer = null;
    if ($('.shield').length < 1) {
      $(this).parent().css('z-index', 'auto');
    }
    
      $('.relaties img').attr('src', stelselplaat.basis_plaat );
//    $('.relaties img').attr('src', stelselplaat.image_location + 'relaties.png');
//    $('.relaties img').attr('src', stelselplaat.image_location + 'pijlen.svg');
    $(this).parent().siblings().stop().animate({
      opacity: 1
    }, 'fast');
  });

  /* close button */
  $('.close').live('click', function () {
    $(this).parents('li').each(function () {
      $(this).css('z-index', 'auto').animate({
        top: $.data(this, 'top'),
        left: $.data(this, 'left'),
        height: $.data(this, 'height'),
        width: $.data(this, 'width')
      }, 'fast');
      $('.popup, .shield').fadeOut(250, function () {
        $(this).remove();
      });
    });
  });

  $('.shield').live('click', function () {
    $('.popup .close').click();
  });
  // Change links to the basisregistrations to open the corresponding pop-up.
  // Note that these links don't have an index postfix, so no additional split on '~' is necessary
  $('.zij a').live('click', function () {
    var id = $(this).prop('href').split('#');
    $('#' + id[1] + '_li a').click();
  });

  /*
   * Tabs
   */
  $('.br').each(function () {
    $(this).find('h2').eq(0).after('<ul class="tabs" />');
    var id = $(this).attr('id');
    $(this).find('.tab h2').each(function (i) {
      $(this).parents('.br').find('.tabs').append('<li><a href="#' + id + '~' + i + '">' + $(this).html() + '</a></li>');
      $(this).remove();
    });
  });

  /**
   * Select a tab based on the hash
   */
  function openTab() {
    var time = 0;
    if ($(this).data('time') == undefined) time = 250;
    $(this).removeData('time');
    // show tab
    var i = $(this).parent().index();
    $(this).parents('div').find('.tab').eq(i).siblings('.tab').animate({ marginLeft: '-600px' }, time);
    $(this).parents('div').find('.tab').eq(i).show().css('margin-left', 600).animate({ marginLeft: 0 }, time);
    // set nav
    $('.tabs .active').removeClass('active');
    $(this).parent().addClass('active');

    // Check whether we should increase the height of the container to show all the information.
    var activeTab = $(this).parents('div').find('.tab').eq(i);

    if (activeTab.length) {
      var top = activeTab.prevAll('h2').outerHeight(true) + activeTab.prevAll('ul.tabs').outerHeight(true);
      var newHeight = Math.max(600, activeTab.outerHeight(true) + top);
      activeTab.parents('div.popup').parent('li').css('height', newHeight);
      // Set a min-height to increase the height of kolom2. This doesn't increase automatically.
      $('div#kolom2').css('min-height', newHeight == 600 ? 0 : $('ul.stelsel').offset().top + newHeight - $('#page').offset().top);
    } else {
      $(this).parents('div').find('.tab').parents('div.popup').parent('li').css('height', 600);
    }
  };

  // If the user loaded a page with a prefix # load it. This function is also called when using back button and the popup is not loaded yet
  function hashChange() {
    if(window.location.hash) {
      var popupElement = $('.stelsel > li > a[href="' + window.location.hash.split('~')[0] + '"]');

      // If popup is not open, open the popup
      if($('.popup').length < 1) {
        popupElement.each(openPopup);
      }
      // If popup is open, check if we should open another popup, or just change the tab
      else {
        var hashParts = window.location.hash.split('~'),
            popupParts = $('.popup').prev().attr('href').split('~')
        if (hashParts[0] == popupParts[0]) {
          // Trigger first (default tab) or specific tab from hash
          hashParts[1] = hashParts[1] ? hashParts[1] : '0';
          $('.popup .tabs > li > a[href$="' + hashParts.join('~') + '"]').each(openTab);
        } else {
          popupElement.each(openPopup);
        }

      }
    } else {
      // If there is no hash, close the popup (if open)
      $('.close').click();
    }
  }

  $(window).on('hashchange', hashChange);

  // Init page
  hashChange();
if ( 22 === 33 ) {
  
  $('.stelselplaat').before('<div class="begrippen-filter"><h2><a href="#">Filter op begrippen</a></h2><p>Toon basisregistraties met begrip:</p></div>');
  $('.begrippen-filter h2').siblings().hide();
  $('.begrippen-filter h2').live('click', function (e) {
    // close open dialog
    $('.popup .close').click();
    // open filters
    $('.begrippen-filter h2').siblings().slideToggle();

    // add filters
    if ( $('.begrippen-filter ul').length === 0 ) {
      $('.begrippen-filter').append('<ul></ul>');
      for (var begrip in stelselplaat.begrippen_relations) {
        $('.begrippen-filter ul').append('<li><a href="#">' + begrip + '</a></li>');
      }
    }
    // Prevent opening anchor.
    e.preventDefault();
  });
  $('.begrippen-filter ul li a').live('click', function (e) {
    // vars
    var begrip = $(this).text();
    var relaties = stelselplaat.begrippen_relations[begrip];

    // reset
    $('.stelsel .object').remove();
    $('.stelsel>li').not('.relaties').stop().animate({ opacity: 0.1 });

    // animate
    if ($(this).html() == $('.begrippen-filter .active').html()) {
      filter_active = false;

      $('.stelsel>li').stop().animate({ opacity: 1 });
      $('.begrippen-filter h2 a').text('Filter op begrippen');
      $('.begrippen-filter .active').removeClass('active');
      $('.relaties img').attr('src', stelselplaat.basis_plaat );
//    $('.relaties img').attr('src', stelselplaat.image_location + 'relaties.png');
//      $('.relaties img').attr('src', stelselplaat.image_location + 'pijlen.svg');

    }
    else {
      filter_active = true;

      // toon objecten
      for (var i in relaties) {
        var id = '#' + $.trim(relaties[i][0]) + '_li';
        if (relaties[i].length > 1) $(id).append('<div class="object">' + relaties[i][1] + '</div>');
        $(id).stop().animate({ opacity: 1 });
      }

      // relaties
      // Linux server is case sensitive, uploading within Joomla convert file name to lowercase and replace a space with _.
      var image_lowercase = begrip.toLowerCase().replace(/\s/g, '_');
      $('.relaties img').attr('src', stelselplaat.image_location + image_lowercase + '.png');

      // filter
      $('.begrippen-filter h2 a').html('Filter op: <em>' + $(this).text() + '</em>');
      $('.begrippen-filter .active').removeClass('active');
      $(this).addClass('active');
    }

    e.preventDefault();
  });
}

});

var stelselplaat = stelselplaat || {};
stelselplaat.initBegrippenFilter = function () {

};