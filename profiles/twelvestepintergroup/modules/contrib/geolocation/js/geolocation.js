/**
 * @file
 *   Javascript for the geolocation module.
 */

/**
 * @param {Object} drupalSettings
 * @param {Object} drupalSettings.geolocation
 * @param {String} drupalSettings.geolocation.google_map_api_key
 */

/**
 * @name GoogleMapEvent
 * @property {Function} addDomListener
 */

/**
 * @name GoogleMap
 * @property {Object} ZoomControlStyle
 * @property {String} ZoomControlStyle.LARGE
 *
 * @property {Object} ControlPosition
 * @property {String} ControlPosition.LEFT_TOP
 * @property {String} ControlPosition.TOP_LEFT
 *
 * @property {Object} MapTypeId
 * @property {String} MapTypeId.ROADMAP
 *
 * @property {Object} GeocoderStatus
 * @property {String} GeocoderStatus.OK
 *
 * @property {Function} LatLng
 *
 * @function
 * @property Map
 *
 * @function
 * @property InfoWindow
 *
 * @function
 * @property {function(Object):Object} Marker
 * @property {Function} Marker.setPosition
 * @property {Function} Marker.setMap
 *
 * @function
 * @property {function():Object} Geocoder
 * @property {Function} Geocoder.geocode
 *
 * @property {Function} fitBounds
 */

/**
 * @name google
 * @object
 * @property {GoogleMap[]} maps
 * @property {GoogleMapEvent[]} events
 */

(function ($, _, Drupal, drupalSettings) {
  'use strict';

  /* global google */

  /**
   * JSLint handing.
   *
   *  @callback geolocationCallback
   */

  /**
   * @namespace
   */
  Drupal.geolocation = Drupal.geolocation || {};

  // Google Maps are loaded lazily. In some situations load_google() is called twice, which results in
  // "You have included the Google Maps API multiple times on this page. This may cause unexpected errors." errors.
  // This flag will prevent repeat $.getScript() calls.
  Drupal.geolocation.maps_api_loading = false;

  /**
   * Gets the default settings for the google map.
   *
   * @return {{scrollwheel: boolean, panControl: boolean, mapTypeControl: boolean, scaleControl: boolean, streetViewControl: boolean, overviewMapControl: boolean, zoomControl: boolean, zoomControlOptions: {style: *, position: *}, mapTypeId: *, zoom: number}} - The map settings mostly.
   */
  Drupal.geolocation.defaultSettings = function () {
    return {
      google_map_settings: {
        scrollwheel: false,
        panControl: false,
        mapTypeControl: true,
        scaleControl: false,
        streetViewControl: false,
        overviewMapControl: false,
        zoomControl: true,
        zoomControlOptions: {
          style: google.maps.ZoomControlStyle.LARGE,
          position: google.maps.ControlPosition.LEFT_TOP
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoom: 2,
        style: []
      }
    };
  };

  /**
   * Provides the callback that is called when maps loads.
   */
  Drupal.geolocation.googleCallback = function () {
    // Ensure callbacks array;
    Drupal.geolocation.googleCallbacks = Drupal.geolocation.googleCallbacks || [];

    // Wait until the window load event to try to use the maps library.
    $(document).ready(function (e) {
      _.invoke(Drupal.geolocation.googleCallbacks, 'callback');
      Drupal.geolocation.googleCallbacks = [];
    });
  };

  /**
   * Adds a callback that will be called once the maps library is loaded.
   *
   * @param {geolocationCallback} callback - The callback
   */
  Drupal.geolocation.addCallback = function (callback) {
    Drupal.geolocation.googleCallbacks = Drupal.geolocation.googleCallbacks || [];
    Drupal.geolocation.googleCallbacks.push({callback: callback});
  };

  /**
   * Load google maps and set a callback to run when it's ready.
   *
   * @param {geolocationCallback} callback - The callback
   */
  Drupal.geolocation.loadGoogle = function (callback) {

    // Add the callback.
    Drupal.geolocation.addCallback(callback);

    // Check for google maps.
    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
      if (Drupal.geolocation.maps_api_loading === true) {
        return;
      }

      Drupal.geolocation.maps_api_loading = true;
      // Google maps isn't loaded so lazy load google maps.

      // Default script path.
      var scriptPath = '//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=Drupal.geolocation.googleCallback';

      // If a Google API key isset, use it.
      if (typeof drupalSettings.geolocation.google_map_api_key !== 'undefined') {
        scriptPath += '&key=' + drupalSettings.geolocation.google_map_api_key;
      }

      $.getScript(scriptPath)
        .done(function () {
          Drupal.geolocation.maps_api_loading = false;
        });

    }
    else {
      // Google maps loaded. Run callback.
      Drupal.geolocation.googleCallback();
    }
  };

  /**
   * Load google maps and set a callback to run when it's ready.
   *
   * @param {object} map - Container of settings and ID.
   *
   * @return {object} - The google map object.
   */
  Drupal.geolocation.addMap = function (map) {
    // Add any missing settings.
    map.settings = $.extend(Drupal.geolocation.defaultSettings(), map.settings);

    // Set the container size.
    map.container.css({
      height: map.settings.google_map_settings.height,
      width: map.settings.google_map_settings.width
    });

    // Get the center point.
    var center = new google.maps.LatLng(map.lat, map.lng);

    // Create the map object and assign it to the map.
    map.googleMap = new google.maps.Map(map.container.get(0), {
      zoom: parseInt(map.settings.google_map_settings.zoom),
      center: center,
      mapTypeId: google.maps.MapTypeId[map.settings.google_map_settings.type],
      zoomControl: map.settings.google_map_settings.zoomControl,
      streetViewControl: map.settings.google_map_settings.streetViewControl,
      mapTypeControl: map.settings.google_map_settings.mapTypeControl,
      scrollwheel: map.settings.google_map_settings.scrollwheel,
      disableDoubleClickZoom: map.settings.google_map_settings.disableDoubleClickZoom,
      draggable: map.settings.google_map_settings.draggable,
      styles: map.settings.google_map_settings.style
    });

    // Set the map marker.
    if (map.lat !== '' && map.lng !== '') {
      Drupal.geolocation.setMapMarker(center, map);
    }

    if (!Drupal.geolocation.hasOwnProperty('maps')) {
      Drupal.geolocation.maps = [];
    }

    Drupal.geolocation.maps.push(map);

    return map.googleMap;
  };

  /**
   * Set/Update a marker on a map
   *
   * @param {Object} latLng - A location (latLng) object from google maps API.
   * @param {Object} map - The settings object that contains all of the necessary metadata for this map.
   */
  Drupal.geolocation.setMapMarker = function (latLng, map) {
    // make sure the marker exists.
    if (map.marker instanceof google.maps.Marker) {
      map.marker.setPosition(latLng);
      map.marker.setMap(map.googleMap);
    }
    else {

      // Set the info popup text.
      map.infowindow = new google.maps.InfoWindow({
        content: map.settings.info_text
      });

      // Add the marker to the map.
      map.marker = new google.maps.Marker({
        position: latLng,
        map: map.googleMap,
        title: map.settings.title,
        label: map.settings.label
      });

      // Add the info window event if the info text has been set.
      if (map.settings.info_text && map.settings.info_text.length > 0) {
        map.marker.addListener('click', function () {
          map.infowindow.open(map.googleMap, map.marker);
        });
        if (map.settings.info_auto_display) {
          map.infowindow.open(map.googleMap, map.marker);
        }
      }
    }

    // Add a visual indicator.
    $(map.controls).children('.geolocation-map-indicator')
      .text(latLng.lat() + ', ' + latLng.lng())
      .addClass('has-location');
  };

})(jQuery, _, Drupal, drupalSettings);
