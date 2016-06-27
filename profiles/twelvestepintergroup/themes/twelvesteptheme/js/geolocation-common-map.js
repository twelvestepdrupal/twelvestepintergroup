/**
 * @file
 * Handle the common map.
 */

(function ($) {
    "use strict";

    Drupal.behaviors.twelvestepLocationMap = {
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

            var googleMap = new google.maps.Map($container[0], {
                center: center,
                zoom: 12
            });

            $map.find('.geolocation-common-map-locations .geolocation').each(function(index, item) {
                item = $(item);
                var position = new google.maps.LatLng(item.data('lat'), item.data('lng'));

                bounds.extend(position);

                var marker = new google.maps.Marker({
                    position: position,
                    map: googleMap,
                    title: item.children('h2').text(),
                    content: item.html()
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
                    bubble.open(googleMap, marker);
                    bubbles.push(bubble)
                });
            });

            // Always show as much of the map as possible.
            $(window).resize(function() {
                Drupal.behaviors.twelvestepLocationMap.resizeMap($container, googleMap, bounds);
            });
            // @todo: what are we waiting on here? something with the map,
            // without this delay, the initial map is too high (on Chrome).
            setTimeout(function() {
                Drupal.behaviors.twelvestepLocationMap.resizeMap($container, googleMap, bounds);
            }, 100)
        },

        resizeMap: function($container, googleMap, bounds) {
            // Resize the map to take the entire viewport height (less 10px).
            var vh = Math.max(document.documentElement.clientHeight, window.innerHeight || 0)
            var top = $container.offset().top;
            $container.css('height', vh - top - 10);
            // Resize the map to fit better.
            googleMap.fitBounds(bounds);
            $(googleMap.getDiv()).toggle().toggle();
        }
    };

})(jQuery);
