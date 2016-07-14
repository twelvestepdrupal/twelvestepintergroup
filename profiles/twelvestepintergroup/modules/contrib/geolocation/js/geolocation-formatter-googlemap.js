/**
 * @file
 * Javascript for the Google map formatter.
 */
(function ($, Drupal) {

  'use strict';

  /**
   * @namespace
   */
  Drupal.geolocation = Drupal.geolocation || {};

  /**
   * Attach google map formatter functionality.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches google map formatter functionality to relevant elements.
   */
  Drupal.behaviors.geolocationGoogleMaps = {
    attach: function (context, settings) {
      // Ensure itterables.
      settings.geolocation = settings.geolocation || {maps: []};

      var mapIds = [];
      $.each(settings.geolocation.maps, function (index, item) {
        mapIds.push('#' + item.id);
      });

      if ($(mapIds.join(', '), context).length < 1) {
        // None of the target IDs is present. Stop here.
        return;
      }

      // Make sure the lazy loader is available.
      if (typeof Drupal.geolocation.loadGoogle === 'function') {
        // First load the library from google.
        Drupal.geolocation.loadGoogle(function () {
          initialize(settings.geolocation.maps, context);
        });
      }
    }
  };

  /**
   * Runs after the google maps api is available
   *
   * @param {object} maps - The google map object.
   * @param {object} context - The html context.
   */
  function initialize(maps, context) {
    // Loop though all objects and add maps to the page.
    $.each(maps, function (delta, map) {
      // Get the map container.
      map.container = $('#' + map.id, context).first();

      if (map.container.length && !map.container.hasClass('geolocation-processed')) {
        // Add the map by ID with settings.
        Drupal.geolocation.addMap(map);
        // Set the already processed flag.
        map.container.addClass('geolocation-processed');
      }
    });
  }
})(jQuery, Drupal);
