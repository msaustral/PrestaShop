import $ from 'jquery';

$(document).ready(function () {
  createProductSpin();
  createInputFile();
  coverImage();

  $('body').on(
    'click',
    '.product-refresh',
    function (event, extraParameters) {
      var $productRefresh = $(this);
      event.preventDefault();

      let eventType = 'combination updated';
      if (typeof extraParameters !== 'undefined' && extraParameters.eventType) {
        eventType = extraParameters.eventType;
      }

      var query = $(event.target.form).serialize() + '&ajax=1&action=productrefresh';
      var actionURL = $(event.target.form).attr('action');

      $.post(actionURL, query, null, 'json').then(function(resp) {
        prestashop.emit('product updated', {
          reason: {
           productUrl: resp.productUrl
          },
          refreshUrl: $productRefresh.data('url-update'),
          eventType: eventType
        });
      });
    }
  );

  prestashop.on('product dom updated', function(event) {
    createInputFile();
    coverImage();

    if (event && event.product_minimal_quantity) {
      const minimalProductQuantity = parseInt(event.product_minimal_quantity, 10);
      const quantityInputSelector = '#quantity_wanted';
      let quantityInput = $(quantityInputSelector);

      // @see http://www.virtuosoft.eu/code/bootstrap-touchspin/ about Bootstrap TouchSpin
      quantityInput.trigger('touchspin.updatesettings', {min: minimalProductQuantity});
    }

    $($('.tabs .nav-link.active').attr('href')).addClass('active').removeClass('fade');
  });

  function coverImage() {
    $('.js-thumb').on(
      'click',
      (event) => {
        $('.selected').removeClass('selected');
        $(event.target).addClass('selected');
        $('.js-qv-product-cover').prop('src', $(event.currentTarget).data('image-large-src'));
      }
    );
  }

  function createInputFile()
  {
    $('.js-file-input').on('change',(event)=>{
      $('.js-file-name').text($(event.currentTarget).val());
    });
  }

  function createProductSpin()
  {
    let quantityInput = $('#quantity_wanted');
    quantityInput.TouchSpin({
      verticalbuttons: true,
      verticalupclass: 'material-icons touchspin-up',
      verticaldownclass: 'material-icons touchspin-down',
      buttondown_class: 'btn btn-touchspin js-touchspin',
      buttonup_class: 'btn btn-touchspin js-touchspin',
      min: parseInt(quantityInput.attr('min'), 10),
      max: 1000000
    });

    quantityInput.on('change', function (event) {
      let $productRefresh = $('.product-refresh');
      $(event.currentTarget).trigger('touchspin.stopspin');
      $productRefresh.trigger('click', {eventType: 'product quantity updated'});
      event.preventDefault();

      return false;
    });
  }
});
