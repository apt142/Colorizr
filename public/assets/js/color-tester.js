/* globals $, tinycolor */

$(document).ready(function () {
  'use strict';

  var body = $('body');

  body.on('change', '#sample', function () {
    var color = tinycolor($(this).val());
    $('#sample-rgb').val(color.toHexString());
  });


  body.on('change', '#sample-rgb', function () {
    var color = tinycolor($(this).val());

    if (color.isValid()) {
      $(this).val(color.toHexString());
      $('#sample').val(color.toHexString());
    }
  });

  body.on('click', '#build-palette', function () {
    var color = tinycolor($('#sample-rgb').val());

    if (color.isValid()) {
      fetchRelatedColors(color.toHexString());
    }
  });

  function fetchRelatedColors(color) {
    color = color.replace('#', '');

    $.get('/theme/' + color, {}, function (data) {
      colorSwatch('.primary-swatch', '#' + color);
      colorSwatch('.success-swatch', '#' + data.success);
      colorSwatch('.info-swatch', '#' + data.info);
      colorSwatch('.warning-swatch', '#' + data.warning);
      colorSwatch('.danger-swatch', '#' + data.danger);

    });

    function colorSwatch(className, color) {
      var textColor = '#ccc';
      $(className + ' .swatch-color').css({background: color});
      $(className + ' .swatch-color-label').html(color);
      if (tinycolor(color).isDark()) {
        textColor = '#ddd';
      } else {
        textColor = '#111';
      }
      $(className + ' .swatch-color-label').css({color: textColor});

    }

  }

});



