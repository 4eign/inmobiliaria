(function ($, Drupal) {

  Drupal.behaviors.inmueblesRecientes = {
    attach: function (context, settings) {

      $('.contact-information').hide();
      $('#ask-info').on('click',function () {
        $('.contact-information').show();
      });

    }
  }
})(jQuery, Drupal);
