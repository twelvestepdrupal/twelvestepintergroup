/**
 * @file
 * Handle the TwelveStepMeeting map.
 */

(function ($) {
  'use strict';

  Drupal.behaviors.meetingMap = {
    attach: function (context, settings) {
      if (typeof Drupal.geolocation.loadGoogle === 'function') {
        Drupal.geolocation.loadGoogle(function () {
          Drupal.behaviors.meetingMap.initialize(settings.geolocation, context, 1);
        });
      }

      // If the user selects the 'next' time, also select 'today'.
      var $select = $('.form-item-time select', context);
      $select.change(function() {
        if (this.value == 'next') {
          $('.form-item-days select').val('today');
        }
      });
    },

    initialize: function(settings, context, attempts) {
      // run after Drupal.behaviors.geolocationCommonMap.initialize().
      if (typeof Drupal.geolocation.maps === 'undefined' && attempts++ < 5) {
        setTimeout(function() {
          Drupal.behaviors.meetingMap.initialize(settings, context, attempts + 1);
        }, 0);
        return;
      }

      // Their could be several maps/views present. Go over each entry.
      var mapNumber = 0;
      $.each(settings.commonMap, function (mapId, mapSettings) {
        var map = $('#' + mapId, context);
        var geolocationMap = Drupal.geolocation.maps[mapNumber++];

        google.maps.event.addListener(geolocationMap.googleMap, 'zoom_changed', function () {
          Drupal.behaviors.meetingMap.showCardsOnMap(map, geolocationMap);
        });
        google.maps.event.addListener(geolocationMap.googleMap, 'dragend', function () {
          Drupal.behaviors.meetingMap.showCardsOnMap(map, geolocationMap);
        });
      });
    },

    showCardsOnMap: function(map, geolocationMap) {
      var boundary = geolocationMap.googleMap.getBounds();
      map.find('.geolocation-common-map-locations .geolocation').each(function(index, location) {
        if (typeof location.position === 'undefined') {
          location.position = new google.maps.LatLng($(location).data('lat'), $(location).data('lng'));
        }

        if (boundary.contains(location.position)) {
          $(location).show();
        }
        else {
          $(location).hide();
        }
      });
    }

  };
})(jQuery);
