
/**
 * ICTU / WP Stelselplaat plugin - stelselcatalogus-template.php
 * ----------------------------------------------------------------------------------
 * Zoekresultaatpagina
 * ----------------------------------------------------------------------------------
 * Description:   De mogelijkheid om een stelselplaat te tonen op een pagina
 * Version:       1.0.5
 * Version desc:  Focus binnen popup houden. Toegankelijkheidsissues.
 * Author:        Paul van Buuren
 * Author URI:    https://wbvb.nl
 * License:       GPL-2.0+
 * @link    http://wbvb.nl/themes/wp-rijkshuisstijl/
 */

jQuery(document).ready(function ($) {

  var doPopupWindows  = false,
      modalOpen       = false,
      allNodes        = document.querySelectorAll('*'),
      modal,
      i,
      lastFocus,
      $relaties,
      $rel,
      $selector;


  
  // =========================================================================================================
  
  // media query event handler
  if (matchMedia) {
      var mq = window.matchMedia('(min-width: 900px)');
      mq.addListener(WidthChange);
      WidthChange(mq);
  }
  
  // =========================================================================================================
  
  // media query change
  function WidthChange(mq) {
      
    if (mq.matches) {
      // window width is at least 900px
      // don't show menu button
      $('.br').hide();
      doPopupWindows = true;  
    }
    else {
      // window width is less than 900px
      // DO show menu button
      $('.br').show();

      $(this).parents('.br').each(function () {
        $(this).show();
      });
      
      doPopupWindows = false;  
      $('.popup .close').click();

    }
  
  }
  
  // =========================================================================================================
  
  // Restrict focus to the modal window when it's open.
  // Tabbing will just loop through the whole modal.
  // Shift + Tab will allow backup to the top of the modal,
  // and then stop.
  function focusRestrict ( event ) {

    var myModal = document.getElementById('thepopupwindow');

    if ( modalOpen && !myModal.contains( event.target ) ) {
      event.stopPropagation();
      myModal.focus();
    }

  }
  
  // =========================================================================================================
  /**
   * Open popup based on hash
   */
  function openPopup() {

    if(typeof document.activeElement === 'undefined') {
    }
    else {
      lastFocus = document.activeElement;
    }

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
      var theAnchor = $(this).find('a');
      theAnchor.attr('aria-expanded', 'false');
      var thisID    = $(this).attr('id');
      theAnchor.attr('id', thisID + '_a');
      
      $(this).animate({
        top: $.data(this, 'top'),
        left: $.data(this, 'left'),
        height: $.data(this, 'height'),
        width: $.data(this, 'width')
      }, 150).css('z-index', 'auto').attr('tabindex', '-1');
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
      height: 620,
      width: 700
    }, 200, 'linear', function () {
      // extra content
      $(this).append('<div class="popup mod box closed" />');
      $(this).find('.popup').hide().fadeIn().prepend('<button class="close" id="modal_close" type="button">Sluit popup</button>');
      $('#' + $(this).prop('id').slice(0, -3) + '>*').clone().appendTo('.popup');

      modal = $(this).find('.popup');
      modalOpen = true;
      modal.attr('tabindex', '-1');
      modal.attr('aria-expanded', 'true');
      modal.attr('id','thepopupwindow');
      modal.focus();        

    });

    lastFocus = $(this);

    // restrict tab focus on elements only inside modal window
    for (i = 0; i < allNodes.length; i++) {
      allNodes.item(i).addEventListener('focus', focusRestrict);
    }  

  }
  
  // =========================================================================================================
  
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

      var image_lowercase       = $selector.toLowerCase();
//      var image_hover_location  = stelselplaat.image_location + image_lowercase + '.png';
      var image_hover_location  = stelselplaat.image_location + image_lowercase + '.svg';


      var theKey = 'hoverimages_'  + image_lowercase;
      
      if(typeof stelselplaat[theKey] === 'undefined') {
        // does not exist
      }
      else {
        // does exist
        image_hover_location  = stelselplaat[theKey];
      }

      $('.relaties img').attr('src', image_hover_location );
    }, 250);
  });
  
  // =========================================================================================================
  
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
  
  // =========================================================================================================
  
  /* close button */
  $('.close').live('click', function () {

    // remove tab restriction
    for (i = 0; i < allNodes.length; i++) {
      allNodes.item(i).removeEventListener('focus', focusRestrict);
    }  
    
    modal.attr('aria-expanded', 'false');
    
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

    modalOpen = false;

    if(typeof lastFocus === 'undefined') {
    }
    else {
      lastFocus.focus();
    }
    
  });

  // =========================================================================================================
  
  // close modal by keydown, but only if modal is open
  document.addEventListener('keydown', checkEscapeButton);

  // =========================================================================================================

    // binds to both the button click and the escape key to close the modal window
  // but only if modalOpen is set to true
  function checkEscapeButton ( event ) {
    if (modalOpen && ( !event.keyCode || event.keyCode === 27 ) ) {
      $('.popup .close').click();
    }
  }
  
  // =========================================================================================================
  
  $('.shield').live('click', function () {
    $('.popup .close').click();
  });
  
  // =========================================================================================================
  
  // Change links to the basisregistrations to open the corresponding pop-up.
  // Note that these links don't have an index postfix, so no additional split on '~' is necessary
  $('.zij a').live('click', function () {
    var id = $(this).prop('href').split('#');
    $('#' + id[1] + '_li a').click();
  });
  
  // =========================================================================================================
  
  /**
   * Select a tab based on the hash
   */
  function openTab() {
    var time = 0;
    if ($(this).data('time') === undefined) { time = 250; }
    $(this).removeData('time');
    // show tab
    var i = $(this).parent().index();
    $(this).parents('div').find('.tab').eq(i).siblings('.tab').animate({ marginLeft: '-500px' }, time);
    $(this).parents('div').find('.tab').eq(i).show().css('margin-left', 500).animate({ marginLeft: 0 }, time);
    // set nav
    $('.tabs .active').removeClass('active');
    $(this).parent().addClass('active');

    // Check whether we should increase the height of the container to show all the information.
    var activeTab = $(this).parents('div').find('.tab').eq(i);

    if (activeTab.length) {
      var top = activeTab.prevAll('h2').outerHeight(true) + activeTab.prevAll('ul.tabs').outerHeight(true);
      var newHeight = Math.max(500, activeTab.outerHeight(true) + top);
      activeTab.parents('div.popup').parent('li').css('height', newHeight);
      // Set a min-height to increase the height of stelselplaat-container. This doesn't increase automatically.
      $('div#stelselplaat-container').css('min-height', newHeight === 610 ? 0 : $('ul.stelsel').offset().top + newHeight - $('#page').offset().top);
    } else {
      $(this).parents('div').find('.tab').parents('div.popup').parent('li').css('min-height', 630);
    }
  }
  
  // =========================================================================================================
  
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
            popupParts = $('.popup').prev().attr('href').split('~');
        if (hashParts[0] === popupParts[0]) {
          // Trigger first (default tab) or specific tab from hash
          hashParts[1] = hashParts[1] ? hashParts[1] : '0';
          $('.popup .tabs > li > a[href$="' + hashParts.join('~') + '"]').each(openTab);
        }
        else {
          popupElement.each(openPopup);
        }
      }
    }
    else {
      // If there is no hash, close the popup (if open)
      $('.close').click();
    }

  }

  $(window).on('hashchange', hashChange);

  // Init page
  hashChange();
  
  // =========================================================================================================

});

var stelselplaat = stelselplaat || {};
stelselplaat.initBegrippenFilter = function () {
};