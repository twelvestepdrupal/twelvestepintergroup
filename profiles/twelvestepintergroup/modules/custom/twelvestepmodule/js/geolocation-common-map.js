/**
 * @file
 * Handle the common map.
 */

(function ($) {
    "use strict";

    Drupal.behaviors.twelvestepGoogleMap = {
        attach: function (context, settings) {
            var map = $('#' + drupalSettings.geolocation.commonMap.id, context);
            var container = map.children('.geolocation-common-map-container');
            var bounds = new google.maps.LatLngBounds();
            var bubbles = [];
            container.show();
            if (map.data('centre-lat'), map.data('centre-lng')) {
                var center = new google.maps.LatLng(map.data('centre-lat'), map.data('centre-lng'));
                bounds.extend(center);
            }
            else {
                var center = new google.maps.LatLng(0, 0);
            }

            var googleMap = new google.maps.Map(container[0], {
                center: center,
                zoom: 12
            });

            map.find('.geolocation-common-map-locations .geolocation').each(function(index, item) {
                item.position = new google.maps.LatLng($(item).data('lat'), $(item).data('lng'));

                bounds.extend(item.position);

                var marker = new google.maps.Marker({
                    position: item.position,
                    map: googleMap,
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
                    bubble.open(googleMap, marker);
                    bubbles.push(bubble)
                });
            });

            // Show as much of the map as possible.
            $(window).resize(function() {
                Drupal.behaviors.twelvestepGoogleMap.resizeContainer(container);
                googleMap.fitBounds(bounds);
            });
            Drupal.behaviors.twelvestepGoogleMap.resizeContainer(container);
            googleMap.fitBounds(bounds);

            // When the boundaries change, only show locations in bounds.
            google.maps.event.addListener(googleMap, 'zoom_changed', function() {
                Drupal.behaviors.twelvestepGoogleMap.zoomMap(googleMap);
            });
            google.maps.event.addListener(googleMap, 'dragend', function() {
                Drupal.behaviors.twelvestepGoogleMap.zoomMap(googleMap);
            });
        },

        resizeContainer: function(container) {
            // Resize the map to take the entire viewport height (less 10px).
            var vh = Math.max(document.documentElement.clientHeight, window.innerHeight || 0)
            var top = container.offset().top;
            var height = Math.max(vh - top - 10, 300);
            container.css('height', height);
        },

        zoomMap: function(googleMap) {
            var boundary = googleMap.getBounds();
            var map = $('#' + drupalSettings.geolocation.commonMap.id);
            map.find('.geolocation-common-map-locations .geolocation').each(function(index, item) {
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
