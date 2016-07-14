/**
 * @file
 *   Javascript for the Google geocoder widget.
 */

/**
 * @param {Object} drupalSettings.geolocation.widgetSettings
 * @param {String} drupalSettings.geolocation.widgetSettings.addressFieldTarget
 */
(function ($, Drupal, drupalSettings) {
  'use strict';

  /* global google */

  /**
   * @namespace
   */
  Drupal.geolocation = Drupal.geolocation || {};
  Drupal.geolocation.geocoderWidget = Drupal.geolocation.geocoderWidget || {};

  /**
   * Attach geocoder functionality.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches geocoder functionality to relevant elements.
   */
  Drupal.behaviors.geolocationGeocoderWidget = {
    attach: function (context, settings) {
      // Ensure iterables.
      settings.geolocation = settings.geolocation || {widgetMaps: []};
      // Make sure the lazy loader is available.
      if (typeof Drupal.geolocation.loadGoogle === 'function') {
        // First load the library from google.
        Drupal.geolocation.loadGoogle(function () {
          // This won't fire until window load.
          initialize(settings.geolocation.widgetMaps, context);
        });
      }
    }
  };

  /**
   * Adds the click listeners to the map.
   *
   * @param {object} map - The current map object.
   */
  Drupal.geolocation.geocoderWidget.addClickListener = function (map) {
    // Used for a single click timeout.
    var singleClick;

    /**
     * Add the click listener.
     *
     * @param {{latLng:object}} e
     */
    google.maps.event.addListener(map.googleMap, 'click', function (e) {
      // Create 500ms timeout to wait for double click.
      singleClick = setTimeout(function () {
        Drupal.geolocation.geocoderWidget.setHiddenInputFields(e.latLng, map);
        Drupal.geolocation.setMapMarker(e.latLng, map);
      }, 500);
    });

    // Add a doubleclick listener.
    google.maps.event.addListener(map.googleMap, 'dblclick', function (e) {
      clearTimeout(singleClick);
    });
  };

  /**
   * Runs after the google maps api is available
   *
   * @param {object} maps - The google map object.
   * @param {object} context - The html context.
   */
  function initialize(maps, context) {
    // Process drupalSettings for every Google map present on the current page.
    $.each(maps, function (widget_id, map) {

      // Get the container object.
      map.container = $('#' + map.id, context).first();

      if ($(map.container).length >= 1
        && !$(map.container).hasClass('geolocation-processed')
        && typeof google !== 'undefined'
        && typeof google.maps !== 'undefined'
      ) {
        // Add any missing settings.
        map.settings = $.extend(Drupal.geolocation.defaultSettings(), map.settings);

        // Set the lat / lng if not already set.
        if (map.lat === 0 || map.lng === 0) {
          map.lat = $('fieldset.canvas-' + map.id + ' .geolocation-hidden-lat').attr('value');
          map.lng = $('fieldset.canvas-' + map.id + ' .geolocation-hidden-lng').attr('value');
        }

        // Add the map by ID with settings.
        Drupal.geolocation.addMap(map);

        // Add the geocoder to the map.
        Drupal.geolocation.geocoder.add(map);

        Drupal.geolocation.geocoder.addResultCallback(function (address) {
          Drupal.geolocation.geocoderWidget.setHiddenInputFields(address.geometry.location, map);
        });

        if (typeof drupalSettings.geolocation.widgetSettings.addressFieldTarget !== 'undefined') {
          var targetField = drupalSettings.geolocation.widgetSettings.addressFieldTarget;

          Drupal.geolocation.geocoder.addResultCallback(function (address) {
            var addressField = $('.field--type-address.field--widget-address-default.field--name-' + targetField.replace(/_/g, '-'), context);

            var addressLine1 = '';
            var addressLine2 = '';
            var postalTown = '';
            var countryCode = null;
            var postalCode = null;
            var streetNumber = null;
            var premise = null;
            var route = null;
            var locality = null;
            var administrativeArea = null;

            $.each(address.address_components, function (key, value) {
              var component = address.address_components[key];
              var types = component.types;

              switch (types[0]) {
                case 'country':
                  countryCode = component.short_name;
                  break;
                case 'postal_town':
                  postalTown = component.long_name;
                  break;
                case 'postal_code':
                  postalCode = component.long_name;
                  break;
                case 'street_number':
                  streetNumber = component.long_name;
                  break;
                case 'premise':
                  premise = component.long_name;
                  break;
                case 'route':
                  route = component.long_name;
                  break;
                case 'locality':
                  locality = component.long_name;
                  break;
                case 'administrative_area_level_1':
                  administrativeArea = component.short_name;
                  break;
              }
            });

            if (addressField.length === 0) {
              // TODO: Widget is hidden. Store values now in form and save them in Drupal later.
            }
            else {
              // Set the country.
              addressField.find('.country.form-select').val(countryCode).trigger('change');

              if (streetNumber) {
                addressLine1 = streetNumber + ' ' + route;
              }
              else {
                addressLine1 = route;
              }

              if (!!locality && locality !== postalTown) {
                addressLine2 = locality;
              }

              $(document).ajaxComplete(function (event, xhr, settings) {
                if (settings.extraData._drupal_ajax && settings.extraData._triggering_element_name === targetField + '[0][country_code]') {
                  var addressDetails = addressField.find('.details-wrapper').first();
                  // Populate the address fields, once they have been added to the DOM.
                  addressDetails.find('.organization').val(premise);
                  addressDetails.find('.address-line1').val(addressLine1);
                  addressDetails.find('.address-line2').val(addressLine2);
                  addressDetails.find('.locality').val(locality);
                  addressDetails.find('.administrative-area').val(countryCode + '-' + administrativeArea);
                  addressDetails.find('.postal-code').val(postalCode);
                }
              });
            }
          });
        }

        google.maps.event.addDomListener($(map.controls).children('button.clear')[0], 'click', function (e) {
          Drupal.geolocation.geocoderWidget.clearHiddenInputFields(map);
        });

        // Add the click responders for setting the value.
        Drupal.geolocation.geocoderWidget.addClickListener(map);

        // Set the already processed flag.
        $(map.container).addClass('geolocation-processed');
      }
    });
  }

  /**
   * Set the latitude and longitude values to the input fields
   *
   * @param {object} latLng - A location (latLng) object from google maps API.
   * @param {object} map - The settings object that contains all of the necessary metadata for this map.
   */
  Drupal.geolocation.geocoderWidget.setHiddenInputFields = function (latLng, map) {
    // Update the lat and lng input fields.
    $('fieldset.canvas-' + map.id + ' .geolocation-hidden-lat').attr('value', latLng.lat());
    $('fieldset.canvas-' + map.id + ' .geolocation-hidden-lng').attr('value', latLng.lng());
  };

  /**
   * Set the latitude and longitude values to the input fields
   *
   * @param {object} map - The settings object that contains all of the necessary metadata for this map.
   */
  Drupal.geolocation.geocoderWidget.clearHiddenInputFields = function (map) {
    // Update the lat and lng input fields.
    $('fieldset.canvas-' + map.id + ' .geolocation-hidden-lat').attr('value', '');
    $('fieldset.canvas-' + map.id + ' .geolocation-hidden-lng').attr('value', '');
  };


})(jQuery, Drupal, drupalSettings);
