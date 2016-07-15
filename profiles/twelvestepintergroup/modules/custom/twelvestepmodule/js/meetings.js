/**
 * @file
 * Handle the TwelveStepMeeting map.
 */

(function ($) {
  'use strict';

  Drupal.behaviors.meetingMap = {
    attach: function (context, settings) {
      // If the user selects the 'next' time, also select 'today'.
      var $select = $('.form-item-time select', context);
      $select.change(function() {
        if (this.value == 'next') {
          $('.form-item-days select').val('today');
        }
      });
    },
  };
})(jQuery);
