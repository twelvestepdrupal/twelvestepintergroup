/**
 * @file
 * Handle the common map.
 */

(function ($) {
  'use strict';

  /* global google */

  /**
   * @namespace
   */
  Drupal.geolocation = Drupal.geolocation || {};

  /**
   * Attach common map style functionality.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches common map style functionality to relevant elements.
   */
  Drupal.behaviors.geolocationCommonMap = {
    attach: function (context, settings) {
      if (typeof Drupal.geolocation.loadGoogle === 'function') {
        // First load the library from google.
        Drupal.geolocation.loadGoogle(function () {
          initialize(settings.geolocation, context);
        });
      }
    }
  };

  function initialize(settings, context) {
    // Their could be several maps/views present. Go over each entry.
    $.each(settings.commonMap, function (mapId, mapSettings) {

      var bubble; // Keep track if a bubble is currently open.
      var fitBounds = false; // Whether to execute fitBounds().
      var bounds = false; // Placeholder for google boundaries tool.

      // The DOM-node the map and everything else resides in.
      var map = $('#' + mapId, context);

      // If the map is not present, we can go to the next entry.
      if (!map.length) {
        return;
      }

      // Hide the graceful-fallback HTML list; map will propably work now.
      map.children('.geolocation-common-map-locations').hide();
      // Map-container is not hidden by default in case of graceful-fallback.

      var geolocationMap = {};
      geolocationMap.settings = mapSettings.settings;

      geolocationMap.container = map.children('.geolocation-common-map-container');
      geolocationMap.container.show();

      if (map.data('centre-lat') && map.data('centre-lng')) {
        geolocationMap.lat = map.data('centre-lat');
        geolocationMap.lng = map.data('centre-lng');
      }
      else {
        geolocationMap.lat = geolocationMap.lng = 0;
      }

      if (map.data('fitbounds')) {
        fitBounds = map.data('fitbounds');

        // A google maps API tool to re-center the map on its content.
        bounds = new google.maps.LatLngBounds();
      }

      var googleMap = Drupal.geolocation.addMap(geolocationMap);

      // Add the locations to the map.
      map.find('.geolocation-common-map-locations .geolocation').each(function (key, location) {
        location = $(location);
        var position = new google.maps.LatLng(location.data('lat'), location.data('lng'));

        if (fitBounds && bounds) {
          bounds.extend(position);
        }

        var marker = new google.maps.Marker({
          position: position,
          map: googleMap,
          title: location.children('h2').text(),
          content: location.html()
        });

        marker.addListener('click', function () {
          if (bubble) {
            bubble.close();
          }
          bubble = new google.maps.InfoWindow({
            content: marker.content,
            maxWidth: 200
          });
          bubble.open(googleMap, marker);
        });
      });

      if (fitBounds) {
        // Fit map center and zoom to all currently loaded markers.
        googleMap.fitBounds(bounds);
      }
    });
  }

})(jQuery);
