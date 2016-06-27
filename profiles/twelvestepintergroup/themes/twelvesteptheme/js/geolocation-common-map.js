/**
 * @file
 * Handle the common map.
 */

(function ($) {
    "use strict";

    Drupal.behaviors.twelvestepGoogleMap = {
        obj: null,

        attach: function (context, settings) {
            var $map = $('#' + drupalSettings.geolocation.commonMap.id, context);
            var $container = $map.children('.geolocation-common-map-container');
            var bounds = new google.maps.LatLngBounds();
            var bubbles = [];
            $container.show();
            if ($map.data('centre-lat'), $map.data('centre-lng')) {
                var center = new google.maps.LatLng($map.data('centre-lat'), $map.data('centre-lng'));
                bounds.extend(center);
            }
            else {
                var center = new google.maps.LatLng(0, 0);
            }

            Drupal.behaviors.twelvestepGoogleMap.obj = new google.maps.Map($container[0], {
                center: center,
                zoom: 12
            });

            var $locations = $map.find('.geolocation-common-map-locations .geolocation');
            $locations.each(function(index, item) {
                item.position = new google.maps.LatLng($(item).data('lat'), $(item).data('lng'));

                bounds.extend(item.position);

                var marker = new google.maps.Marker({
                    position: item.position,
                    map: Drupal.behaviors.twelvestepGoogleMap.obj,
                    title: $(item).children('h2').text(),
                    content: $(item).html()
                });

                marker.addListener('click', function() {
                    // Close open bubbles (there should be only one).
                    while (bubbles.length > 0) {
                        bubbles.pop().close();
                    }

                    // Open this marker.
                    var bubble = new google.maps.InfoWindow({
                        content: marker.content
                    });
                    bubble.open(Drupal.behaviors.twelvestepGoogleMap.obj, marker);
                    bubbles.push(bubble)
                });
            });

            // Show as much of the map as possible.
            $(window).resize(function() {
                Drupal.behaviors.twelvestepGoogleMap.resizeMap($container, Drupal.behaviors.twelvestepGoogleMap.obj, bounds);
            });
            // @todo: what are we waiting on here? something with the map,
            // without this delay, the initial map is too high (on Chrome).
            setTimeout(function() {
                Drupal.behaviors.twelvestepGoogleMap.resizeMap($container, bounds);
            }, 100);

            // When the boundaries change, only show locations in bounds.
            google.maps.event.addListener(Drupal.behaviors.twelvestepGoogleMap.obj, 'zoom_changed', Drupal.behaviors.twelvestepGoogleMap.zoomMap);
            google.maps.event.addListener(Drupal.behaviors.twelvestepGoogleMap.obj, 'dragend', Drupal.behaviors.twelvestepGoogleMap.zoomMap);
        },

        resizeMap: function($container, bounds) {
            // Resize the map to take the entire viewport height (less 10px).
            var vh = Math.max(document.documentElement.clientHeight, window.innerHeight || 0)
            var top = $container.offset().top;
            $container.css('height', vh - top - 10);
            // Resize the map to fit better.
            Drupal.behaviors.twelvestepGoogleMap.obj.fitBounds(bounds);
            var $div = Drupal.behaviors.twelvestepGoogleMap.obj.getDiv();
            $($div).toggle().toggle();
        },

        zoomMap: function() {
            var boundary = Drupal.behaviors.twelvestepGoogleMap.obj.getBounds();
            var $map = $('#' + drupalSettings.geolocation.commonMap.id);
            var $locations = $map.find(' .geolocation-common-map-locations .geolocation');
            $locations.each(function(index, item) {
                if (boundary.contains(item.position)) {
                    $(item).show();
                }
                else {
                    $(item).hide();
                }
            });
        }
    };

})(jQuery);
