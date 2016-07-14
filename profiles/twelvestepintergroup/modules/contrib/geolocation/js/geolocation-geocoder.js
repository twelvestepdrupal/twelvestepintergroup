/**
 * @file
 *   Javascript for the Google geocoder function.
 */

/**
 * @name AddressComponent
 * @property {String} long_name - Long component name
 * @property {String} short_name - Short component name
 * @property {String[]} types - Compontent type
 * @property {Object} geometry
 * @property {Object} geometry.location
 */

/**
 * @name GoogleAddress
 * @property {AddressComponent[]} address_components - Compontents
 */

(function ($, Drupal, _) {
  'use strict';

  /* global google */

  /**
   * @namespace
   */
  Drupal.geolocation = Drupal.geolocation || {};
  Drupal.geolocation.geocoder = Drupal.geolocation.geocoder || {};

  /**
   * Load google maps and set a callback to run when it's ready.
   *
   * @param {object} map - The Google Map object
   */
  Drupal.geolocation.geocoder.add = function (map) {
    map.geocoder = new google.maps.Geocoder();
    map.controls = $('<form class="geocode-controls-wrapper" />')
      .append($('<input id="geocoder-input-' + map.id + '" type="text" class="input" placeholder="Enter a location" />'))
      // Create submit button
      .append($('<button class="submit" />'))
      // Create clear button
      .append($('<button class="clear" />'))
      // Create clear button
      .append($('<div class="geolocation-map-indicator" />'))
      // Use the DOM element.
      .get(0);

    // Add the default indicator if the values aren't blank.
    if (map.lat !== '' && map.lng !== '') {
      $(map.controls).children('.geolocation-map-indicator')
        .addClass('has-location')
        .text(map.lat + ', ' + map.lng);
    }

    map.controls.index = 1;

    map.googleMap.controls[google.maps.ControlPosition.TOP_LEFT].push(map.controls);

    $(map.controls).children('input.input').first().autocomplete({
      autoFocus: true,
      source: function (request, response) {
        var responseData = [];
        map.geocoder.geocode({address: request.term}, function (results, status) {
          if (status === google.maps.GeocoderStatus.OK) {
            $.each(results, function (index, item) {
              responseData.push({
                value: item.formatted_address,
                address: item
              });
            });
          }
          response(responseData);
        });
      },
      select: function (event, ui) {
        // Set the map viewport.
        map.googleMap.fitBounds(ui.item.address.geometry.viewport);
        // Set the map marker.
        Drupal.geolocation.setMapMarker(ui.item.address.geometry.location, map);
        Drupal.geolocation.geocoder.resultCallback(ui.item.address);
      }
    });

    $(map.controls).submit(function (e) {
      e.preventDefault();
      map.geocoder.geocode({address: $(this).children('input.input').val()}, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
          map.googleMap.fitBounds(results[0].geometry.viewport);
          // Set the map marker.
          Drupal.geolocation.setMapMarker(results[0].geometry.location, map);
          Drupal.geolocation.geocoder.resultCallback(results[0]);
        }
        else {
          // Alert of the error geocoding.
          alert(Drupal.t('Geocode was not successful for the following reason: ') + status);
        }
      });
    });

    google.maps.event.addDomListener($(map.controls).children('button.clear')[0], 'click', function (e) {
      // Stop all that bubbling and form submitting.
      e.preventDefault();
      // Remove the coordinates.
      $(map.controls).children('.geolocation-map-indicator').text('').removeClass('has-location');
      // Clear the map point.
      map.marker.setMap();
      // Clear the input text.
      $(map.controls).children('input.input').val('');
    });
  };

  /**
   * Provides the callback that is called when geocoded results are found loads.
   *
   * @param {object} result - first returned address
   */
  Drupal.geolocation.geocoder.resultCallback = function (result) {
    // Ensure callbacks array;
    Drupal.geolocation.geocoder.resultCallbacks = Drupal.geolocation.geocoder.resultCallbacks || [];
    _.invoke(Drupal.geolocation.geocoder.resultCallbacks, 'callback', result);
  };

  /**
   * Adds a callback that will be called when results are found.
   *
   * @param {geolocationCallback} callback - The callback
   * @param {string} [id] - Identify the callback
   */
  Drupal.geolocation.geocoder.addResultCallback = function (callback, id) {
    if (typeof id === 'undefined') {
      id = 'none';
    }
    Drupal.geolocation.geocoder.resultCallbacks = Drupal.geolocation.geocoder.resultCallbacks || [];
    Drupal.geolocation.geocoder.resultCallbacks.push({callback: callback, id: id});
  };

  /**
   * Remove a callback that will be called when results are found.
   *
   * @param {string} id - Identify the callback
   */
  Drupal.geolocation.geocoder.removeResultCallback = function (id) {
    Drupal.geolocation.geocoder.resultCallbacks = Drupal.geolocation.geocoder.resultCallbacks || [];
    $.each(Drupal.geolocation.geocoder.resultCallbacks, function (index, callback) {
      if (callback.id !== 'none' && callback.id === id) {
        Drupal.geolocation.geocoder.resultCallbacks.splice(index, 1);
      }
    });
  };

})(jQuery, Drupal, _);
